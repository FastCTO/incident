<?php

namespace App\Http\Controllers;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;
use Illuminate\Support\Facades\Auth;

class VideoLogController extends Controller
{
    public function index()
    {
	$client = new DynamoDbClient([
    'region' => 'us-west-2',
    'version' => 'latest',
    'credentials' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
    ],
]);

        $marshaler = new Marshaler();

        $result = $client->scan([
            'TableName' => 'video_access_logs'
        ]);

        $logs = [];

        if ($result['Count'] > 0) {
            foreach ($result['Items'] as $item) {
                $logs[] = $marshaler->unmarshalItem($item);
            }
        }

        return view('video_logs', ['logs' => $logs]);
    }
}

