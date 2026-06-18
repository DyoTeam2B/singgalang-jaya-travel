<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user()->loadMissing(['pelanggan', 'driver.armada']);

        $view = match ($user->role) {
            'admin' => 'profile.admin-edit',
            'driver' => 'profile.driver-edit',
            'pelanggan' => 'profile.public-edit',
            default => 'profile.edit',
        };

        return view($view, [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $user = $request->user();

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->syncRoleProfile($request, $validated);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Keep role-specific profile tables aligned with the account profile.
     *
     * @param array<string, mixed> $validated
     */
    private function syncRoleProfile(ProfileUpdateRequest $request, array $validated): void
    {
        $user = $request->user()->loadMissing(['pelanggan', 'driver']);

        if ($user->role === 'pelanggan' && ($request->has('no_hp') || $user->pelanggan)) {
            $user->pelanggan()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nama' => $validated['name'],
                    'no_hp' => $validated['no_hp'] ?? $user->pelanggan?->no_hp ?? '',
                ]
            );
        }

        if ($user->role === 'driver' && $user->driver) {
            $user->driver->update([
                'nama_driver' => $validated['name'],
                'no_hp' => $validated['no_hp'] ?? $user->driver->no_hp,
            ]);
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
