# -*- coding: utf-8 -*-
"""
test_decision_engine.py
=========================
اختبارات وحدة لمحرك الدمج والقرار، مبنية على عقد الـ API النهائي.
تشغيل: python -m unittest test_decision_engine.py -v
"""

import unittest
from decision_engine import DecisionEngine, RESPONSE_TYPE_MAP


class TestDecisionEngine(unittest.TestCase):

    def setUp(self):
        self.engine = DecisionEngine()
        self.location = {"latitude": 15.3694, "longitude": 44.1910}

    # -------------------- حالات كاملة (NLP + Vision) --------------------

    def test_agreement_fire(self):
        """عندما يتفق NLP و Vision على نفس النوع (حريق)."""
        result = self.engine.decide(
            emergency_id="EMG-000001",
            nlp={"text_urgency": 85, "emergency_type": "Fire", "priority": "critical", "entities": []},
            vision={"image_type": "Fire", "confidence": 0.94},
            location=self.location,
        )
        self.assertEqual(result.final_type, "Fire")
        self.assertEqual(result.response_type, "Fire_Rescue")
        self.assertEqual(result.priority, "critical")
        self.assertEqual(result.location, self.location)

    def test_conflict_resolution(self):
        """تعارض بين NLP وVision: يجب أن يُحسم القرار دون خطأ ولصالح أعلى وزن."""
        result = self.engine.decide(
            emergency_id="EMG-000002",
            nlp={"text_urgency": 40, "emergency_type": "Other", "priority": "low", "entities": []},
            vision={"image_type": "Flood", "confidence": 0.9},
            location=self.location,
        )
        self.assertIn(result.final_type, ["Other", "Flood"])
        self.assertEqual(result.response_type, RESPONSE_TYPE_MAP[result.final_type])

    def test_medical_emergency_maps_to_ambulance(self):
        result = self.engine.decide(
            emergency_id="EMG-000003",
            nlp={"text_urgency": 70, "emergency_type": "Medical_Emergency", "priority": "high", "entities": []},
            vision={"image_type": "Medical_Emergency", "confidence": 0.8},
        )
        self.assertEqual(result.response_type, "Ambulance")

    def test_earthquake_high_risk_override(self):
        """الأنواع عالية الخطورة (زلزال) مع درجة عالية يجب أن تُجبر priority إلى critical."""
        result = self.engine.decide(
            emergency_id="EMG-000004",
            nlp={"text_urgency": 80, "emergency_type": "Earthquake", "priority": "high", "entities": []},
            vision={"image_type": "Earthquake", "confidence": 0.9},
        )
        self.assertEqual(result.priority, "critical")
        self.assertGreaterEqual(result.risk_score, 75)

    def test_normal_case_low_priority(self):
        result = self.engine.decide(
            emergency_id="EMG-000005",
            nlp={"text_urgency": 5, "emergency_type": "Normal", "priority": "low", "entities": []},
            vision={"image_type": "Normal", "confidence": 0.2},
        )
        self.assertEqual(result.final_type, "Normal")
        self.assertEqual(result.response_type, "No_Response")
        self.assertEqual(result.priority, "low")

    # -------------------- حالات Single-Source --------------------

    def test_nlp_only(self):
        result = self.engine.decide(
            emergency_id="EMG-000006",
            nlp={"text_urgency": 60, "emergency_type": "Traffic_Accident", "priority": "medium", "entities": []},
        )
        self.assertEqual(result.final_type, "Traffic_Accident")
        self.assertEqual(result.response_type, "Ambulance")
        self.assertLessEqual(result.confidence, 0.75)

    def test_vision_only(self):
        result = self.engine.decide(
            emergency_id="EMG-000007",
            vision={"image_type": "Fire", "confidence": 0.88},
        )
        self.assertEqual(result.final_type, "Fire")
        self.assertEqual(result.response_type, "Fire_Rescue")

    # -------------------- حالات التحقق من الأخطاء --------------------

    def test_missing_both_sources_raises(self):
        with self.assertRaises(ValueError):
            self.engine.decide(emergency_id="EMG-000008")

    def test_missing_emergency_id_raises(self):
        with self.assertRaises(ValueError):
            self.engine.decide(
                emergency_id="",
                nlp={"text_urgency": 50, "emergency_type": "Fire", "priority": "high", "entities": []},
            )

    def test_invalid_emergency_type_raises(self):
        with self.assertRaises(ValueError):
            self.engine.decide(
                emergency_id="EMG-000009",
                nlp={"text_urgency": 50, "emergency_type": "Alien_Invasion", "priority": "high", "entities": []},
            )

    def test_location_passed_through_unchanged(self):
        loc = {"latitude": 12.5, "longitude": 45.0}
        result = self.engine.decide(
            emergency_id="EMG-000010",
            vision={"image_type": "Flood", "confidence": 0.7},
            location=loc,
        )
        self.assertEqual(result.location, loc)


if __name__ == "__main__":
    unittest.main()
