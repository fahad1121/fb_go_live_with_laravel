<?php
namespace App\Http\Controllers;

use App\Models\User;
use CURLFile;
use Exception;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Illuminate\Support\Facades\Auth;
use JetBrains\PhpStorm\NoReturn;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function facebookRedirect(): \Symfony\Component\HttpFoundation\RedirectResponse|\Illuminate\Http\RedirectResponse
    {
        return Socialite::driver('facebook')->scopes(['email','user_photos','publish_actions'])->redirect();
    }
    public function loginWithFacebook(): \Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        try {

            $user = Socialite::driver('facebook')->stateless()->user();
            $existingUser = User::where('facebook_provider_id', $user->id)->first();

            if($existingUser){

                User::where('facebook_provider_id',$user->id)->update(
                    [
                        'facebook_session_token' => $this->getLongLivedAccessToken($user->token)
                    ]
                );

                Auth::login($existingUser);
                return redirect('/home');
            }else{

                $createUser = User::create([
                    'name'                      => $user->name,
                    'email'                     => $user->email,
                    'facebook_provider_id'      => $user->id,
                     'facebook_session_token'   => $this->getLongLivedAccessToken($user->token),
                     'password'                 => encrypt('admin@123')
                ]);

                Auth::login($createUser);
                return redirect('/home');
            }

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * @throws FacebookSDKException
     */
    #[NoReturn] public function uploadLiveSavedVideoToFacebook(){
        $fb = new Facebook([
            'app_id' => config('services.facebook.client_id'),
            'app_secret' => config('services.facebook.client_secret'),
            'default_graph_version' => 'v18.0',
        ]);
        $video = public_path('fb_videos/video_1.mp4');
        $token = Auth::user()->facebook_session_token;
        dd($token);

    }

    private function getLongLivedAccessToken($token){
        $url = "https://graph.facebook.com/v12.0/oauth/access_token?grant_type=fb_exchange_token&client_id=".config('services.facebook.client_id')."&client_secret=".config('services.facebook.client_secret')."&fb_exchange_token=$token";
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        return $data['access_token'];
    }
}
