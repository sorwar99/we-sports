<?php

namespace App\Http\Controllers;

use App\ApiHandlers\CallHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private $callHandler;

    public function __construct(CallHandler $callHandler)
    {
        $this->callHandler = $callHandler;
    }

    public function getProfile()
    {
        $url = url()->current();
        $pos_nickname = strrpos($url, "/", 0);
        $nickname = substr($url, $pos_nickname + 1, strlen($url));
        $requestUrl = '/users?nickname=' . $nickname;
        $user = $this->callHandler->unauthorizedGetMethodHandler($requestUrl);;
        $user = \reset($user);
        if (!empty($user)){
            $userId = $user['id'];
            $events = $this->getUserEvents($userId);
            return view('user.profile', [
                'user' => $user,
                'createdEvents' => $events
            ]);
        } dump('TODO si no existe el usuario, si usuario existe pero no tiene eventos que mostrar');
    }

    public function getUserEvents(int $userId)
    {
        $requestUrl = '/users-events/'.$userId;
        return $this->callHandler->unauthorizedGetMethodHandler($requestUrl);
    }

}