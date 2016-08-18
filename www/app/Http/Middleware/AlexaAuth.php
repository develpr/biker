<?php

namespace Biker\Http\Middleware;

use Biker\User;
use Closure;
use Alexa;
use Develpr\AlexaApp\Response\Card;

class AlexaAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $accessToken = Alexa::request()->getAccessToken();
        if( ! $accessToken ){
            $test = Alexa::say("Before I can help you check for bikes you'll need to sign into the biker app.")
                ->withCard(new Card('Link Account', 'Please Link Your Account', '', Card::LINK_ACCOUNT_CARD_TYPE));
            return response($test);
        }
        
        $user = \Biker\User::where(['alexa_token' => $accessToken])->first();
       
        if( ! $user ){
            $test = Alexa::say("Before I can help you check for bikes you'll need to sign into the biker app.")
                ->withCard(new Card('Link Account', 'Please Link Your Account', '', Card::LINK_ACCOUNT_CARD_TYPE));
            return response($test);
        }
        
        
        \Auth::login($user);
        $user = \Auth::user();

        return $next($request);
    }
}
