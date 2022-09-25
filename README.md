## Laravel 7 REST API with Passport Authentication 💻

<a href="//github.com/ajaylove1shi/restapi-laravel7-using-passport"> Restapi Laravel Using Passport </a> 

``` 
- ⚡ Login Api, 
- ⚡ Register Api, 
- ⚡ Forgot Password Api, 
- ⚡ Reset Password Api &amp; 
- ⚡ User Info Api.
``` 

#### Step 1: Install Laravel

``laravel new project-name``  
or  
``composer create-project --prefer-dist laravel/laravel project-name``

#### Step 2: Database Configuration

Create a database and configure the env file.  

#### Step 3: Passport Installation

To get started, install Passport via the Composer package manager:

``composer require laravel/passport``

The Passport service provider registers its own database migration directory with the framework, so you should migrate your database after installing the package. The Passport migrations will create the tables your application needs to store clients and access tokens:

``php artisan migrate``

Next, you should run the `passport:install` command. This command will create the encryption keys needed to generate secure access tokens. In addition, the command will create "personal access" and "password grant" clients which will be used to generate access tokens:

``php artisan passport:install``

#### Step 4: Passport Configuration

After running the `passport:install` command, add the `Laravel\Passport\HasApiTokens` trait to your `App` model. This trait will provide a few helper methods to your model which allow you to inspect the authenticated user's token and scopes:

```
<?php
namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

}
```

Next, you should call the `Passport::routes` method within the `boot` method of your `AuthServiceProvider`. This method will register the routes necessary to issue access tokens and revoke access tokens, clients, and personal access tokens:

```
<?php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
        //
    }
}
```

Finally, in your `config/auth.php` configuration file, you should set the `driver` option of the `api` authentication guard to `passport`. This will instruct your application to use Passport's `TokenGuard` when authenticating incoming API requests:

```
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],

    'api' => [
        'driver' => 'passport',
        'provider' => 'users',
    ],
],
```

#### Step 8: Create API Routes
In this step, we will create api routes. Laravel provide api.php file for write web services route. So, let's add new route on that file.

**routes/api.php**

```
<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

/**
|-----------------------------------------------
| Authentication Routes.......
|-----------------------------------------------
 */
Route::group(['namespace' => 'App\Auth', 'middleware' => 'api', 'prefix' => 'auth'], function () {

    //Login...
    Route::post('login', 'LoginController@login');

    //Register...
    // Route::post('register', 'RegisterController@register');
    Route::post('register', 'RegisterControllert@register');
    //Forgot Password...
    Route::post('password/forgot', 'ForgotPasswordController@forgot');

    //Reset Password...
    Route::get('token/{token}', 'ResetPasswordController@token');
    Route::post('reset', 'ResetPasswordController@reset');
});

/**
|-----------------------------------------------
| Logout Routes.......
|-----------------------------------------------
 */
Route::group(['namespace' => 'App\Auth', 'middleware' => 'auth:api', 'prefix' => 'auth'], function () {
    Route::get('logout', 'LoginController@logout');
});

/**
|-----------------------------------------------
| Profile Routes.......
|-----------------------------------------------
 */
Route::group(['namespace' => 'App\User', 'middleware' => 'auth:api'], function () {
    Route::get('profile', 'UserController@profile');
    Route::post('profile/update', 'UserController@profileUpdate');
    Route::post('profile/change-password', 'UserController@profileChangePassword');
});
```

#### Step 8: Create Helper Functions

**app/Helpers/Helpers.php**

