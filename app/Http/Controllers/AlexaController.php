<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Develpr\AlexaApp\Facades\Alexa;
use Develpr\AlexaApp\Response\Card;
use App\Task;
use Laravel\Passport\Passport;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Illuminate\Support\Facades\Log;

class AlexaController extends Controller
{
    public function open(Request $request)
    {
        $token = $request->get('session')['user']['accessToken'];
        $userId = $this->parseToken($token);
        Log::info($token);
        Log::info($userId);
        return Alexa::say("Welcome to Laravel Mumbai Meetup.")
            ->withCard(new Card("Title", 'Subtitle', 'Content'));
    }

    public function totalTasks(Request $request)
    {
        $token = $request->get('session')['user']['accessToken'];
        $userId = $this->parseToken($token);

        $pendingTasks = Task::whereUserId($userId)->whereNull('completed')->count();
        $completedTasks = Task::whereUserId($userId)->whereNotNull('completed')->count();

        return Alexa::say("You have total " . $pendingTasks . " pending and " . $completedTasks . " completed tasks.")
            ->withCard(new Card("Title", 'Subtitle', 'Content'));
    }

    public function parseToken($token)
    {
        $key_path = Passport::keyPath('oauth-public.key');
        $this->parseTokenKey = file_get_contents($key_path);

        $token = (new Parser())->parse((string)$token);
        $signer = new Sha256();
        if ($token->verify($signer, $this->parseTokenKey)) {
            $userId = $token->getClaim('sub');
            return $userId;
        } else {
            return false;
        }
    }
}
