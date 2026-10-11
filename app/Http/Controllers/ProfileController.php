<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // deisplay profile details
    public function show()
    {
        $user = Auth::user();

        $my_posts = $user->posts()->latest()->get();

        $targetRole = ($user->role === 'seeker') ? 'owner' : 'seeker';

        $bookmarkedPosts = $user->bookmarkedPosts()
                                 ->whereHas('user', function($query) use ($targetRole){
                                    $query->where('role', $targetRole);
                                 })->latest()->get();

        return view('users.show', compact('user', 'my_posts', 'bookmarkedPosts'));
    }

    // edit profile
    public function edit()
    {
        $user = Auth::user();

        return view('users.edit', compact('user'));
    }

    // update
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'  => ['required', 'string', 'max:250'],
            'email' => ['required', 'string', 'max:250', Rule::unique('users')->ignore($user->id)],
            'avatar'=> ['nullable', 'image', 'mimes:jpeg,png,jpg.gif', 'max:1048'],
        ]);

        $data = [
            'name' => $request->name,
            'email'=> $request->email,
        ];
        // if new image are uploaded
        if($request->hasFile('avatar')){
            // if there is a privious img existed, it will delete
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)){
                Storage::disk('public')->delete($user->avatar);
            }
            // store new img to storage/app/public/avatars file
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        $user->update($data);

        return redirect()->route('profile.show')->with('status', 'Profile updated Successfully.');
    }

    // delete
    public function destroy(Request $request)
    {
        $user = Auth::user();

        // if avatar exists also delete
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)){
            Storage::disk('public')->delete($user->avatar);
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Your account has been Deleted.');
    }
}