```
 <?php

/**
|-----------------------------------------------
| Api Response Helper Functions.......
|-----------------------------------------------
 */
if (!function_exists('indexApiResponse')) {
    function indexApiResponse($text = '', $results = [], $code = '201')
    {
        return response()->json(['status' => true, 'message' => 'All ' . ucfirst($text) . ' has been fetched successfully.', 'results' => $results], $code);
    }
}
if (!function_exists('showApiResponse')) {

    function showApiResponse($text = '', $results = [], $code = '201')
    {
        return response()->json(['status' => true, 'message' => ucfirst($text) . ' has been fetched successfully.', 'results' => $results], $code);
    }
}

if (!function_exists('storeApiResponse')) {
    function storeApiResponse($text = '', $results = [], $code = '201')
    {
        return response()->json(['status' => true, 'message' => ucfirst($text) . ' has been added successfully.', 'results' => $results], $code);
    }
}

if (!function_exists('updateApiResponse')) {
    function updateApiResponse($text = '', $results = [], $code = '201')
    {
        return response()->json(['status' => true, 'message' => ucfirst($text) . ' has been updated successfully.', 'results' => $results], $code);
    }
}

if (!function_exists('destroyApiResponse')) {
    function destroyApiResponse($text = '', $results = [], $code = '201')
    {
        return response()->json(['status' => true, 'message' => ucfirst($text) . ' has been trashed successfully.', 'results' => $results], $code);
    }
}

if (!function_exists('validatorApiResponse')) {
    function validatorApiResponse($errors = [])
    {
        return response()->json(['status' => false, 'message' => 'Please fill all required fields.', 'errors' => $errors]);
    }
}

if (!function_exists('failedApiResponse')) {
    function failedApiResponse($message = '', $results = [], $code = '201')
    {
        return response()->json(['status' => false, 'message' => $message, 'results' => $results], $code);
    }
}
if (!function_exists('successApiResponse')) {

    function successApiResponse($message = '', $results = [], $code = '201')
    {
        return response()->json(['status' => true, 'message' => $message, 'results' => $results], $code);
    }
}

if (!function_exists('errorApiResponse')) {

    function errorApiResponse()
    {
        return response()->json(['status' => 'error', 'title' => 'Error!', 'text' => 'Something is wrong, please try again...']);
    }
}

if (!function_exists('statusApiResponse')) {
    function statusApiResponse($message = '')
    {
        return response()->json(['status' => 'success', 'title' => 'Changed!', 'text' => $message]);
    }
}
```


**app\Http\Controllers\App\Auth\LoginController.php**

```
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

```
**app\Http\Controllers\App\Auth\RegisterControllert.php**

```
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

```
**app\Http\Controllers\App\Auth\ForgotPasswordController.php**

```
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

```
**app\Http\Controllers\App\User\UserController.php**

```
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

```
Now we are ready to run full restful api and also passport api in laravel. so let's run our example so run bellow command for quick run:

``php artisan serve``

make sure in details api we will use following headers as listed bellow:

```
'headers' => [
    'Accept'        => 'application/json',
    'Authorization' => 'Bearer '.$accessToken,
]
```

Here is Routes URL with Verb:

Now simply you can run above listed url like:

- **User Register API:** Verb:POST, URL: http://127.0.0.1:8000/api/auth/register
- **User Login API:** Verb:POST, URL: http://127.0.0.1:8000/api/auth/login
- **User Logout API:** Verb:GET, URL: http://127.0.0.1:8000/api/auth/logout
- **User Forgot Password API:** Verb:POST, URL: http://127.0.0.1:8000/api/auth/password/forgot
- **User User Profile API:** Verb:GET, URL: http://127.0.0.1:8000/api/profile
- **User User Profile Update API:** Verb:GET, URL: http://127.0.0.1:8000/api/profile/update
- **User User Change Password API:** Verb:POST, URL: http://127.0.0.1:8000/api/profile/change-password

<br><br>


