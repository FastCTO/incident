<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use App\Services\SinchSmsService;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    private string $notificationEmail = 'vic@fsv.io';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'organization_name' => ['nullable', 'string', 'max:255'],
            'cell_phone' => ['nullable', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        $organizationName = trim($data['organization_name'] ?? '');

        if ($organizationName === '') {
            $organizationName = $data['name'] . ' Account';
        }

        $organization = Organization::create([
            'name' => $organizationName,
            'organization_type' => 'customer',
            'status' => 'active',
            'contact_name' => $data['name'],
            'contact_email' => $data['email'],
            'contact_phone' => $data['cell_phone'] ?? null,
            'country' => 'US',
            'notes' => 'Organization created during public registration.',
        ]);

        $user = User::create([
            'organization_id' => $organization->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'cell_phone' => $data['cell_phone'] ?? null,
            'organization_name' => $organization->name,
            'role_title' => 'Account Contact',
            'password' => Hash::make($data['password']),
        ]);

        $this->sendRegistrationNotification($data, $organization, $user);

        app(SinchSmsService::class)->sendAdminAlert(
            "New FSV registration: {$user->name} / {$user->cell_phone} / {$user->email} / {$organization->name}"
        );

        return $user;
    }

    private function sendRegistrationNotification(array $data, Organization $organization, User $user): void
    {
        try {
            $body = implode("\n", [
                'New FSV Incident registration',
                '',
                'Name: ' . $user->name,
                'Email: ' . $user->email,
                'Phone: ' . ($user->cell_phone ?? '-'),
                '',
                'Organization: ' . $organization->name . ' (#' . $organization->id . ')',
                'Organization Type: ' . $organization->organization_type,
                'Organization Status: ' . $organization->status,
                '',
                'User ID: ' . $user->id,
                'Submitted at: ' . now()->toDateTimeString(),
            ]);

            Mail::raw($body, function ($message) use ($organization) {
                $message->to($this->notificationEmail)
                    ->subject('FSV Incident Registration: ' . $organization->name);
            });
        } catch (\Throwable $e) {
            Log::warning('Registration notification email failed.', [
                'error' => $e->getMessage(),
                'organization_id' => $organization->id,
                'user_id' => $user->id,
            ]);
        }
    }
}
