#!/bin/bash
curl -X POST "https://sms.api.sinch.com/xms/v1/0986c99adc6346249028b5a5d5543331/batches" \
-H "Content-Type: application/json" \
-H "Authorization: Bearer 0b75f8d79d7d40cb9f7003e5498c29f0" \
-d '{
  "to": ["+12137034927"],
  "from": "19312230233",
  "body": "Test Emergency Alert."
}'

