<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Mail\General;
use App\Models\Dashboard\City;
use App\Models\Dashboard\Notification;
use App\Models\Dashboard\Order;
use App\Models\Dashboard\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

class NotificationController extends Controller
{
    public function index(){
        return view('dashboard.notifications.add');
    }
    public function user(){
        $users_all=User::all();
        return view('dashboard.notifications.user',compact('users_all'));
    }
    public function send_notifications_all(Request $request) {
//        return $request->all();
        // FCM API Url
        $url = 'https://fcm.googleapis.com/fcm/send';

        // Put your Server Key here
        $apiKey = "AAAAIG0oiI8:APA91bGaXvuDa8f9zEzg8NJgOid9OKyhTJn7JfCxvpdRQHAbu2wz7iGvBMnXJQod_5XY9iNJHpP4S3Eknx70Dcoc2ZnuVzLKNbBIZk3frZCmiSAF7uLcEbZQnepbZHx7UAlToHOqdNs9";


        // Compile headers in one variable
        $headers = array (
            'Authorization:key=' . $apiKey,
            'Content-Type:application/json'
        );
        $data =$request->all();
        if ($request->hasfile('image') ) {
            $image_url = $request->file('image');
            $image_name = url('/') . '/public/uploads/notifications/' . time() . '.' . $image_url->getClientOriginalExtension();
            $image_url->move(public_path('uploads/notifications/'), $image_name);

            $data['image'] = $image_name;
        }else{
            $image_name='';
        }

        // Add notification content to a variable for easy reference
        $notifData = [
            'title' => $request->title,
            'body' => $request->description,
            "image" =>  $image_name,
//            'click_action' => "activities.NotifHandlerActivity" //Action/Activity - Optional
        ];
//        Notification::query()->create([
//
//            'data'=>'[{"title":"'.$notifData['title'].'","body":"'.$notifData['body'].'","image":"'.$notifData['image'].'"}]',
//        ]);
        $dataPayload = ['to'=> 'My Name',
            'points'=>80,
            'other_data' => 'This is extra payload'
        ];
        $notifData['date']=$request->date;
        // Create the api body
        if ($notifData['date']!=null){
            $apiBody = [
                'notification' => $notifData,
                'data' => $dataPayload, //Optional
//            'time_to_live' => 600, // optional - In Seconds
                'to' => '/topics/all',
                "isScheduled" => true,
                "scheduledTime" => $notifData['date']
                //'registration_ids' = ID ARRAY
//            'to' => 'cc3y906oCS0:APA91bHhifJikCe-6q_5EXTdkAu57Oy1bqkSExZYkBvL6iKCq2hq3nrqKWymoxfTJRnzMSqiUkrWh4uuzzEt3yF5KZTV6tLQPOe9MCepimPDGTkrO8lyDy79O5sv046-etzqCGmKsKT4'
            ];
        }
        else{
            $apiBody = [
                'notification' => $notifData,
                'data' => $dataPayload, //Optional
//            'time_to_live' => 600, // optional - In Seconds
                'to' => '/topics/all',

                //'registration_ids' = ID ARRAY
//            'to' => 'cc3y906oCS0:APA91bHhifJikCe-6q_5EXTdkAu57Oy1bqkSExZYkBvL6iKCq2hq3nrqKWymoxfTJRnzMSqiUkrWh4uuzzEt3yF5KZTV6tLQPOe9MCepimPDGTkrO8lyDy79O5sv046-etzqCGmKsKT4'
            ];
        }


        // Initialize curl with the prepared headers and body
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_POST, true);
        curl_setopt ($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_POSTFIELDS, json_encode($apiBody));

        // Execute call and save result
        $result = curl_exec($ch);
//        print($result);
        // Close curl after call
        curl_close($ch);

