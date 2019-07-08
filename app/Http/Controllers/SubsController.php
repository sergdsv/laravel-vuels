<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subscription;
use App\Mail\SubscribeEmail;

class SubsController extends Controller
{
    public function subscribe(Request $request)
    {
    	$subscriber = Subscription::add($request->get('email'));
        $subscriber->generateToken();
    	\Mail::to($subscriber)->send(new SubscribeEmail($subscriber));


    	return redirect()->back()->with('status', 'Проверьте Вашу почту и подвердите подписку!');
    }

    public function verify($token)
    {
    	$subscriber = Subscription:: where('token', $token)->firstOrFail();
    	$subscriber->token = null;
    	$subscriber->save();
    	return redirect('/')->with('status', 'Ваша почта подтверждена!');
    }
}
