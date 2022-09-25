<?php

namespace App\Http\Controllers\App\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Login controller is a class for logins.
 *
 * Login is a class that has logins related data.
 *
 * @package Login
 * @subpackage Controller
 * @author Ajay Lowanshi <www.Ajaylove1shi.com>
 * @version 1.0.0
 */
class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
            // 'remember_me' => 'boolean'
        ]);

        $user = User::where('email', $request->email)->first();
        if (!empty($user)) {
            if (sha1($request->password) == $user->password) {

                //login user...
                Auth::login($user);

                //create token...
                $user        = $request->user();
                $tokenResult = $user->createToken('Personal Access Token');
                $token       = $tokenResult->token;
                if ($request->remember_me) {
                    $token->expires_at = Carbon::now()->addWeeks(1);
                }
                $token->save();

                return successApiResponse('Login Successfully...', array_merge($user->toArray(), [
                    'access' => [
                        'token'      => $tokenResult->accessToken,
                        'type'       => 'Bearer',
                        'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
                    ],
                ]), 201);
            }
        }

        return failedApiResponse('These credentials do not match our records.', [], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return successApiResponse('Successfully logged out', [], 201);
    }

}