        return response()->json(['success'=>"The process has successfully"]);
    }
    function send_notification($fcm_token,$data)
    {
        $from = "AAAAIG0oiI8:APA91bGaXvuDa8f9zEzg8NJgOid9OKyhTJn7JfCxvpdRQHAbu2wz7iGvBMnXJQod_5XY9iNJHpP4S3Eknx70Dcoc2ZnuVzLKNbBIZk3frZCmiSAF7uLcEbZQnepbZHx7UAlToHOqdNs9";

        $dataPayload = ['to'=> 'Hisham App',
            'points'=>80,
            'other_data' => 'This is extra payload'
        ];
//        return$data;
        if ($data['date']!=null){
            $apiBody = [
                'notification' => $data,
                'data' => $dataPayload, //Optional
//            'time_to_live' => 600, // optional - In Seconds
                'to' => $fcm_token,
                "isScheduled" => true,
                "scheduledTime" => $data['date']
                //'registration_ids' = ID ARRAY
//            'to' => 'cc3y906oCS0:APA91bHhifJikCe-6q_5EXTdkAu57Oy1bqkSExZYkBvL6iKCq2hq3nrqKWymoxfTJRnzMSqiUkrWh4uuzzEt3yF5KZTV6tLQPOe9MCepimPDGTkrO8lyDy79O5sv046-etzqCGmKsKT4'
            ];
        }
        else{
            $apiBody = [
                'notification' => $data,
                'data' => $dataPayload, //Optional
//            'time_to_live' => 600, // optional - In Seconds
                'to' => $fcm_token,
                //'registration_ids' = ID ARRAY
//            'to' => 'cc3y906oCS0:APA91bHhifJikCe-6q_5EXTdkAu57Oy1bqkSExZYkBvL6iKCq2hq3nrqKWymoxfTJRnzMSqiUkrWh4uuzzEt3yF5KZTV6tLQPOe9MCepimPDGTkrO8lyDy79O5sv046-etzqCGmKsKT4'
            ];
        }
        // Create the api body
        $apiBody = [
            'notification' => $data,
            'data' => $dataPayload, //Optional
//            'time_to_live' => 600, // optional - In Seconds
            'to' => $fcm_token,
            //'registration_ids' = ID ARRAY
//            'to' => 'cc3y906oCS0:APA91bHhifJikCe-6q_5EXTdkAu57Oy1bqkSExZYkBvL6iKCq2hq3nrqKWymoxfTJRnzMSqiUkrWh4uuzzEt3yF5KZTV6tLQPOe9MCepimPDGTkrO8lyDy79O5sv046-etzqCGmKsKT4'
        ];

        $headers = array
        (
            'Authorization: key=' . $from,
            'Content-Type: application/json'
        );
        //#Send Reponse To FireBase Server
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($apiBody));
        $result = curl_exec($ch);
        curl_close($ch);


    }
    public function send_to_user(Request $request){
        $user=User::query()->where('id',$request->user_id)->first();
        $data =$request->all();
        if ($request->hasfile('image') ) {
            $image_url = $request->file('image');
            $image_name = url('/') . '/public/uploads/notifications/' . time() . '.' . $image_url->getClientOriginalExtension();
            $image_url->move(public_path('uploads/notifications/'), $image_name);

            $data['image'] = $image_name;
        }else{
            $image_name='';
        }

        // Add notification content to a variable for easy reference
        $notifData = [
            'title' => $request->title,
            'body' => $request->description,
            "image" =>  $image_name,
//            'click_action' => "activities.NotifHandlerActivity" //Action/Activity - Optional
        ];
//        Notification::query()->create([
//            'receiver_id'=>$user->id,
//            'data'=>'[{"title":"'.$notifData['title'].'","body":"'.$notifData['body'].'","image":"'.$notifData['image'].'"}]',
//        ]);
        $notifData['date']=$request->date;
        $this->send_notification($user->fcm_token,$notifData);
        return response()->json(['success'=>"The process has successfully"]);

    }
    public function show($notify_id){

        $user=Auth::user();
        $notification= $user->notifications()->findOrFail($notify_id);
//        return $notification->data['type'];
        $notification->markAsRead();
        return  Redirect::to($notification->data['url']);
    }
    public function read_all(){

        $market=Auth::user();
        $notifications= Auth::user()->unreadNotifications()->get();
//        return $notifications;
        $notifications->markAsRead();
        return redirect()->back();
    }
    public function notifications()
    {$user=Auth::user();
        $notifications= $user->notifications()->get();

        return view('dashboard.notifications.index',compact('notifications'));
    }

}
