<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

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

    public function saveToken(Request $request)
    {
        auth()->user()->update(['device_token'=>$request->token]);

        return response()->json(['token saved successfully.']);
    }

    public function sendNotification(Request $request)
    {
        $firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();
        $SERVER_API_KEY = 'AAAACimbyX8:APA91bF0TiU_gTM9OEHjJlmHRBILh2YW9w30wz7gJb-roqx0LB622BENsrIRN3WnthpbBOP9N-eN2_NckQ6x4dddLv4DkTEjgAy1Egbzdnjp3hGx53YdI2OQe9PvsgriOlg6LZ_zpOc8';

        $data = [
            "registration_ids" => $firebaseToken,
            "priority"  => "high",
            "data"      => [
                "link"  => "https://web.facebook.com/"
            ],
            "notification"  => [
                "title" => $request->title,
                "body"  => $request->body,
                "click_action" => "https://web.facebook.com/",
            ]
        ];
        $dataString = json_encode($data);
  
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];
  
        $ch = curl_init();
  
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
              
        $response = curl_exec($ch);
        return json_encode($response);
        // echo json_encode($response);
    }
}
