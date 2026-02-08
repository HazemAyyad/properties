<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Mail\ContactEmail;
use App\Mail\GeneralEmail;
use App\Mail\SupportEmail;
use App\Models\Dashboard\Admin;
use App\Models\Dashboard\Blog;
use App\Models\Dashboard\Messages;
use App\Models\Dashboard\MessagesSupport;
use App\Models\Dashboard\ShipmentPackage;
use App\Models\Dashboard\ShipPackageRequest;
use App\Models\Dashboard\Support;
use App\Models\Dashboard\Faqs;
use App\Models\Dashboard\HsCode;
use App\Models\Dashboard\WarehousePackage;
use App\Models\User;
use App\Notifications\NewReplyTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class SupportController extends Controller
{

    public function index($status){
//        return $status;

        return view('dashboard.supports.index',compact('status'));
    }
    public function get_supports(Request $request,$status)

    {
//      return  Support::query()->where('status',$status)->with('user')->get();
        return DataTables::of(Support::query()->where('status',$status)->with('user')->get())
            ->addIndexColumn()
            ->addColumn('action', function($row) use ($request){
                if ($row->support_type==1){
                    return '<a href="'.route('orders.view',[ $row->package_id]).'#ticket"><i class="fas fa-reply text-success fa-2x"></i></a>
                            <a href="javascript:void(0)" onclick="deleteItem('.$row->id.')" class="mx-2"><i class="fas fa-trash-alt text-danger fa-2x"></i></a>';

                }
                elseif ($row->support_type==2){
                    return '<a href="'.route('warehouse_orders.view',$row->package_id).'#ticket"><i class="fas fa-reply text-success fa-2x"></i></a>
                                    <a href="javascript:void(0)" onclick="deleteItem('.$row->id.')" class="mx-2"><i class="fas fa-trash-alt text-danger fa-2x"></i></a>';

                }
                elseif($row->support_type==3){
                    return '<a href="'.route('supports.package.reply',[2,$row->package_id]).'"><i class="fas fa-reply text-success fa-2x"></i></a>
                            <a href="javascript:void(0)" onclick="deleteItem('.$row->id.')" class="mx-2"><i class="fas fa-trash-alt text-danger fa-2x"></i></a>';
                }
                elseif($row->support_type==4){
                    return '<a href="'.route('supports.package.reply',[1,$row->package_id]).'"><i class="fas fa-reply text-success fa-2x"></i></a>
                            <a href="javascript:void(0)" onclick="deleteItem('.$row->id.')" class="mx-2"><i class="fas fa-trash-alt text-danger fa-2x"></i></a>';
                }else{
                    return '<a href="'.route('supports.reply',$row->id).'"><i class="fas fa-reply text-success fa-2x"></i></a>
                            <a href="javascript:void(0)" onclick="deleteItem('.$row->id.')" class="mx-2"><i class="fas fa-trash-alt text-danger fa-2x"></i></a>';

                }
//                $url=route('supports.reply',$row->id);
//
//                $btn = '<a href="'.$url .'" class="mx-2"><i class="fas fa-reply text-success fa-2x"></i></a>
//                        <a href="javascript:void(0)" onclick="deleteItem('.$row->id.')" class="mx-2"><i class="fas fa-trash-alt text-danger fa-2x"></i></a>';
//
//                return $btn;
            })

            ->editColumn('name', function ($row) {
                if($row->re_open == 1){
                    if($row->user!=null){
                        return '<strong class="Titillium-font danger">' . $row->user->name .'<p class="text-danger">( Re-Open )</p></strong>';
                    }else{
                        return '<strong   tabindex="0" data-toggle="tooltip"  >--</strong>';
                    }

                }else{
                    if($row->user!=null){
                        return '<strong class="Titillium-font danger">' . $row->user->name .'<p class="text-danger">( Re-Open )</p></strong>';
                    }else{
                        return '<strong   tabindex="0" data-toggle="tooltip"  >--</strong>';
                    }

                }
            })
            ->editColumn('phone', function ($row) {
                if($row->user!=null){
                    return '<strong class="Titillium-font danger">' . $row->user->phone .'</strong>';
                }else{
                    return '<strong   tabindex="0" data-toggle="tooltip"  >--</strong>';
                }

            })
            ->editColumn('email', function ($row) {
                if($row->user!=null){
                    return '<strong class="Titillium-font danger">' . $row->user->email .'</strong>';
                }else{
                    return '<strong   tabindex="0" data-toggle="tooltip"  >--</strong>';
                }
            })
            ->editColumn('subject', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->subject . '</strong>';
            })
            ->editColumn('type', function ($row) {

                $type = $row->type;
                if ($type == 1) {
                    $class = 'text-success';
                    $tooltipetitle = __('General');
                } elseif ($type == 2) {
                    $class = 'text-primary';
                    $tooltipetitle = __('Payment');
                } elseif ($type == 3) {
                    $class = 'text-primary';
                    $tooltipetitle = __('Business');
                }elseif ($type == 4) {
                    $class = 'text-primary';
                    $tooltipetitle = __('Shipping Rate');
                }
                elseif ($type == 5) {
                    $class = 'text-primary';
                    $tooltipetitle = __('problem in website');
                } elseif ($type == 6) {
                    $class = 'text-danger';
                    $tooltipetitle = __('Claims');
                }
                elseif ($type == 7) {
                    $class = 'text-danger';
                    $tooltipetitle = __('Order Shipment');
                }
                elseif ($type == 8) {
                    $class = 'text-danger';
                    $tooltipetitle = __('Order Warehouse');
                }
                elseif ($type == 9) {
                    $class = 'text-danger';
                    $tooltipetitle = __('Received Packages');
                }
                else {
                    $class = 'text-danger';
                    $tooltipetitle = $type;
                }
                return '<strong  class="' . $class . ' " tabindex="0" data-toggle="tooltip" title="' . $tooltipetitle . '" >' . $tooltipetitle . '</strong>';

            })
            ->editColumn('created_at', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->created_at->format('m/d/Y - H:i') . '</strong>';
            })
            ->editColumn('updated_at', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->updated_at->format('m/d/Y - H:i') . '</strong>';
            })

