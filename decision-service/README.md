# محرك الدمج والقرار (Decision / Fusion Engine)
### جزئية آية — نظام الاستجابة لحالات الطوارئ متعدد الوسائط

هذا المكوّن يمثل خدمة **S3** في عقد الـ API النهائي المتفق عليه بين الفريق، ويستقبل مخرجات
NLP (إيلاف) و Vision (أنهار) والموقع الجغرافي، وينتج القرار النهائي الموحّد.

---

## 1. تشغيل المشروع

```bash
pip install -r requirements.txt
python api.py
```

الخدمة تعمل افتراضيًا على: `http://localhost:5003`

## 2. الـ Endpoint

```
POST /decision/analyze
```

### مثال طلب (Request)

```json
{
  "emergency_id": "EMG-000001",
  "nlp": {
    "text_urgency": 85,
    "emergency_type": "Fire",
    "priority": "critical",
    "entities": []
  },
  "vision": {
    "image_type": "Fire",
    "confidence": 0.94
  },
  "location": {
    "latitude": 15.3694,
    "longitude": 44.1910
  }
}
```

### مثال استجابة (Response)

```json
{
  "emergency_id": "EMG-000001",
  "final_type": "Fire",
  "response_type": "Fire_Rescue",
  "risk_score": 90,
  "priority": "critical",
  "confidence": 0.9,
  "location": {
    "latitude": 15.3694,
    "longitude": 44.191
  }
}
```

## 3. حالة "مصدر واحد فقط" (Single-Source)

الحقلان `nlp` و `vision` كلاهما اختياري، لكن **يجب توفر واحد منهما على الأقل**:

- إذا وصل `nlp` فقط → القرار يعتمد كليًا على نتيجة NLP (بثقة مخفّضة).
- إذا وصل `vision` فقط → القرار يعتمد كليًا على نتيجة Vision (بثقة مخفّضة).
- إذا غاب الاثنان → تُرفع خطأ `ValueError`.

## 4. منطق حل التعارض (Conflict Resolution)

عندما يتفق `emergency_type` من NLP مع `image_type` من Vision، يتم دمج الدرجات بوزن متساوٍ (50/50).

عند **التعارض**، يُعطى وزن أكبر قليلًا للرؤية الحاسوبية (55%) لأنها تعتمد على دليل بصري مباشر،
مقابل 45% للنص، ثم تُخفَّض درجة الثقة النهائية لتعكس حالة عدم اليقين.

## 5. جدول تحويل النوع إلى الاستجابة (Response Type Mapping)

| النوع النهائي (final_type) | نوع الاستجابة (response_type) |
|---|---|
| Fire | Fire_Rescue |
| Traffic_Accident | Ambulance |
| Medical_Emergency | Ambulance |
| Flood | Rescue_Team |
| Earthquake | Rescue_Team |
| Other | General_Response |
| Normal | No_Response |

هذا الجدول ثابت ومطابق للعقد المتفق عليه — لا يجوز لأي خدمة اختراع قيم جديدة.

## 6. تجاوز الأمان الحرج (Critical Safety Override)

إذا كان `final_type` من الأنواع عالية الخطورة (`Fire` أو `Earthquake`) ووصلت `risk_score`
إلى 75 أو أكثر، يُفرض `priority = "critical"` تلقائيًا بغض النظر عن الحسابات العادية،
حماية من التقليل من خطورة الحالات الحرجة.

## 6.5 تخفيض الثقة في حالة "مصدر واحد فقط" (Single-Source Confidence)

هذه القاعدة **متفق عليها مع الفريق** ومطبّقة حرفيًا في الكود:

```
confidence_final = confidence_source × 0.7
```

- لو وصل `nlp` فقط → `confidence_final = (text_urgency / 100) × 0.7`
- لو وصل `vision` فقط → `confidence_final = vision.confidence × 0.7`

هذا يعكس أن القرار المبني على مصدر واحد أقل موثوقية من القرار المبني على دمج مصدرين.

## 6.6 قاعدة "واحد على الأقل" (At Least One Modality)

- `nlp` و `vision` كلاهما **اختياري** في جسم طلب `/decision/analyze`.
- يمكن إرسال أحدهما بقيمة `null` صراحة، أو حذف المفتاح تمامًا — النتيجة متطابقة تمامًا في الكود.
- لو غاب الاثنان معًا (`nlp` و `vision` كلاهما `null`/محذوف) → تُرجع الخدمة **خطأ 400** مع رسالة:
  `"يجب توفر نتيجة NLP أو Vision على الأقل"`
- `location` يجب أن تصل دائمًا من الـ Backend (تُمرَّر كما هي دون أي تعديل إلى Routing).


## 7. عتبات تحويل risk_score إلى priority

