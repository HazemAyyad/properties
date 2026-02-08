<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
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
//        $this->middleware(
//            'permission:Staff List', ['only' => ['index']]);
//        $this->middleware('permission:Staff Create',
//            ['only' => ['create','store']]);
//        $this->middleware('permission:Staff Edit',
//            ['only' => ['edit','update']]);
//        $this->middleware('permission:Staff Delete',
//            ['only' => ['delete']]);

    }
    public function index(){

        return view('dashboard.users.index');

    }
    public function login($id){
        $user=User::query()->find($id);
        Auth::login($user);
        if(Auth::check()){
            $user->update(['ip_admin'=>$_SERVER['REMOTE_ADDR']]);
//            $user= Auth::user();
//            Session::put('user', $user);
//          return  'User IP Address - '.$_SERVER['REMOTE_ADDR'];
//            $user_id = DB::table('oauth_access_tokens')->where('id', $user_token)->first();
        return    Redirect::to(env('SITE_URL').'login-admin/'.$id.'/'.$_SERVER['REMOTE_ADDR']);
        }
//        return Auth::user();
//        return view('dashboard.users.index');

    }
    public function get_users(Request $request)
    {


        return DataTables::of(User::query()->orderBy('created_at','desc')->get())
            ->addIndexColumn()
            ->addColumn('action', function($row) use ($request){

                    $url=route('admin.users.edit',$row->id);
                    $url_login=route('admin.users.login',$row->id);
                    $url_email = route('admin.users.email_to_user', $row->id);


                $btn = '<a href="'.$url .'" class="ms-2"><i class="fas fa-edit text-success"></i></a>
                        <a href="'.$url_login .'" target="_blank" class="ms-2"><i class="fa-solid fa-right-to-bracket text-primary" ></i></a>
                         <a href="' . $url_email . '" class="ms-2"><i class="fas bi-envelope-open text-success"></i></a>

                        <a href="javascript:void(0)" onclick="deleteItem('.$row->id.')" class=""><i class="fas fa-trash-alt text-danger"></i></a>';

                return $btn;
            })

            ->editColumn('name', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->name . '</strong>';
            })
            ->editColumn('user_no', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->user_no . '</strong>';
            })
            ->editColumn('created_at', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->created_at->format('m/d/Y - H:i') . '</strong>';
            })
            ->addColumn('from', function ($row) {
                if ($row->google_id!=null){
                return '<strong class="Titillium-font text-danger">Google</strong>';

                }else{
                    return '<strong class="Titillium-font text-danger">Normal</strong>';

                }
            })
            ->editColumn('email_verified_at', function ($row) {
                if ($row->email_verified_at != null) {
                    $status = 'checked';
                } else {
                    $status = '';
                }

                return '<label class="switch switch-square" for="email_verified_at-'.$row->id.'">
                          <input type="checkbox" class="switch-input" onclick="changeStatusVerify('.$row->id.')" value="'.$row->email_verified_at.'" name="email_verified_at" id="email_verified_at-' . $row->id . '" ' . $status . ' />
                          <span class="switch-toggle-slider">
                            <span class="switch-on"><i class="ti ti-check"></i></span>
                            <span class="switch-off"><i class="ti ti-x"></i></span>
                          </span>

                        </label>';
            })
//            ->escapeColumns('name')
            ->rawColumns(['name','user_no','email','email_verified_at','mobile','created_at','action','from'])
            ->make(true);

    }
    public function get_user_referrals(Request $request,$user_id)
    {

        $user=User::query()->findOrFail($user_id);
        return DataTables::of(User::query()->where('parent_referral',$user->referral_code)->get())
            ->addIndexColumn()
            ->addColumn('action', function($row) use ($request){

                    $url=route('users.edit',$row->id);
                    $url_login=route('users.login',$row->id);
                    $url_email = route('users.email_to_user', $row->id);
                    $url_balance = route('users.add_balance_to_user', $row->id);

                $btn = '<a href="'.$url .'" class="ms-2"><i class="fas fa-edit text-success"></i></a>
                        <a href="'.$url_login .'" target="_blank" class="ms-2"><i class="fa-solid fa-right-to-bracket text-primary" ></i></a>
                         <a href="' . $url_email . '" class="ms-2"><i class="fas bi-envelope-open text-success"></i></a>
                <a href="' . $url_balance . '" class="ms-2"><i class="fas fa-wallet text-warning"></i></a>
                        <a href="javascript:void(0)" onclick="deleteItem('.$row->id.')" class=""><i class="fas fa-trash-alt text-danger"></i></a>';

                return $btn;
            })

            ->editColumn('name', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->name . '</strong>';
            })
            ->editColumn('user_no', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->user_no . '</strong>';
            })
            ->editColumn('created_at', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->created_at->format('m/d/Y - H:i') . '</strong>';
            })
            ->editColumn('email_verified_at', function ($row) {
                if ($row->email_verified_at != null) {
                    $status = 'checked';
                } else {
                    $status = '';
                }

                return '<label class="switch switch-square" for="email_verified_at-'.$row->id.'">
                          <input type="checkbox" class="switch-input" onclick="changeStatusVerify('.$row->id.')" value="'.$row->email_verified_at.'" name="email_verified_at" id="email_verified_at-' . $row->id . '" ' . $status . ' />
                          <span class="switch-toggle-slider">
                            <span class="switch-on"><i class="ti ti-check"></i></span>
                            <span class="switch-off"><i class="ti ti-x"></i></span>
                          </span>

                        </label>';
            })
