<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Mail\ContactEmail;
use App\Mail\GeneralEmail;
use App\Models\Dashboard\Admin;
use App\Models\Dashboard\Blog;
use App\Models\Dashboard\ContactUs;
use App\Models\Dashboard\Faqs;
use App\Models\Dashboard\HsCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class ContactController extends Controller
{

    public function index(){

                return view('dashboard.contact.index');

    }
    public function get_contact(Request $request)
    {
 

        return DataTables::of(ContactUs::query()->get())
            ->addIndexColumn()
            ->addColumn('action', function($row) use ($request){

                $url=route('contact.reply',$row->id);

                $btn = '<a href="'.$url .'" class="ms-2"><i class="fas fa-reply text-success"></i></a>
                        <a href="javascript:void(0)" onclick="deleteItem('.$row->id.')" class=""><i class="fas fa-trash-alt text-danger"></i></a>';

                return $btn;
            })
            ->editColumn('name', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->name . '</strong>';
            })
            ->editColumn('phone', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->phone . '</strong>';
            })
            ->editColumn('email', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->email . '</strong>';
            })
            ->editColumn('subject', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->subject . '</strong>';
            })
            ->editColumn('created_at', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->created_at->format('m/d/Y - H:i') . '</strong>';
            })
            ->editColumn('status', function ($row) {
                if ($row->status == 1) {
                    $status = 'checked';
                } else {
                    $status = '';
                }
                return '<label class="switch switch-square" for="status-'.$row->id.'">
                          <input type="checkbox" class="switch-input" onclick="changeStatus('.$row->id.')" value="'.$row->status.'" id="status-'.$row->id.'" '.$status.' />
                          <span class="switch-toggle-slider">
                            <span class="switch-on"><i class="ti ti-check"></i></span>
                            <span class="switch-off"><i class="ti ti-x"></i></span>
                          </span>

                        </label>';
            })
//            ->escapeColumns('name')
            ->rawColumns(['name','phone','email','subject','action','created_at','status'])
            ->make(true);

    }

    public function reply($id){
        $contact=ContactUs::query()->findOrFail($id);
        return view('dashboard.contact.reply',compact('contact'));
    }
    public function send_reply(Request $request,$id){
        $validator = Validator::make($request->all(), [
            'subject' => 'required',
            'description' => 'required',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input=$request->all();
            return response(["responseJSON" => $errors,"input"=>$input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        if ($validator->passes()) {
            $data=$request->all();
            $text=$request->description;
            $subject=$request->subject;
            $contact=ContactUs::query()->where('id',$id)->first();

            Mail::to($contact->email)->send(new ContactEmail($text,$contact,$subject));

            return response()->json(['success'=>"The process has successfully"]);
        }
    }
    public function status(Request $request, $id)
    {
        $admin =  ContactUs::find($id);
        $validator = Validator::make($request->all(), [
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response(["responseJSON" => $errors, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        $data = $request->all();
        unset($data['_token']);
  ContactUs::query()->where('id', $id)
            ->update($data);

        if ($validator->passes()) {
            return response()->json(['success' => "The process has successfully"]);
        }
    }
    public function delete($id)
    {
        $contact =ContactUs::find($id);
        $contact->delete();
        $arr = array('msg' => 'There are some errors, try again', 'status' => false);
        if($contact){
            $arr = array('msg' => "operation accomplished successfully", 'status' => true);
        }
        return Response()->json($arr);

    }
}