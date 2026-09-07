# -*- coding: utf-8 -*-
"""
decision_engine.py
====================
محرك الدمج والقرار (Multimodal Fusion & Decision Engine)
مسؤولية آية في نظام الاستجابة لحالات الطوارئ متعدد الوسائط.

هذا الملف مبني ليطابق حرفيًا "عقد الـ API النهائي" المتفق عليه بين الفريق:
- يستقبل: emergency_id + نتيجة NLP + نتيجة Vision + الموقع الجغرافي (GPS)
- يُرجع: final_type / response_type / risk_score / priority / confidence / location

المسؤوليات المؤكدة (بحسب العقد):
1) التحقق من emergency_id
2) دمج NLP + Vision (Fusion)
3) حل التعارض بين المصدرين (Conflict Resolution)
4) حساب risk_score
5) تحديد priority النهائي
6) تحديد final_type
7) تحويل final_type إلى response_type عبر جدول التطابق المعتمد
8) تمرير الموقع الأصلي كما هو إلى Routing (بدون تعديل)
"""

from dataclasses import dataclass, field
from typing import Optional, List, Dict, Any


# ---------------------------------------------------------------------------
# 1) القيم الثابتة المتفق عليها في العقد
# ---------------------------------------------------------------------------

# أنواع الطوارئ المسموح بها (يجب أن تطابق NLP و Vision تمامًا)
VALID_EMERGENCY_TYPES = {
    "Fire", "Flood", "Earthquake", "Traffic_Accident",
    "Medical_Emergency", "Other", "Normal",
}

# جدول تحويل النوع النهائي إلى نوع الاستجابة (لا يجوز لأي خدمة اختراع قيمة جديدة)
RESPONSE_TYPE_MAP: Dict[str, str] = {
    "Fire": "Fire_Rescue",
    "Traffic_Accident": "Ambulance",
    "Medical_Emergency": "Ambulance",
    "Flood": "Rescue_Team",
    "Earthquake": "Rescue_Team",
    "Other": "General_Response",
    "Normal": "No_Response",
}

# الأنواع عالية الخطورة (تُستخدم في تجاوز الأمان الحرج - Critical Override)
HIGH_RISK_TYPES = {"Fire", "Earthquake"}

# عتبات تحويل risk_score إلى priority
PRIORITY_THRESHOLDS = [
    (85, "critical"),
    (60, "high"),
    (30, "medium"),
    (0, "low"),
]

# عتبة تجاوز الأمان: إذا كان النوع عالي الخطورة والدرجة فوق هذه القيمة -> critical إجباري
CRITICAL_OVERRIDE_THRESHOLD = 75


# ---------------------------------------------------------------------------
# 2) هياكل البيانات (Data Classes) - تطابق حقول العقد حرفيًا
# ---------------------------------------------------------------------------

@dataclass
class NLPResult:
    """نتيجة خدمة NLP كما تصل في جسم الطلب تحت المفتاح 'nlp'."""
    text_urgency: int
    emergency_type: str
    priority: str
    entities: List[Any] = field(default_factory=list)

    def __post_init__(self):
        if not (0 <= self.text_urgency <= 100):
            raise ValueError("text_urgency يجب أن تكون بين 0 و 100")
        if self.emergency_type not in VALID_EMERGENCY_TYPES:
            raise ValueError(f"emergency_type غير معروف: {self.emergency_type}")


@dataclass
class VisionResult:
    """نتيجة خدمة Vision كما تصل تحت المفتاح 'vision'."""
    image_type: str
    confidence: float

    def __post_init__(self):
        if not (0.0 <= self.confidence <= 1.0):
            raise ValueError("confidence يجب أن تكون بين 0.0 و 1.0")
        if self.image_type not in VALID_EMERGENCY_TYPES:
            raise ValueError(f"image_type غير معروف: {self.image_type}")


@dataclass
class Location:
    """الموقع الجغرافي - يُمرَّر كما هو دون تعديل إلى Routing."""
    latitude: float
    longitude: float


@dataclass
class DecisionResult:
    """الناتج النهائي المطابق تمامًا لمخرجات العقد في القسم 5."""
    emergency_id: str
    final_type: str
    response_type: str
    risk_score: int
    priority: str
    confidence: float
    location: Optional[Dict[str, float]]

    def to_dict(self) -> Dict[str, Any]:
        return {
            "emergency_id": self.emergency_id,
            "final_type": self.final_type,
            "response_type": self.response_type,
            "risk_score": self.risk_score,
            "priority": self.priority,
            "confidence": round(self.confidence, 2),
            "location": self.location,
        }


# ---------------------------------------------------------------------------
# 3) محرك القرار
# ---------------------------------------------------------------------------

