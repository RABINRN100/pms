<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function update(RegisterRequest $request){
        $data = $request->validated();
        $id = auth()->user()->id;
        $existingUser = User::where('email', $data['email'])->where('id', '!=', $id)->first();
        if (!empty($existingUser)) {
            return redirect()->back()->with('error', 'Email address already exists. Please use a different email address.')->withInput();
        }
        $existingUser = User::where('mobile', $data['mobile'])->where('id', '!=', $id)->first();
        if (!empty($existingUser)) {
            return redirect()->back()->with('error', 'Mobile address already exists. Please use a different email address.')->withInput();
        }
        if ($request->hasFile('profile_pic')) {
            $fileName = time() . '.' . $request->profile_pic->extension();
            $request->profile_pic->move(public_path('profile'), $fileName);
            $data['profile_pic'] = $fileName;
        }
        $user = auth()->user();
        $user->update($data);
            
        return redirect()->back()->with('success', 'Profile updated Successfully!');
    }

    public function deleteProfilePic(Request $request)
    {
        $user = User::find($request->user_id);

        if ($user->profile_pic) {
            $filePath = public_path('profile/' . $user->profile_pic);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $user->update(['profile_pic' => null]);
            return redirect()->back()->with('success', 'Profile picture deleted successfully.');
        }
        return redirect()->back()->with('error', 'No profile picture found to delete.');
    }
}