| risk_score | priority |
|---|---|
| 85 فأكثر | critical |
| 60 - 84 | high |
| 30 - 59 | medium |
| أقل من 30 | low |

## 8. الملفات

| الملف | الوصف |
|---|---|
| `decision_engine.py` | المحرك الأساسي: الدمج، حل التعارض، حساب الخطورة والأولوية |
| `api.py` | واجهة Flask REST المطابقة لمسار `/decision/analyze` |
| `test_decision_engine.py` | 11 اختبار وحدة تغطي كل الحالات (اتفاق، تعارض، مصدر واحد، أخطاء) |
| `curl_examples.sh` | 7 أمثلة curl جاهزة للتشغيل تغطي كل السيناريوهات (شغّليها بعد `python api.py`) |
| `requirements.txt` | متطلبات التشغيل |

## 9. تشغيل الاختبارات

```bash
python -m unittest test_decision_engine.py -v
```

جميع الاختبارات (11) يجب أن تنجح.

## 9.5 اختبار سريع بأمثلة curl

```bash
# في تيرمنال أول
python api.py

# في تيرمنال ثانٍ
bash curl_examples.sh
```

يغطي 7 حالات: اتفاق، تعارض، نص فقط، صورة فقط، زلزال حرج، حالة طبيعية، وحالة خاطئة (بدون أي مصدر).

## 10. ما ترسلينه للعصماء (Backend)

1. **الملفات:** `api.py`, `decision_engine.py` (والمشروع كامل احتياطًا)
2. **معلومة الاتصال:** المنفذ `5003` والمسار `POST /decision/analyze`
3. **الحقول الاختيارية:** أبلغيها أن `nlp` و `vision` كلاهما اختياري (null أو محذوف)، لكن يجب توفر واحد منهما على الأقل مع `location` دائمًا
4. **مثال Request/Response** من هذا الملف، وأمثلة `curl_examples.sh` كمرجع سريع لها

## 11. ما ترسلينه لأحلام (Routing) — عبر الـ Backend

الحقول التي يحتاجها A*/Dijkstra من ناتج قرارك: `emergency_id`, `response_type`, `priority`, `risk_score`, `location`.
راجعي القسم أعلاه في المحادثة أو اسأليني إذا احتجتِ توثيقها بشكل منفصل.

## 12. ملاحظة حول ملف fusion_model.py السابق

النسخة الحالية تعتمد منطق قواعد (rule-based) واضح ومباشر يطابق العقد حرفيًا،
وهو أسهل للشرح والتوثيق أمام لجنة المناقشة. يمكن إعادة دمج نموذج `MLPClassifier`
(fusion_model.py من العمل السابق) كطبقة اختيارية بديلة لاحقًا إذا رغب الفريق
بإظهار مكوّن "تعلّم آلي" صريح ضمن التقييم، دون التعارض مع بنية هذا العقد.











Windows PowerShell
Copyright (C) Microsoft Corporation. All rights reserved.

Install the latest PowerShell for new features and improvements! https://aka.ms/PSWindows

PS C:\Users\ALQURSAN\Desktop\aya_decision_engine-3\aya_decision_engine> python -m unittest test_decision_engine.py -v
test_agreement_fire (test_decision_engine.TestDecisionEngine.test_agreement_fire)
عندما يتفق NLP و Vision على نفس النوع (حريق). ... ok
test_conflict_resolution (test_decision_engine.TestDecisionEngine.test_conflict_resolution)
تعارض بين NLP وVision: يجب أن يُحسم القرار دون خطأ ولصالح أعلى وزن. ... ok
test_earthquake_high_risk_override (test_decision_engine.TestDecisionEngine.test_earthquake_high_risk_override)
الأنواع عالية الخطورة (زلزال) مع درجة عالية يجب أن تُجبر priority إلى critical. ... ok
test_invalid_emergency_type_raises (test_decision_engine.TestDecisionEngine.test_invalid_emergency_type_raises) ... ok
test_location_passed_through_unchanged (test_decision_engine.TestDecisionEngine.test_location_passed_through_unchanged) ... ok
test_medical_emergency_maps_to_ambulance (test_decision_engine.TestDecisionEngine.test_medical_emergency_maps_to_ambulance) ... ok
test_missing_both_sources_raises (test_decision_engine.TestDecisionEngine.test_missing_both_sources_raises) ... ok
test_missing_emergency_id_raises (test_decision_engine.TestDecisionEngine.test_missing_emergency_id_raises) ... ok
test_nlp_only (test_decision_engine.TestDecisionEngine.test_nlp_only) ... ok
test_normal_case_low_priority (test_decision_engine.TestDecisionEngine.test_normal_case_low_priority) ... ok
test_vision_only (test_decision_engine.TestDecisionEngine.test_vision_only) ... ok

