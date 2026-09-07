#!/bin/bash
# ==========================================================
# curl_examples.sh
# أمثلة جاهزة لاختبار /decision/analyze لكل السيناريوهات الممكنة
# شغّلي أولًا: python api.py   (في تيرمنال منفصل)
# ثم شغّلي هذا الملف: bash curl_examples.sh
# ==========================================================

BASE_URL="http://localhost:5003"

echo "=== 0) فحص أن الخدمة تعمل ==="
curl -s -X GET "$BASE_URL/health"
echo -e "\n"

echo "=== 1) حالة كاملة: NLP + Vision متفقان (حريق) ==="
curl -s -X POST "$BASE_URL/decision/analyze" \
  -H "Content-Type: application/json" \
  -d '{
    "emergency_id": "EMG-000001",
    "nlp": {"text_urgency": 85, "emergency_type": "Fire", "priority": "critical", "entities": []},
    "vision": {"image_type": "Fire", "confidence": 0.94},
    "location": {"latitude": 15.3694, "longitude": 44.1910}
  }'
echo -e "\n"

echo "=== 2) حالة تعارض: NLP يقول Other لكن Vision يقول Flood ==="
curl -s -X POST "$BASE_URL/decision/analyze" \
  -H "Content-Type: application/json" \
  -d '{
    "emergency_id": "EMG-000002",
    "nlp": {"text_urgency": 40, "emergency_type": "Other", "priority": "low", "entities": []},
    "vision": {"image_type": "Flood", "confidence": 0.9},
    "location": {"latitude": 15.3694, "longitude": 44.1910}
  }'
echo -e "\n"

echo "=== 3) نص فقط بدون صورة (vision = null صراحة) ==="
curl -s -X POST "$BASE_URL/decision/analyze" \
  -H "Content-Type: application/json" \
  -d '{
    "emergency_id": "EMG-000003",
    "nlp": {"text_urgency": 70, "emergency_type": "Traffic_Accident", "priority": "high", "entities": []},
    "vision": null,
    "location": {"latitude": 15.3694, "longitude": 44.1910}
  }'
echo -e "\n"

echo "=== 4) صورة فقط بدون نص (nlp = null صراحة) ==="
curl -s -X POST "$BASE_URL/decision/analyze" \
  -H "Content-Type: application/json" \
  -d '{
    "emergency_id": "EMG-000004",
    "nlp": null,
    "vision": {"image_type": "Fire", "confidence": 0.88},
    "location": {"latitude": 15.3694, "longitude": 44.1910}
  }'
echo -e "\n"

echo "=== 5) حالة زلزال عالية الخطورة (يجب أن تصبح critical إجباريًا) ==="
curl -s -X POST "$BASE_URL/decision/analyze" \
  -H "Content-Type: application/json" \
  -d '{
    "emergency_id": "EMG-000005",
    "nlp": {"text_urgency": 80, "emergency_type": "Earthquake", "priority": "high", "entities": []},
    "vision": {"image_type": "Earthquake", "confidence": 0.9},
    "location": {"latitude": 15.3694, "longitude": 44.1910}
  }'
echo -e "\n"

echo "=== 6) حالة طبيعية (لا يوجد طارئ فعلي) ==="
curl -s -X POST "$BASE_URL/decision/analyze" \
  -H "Content-Type: application/json" \
  -d '{
    "emergency_id": "EMG-000006",
    "nlp": {"text_urgency": 5, "emergency_type": "Normal", "priority": "low", "entities": []},
    "vision": {"image_type": "Normal", "confidence": 0.2},
    "location": {"latitude": 15.3694, "longitude": 44.1910}
  }'
echo -e "\n"

echo "=== 7) حالة غير صحيحة: لا text ولا image (يجب أن ترجع خطأ 400) ==="
curl -s -X POST "$BASE_URL/decision/analyze" \
  -H "Content-Type: application/json" \
  -d '{
    "emergency_id": "EMG-000007",
    "location": {"latitude": 15.3694, "longitude": 44.1910}
  }'
echo -e "\n"
