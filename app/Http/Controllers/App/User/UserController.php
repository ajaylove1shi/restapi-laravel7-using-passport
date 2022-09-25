<?php

namespace App\Http\Controllers\App\User;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * User controller is a class for Users.
 *
 * User is a class that has Users related data.
 *
 * @package User
 * @subpackage Controller
 * @author Ajay Lowanshi <www.Ajaylove1shi.com>
 * @version 1.0.0
 */

class UserController extends Controller
{

    /**
     * [profile: getting profile data]
     */
    public function profile(Request $request)
    {
        if ($request->ajax()) {
            return successApiResponse('Profile has been fatched successfully.', $request->user(), 201);
        }
    }

    /**
     * [profile: update profile data]
     */
    public function profileUpdate(Request $request)
    {
        if ($request->ajax()) {

            $request->validate([
                'email' => 'required|string|email',
                'name'  => 'required|string',
            ]);

            DB::table('users')->where('id', $request->user()->id)->update([
                'email' => $request->email,
                'name'  => $request->name,
            ]);

            $user = DB::table('users')->find($request->user()->id);

            return successApiResponse('Profile has been updated successfully.', $user, 201);
        }
    }

    /**
     * [profile: profile Change Password]
     */
    public function profileChangePassword(Request $request)
    {
        if ($request->ajax()) {

            $request->validate([
                'current_password' => 'required|string|min:8',
                'password'         => 'required|string|min:8|confirmed',
            ]);

            $user = User::where('password', sha1($request->current_password))->first();
            if (!empty($user)) {
                $user = DB::table('user')->where('id', $request->user()->id)->update([
                    'password' => sha1($request->password),
                ]);
                return successApiResponse('Password has been updated successfully.', $request->user(), 201);
            }
            return failedApiResponse('Opps! current password does not match.');
        }
    }

}
