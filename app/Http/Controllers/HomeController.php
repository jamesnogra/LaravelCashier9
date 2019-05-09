<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function subscriptions()
    {
        $user = Auth::user();
        return view('subscriptions', ['user'=>$user]);
    }

    public function subscribe(Request $request)
    {
        $user = Auth::user();
        $user->newSubscription($request->plan, $request->plan_id)->create($request->stripeToken);
        return redirect(action('HomeController@subscriptions'));
    }

    public function changeSubscription(Request $request)
    {
        $user = Auth::user();
        if ($request->plan_id=='CANCEL') {
            $user->subscription($request->plan)->cancelNow();
        } else {
            $user->subscription($request->plan)->swap($request->plan_id);
        }
        return redirect(action('HomeController@subscriptions'));
    }

    public function invoice(Request $request)
    {
        return $request->user()->downloadInvoice($request->invoice_id, [
            'vendor'  => 'Dog Treats',
            'product' => 'Product',
        ]);
    }
}
