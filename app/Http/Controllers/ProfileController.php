<?php

namespace App\Http\Controllers;

use App\Models\Contact;

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
        return view('admin.profile.edit', [
            'user' => $request->user(),
            'contacts' => $request->user()->contacts,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
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

    /**
     * Add a new contact to the user's profile.
     */
    public function addContact(Request $request): RedirectResponse
    {
        $request->validate([
            'contact_name' => ['required', 'string', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:255'],
        ], [
            'contact_name.required' => 'Trường tên liên hệ không được để trống.',
            'contact_phone.required' => 'Trường số điện thoại không được để trống.',
        ]);

        $request->user()->contacts()->create([
            'name' => $request->contact_name,
            'phone' => $request->contact_phone,
        ]);

        return Redirect::route('profile.edit')->with('success', 'Thêm liên hệ thành công');
    }

    /**
     * Update a contact in the user's profile.
     */
    public function updateContact(Request $request, Contact $contact): RedirectResponse
    {
        $request->validate([
            'contact_name' => ['required', 'string', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:255'],
        ], [
            'contact_name.required' => 'Trường tên liên hệ không được để trống.',
            'contact_phone.required' => 'Trường số điện thoại không được để trống.',
        ]);

        $contact->update([
            'name' => $request->contact_name,
            'phone' => $request->contact_phone,
        ]);

        return Redirect::route('profile.edit')->with('success', 'Câp nhật liên hệ thành công');
    }

    /**
     * Delete a contact from the user's profile.
     */
    public function deleteContact(Contact $contact): RedirectResponse
    {
        $contact->delete();

        return Redirect::route('profile.edit')->with('success', 'Xoá liên hệ thành công');
    }
}
