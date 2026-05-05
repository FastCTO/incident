<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

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

        return User::create([
            'organization_id' => $organization->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'cell_phone' => $data['cell_phone'] ?? null,
            'organization_name' => $organization->name,
            'role_title' => 'Account Contact',
            'password' => Hash::make($data['password']),
        ]);
    }
}
