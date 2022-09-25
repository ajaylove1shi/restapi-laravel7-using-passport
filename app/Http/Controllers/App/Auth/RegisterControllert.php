<?php

namespace App\Http\Controllers\App\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Register controller is a class for registers.
 *
 * Register is a class that has registers related data.
 *
 * @package Register
 * @subpackage Controller
 * @author Ajay Lowanshi <www.Ajaylove1shi.com>
 * @version 1.0.0
 */

class RegisterControllert extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->input('name'),
            'email'    => $request->input('email'),
            'password' => sha1($request->input('password')),
        ]);

        if (!empty($user)) {

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

            return successApiResponse('Welcome to App...', array_merge($user->toArray(), [
                'access' => [
                    'token'      => $tokenResult->accessToken,
                    'type'       => 'Bearer',
                    'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
                ],
            ]), 201);
        }

        return failedApiResponse('Ooops! Something went wrong, Please try again after sometime...', [], 401);
    }
}
