<?php

namespace App\Http\Controllers\App\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * ForgotPassword controller is a class for ForgotPasswords.
 *
 * ForgotPassword is a class that has ForgotPasswords related data.
 *
 * @package ForgotPassword
 * @subpackage Controller
 * @author Ajay Lowanshi <www.Ajaylove1shi.com>
 * @version 1.0.0
 */
class ForgotPasswordController extends Controller
{
    public function forgot(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!empty($user)) {
            $new_pass       = substr(hash('sha512', rand()), 0, 12);
            $user->password = sha1($new_pass);
            if ($user->save()) {

                // \Mail::send([], [], function ($message) {
                //     $message->to($user->email, $user->username . ' ' . $user->surname)
                //         ->subject('Password Reset Successful')
                //         ->setBody('Hi')
                //         ->setBody('<h1>Congratulations! Mail is working...</h1>', 'text/html');
                // });

                // return redirect('login')->with('success', 'Mail is Sent, Please login.');

                return successApiResponse('New password has been send to your email address successfully...', [
                    'new_pass' => $new_pass,
                ], 201);
            }
        }

        return failedApiResponse("We can't find a user with that e-mail address.", [], 401);
    }
}
