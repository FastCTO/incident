<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class InvitePoliceController extends Controller
{
    public function show(Request $request, $token)
    {
        if (!URL::hasValidSignature($request)) {
            return response()->view('errors.link-expired', [], 403);
        }

        return view('invite-police');
    }

    // 🔗 Used to send police the main invite-police page
    public static function generateSecureLink()
    {
        return URL::temporarySignedRoute(
            'invite.police.show',
            now()->addMinutes(90),
            ['token' => uniqid()]
        );
    }

    // 🎥 Used INSIDE the police link to render live cameras without login
    public static function generateSecureMulticamUrl()
    {
        return URL::temporarySignedRoute(
            'secure.multistream',
            now()->addMinutes(90)
        );
    }
}

