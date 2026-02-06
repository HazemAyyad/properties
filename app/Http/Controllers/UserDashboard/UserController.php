<?php

namespace App\Http\Controllers\UserDashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Dashboard\CustomDeclarationItem;
use App\Http\Controllers\Dashboard\Label;
use App\Http\Controllers\Dashboard\Order;
use App\Http\Controllers\Dashboard\TrackNo;
use App\Http\Controllers\Dashboard\UserCard;
use App\Mail\AfterVerified;
use App\Mail\GeneralEmail;
use App\Models\Dashboard\Admin;

use App\Models\Dashboard\Country;
use App\Models\Dashboard\HistoryCoins;
use App\Models\Dashboard\Invoice;
use App\Models\Dashboard\PackageItem;
use App\Models\Dashboard\Setting;
use App\Models\Dashboard\ShipmentPackage;
use App\Models\Dashboard\State;
use App\Models\Dashboard\UserAddresses;
use App\Models\Dashboard\Wallet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
class UserController extends Controller
{
    function __construct()
    {


    }
    public function profile(){
        $user=Auth::user();
         return view('user_dashboard.profile.index',compact('user'));

    }


    public function update(Request $request){
        $user = User::find(Auth::id());
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'company' => 'required',
            'position' => 'required',
            'office_no' => 'required',
            'office_address' => 'required',
            'job' => 'required',
            'location' => 'required',
            'facebook' => 'required|url',  // Validate as URL
            'twitter' => 'required|url',   // Validate as URL
            'linkedin' => 'required|url',  // Validate as URL
             'mobile' => 'required|unique:users,mobile,'.Auth::id(),
             'email' => 'required|unique:users,email,'.Auth::id(),
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation for photo
            'agent_poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation for agent poster
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input=$request->all();
            return response(["responseJSON" => $errors,"input"=>$input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        $data = $request->all();
        unset($data['_token']);

        if ($validator->passes()) {
            // Handling file uploads
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoName = time() . '_' . $photo->getClientOriginalName();
                $photo->move(public_path('uploads/avatars'), $photoName);
                $data['photo'] = '/public/uploads/avatars/' . $photoName;
            }

            if ($request->hasFile('agent_poster')) {
                $agentPoster = $request->file('agent_poster');
                $posterName = time() . '_' . $agentPoster->getClientOriginalName();
                $agentPoster->move(public_path('uploads/posters'), $posterName);
                $data['agent_poster'] = '/public/uploads/posters/' . $posterName;
            }

            $user->update($data);
            return response()->json(['success'=>"The process has successfully"]);
        }
    }
    public function delete_account($id)
    {
        $user =User::find($id);
        $arr = array('msg' => 'There are some errors, try again', 'status' => false);
        if($user){
            $user->delete();
            $arr = array('msg' => "operation accomplished successfully", 'status' => true);
        }
        return Response()->json($arr);

    }

    public function update_password(Request $request)
    {
//        return $request->all();
        $messages = [
            'password_confirmation.same' => __('Password does not match'),
            'password.required' => __('Please enter the password'),
        ];

        $validator = Validator::make($request->all(), [
            'password' => 'required|same:password|min:6',
            'password_confirmation' => 'required|same:password|min:6',
        ], $messages);
        $user = User::query()->where('id', Auth::id())->first();
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input = $request->all();
            return response(["responseJSON" => $errors, "input" => $input, "message" => __('Verify that the data is correct, fill in all fields')], 422);
        }

        if ($validator->passes()) {

            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json(['success' => __('The process has successfully')]);


        }

    }




 }
