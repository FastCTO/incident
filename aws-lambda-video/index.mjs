// index.mjs
import { DynamoDBClient, PutItemCommand } from "@aws-sdk/client-dynamodb";

const ddbClient = new DynamoDBClient({ region: "us-west-2" });

export async function handler(event) {
  console.log("Received event:", JSON.stringify(event, null, 2));

  let body;
  try {
    body = typeof event.body === "string" ? JSON.parse(event.body) : event.body;
  } catch (err) {
    console.error("❌ Error parsing JSON:", err);
    return {
      statusCode: 400,
      body: JSON.stringify({ error: "Invalid JSON" }),
    };
  }

  const { videoId, hash, viewerId, outboundPhone } = body;

  if (!videoId || !viewerId || !outboundPhone) {
    console.error("❌ Missing required fields");
    return {
      statusCode: 400,
      body: JSON.stringify({ error: "Missing required fields" }),
    };
  }

  console.log("🔹 videoId:", videoId);
  console.log("🔹 hash:", hash);
  console.log("🔹 viewerId:", viewerId);
  console.log("🔹 outboundPhone:", outboundPhone);

  const item = {
    videoId: { S: videoId },
    viewerId: { S: viewerId },
    outboundPhone: { S: outboundPhone },
    timestamp: { S: new Date().toISOString() },
    ipAddress: { S: event.requestContext?.identity?.sourceIp || "unknown" },
    additionalData: {
      M: {
        hash: { S: hash },
      },
    },
  };

  const command = new PutItemCommand({
    TableName: "video_access_logs",
    Item: item,
  });

  try {
    await ddbClient.send(command);
    console.log("✅ Video access logged to DynamoDB");
  } catch (err) {
    console.error("❌ DynamoDB put error:", err);
    return {
      statusCode: 500,
      body: JSON.stringify({ error: "Internal server error" }),
    };
  }

  return {
    statusCode: 200,
    body: JSON.stringify({
      message: "Data received and logged!",
      received: { videoId, hash, viewerId, outboundPhone },
    }),
  };
}

