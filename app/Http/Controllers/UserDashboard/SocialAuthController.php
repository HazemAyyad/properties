<?php
namespace App\Http\Controllers\UserDashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SocialAuthController extends Controller
{
    /**
     * Redirect to the respective social provider.
     *
     * @param string $provider
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from the respective social provider.
     *
     * @param string $provider
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->user();
            $finduser = User::where($provider . '_id', $user->id)->first();

            if ($finduser) {
                Auth::login($finduser);
                return redirect()->intended('user/dashboard');
            } else {
                // Save user data including profile photo URL
                $newUser = User::updateOrCreate(
                    ['email' => $user->email],
                    [
                        'name' => $user->name,
                        $provider . '_id' => $user->id,
                        'photo' => $user->avatar, // Profile photo URL
                        'password' => encrypt('123456dummy')
                    ]
                );

                Auth::login($newUser);
                return redirect()->intended('user/dashboard');
            }

        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}

