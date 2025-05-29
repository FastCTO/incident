import axios from "axios";

// Replace with your API endpoint
const apiUrl = "https://3061sy2r1b.execute-api.us-west-2.amazonaws.com/production/video";

// Replace with your API key if you have one
const apiKey = ""; // e.g., "abcdef123456"

// Data to send
const data = {
  videoId: "vid123",
  hash: "abc123",
  viewerId: "user456"
};

const headers = {
  "Content-Type": "application/json"
};

if (apiKey) {
  headers["x-api-key"] = apiKey;
}

async function sendVideoData() {
  try {
    const response = await axios.post(apiUrl, data, { headers });
    console.log("✅ Success:", response.data);
  } catch (error) {
    console.error("❌ Error:", error.response ? error.response.data : error.message);
  }
}

sendVideoData();

