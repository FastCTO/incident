<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class WaveAuthController extends Controller
{
    /**
     * Show the authentication page.
     */
    public function showAuthPage()
    {
        return view('wave.auth');
    }

    /**
     * Authenticate with Wave API and store session token.
     */
    public function authenticate(Request $request)
    {
        $credentials = [
            'grant_type'    => 'password',
            'response_type' => 'token',
            'client_id'     => '3rdParty',
            'scope'         => 'cloudSystemId=f69fafd7-1482-4637-add4-2b1139cd6521',
            'username'      => $request->input('username', 'vic@fsv.io'),
            'password'      => $request->input('password', 'H0tpussy')
        ];

        $url = "https://sync.wavevms.com/cdb/oauth2/token";

        $response = Http::asJson()->post($url, $credentials);
        $data = $response->json();

        Log::info('Wave Auth Response:', $data);

        if (isset($data['access_token'])) {
            Session::put('wave_token', $data['access_token']);
            Session::save(); // Force session save
            return redirect()->route('wave.camera.hls')->with('success', 'Authenticated successfully.');
        } else {
            return back()->with('error', 'Authentication failed.');
        }
    }
}