I write about software development on [my blog!](https://www.ajaylove1shi.com), Want to know how I may help your project? or just want to say hi? [contact me!](https://www.ajaylove1shi.com/contact-me) or send me an email to [ajaylove1shi@gmail.com!](mailto:ajaylove1shi@gmail.com).

[![Twitter: ajaylove1shi](https://img.shields.io/twitter/follow/ajaylove1shi?style=social)](https://twitter.com/ajaylove1shi)
[![Linkedin: ajaylove1shi](https://img.shields.io/badge/-ajaylove1shi-blue?style=flat-square&logo=Linkedin&logoColor=white&link=https://www.linkedin.com/in/ajaylove1shi/)](https://www.linkedin.com/in/ajaylove1shi/)
[![GitHub ajaylove1shi](https://img.shields.io/github/followers/ajaylove1shi?label=follow&style=social)](https://github.com/ajaylove1shi)
[![website](https://img.shields.io/badge/Blog-ajaylove1shi.com-2648ff?style=flat-square&logo=google-chrome)](https://www.ajaylove1shi.com)

Want to get connected? Follow me on the social channels below.
<p>
<a href="https://www.facebook.com/ajaylove1shi">
	<img align="left" alt="Ajay's Facebook" width="20px" src="https://raw.githubusercontent.com/ajaylove1shi/ajaylove1shi/main/fb.svg" />
</a>
<a href="https://www.instagram.com/ajaylove1shi/">
	<img align="left" alt="Ajay's Instagram" width="20px" src="https://raw.githubusercontent.com/ajaylove1shi/ajaylove1shi/main/insta.svg" />
</a>
<a href="https://twitter.com/ajaylove1shi">
	<img align="left" alt="Ajay's Twitter" width="20px" src="https://raw.githubusercontent.com/ajaylove1shi/ajaylove1shi/main/twitter.svg" />
</a>
<a href="https://www.youtube.com/channel/ajaylove1shi">
	<img align="left" alt="Ajay's Youtube" width="20px" src="https://raw.githubusercontent.com/ajaylove1shi/ajaylove1shi/main/youtube.svg" />
</a>
<a href="https://www.linkedin.com/in/ajaylove1shi">
	<img align="left" alt="Ajay's Linkdein" width="20px" src="https://raw.githubusercontent.com/ajaylove1shi/ajaylove1shi/main/linkedin.svg" /> 
</a>
<a href="https://github.com/ajaylove1shi">
	<img align="left" alt="Ajay's Github" width="20px" src="https://raw.githubusercontent.com/ajaylove1shi/ajaylove1shi/main/github.svg" />
</a>
</p>

<br>

<!--
**ajaylove1shi/ajaylove1shi** is a ✨ _special_ ✨ repository because its `README.md` (this file) appears on your GitHub profile.
[![Website Badge](https://img.shields.io/badge/-crumet-47CCCC?style=flat&logo=Google-Chrome&logoColor=white&link=https://jaylove1nshi)](https://jaylove1nshi.com)
[![Linkedin Badge](https://img.shields.io/badge/-ajaylove1shi-blue?style=flat&logo=Linkedin&logoColor=white&link=https://www.linkedin.com/in/ajaylove1shi/)](https://www.linkedin.com/in/ajaylove1shi/)
[![Twitter Badge](https://img.shields.io/badge/-@ajaylove1shi-1ca0f1?style=flat&labelColor=1ca0f1&logo=twitter&logoColor=white&link=https://twitter.com/ajaylove1shi)](https://twitter.com/ajaylove1shi)
[![Instagram Badge](https://img.shields.io/badge/-@ajaylove1shi-purple?style=flat&logo=instagram&logoColor=white&link=https://instagram.com/ajaylove1shi/)](https://instagram.com/ajaylove1shi)
[![Gmail Badge](https://img.shields.io/badge/-ajaylove1shi-c14438?style=flat&logo=Gmail&logoColor=white&link=mailto:ajaylove1shi@gmail.com)](mailto:ajaylove1shi@gmail.com)

Here are some ideas to get you started:

- 🔭 I’m currently working on ...
- 🌱 I’m currently learning ...
- 👯 I’m looking to collaborate on ...
- 🤔 I’m looking for help with ...
- 💬 Ask me about ...
- 📫 How to reach me: ...
- 😄 Pronouns: ...
- ⚡ Fun fact: ...
-->
