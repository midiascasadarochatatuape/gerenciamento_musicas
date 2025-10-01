<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $remember = $request->filled('remember');

        return $this->guard()->attempt(
            $this->credentials($request),
            $remember
        );
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Se o remember estiver marcado, configurar cookie para 1 ano
        if ($request->filled('remember')) {
            // 1 ano em minutos
            $oneYearInMinutes = 525600;

            // Regenerar remember token
            $user->setRememberToken(\Illuminate\Support\Str::random(60));
            $user->save();

            // Configurar cookie remember com duração de 1 ano
            $recaller = $user->id . '|' . $user->getRememberToken() . '|' . $user->getAuthPassword();

            cookie()->queue(
                Auth::getRecallerName(),
                encrypt($recaller),
                $oneYearInMinutes,
                null,
                null,
                config('session.secure', false), // secure baseado na configuração
                true  // httpOnly
            );

            // Log para debug (remova em produção)
            \Log::info('Remember token configurado', [
                'user_id' => $user->id,
                'remember_token' => $user->getRememberToken(),
                'cookie_lifetime' => $oneYearInMinutes . ' minutes (1 year)',
                'cookie_name' => Auth::getRecallerName()
            ]);
        }
    }
}
