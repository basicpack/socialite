<?php
//Anderson Ismael
//08 de abril de 2019

require __DIR__.'/vendor/autoload.php';

use Illuminate\Http\Request;
use Illuminate\Support\Fluent;
use Laravel\Socialite\SocialiteManager;
use Symfony\Component\HttpFoundation\Session\Session;

function socialite($serviceName){
    //https://github.com/recca0120/laravel-socialite-standalone-demo
    $app = new Fluent();
    $config = new Fluent();
    $request = Request::capture();
    $session = new Session();
    $session->start();
    $request->setSession($session);
    $config['services.facebook'] = [
        'client_id' => $_ENV['FACEBOOK_KEY'],
        'client_secret' => $_ENV['FACEBOOK_SECRET'],
        'redirect' => $_ENV['FACEBOOK_REDIRECT_URI'],
    ];
    $config['services.github'] = [
        'client_id' => $_ENV['GITHUB_KEY'],
        'client_secret' => $_ENV['GITHUB_SECRET'],
        'redirect' => $_ENV['GITHUB_REDIRECT_URI'],
    ];
    $app['config'] = $config;
    $app['request'] = $request;
    $socialiteManager = new SocialiteManager($app);
    $provider = $socialiteManager
    ->with($serviceName)
    ->stateless();
    if (strpos($_SERVER['REQUEST_URI'], '/callback') === false) {
        $response = $provider->redirect();
        $response->send();
        return false;
    }else{
        return (array) $provider->user();
    }
}
