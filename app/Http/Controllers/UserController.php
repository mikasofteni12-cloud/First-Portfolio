<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {

    // dd($request->all(), $request->file('avatar'));

        $user = User::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users,email,' . $id,
            'avatar'=> 'nullable|image|mimes:png,jpg,jpeg,gif|max:1048',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;

        // if uploaded img
        if($request->hasFile('avatar')){
            // delete if already has img
            if($user->avatar && Storage::disk('public')->exists($user->avatar)){
                Storage::disk('public')->delete($user->avatar);
            }

            // store new img to storage/app/public/avatars
            $path = $request->file('avatar')->store('avatars', 'public');

            // set the avatar column in DB
            $user->avatar = $path;
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // if the user as img also delete
        if($user->avatar && Storage::disk('public')->exists($user->avatar)){
            Storage::disk('public')->delete($user->avatar);
        }
        
        $user->delete();

        // back to welcome.blade ; ligin register screen
        if (Auth::id() == $id){
            Auth::logout();
            return redirect()->route('views.welcome')->with('success', 'Your account has been deleted.');
        }

        // if admin deleted some account
        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}