----------------------------------------------------------------------
Ran 11 tests in 0.006s

OK
PS C:\Users\ALQURSAN\Desktop\aya_decision_engine-3\aya_decision_engine> $body = @{
>>     image_result = @{
>>         class_probabilities = @{
>>             Fire = 0.85
>>             Earthquake = 0.02
>>             Flood = 0.05
>>             "No Emergency" = 0.03
>>             "Traffic Accident" = 0.05
>>         }
>>     }
>> } | ConvertTo-Json -Depth 5

PS C:\Users\ALQURSAN\Desktop\aya_decision_engine-3\aya_decision_engine> $body = @{
>>     emergency_id = "EMG-000001"
>>     nlp = @{text_urgency=85; emergency_type="Fire"; priority="critical"; entities=@()}
>>     vision = @{image_type="Fire"; confidence=0.94}
>>     location = @{latitude=15.3694; longitude=44.1910}
>> } | ConvertTo-Json -Depth 5
PS C:\Users\ALQURSAN\Desktop\aya_decision_engine-3\aya_decision_engine>
PS C:\Users\ALQURSAN\Desktop\aya_decision_engine-3\aya_decision_engine> Invoke-RestMethod -Uri "http://localhost:5003/decision/analyze" -Method Post -Body $body -ContentType "application/json"


confidence    : 0.9
emergency_id  : EMG-000001
final_type    : Fire
location      : @{latitude=15.3694; longitude=44.191}
priority      : critical
response_type : Fire_Rescue
risk_score    : 90



PS C:\Users\ALQURSAN\Desktop\aya_decision_engine-3\aya_decision_engine> $bodyEarthquake = @{
>>     emergency_id = "EMG-000012"
>>     nlp = @{text_urgency=80; emergency_type="Earthquake"; priority="high"; entities=@()}
>>     vision = @{image_type="Earthquake"; confidence=0.9}
>>     location = @{latitude=15.3694; longitude=44.1910}
>> } | ConvertTo-Json -Depth 5
PS C:\Users\ALQURSAN\Desktop\aya_decision_engine-3\aya_decision_engine>
PS C:\Users\ALQURSAN\Desktop\aya_decision_engine-3\aya_decision_engine> Invoke-RestMethod -Uri "http://localhost:5003/decision/analyze" -Method Post -Body $bodyEarthquake -ContentType "application/json"


confidence    : 0.85
emergency_id  : EMG-000012
final_type    : Earthquake
location      : @{latitude=15.3694; longitude=44.191}
priority      : critical
response_type : Rescue_Team
risk_score    : 90



PS C:\Users\ALQURSAN\Desktop\aya_decision_engine-3\aya_decision_engine> $bodyFlood = @{
>>     emergency_id = "EMG-000010"
>>     nlp = @{text_urgency=75; emergency_type="Flood"; priority="high"; entities=@()}
>>     vision = @{image_type="Flood"; confidence=0.88}
>>     location = @{latitude=15.3694; longitude=44.1910}
>> } | ConvertTo-Json -Depth 5
PS C:\Users\ALQURSAN\Desktop\aya_decision_engine-3\aya_decision_engine>
PS C:\Users\ALQURSAN\Desktop\aya_decision_engine-3\aya_decision_engine> Invoke-RestMethod -Uri "http://localhost:5003/decision/analyze" -Method Post -Body $bodyFlood -ContentType "application/json"


confidence    : 0.81
emergency_id  : EMG-000010
final_type    : Flood
location      : @{latitude=15.3694; longitude=44.191}
priority      : high
response_type : Rescue_Team
risk_score    : 82



PS C:\Users\ALQURSAN\Desktop\aya_decision_engine-3\aya_decision_engine> $body2 = @{
>>     emergency_id = "EMG-000002"
>>     nlp = @{text_urgency=70; emergency_type="Traffic_Accident"; priority="high"; entities=@()}
>>     vision = $null
>>     location = @{latitude=15.3694; longitude=44.1910}
>> } | ConvertTo-Json -Depth 5
PS C:\Users\ALQURSAN\Desktop\aya_decision_engine-3\aya_decision_engine>
PS C:\Users\ALQURSAN\Desktop\aya_decision_engine-3\aya_decision_engine> Invoke-RestMethod -Uri "http://localhost:5003/decision/analyze" -Method Post -Body $body2 -ContentType "application/json"


confidence    : 0.52
emergency_id  : EMG-000002
final_type    : Traffic_Accident
location      : @{latitude=15.3694; longitude=44.191}
priority      : high
response_type : Ambulance
risk_score    : 70



PS C:\Users\ALQURSAN\Desktop\aya_decision_engine-3\aya_decision_engine>