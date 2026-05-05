<?php

namespace App\Http\Controllers;

use App\Services\SinchSmsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DemoNotificationController extends Controller
{
    public function sendDvrAuditAlert(SinchSmsService $sms): RedirectResponse
    {
        $user = Auth::user();

        $emailTo = env('FSV_ALERT_EMAIL', 'vic@fsv.io');

        $subject = 'FSV Incident Test DVR Audit Alert';

        $shortMessage = 'FSV Incident test alert: DVR/NVR audit needed. Please review the video source profile and confirm recording, access, time sync, and retention.';

        $body = implode("\n", [
            'FSV Incident Test DVR Audit Alert',
            '',
            'This is a demo/test notification from FSV Incident.',
            '',
            'Audit focus:',
            '- Confirm DVR/NVR is online',
            '- Confirm recording is active',
            '- Confirm time/date settings',
            '- Confirm remote access',
            '- Confirm estimated retention',
            '- Review latest audit notes',
            '',
            'Triggered by:',
            $user?->display_name ?? $user?->name ?? $user?->email ?? 'Unknown user',
            '',
            'Triggered at:',
            now()->toDateTimeString(),
        ]);

        $emailSent = false;
        $smsSent = false;

        try {
            Mail::raw($body, function ($message) use ($emailTo, $subject) {
                $message->to($emailTo)->subject($subject);
            });

            $emailSent = true;
        } catch (\Throwable $e) {
            Log::warning('Demo DVR audit email notification failed.', [
                'error' => $e->getMessage(),
                'email_to' => $emailTo,
            ]);
        }

        try {
            $smsSent = $sms->sendAdminAlert($shortMessage);
        } catch (\Throwable $e) {
            Log::warning('Demo DVR audit SMS notification failed.', [
                'error' => $e->getMessage(),
            ]);
        }

        Log::info('Demo DVR audit notification triggered.', [
            'email_sent' => $emailSent,
            'sms_sent' => $smsSent,
            'triggered_by_user_id' => $user?->id,
        ]);

        if ($emailSent && $smsSent) {
            return back()->with('success', 'Test DVR audit alert sent by email and SMS.');
        }

        if ($emailSent) {
            return back()->with('success', 'Test DVR audit alert email sent. SMS did not confirm.');
        }

        if ($smsSent) {
            return back()->with('success', 'Test DVR audit alert SMS sent. Email did not confirm.');
        }

        return back()->with('success', 'Test DVR audit alert was triggered, but email/SMS did not confirm. Check logs.');
    }
}
