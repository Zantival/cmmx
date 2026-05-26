import sys
import json
import random

def analyze(data_input):
    try:
        data = json.loads(data_input)
    except:
        data = {}

    try:
        rating_avg = float(data.get('average_rating', random.uniform(2.5, 5.0)))
        sales_volume = int(data.get('sales_volume', random.randint(10, 1000)))
    except (ValueError, TypeError):
        rating_avg = 4.0
        sales_volume = 100
    
    # Dummy ML inference
    health_score = (rating_avg / 5.0) * 0.6 + min(sales_volume / 100.0, 1.0) * 0.4
    
    recommendation = "APPROVE" if health_score > 0.65 else "REVIEW_REQUIRED"

    result = {
        "health_score": round(health_score, 2),
        "recommendation": recommendation,
        "input_features": {
            "rating_avg": rating_avg,
            "sales_volume": sales_volume
        }
    }
    
    print(json.dumps(result))

if __name__ == "__main__":
    analyze(sys.argv[1] if len(sys.argv) > 1 else "{}")
