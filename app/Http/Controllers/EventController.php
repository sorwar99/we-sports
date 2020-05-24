<?php

namespace App\Http\Controllers;

use App\ApiHandlers\CallHandler;
use App\ImageManager\ImageManager;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    private $callHandler;
    private $imageManager;

    public function __construct(CallHandler $callHandler, ImageManager $imageManager)
    {
        $this->callHandler = $callHandler;
        $this->imageManager = $imageManager;
    }

    public function createEvent()
    {
        $sports = $this->callHandler->unauthorizedGetMethodHandler('/sports');

        $list = [];

        foreach ($sports as $sport) {
            array_push($list, [
                'id' => $sport['id'],
                'name' => $sport['name']
            ]);
        }
        return view('wesports.events.create', array('sports' => $list));
    }

    public function storeEvent(Request $request)
    {
        $imagePath = $this->imageManager->moveEventImage($request['img']);
        if ($imagePath !== null) {
            $token = $request->session()->get('api_token');
            $eventData = $request->all();
            $eventData['img'] = $imagePath;
            dump($eventData['img']);
            $eventData['creator_id'] = Auth::user()->getAuthIdentifier();
            unset($eventData['_token']);
            $response = $this->callHandler->authorizedPostMethodHandler('/events', $token, $eventData);
        }
    }

    public function eventList(Request $request)
    {
        $sports = $this->callHandler->unauthorizedGetMethodHandler('/sports');

        $requestUrl = '/events?';
        if ($request->get('sport')) {
            $requestUrl .= 'sport=' . $request->get('sport') . '&';
        }
        if ($request->get('city')) {
            $cityParsed = $request->get('city');
            $requestUrl .= 'city=' . $this->callHandler->parseURL($cityParsed) . '&';
        }
        if ($request->get('date')) {
            $requestUrl .= 'datetime=' . $request->get('date') . '&';
        }
        $events = $this->callHandler->unauthorizedGetMethodHandler($requestUrl);
        return view('wesports.events.events-page', [
            'events' => $events,
            'sports' => $sports
        ]);
    }

    public function eventDetail(Request $request)
    {
        $url = url()->current();
        $pos_id = strrpos($url, "/", 0);
        $id = substr($url, $pos_id + 1, strlen($url));
        $requestUrl = '/events/' . $id;

        $event = $this->callHandler->unauthorizedGetMethodHandler($requestUrl);
        $event = \reset($event);
        if (empty($event)) {
            return redirect('/events');
        }

        $participants = $this->callHandler->unauthorizedGetMethodHandler($requestUrl . '/participants');
        $eventSportId = $event['sport_id'];
        $relatedEventsRequestUrl = '/events?sport='.$eventSportId;
        $relatedEvents = $this->callHandler->unauthorizedGetMethodHandler($relatedEventsRequestUrl);
        if (count($relatedEvents) > 4){
            shuffle($relatedEvents);
            array_splice($relatedEvents, 0, 3);
        }

        $loggedUserId = null;
        $isParticipating = null;
        $token = null;
        if (Auth::user()) {
            $token = $request->session()->get('api_token');
            $loggedUserId = Auth::user()->getAuthIdentifier();
            $urlParticipating = '/participating?user_id=' . $loggedUserId . '&event_id=' . $id;
            $isParticipating = $this->callHandler->unauthorizedGetMethodHandler($urlParticipating);
        }

        return view('wesports.events.detail', [
            'event' => $event,
            'participants' => $participants,
            'loggedUserId' => $loggedUserId,
            'token' => $token,
            'isParticipating' => $isParticipating,
            'relatedEvents' => $relatedEvents
        ]);
    }
}
