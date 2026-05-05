<?php

namespace App\Http\Controllers;

use App\Models\NvrSystem;
use App\Models\Organization;
use App\Models\Site;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class FreeCheckupController extends Controller
{
    public function create()
    {
        return view('free-checkup.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'contact_name' => ['required', 'string', 'max:255'],
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:50'],
            'can_view_on_phone' => ['required', 'in:yes,no,not_sure'],
            'can_view_away_from_site' => ['required', 'in:yes,no,not_sure'],

            'organization_name' => ['nullable', 'string', 'max:255'],
            'site_name' => ['nullable', 'string', 'max:255'],
            'physical_address' => ['nullable', 'string', 'max:255'],
            'dvr_location' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $organizationName = trim($validated['organization_name'] ?? '');

        if ($organizationName === '') {
            $organizationName = $validated['contact_name'] . ' Free Checkup';
        }

        $organization = Organization::create([
            'name' => $organizationName,
            'organization_type' => 'customer',
            'status' => 'prospect',
            'contact_name' => $validated['contact_name'],
            'contact_email' => $validated['contact_email'],
            'contact_phone' => $validated['contact_phone'],
            'country' => 'US',
            'notes' => "Free checkup request created from public form.",
        ]);

        $nameParts = preg_split('/\s+/', trim($validated['contact_name']), 2);

        $user = User::create([
            'organization_id' => $organization->id,
            'name' => $validated['contact_name'],
            'first_name' => $nameParts[0] ?? null,
            'last_name' => $nameParts[1] ?? null,
            'email' => $validated['contact_email'],
            'cell_phone' => $validated['contact_phone'],
            'organization_name' => $organization->name,
            'role_title' => 'Checkup Contact',
            'password' => Hash::make(Str::random(32)),
        ]);

        $siteName = trim($validated['site_name'] ?? '');

        if ($siteName === '') {
            $siteName = 'Free Checkup Site';
        }

        $site = Site::create([
            'organization_id' => $organization->id,
            'name' => $siteName,
            'site_type' => 'facility',
            'status' => 'prospect',
            'contact_name' => $validated['contact_name'],
            'contact_email' => $validated['contact_email'],
            'contact_phone' => $validated['contact_phone'],
            'address' => $validated['physical_address'] ?? null,
            'country' => 'US',
            'time_zone' => 'America/Chicago',
            'notes' => $validated['notes'] ?? null,
        ]);

        $nvrNotes = [
            'Free checkup request',
            'Can view cameras from phone/app: ' . $validated['can_view_on_phone'],
            'Can view cameras away from site: ' . $validated['can_view_away_from_site'],
        ];

        if (!empty($validated['dvr_location'])) {
            $nvrNotes[] = 'DVR/NVR location: ' . $validated['dvr_location'];
        }

        if (!empty($validated['notes'])) {
            $nvrNotes[] = 'Notes: ' . $validated['notes'];
        }

        NvrSystem::create([
            'organization_id' => $organization->id,
            'site_id' => $site->id,
            'name' => 'Free Checkup Request',
            'system_type' => 'unknown',
            'status' => 'needs_checkup',
            'access_notes' => implode("\n", $nvrNotes),
            'notes' => 'Created from public Start Free Checkup form.',
        ]);

        Auth::login($user);

        return redirect()
            ->route('sites.index')
            ->with('success', 'Free checkup request received. We created your account and started your site profile.');
    }
}