//            ->escapeColumns('name')
            ->rawColumns(['name','user_no','email','email_verified_at','mobile','created_at','action'])
            ->make(true);

    }
    public function create(){



        return view('dashboard.users.add');

    }
    public function edit($id){
        $user=User::query()->findOrFail($id);
       $count_orders= ShipmentPackage::query()->where(['user_id'=>$id,['status','!=',0]])->count();
       $wallet=Wallet::query()->where('user_id',$id)->first();
        $count_referrals=User::query()->where('parent_referral',$user->referral_code)->count();
       if ($wallet){
           $count_coins=$wallet->balance;
       }else{
           Wallet::query()->create(['user_id'=>$id,'balance'=>0]);
           $count_coins=0;
       }
        return view('dashboard.users.edit',compact('user','count_orders','count_coins','count_referrals'));



    }
    public function security($id){
        $user=User::query()->findOrFail($id);
       $count_orders= ShipmentPackage::query()->where(['user_id'=>$id,['status','!=',0]])->count();
       $count_coins=Wallet::query()->where('user_id',$id)->first()->balance;
        $count_referrals=User::query()->where('parent_referral',$user->referral_code)->count();
        return view('dashboard.users.security',compact('user','count_orders','count_coins','count_referrals'));



    }

    public function address($id,$type){
        $user=User::query()->findOrFail($id);
        $count_orders= ShipmentPackage::query()->where(['user_id'=>$id,['status','!=',0]])->count();
        $count_coins=Wallet::query()->where('user_id',$id)->first()->balance;
        $count_referrals=User::query()->where('parent_referral',$user->referral_code)->count();
        return view('dashboard.users.addresses',compact('user','count_orders','count_coins','type','count_referrals'));
    }
    public function referrals($id){
        $user=User::query()->findOrFail($id);
        $count_orders= ShipmentPackage::query()->where(['user_id'=>$id,['status','!=',0]])->count();
        $count_coins=Wallet::query()->where('user_id',$id)->first()->balance;
        $count_referrals=User::query()->where('parent_referral',$user->referral_code)->count();

        return view('dashboard.users.referrals',compact('user','count_orders','count_coins','count_referrals'));
    }
    public function coins($id){
        $user=User::query()->findOrFail($id);
        $count_orders= ShipmentPackage::query()->where(['user_id'=>$id,['status','!=',0]])->count();
        $count_coins=Wallet::query()->where('user_id',$id)->first()->balance;
        $count_referrals=User::query()->where('parent_referral',$user->referral_code)->count();

        return view('dashboard.users.coins',compact('user','count_orders','count_coins','count_referrals'));
    }
    public function invoices($id){
        $user=User::query()->findOrFail($id);
        $count_orders= ShipmentPackage::query()->where(['user_id'=>$id,['status','!=',0]])->count();
        $count_coins=Wallet::query()->where('user_id',$id)->first()->balance;
        $count_referrals=User::query()->where('parent_referral',$user->referral_code)->count();
        return view('dashboard.users.invoices',compact('user','count_orders','count_coins','count_referrals'));
    }
    public function track_package($carrier_code,$racking_number){




        if ($carrier_code=='UPS'){
            $carrier_code='ups';
        }elseif ($carrier_code=='FEDEX'){
            $carrier_code='fedex';
        }
        elseif ($carrier_code=='DHL'){
            $carrier_code='dhl_express';
//                $carrier_code='dhl_global_mail';
        }
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.shipengine.com/v1/tracking?carrier_code=".$carrier_code."&tracking_number=".$racking_number,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Host: api.shipengine.com",
                "API-Key: NGH+WrBPMyi+vuODX+QtyQ87n2Ui5OoRi1eXmHgTRxk",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $result_api = json_decode($response, true);

        return $result_api;

    }

    public function get_user_coins(Request $request, $user_id)
    {


        return DataTables::of(HistoryCoins::query()->where(['user_id'=>$user_id])
            ->orderBy('id','desc')->get())
            ->addIndexColumn()
            ->editColumn('created_at', function($row) use ($request) {
                return '<strong  tabindex="0" data-toggle="tooltip" >' . $row->created_at->format('m/d/Y - H:i') . '</strong>';



            })
            ->editColumn('amount', function($row) use ($request) {
                return '<strong  tabindex="0" data-toggle="tooltip" >' . $row->amount . '</strong>';
})
            ->editColumn('type', function ($row) {

                $type = $row->type;
                if ($type == '0') {
                    $class = 'text-success';
                    $tooltipetitle ='Admin';
                }
                elseif ($type == '2') {
                    $class = 'text-primary';
                    $tooltipetitle ='Shipment';
                }
                else {
                    $class = 'text-danger';
                    $tooltipetitle = 'Other';
                }
                return '<strong  class="' . $class . ' " tabindex="0" data-toggle="tooltip" title="' . $tooltipetitle . '" >' . $tooltipetitle . '</strong>';

            })
            ->rawColumns(['amount','type','created_at'])
            ->make(true);

    }
    public function get_user_invoices(Request $request, $user_id)
    {


        return DataTables::of(Invoice::query()->where(['user_id' => $user_id])->get())
            ->addIndexColumn()
            ->addColumn('action', function ($row) use ($request) {
                if ($row->status == 2) {
//                    $url = route('pay-invoice', $row->package_id);

                    $btn = '';
                } elseif ($row->status == 1) {
                    $url = route('users.order-invoice', [$row->package_id, $row->id]);
                    $btn = '<a href="' . $url . '" target="_blank" class="ms-2"><i class="fa-solid fa-download text-success"></i></a>';
                } else {
                    $btn = '';
                }

                return $btn;
            })
            ->editColumn('invoice_no', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->invoice_no . '</strong>';
            })
            ->editColumn('amount', function ($row) {
                return '<strong class="Titillium-font danger">$' . $row->amount . '</strong>';
            })
            ->editColumn('created_at', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->created_at->format('d/m/Y') . '</strong>';
            })
            ->editColumn('status', function ($row) {

                $status = $row->status;
                if ($status == 1) {
                    $class = 'text-success';
                    $tooltipetitle = __('Paid');

                } elseif ($status == 2) {
                    $class = 'text-warning';
                    $tooltipetitle = __('Need Pay');
                } elseif ($status == 3) {
                    $class = 'text-danger';
                    $tooltipetitle = __('Canceled');
                } else {
                    $class = 'text-primary';
                    $tooltipetitle = __('Pending');
                }
                return '<strong  class="' . $class . ' " tabindex="0" data-toggle="tooltip" title="' . $tooltipetitle . '" >' . $tooltipetitle . '</strong>';

            })
