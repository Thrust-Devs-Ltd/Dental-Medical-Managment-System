<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $user = User::where('id', Auth::User()->id)->first();
        return view('profile.index', compact('user'));
    }

    //update user bio data info
    public function Update_Bio(Request $request)
    {
        Validator::make($request->all(), [
            'surname' => 'required|string',
            'othername' => 'required|string',
            'email' => 'required|email',
        ])->validate();

        $user = User::where('id', Auth::User()->id)->update(
            [
                'surname' => $request->surname,
                'othername' => $request->othername,
                'email' => $request->email,
                'phone_no' => $request->phone_number,
                'alternative_no' => $request->alternative_no,
                'nin' => $request->national_id,
            ]);
        if ($user) {
            return response()->json(['message' => 'Your profile has been updated successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Error has occurred,please try again later', 'status' => false]);
    }


    public function Update_Avatar(Request $request)
    {
        Validator::make($request->all(), [
            'avatar' => ['required', 'mimes:jpeg,bmp,png,jpg'],
        ])->validate();

        $file = $request->file('avatar');
        //generate hashed string
        $hashed = time();

        $filename = $hashed . '_' . $file->getClientOriginalName();
        $file->move('uploads/users', $filename);
        //now update the photo
        $updated_user = User::where('id', Auth::User()->id)->update(['photo' => $filename]);

        $user = User::where('id', $updated_user)->first();
        return redirect('/profile');

    }

    public function ChangePassword(Request $request)
    {
        Validator::make($request->only('old_password', 'new_password', 'confirm_password'), [
            'old_password' => 'required|string|min:6',
            'new_password' => 'required|string|min:6|different:old_password',
            'confirm_password' => 'required_with:new_password|same:new_password|string|min:6',
        ], [
            'confirm_password.required_with' => 'Confirm password is required.'
        ])->validate();

        //now update password
        $user = User::where('id', Auth::User()->id)->update(['password' => Hash::make($request->new_password)]);
        if ($user) {
            return response()->json(['message' => 'Your password has been changed successfully', 'status' => true]);
        }
        return response()->json(['message' => 'Oops an error has occuried please try again', 'status' => false]);
    }


}