//            ->escapeColumns('name')
            ->rawColumns(['name','phone','email','subject','type','action','created_at'])
            ->make(true);

    }

    public function reply($id){
        $support=Support::query()->with('user')->findOrFail($id);
        $messages = Messages::query()->where('support_id',$id)
            ->with(['team'])

            ->orderBy('created_at','asc')
            ->get()
          ->groupBy(function($date) {
              return \Carbon\Carbon::parse($date->created_at)->format('M-d-y');
          });


                return view('dashboard.supports.reply',compact('support','messages'));



    }

    public function support_package($type,$package_id){

        if ($type==1){
            $package=WarehousePackage::query()->where(['id'=>$package_id ])->first();
            $support=Support::query()->where(['type'=>9, 'package_id'=>$package_id])
                ->with('user')->first();
            $messages = Messages::query()->where('support_id',$support->id)
                ->with(['team'])

                ->orderBy('created_at','asc')
                ->get()
                ->groupBy(function($date) {
                    return \Carbon\Carbon::parse($date->created_at)->format('M-d-y');
                });
            return view('dashboard.supports.reply-package',compact('support','messages','package'));

        }
        elseif ($type==2){
            $package=ShipPackageRequest::query()->where(['id'=>$package_id])->first();
            $support=Support::query()->where(['type'=>8, 'package_id'=>$package_id])
                ->with('user')->first();
            $messages = Messages::query()->where('support_id',$support->id)
                ->with(['team'])

                ->orderBy('created_at','asc')
                ->get()
                ->groupBy(function($date) {
                    return \Carbon\Carbon::parse($date->created_at)->format('M-d-y');
                });
            return view('dashboard.supports.reply-warehouse-order',compact('support','messages','package'));

        }
        else{
            $package=ShipmentPackage::query()->where(['id'=>$package_id,'user_id'=>Auth::id()])->first();
            $support=Support::query()->where(['type'=>7,'user_id'=>Auth::id(),'package_id'=>$package_id])
                ->with('user')->first();
            $messages = Messages::query()->where('support_id',$support->id)
                ->with(['team'])

                ->orderBy('created_at','asc')
                ->get()
                ->groupBy(function($date) {
                    return \Carbon\Carbon::parse($date->created_at)->format('M-d-y');
                });
            return view('dashboard.supports.reply',compact('support','messages','package'));

        }




    }
    public function send_reply(Request $request,$id){
        $support=Support::query()->with('user')->findOrFail($id);
        $validator = Validator::make($request->all(), [
//            'details' => 'required',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input=$request->all();
            return response(["responseJSON" => $errors,"input"=>$input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        if ($validator->passes()) {
            DB::beginTransaction();
            try {
                $data=$request->all();
                $text='You Have New reply For Your Ticket';
                $subject=$support;
                if ($request->hasfile('image')) {
                    $image_url = $request->file('image');
                    $image_name = '/public/uploads/messages/' . time() . '.' . $image_url->getClientOriginalExtension();
                    $image_url->move(env('PATH_FILE_URL').'/uploads/messages/', $image_name);
                    $data['image'] = $image_name;
                }
                $data['support_id'] = $id;
                $data['type'] = 'admin';
                $data['team_id'] = Auth::id();
                $message = Messages::query()->create($data);
                Mail::to($support->user->email)->send(new SupportEmail($support->user,$text,$support));
                $support->touch();
                DB::commit();
                return response()->json(['success'=>"The process has successfully"]);

            }
            catch (Throwable $e) {
                DB::rollBack();
                return response([  "message" => 'Some Errors'.$e->getMessage()], 500);

            }

        }
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
//            'vendor' => 'required',
            'type_support' => 'required',
            'details_support' => 'required',
            'subject_support' => 'required',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input = $request->all();
            return response(["responseJSON" => $errors, "input" => $input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        if ($validator->passes()) {
            DB::beginTransaction();
            try {
                Support::query()->create([
                    'type'=>$request->type_support,
                    'details'=>$request->details_support,
                    'subject'=>$request->subject_support,
                    'user_id'=>Auth::id(),
                ]);


                DB::commit();
                return response()->json(['status'=>true,'success' => "The process has successfully"]);
            }
            catch (Throwable $e) {
                DB::rollBack();
                return response([  "message" => 'Some Errors'.$e->getMessage()], 500);

            }

        }
    }

    public function status(Request $request,$id,$status){
        $support=  Support::find($id);

        if($status == 1){
            $support=  Support::query()->where('id', $id)
            ->update(['status'=>$status, 're_open'=> 1]);
        }else{
            $support=  Support::query()->where('id', $id)
            ->update(['status'=>$status]);
        }
        return redirect()->route('supports.index',$status);


    }
    public function delete($id)
    {
        $support =Support::find($id);
        Messages::query()->where('support_id',$id)->delete();
        $support->delete();
        $arr = array('msg' => 'There are some errors, try again', 'status' => false);
        if($support){
            $arr = array('msg' => "operation accomplished successfully", 'status' => true);
        }
        return Response()->json($arr);

    }
}
