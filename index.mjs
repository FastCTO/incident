// Get your servicePlanId and API token 
    // from the Sinch Customer Dashboard: 
    // https://dashboard.sinch.com/sms/api/rest

import fetch from 'node-fetch';

// Import any other connections you'll need here

async function run() {
  const servicePlanId = '0986c99adc6346249028b5a5d5543331';
  const resp = await fetch(
    `https://us.sms.api.sinch.com/xms/v1/${servicePlanId}/groups`,
    {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Authorization: '0b75f8d79d7d40cb9f7003e5498c29f0'
      },
      body: JSON.stringify({
        members: [
          '12137034927',
          '12132228424',
          '12139957041', 
        ],
        name: 'FSV-test'
      })
    }
  );

  const data = await resp.json();
  console.log(data);
}

run();