class DecisionEngine:
    """
    محرك الدمج والقرار.
    يدعم ثلاث حالات مدخلات:
    - NLP + Vision معًا (الحالة الكاملة)
    - NLP فقط (Single-Source)
    - Vision فقط (Single-Source)
    """

    def decide(
        self,
        emergency_id: str,
        nlp: Optional[Dict[str, Any]] = None,
        vision: Optional[Dict[str, Any]] = None,
        location: Optional[Dict[str, float]] = None,
    ) -> DecisionResult:

        # (1) التحقق من emergency_id
        if not emergency_id or not isinstance(emergency_id, str):
            raise ValueError("emergency_id مطلوب ويجب أن يكون نصًا")

        nlp_obj = NLPResult(**nlp) if nlp else None
        vision_obj = VisionResult(**vision) if vision else None

        if nlp_obj is None and vision_obj is None:
            raise ValueError("يجب توفر نتيجة NLP أو Vision على الأقل")

        loc_dict = None
        if location:
            loc_obj = Location(**location)
            loc_dict = {"latitude": loc_obj.latitude, "longitude": loc_obj.longitude}

        # (2 و 3) الدمج وحل التعارض + (4) حساب risk_score + (6) تحديد final_type
        if nlp_obj and vision_obj:
            final_type, risk_score, confidence = self._fuse_both(nlp_obj, vision_obj)
        elif nlp_obj:
            final_type, risk_score, confidence = self._from_nlp_only(nlp_obj)
        else:
            final_type, risk_score, confidence = self._from_vision_only(vision_obj)

        # تجاوز الأمان الحرج (Critical Safety Override)
        risk_score, priority = self._apply_priority(final_type, risk_score)

        # (7) تحويل النوع النهائي إلى نوع الاستجابة
        response_type = RESPONSE_TYPE_MAP[final_type]

        return DecisionResult(
            emergency_id=emergency_id,
            final_type=final_type,
            response_type=response_type,
            risk_score=risk_score,
            priority=priority,
            confidence=confidence,
            location=loc_dict,  # (8) تمرير الموقع كما هو
        )

    # ------------------------------------------------------------------
    # الحالة الكاملة: NLP + Vision
    # ------------------------------------------------------------------
    def _fuse_both(self, nlp: NLPResult, vision: VisionResult):
        vision_score = vision.confidence * 100
        text_score = nlp.text_urgency

        if nlp.emergency_type == vision.image_type:
            # لا يوجد تعارض -> دمج مرجّح بسيط
            final_type = nlp.emergency_type
            risk_score = round(0.5 * text_score + 0.5 * vision_score)
            confidence = round((0.5 * (text_score / 100)) + (0.5 * vision.confidence), 2)
        else:
            # تعارض بين المصدرين -> حل التعارض بالوزن الأعلى
            # القاعدة: يُعطى وزن أكبر قليلاً للرؤية لأنها تعتمد على دليل بصري مباشر
            weighted_vision = vision_score * 0.55
            weighted_text = text_score * 0.45

            if weighted_vision >= weighted_text:
                final_type = vision.image_type
            else:
                final_type = nlp.emergency_type

            # عند التعارض، الثقة النهائية أقل طبيعيًا
            risk_score = round(0.45 * text_score + 0.55 * vision_score)
            confidence = round(max(vision.confidence, text_score / 100) * 0.85, 2)
        
        if final_type == "Normal":
            risk_score = 0
        
        return final_type, risk_score, confidence

    # ------------------------------------------------------------------
    # حالة NLP فقط (Single-Source)
    # ------------------------------------------------------------------
    def _from_nlp_only(self, nlp: NLPResult):
        final_type = nlp.emergency_type

        if final_type == "Normal":
            risk_score = 0
        else:
            risk_score = nlp.text_urgency

        confidence = round((nlp.text_urgency / 100) * 0.7, 2)

        return final_type, risk_score, confidence
    # ------------------------------------------------------------------
    # حالة Vision فقط (Single-Source)
    # ------------------------------------------------------------------
    def _from_vision_only(self, vision: VisionResult):
        final_type = vision.image_type

        # Normal means there is no emergency,
        # therefore the risk score must be zero.
        if final_type == "Normal":
            risk_score = 0
        else:
            risk_score = round(vision.confidence * 100)

        # Single-source confidence is reduced by 30%
        confidence = round(vision.confidence * 0.7, 2)

        return final_type, risk_score, confidence

    # ------------------------------------------------------------------
    # (5) تحديد priority النهائي + تجاوز الأمان الحرج
    # ------------------------------------------------------------------
    def _apply_priority(self, final_type: str, risk_score: int):
        risk_score = max(0, min(100, int(risk_score)))

        priority = "low"
        for threshold, level in PRIORITY_THRESHOLDS:
            if risk_score >= threshold:
                priority = level
                break

        # تجاوز الأمان: أنواع عالية الخطورة (حريق/زلزال) فوق عتبة معينة => critical إجباري
        if final_type in HIGH_RISK_TYPES and risk_score >= CRITICAL_OVERRIDE_THRESHOLD:
            priority = "critical"
            risk_score = max(risk_score, 90)

        return risk_score, priority