//            ->escapeColumns('name')
            ->rawColumns(['invoice_no', 'amount', 'created_at', 'status', 'action'])
            ->make(true);

    }
    public function order_invoice($package_id,$invoice_id){

        $package=ShipmentPackage::query()->where(['id'=>$package_id])
            ->orderBy('id','desc')->with(['address_from','address_going','full_country'])->first();
        $items= PackageItem::query()->whereIn('id',json_decode($package->package_items,true))
            ->with('full_country')->get();
        $package->items=$items;

        $invoice=Invoice::query()->where('id',$invoice_id)->first();
        if ($invoice->type==1){
            $package->invoice=$invoice;
            $package->type_invoice=1;
        }
        if ($invoice->type==2){
            $package->invoice=$invoice;
            $package->type_invoice=2;
        }
        $package->toArray();
        $pdf = PDF::loadView('dashboard.invoice_pdf',['package'=>$package,'invoice'=>$invoice]);


        return $pdf->stream('invoice.pdf');
    }
    public function get_user_addresses(Request $request, $user_id,$type)
    {


        return DataTables::of(UserAddresses::query()->where(['user_id'=>$user_id,'type'=>$type])
            ->orderBy('id','desc')->get())
            ->addIndexColumn()
            ->addColumn('action', function ($row) use ($user_id,$type) {
                $url=route('users.edit_user_addresses',[$user_id,$type,$row->id]);
                $btn = '<a href="'.$url.'" class="ms-2 btn btn-primary">Edit</a>';
                return $btn;
            })
            ->editColumn('created_at', function($row) use ($request) {
                return '<strong  tabindex="0" data-toggle="tooltip" >' . $row->created_at->format('m/d/Y - H:i') . '</strong>';



            })
            ->editColumn('nikename', function($row) use ($request) {
                return '<strong  tabindex="0" data-toggle="tooltip" >' . ($row->nikename??'-') . '</strong>';
            })
            ->editColumn('email', function($row) use ($request) {
                return '<strong  tabindex="0" data-toggle="tooltip" >' . ($row->email??'-') . '</strong>';
            })
            ->editColumn('full_phone', function($row) use ($request) {
                return '<strong  tabindex="0" data-toggle="tooltip" >' . ($row->full_phone??'-') . '</strong>';
            })
            ->addColumn('address', function($row) use ($request) {
                return '<strong  tabindex="0" data-toggle="tooltip" >' . ($row->country.','.$row->city.','.$row->address_1.','.$row->postal_code) . '</strong>';
            })

            ->rawColumns(['nikename','email','created_at','full_phone','address','action'])
            ->make(true);

    }
    public function edit_user_addresses(Request $request, $user_id,$type,$address_id)
    {
        $address=UserAddresses::query()->where('id',$address_id)->first();
        $user=User::query()->findOrFail($user_id);
        $countries=Country::all();
        $states=State::all();
        return view('dashboard.users.edit_address',compact('user','address','type','countries','states'));


    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users',
            'mobile' => 'required|unique:users',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input=$request->all();
            return response(["responseJSON" => $errors,"input"=>$input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }

        $data = $request->all();
        $password =$request->password;
        $data['password'] = Hash::make($password);
        $verification_code=random_int(100000,999999);
        $referral_code=Str::random(8);
        $rewards_direct_parent_referral=Setting::query()->where('key','rewards_direct_parent_referral')->first()->value;
        $rewards_start=Setting::query()->where('key','rewards_start')->first()->value;


        unset($data['_token']);
        if ($validator->passes()) {
            $data['marketing']=1;
            $data['term_conditions']=1;
            $data['drop_location']=1;
            $rewards_parent_referral=Setting::query()->where('key','rewards_direct_parent_referral')->first()->value;

            $data['referral_code'] = $referral_code;
            $data['referral_rewards'] = $rewards_parent_referral;

            $data['email_verified_at']=now();
            $data['first_name']=$request->first_name;
            $data['last_name']=$request->last_name;
            $data['name']=$request->first_name.' ' .$request->last_name;
            $user = User::query()->create($data);


            Wallet::query()->create([
                'user_id'=>$user->id,
                'balance'=>$rewards_start,
                'symbol'=>'$',
            ]);
            HistoryCoins::query()->create([
                'user_id'=>$user->id,
                'amount'=>$rewards_start,
                'status'=>1,
                'type'=>7,
            ]);
//        $number = sprintf('%06d',$user->id);
            $user->update([
                'user_no'=>1000+$user->id,
            ]);
            // Mail::to($data['email'])->send(new AfterVerified($user,$password));
            return response()->json(['success'=>"The process has successfully"]);
        }
    }
    public function update_address(Request $request,$address_id){
//        return $request->all();
        $validator = Validator::make($request->all(), [
            'full_name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'country' => 'required',
            'city' => 'required',
            'address_1' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input=$request->all();
            return response(["responseJSON" => $errors,"input"=>$input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }

//        $data = $request->all();
//        $password =$request->password;
//        $data['password'] = Hash::make($password);
//        unset($data['_token']);
        if ($validator->passes()) {
            $user_address = UserAddresses::find($address_id);
//            if ($request->type==0){
//                $warehouse=$this->update_warehouse($request,$user_address);
//            }
            $data = $request->input();

            if ($request->full_phone!=null){
                $data['phone']=$request->full_phone;
            }else{
                unset($data['phone']);
                unset($data['full_phone']);
            }
            $user_address->update($data);
            DB::commit();
            return response()->json(['status' => true, 'success' => "The process has successfully"]);
        }
    }
    public function verify(Request $request, $id)
    {
//        return $request->all();
        $user = User::find($id);
        $validator = Validator::make($request->all(), [
            'email_verified_at' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response(["responseJSON" => $errors, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        $data = $request->all();
        unset($data['_token']);
        $user = User::query()->where('id', $id)->first();
        if ($request->email_verified_at==1){
            $user ->update([
                'email_verified_at'=>now()
            ]);
//            $user->email_verified_at=now();
        }
        if ($request->email_verified_at==0){
            $user->update([
                'email_verified_at'=>null
            ]);
//            $user->email_verified_at=null;
        }


        if ($validator->passes()) {
            return response()->json(['success' => "The process has successfully",'email_verified_at'=>$user->email_verified_at]);
        }

    }
    public function update(Request $request,$id){
        $user = User::find($id);
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'mobile' => 'required|unique:users,mobile,'.$id,
            'discount' => 'required',
            'email' => 'required|unique:users,email,'.$id,
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input=$request->all();
            return response(["responseJSON" => $errors,"input"=>$input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        $data = $request->all();
        unset($data['_token']);
//        return $data;
        if ($validator->passes()) {

            $data['name']=$request->first_name.' ' .$request->last_name;
            if ($request->status==1){
                if ($user->email_verified_at==null){
                    $data['email_verified_at']=now();
                }
            }else{
                $data['email_verified_at']=null;
            }
            unset($data['status']);
            $user->update($data);
            return response()->json(['success'=>"The process has successfully"]);
        }
    }
    public function delete($id)
    {
        $user =User::find($id);
        $arr = array('msg' => 'There are some errors, try again', 'status' => false);
        if($user){
            $user->delete();
            $arr = array('msg' => "operation accomplished successfully", 'status' => true);
        }
        return Response()->json($arr);

    }
    public function send_track_no(Request $request, $id)
    {
        $request->all();
        Order::query()->findOrFail($id);
        $validator = Validator::make($request->all(), [
//            'file' => 'required|image',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input = $request->all();
            return response(["responseJSON" => $errors, "input" => $input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        if ($validator->passes()) {
            $order = Order::query()->where('id', $id)
                ->first();
            $user = User::query()->where('id', $order->user_id)->first();


            $order->update([
                    'status' => 1,
                    'status_pay' => 3,
                    'tracking_number' => $request->tracking_number,
                    'tracking_link' => $request->tracking_link
                ]
            );
//            return $order;

            Mail::to($user->email)->send(new \App\Mail\TrackNo($order,$user));
            $order->user->notify(New TrackNo($order));
            return response()->json(['success' => "The process has successfully"]);
        }


    }
    public function send_label(Request $request, $id)
    {
//        return $request->all();
        Order::query()->findOrFail($id);
        $validator = Validator::make($request->all(), [
//            'file' => 'required|image',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input = $request->all();
            return response(["responseJSON" => $errors, "input" => $input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        if ($validator->passes()) {
            $order = Order::query()->where('id', $id)
                ->first();
            $user = User::query()->where('id', $order->user_id)->first();
            $label=null;
            $invoice=null;
            if ($request->hasfile('file')) {
                $file = $request->file('file');
                $file_name = url('/') . '/public/uploads/labels/' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/labels/'), $file_name);

                $label = $file_name;

            }
            if ($request->hasfile('file_invoice')) {
                $file_invoice = $request->file('file_invoice');
                $file_name_invoice = url('/') . '/public/uploads/labels/' . time() . '.' . $file_invoice->getClientOriginalExtension();
                $file_invoice->move(public_path('uploads/labels/'), $file_name_invoice);

                $invoice = $file_name_invoice;

            }

//            $referral_shipment = Setting::query()->where('key','referral_shipment')->first()->value;
            $user=User::query()->where('id',$order->user_id)->first();
//            if ($referral_shipment==1){
            if ($user->give_commission==0){
                if ($user->parent_referral!=null){
                    $parent=User::query()->where('user_no',$user->parent_referral)->first();
                    if ($parent!=null){
                        $parent->update(['total_commission'=>$parent->total_commission+10]);
                        $wallet= Wallet::query()->where('user_id',$parent->id)->first();
                        if ($wallet){
                            $wallet->update(['balance'=>$wallet->balance+10]);
                        }else{
                            Wallet::query()->create([
                                'user_id'=>$parent->id,
                                'balance'=>10,
                            ]);
                        }
                    }


                    $user->update(['give_commission'=>1]);
                }


            }
//            }

            $order->update([
                    'status' => 1,
                    'label_path' => $label,
                    'invoice_path' => $invoice,
                    'tracking_number' => $request->tracking_number
                ]
            );
            Mail::to($user->email)->send(new \App\Mail\Label($order,$user));
            $order->user->notify(New Label($order));
            return response()->json(['success' => "The process has successfully"]);
        }


    }
    public function order_update(Request $request, $id)
    {
        $order = Order::query()->where('id', $id)->first();


        $validator = Validator::make($request->all(), [
            'item' => 'required',
//            'custom_item' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input = $request->all();
            return response(["responseJSON" => $errors, "input" => $input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }

        if ($validator->passes()) {
            $data = $request->all();
//        $order_new='';
            foreach ($request->item as $item) {
//                    return $item;
                $custom_items = $item;
                $order_result = $this->create_order($order, $data, $item, $custom_items);
                $order_new = json_decode($order_result, true);
            }
            $order->update([
                'user_id' => Auth::id(),
                'orderId' => $order_new['orderId'],
                'orderKey' => $order_new['orderKey'],
                'orderNumber' => $order_new['orderNumber'],
                'orderTotal' => $order_new['orderTotal'],
                'packaging_type' => $order_new['packageCode'],
                'tracking_number' => $order_new['tracking_number'] ?? '',
                'shipping_method' => $order_new['serviceCode'],
                'weight' => $order_new['weight']['value'],
                'weight_unit' => $order_new['weight']['units'],
                'dimension_h' => $order_new['dimensions']['height'],
                'dimension_l' => $order_new['dimensions']['length'],
                'dimension_w' => $order_new['dimensions']['width'],
                'dimension_unit' => $order_new['dimensions']['units'],
                'number_of_package' => count($request->custom_item),
                'carrier_type' => $order_new['carrierCode'],
                'contents' => $order_new['internationalOptions']['contents'],
            ]);
            $custom_items_declaration = CustomDeclarationItem::query()->where('order_id', $id)->delete();
//            $custom_items_declaration->delete();
            foreach ($request->custom_item as $item) {
                CustomDeclarationItem::query()->create([
                    'order_id' => $order->id,
                    'description' => $item['item_description'],
                    'quantity' => $item['quantity'],
                    'price' => $item['item_value'],
                    'total_price' => $item['item_total_value'],
                    'origin' => $item['country_origin'],
                ]);
            }
            return response()->json(['success' => "The process has successfully"]);
        }
    }

    public function update_password(Request $request, $id)
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
        $user = User::query()->where('id', $id)->first();
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

    public function get_user_cards(Request $request)
    {
        $user_id = $request->get('user_id');
        $user_cards = UserCard::query()->where('user_id', $user_id)->get();


        $options = '<option value=""></option>';
        if (isset($user_cards)) {
            foreach ($user_cards as $user_card) {

                $options .= '<option value="' . $user_card['card_token'] . '"  >' . $user_card['card_holder_name'] . '</option>';
            }
        }

        return response()->json([
            'success' => TRUE,
            'options' => $options,
        ]);
    }
    public function email_to_user($user_id)
    {
//return $id;
        User::query()->findOrFail($user_id);
        $user = User::query()->where('id', $user_id)
            ->first();

        return view('dashboard.users.email', compact('user'));


    }
    public function send_email_all_users()
    {

        $users_all= User::all();


        return view('dashboard.users.email_to_all', compact('users_all'));


    }
    public function send_email_to_user(Request $request,$user_id){
//        return $request->all();
        $user=User::query()->where('id',$user_id)->first();
        $text=$request->description;
        $subject=$request->subject;
        $user=User::query()->where(['id'=>$user_id])->first();
        Mail::to($user->email)->send(new GeneralEmail($text,$user,$subject));

        return response()->json(['success'=>"The process has successfully"]);

    }
    public function send_email_all(Request $request){
//        return $request->all();
        foreach ($request->user_id as $item){
            $user=User::query()->where('id',$item)->first();
            $text=$request->description;
            $subject=$request->subject;
            $user=User::query()->where(['id'=>$item])->first();
            Mail::to($user->email)->send(new GeneralEmail($text,$user,$subject));
        }


        return response()->json(['success'=>"The process has successfully"]);

    }


 }
