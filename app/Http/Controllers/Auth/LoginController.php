<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function authenticate(Request $request)
    {
        $credentials = array(
            'email' => $request->email,
            'password' => $request->password,
            'status'=>1,
            'is_remove'=>1,

        );

        if (Auth::attempt($credentials))
        {
            User::where('id',Auth::user()->id)->update(['active'=>1]);
            return redirect()->intended('dashboard');
        }
        else {
            return Redirect::to('login')
                ->withMessage(['Invalid username or password','Account need to be approve first by the admin']);
        }
    }

    public function redirectPath()
    {
        switch(Auth::user()->user_type){
            case 1:
                $url = '/admin/dashboard';
                break;
            case 2:
                $url = '/home';
                break;
            case 3:
                $url = '/home';
                break;
        }
        return $url;
    }

}
