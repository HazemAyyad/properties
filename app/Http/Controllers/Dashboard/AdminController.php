<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class AdminController extends Controller
{
    function __construct()
    {
//        $this->middleware(
//            'permission:Staff List',
//            ['only' => ['index']]
//        );
//        $this->middleware(
//            'permission:Staff Create',
//            ['only' => ['create', 'store']]
//        );
//        $this->middleware(
//            'permission:Staff Edit',
//            ['only' => ['edit', 'update']]
//        );
//        $this->middleware(
//            'permission:Staff Delete',
//            ['only' => ['delete']]
//        );
    }
    public function index()
    {

        return view('dashboard.staff.index');
    }
    public function get_staff(Request $request)
    {


        return DataTables::of(Admin::query() )
            ->addIndexColumn()
            ->addColumn('action', function($row) use ($request){

                    $url=route('admin.staff.edit',$row->id);


                $btn = '<a href="'.$url .'" class="ms-2"><i class="fas fa-edit text-success"></i></a>
                        <a href="javascript:void(0)" onclick="deleteItem('.$row->id.')" class=""><i class="fas fa-trash-alt text-danger"></i></a>';

                return $btn;
            })

            ->editColumn('name', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->name . '</strong>';
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
            ->editColumn('type', function ($row) {

                $type = $row->type;
                if ($type == 'Super Admin') {
                    $class = 'text-success';
                    $tooltipetitle = $type;
                } elseif ($type == 'Admin') {
                    $class = 'text-primary';
                    $tooltipetitle = $type;
                } else {
                    $class = 'text-danger';
                    $tooltipetitle = $type;
                }
                return '<strong  class="' . $class . ' " tabindex="0" data-toggle="tooltip" title="' . $tooltipetitle . '" >' . $type . '</strong>';

            })
            //            ->escapeColumns('name')
            ->rawColumns(['name', 'type', 'status', 'action'])
            ->make(true);
    }
    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();


        return view('dashboard.staff.add', compact('roles'));
    }
    public function edit($id)
    {
        $admin = Admin::query()->findOrFail($id);
        $roles = Role::pluck('name', 'name')->all();
        //        return $roles;
        $userRole = $admin->roles->pluck('name', 'name')->all();


        return view('dashboard.staff.edit', compact('admin', 'roles', 'userRole'));
    }
    public function profile($id)
    {
        $admin = Admin::query()->findOrFail($id);



        return view('dashboard.staff.profile', compact('admin'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'name' => 'required',
            'mobile' => 'required|unique:admins',
            'email' => 'required|unique:admins',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input = $request->all();
            return response(["responseJSON" => $errors, "input" => $input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }

        $data = $request->all();
        //        return $data;
        $password = $request->password;
        $data['password'] = Hash::make($password);
        unset($data['_token']);


        if ($validator->passes()) {

            $user = Admin::query()->create($data);
            $user->assignRole($request->input('type'));

            return response()->json(['success' => "The process has successfully"]);
        }
    }
    public function status(Request $request, $id)
    {
        $admin =  Admin::find($id);
        $validator = Validator::make($request->all(), [
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response(["responseJSON" => $errors, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        $data = $request->all();
        unset($data['_token']);

        $admin =  Admin::query()->where('id', $id)
            ->update($data);

        if ($validator->passes()) {
            return response()->json(['success' => "The process has successfully"]);
        }
    }
    public function update(Request $request, $id)
    {
        $admin = Admin::find($id);

        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'name' => 'required',
            'mobile' => 'required|unique:admins,mobile,' . $id,
            'email' => 'required|unique:admins,email,' . $id,
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $input = $request->all();
            return response(["responseJSON" => $errors, "input" => $input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }

        if ($validator->passes()) {
            $data = $request->except('_token'); // Remove the _token field from the request

            // Only hash and update the password if it is provided
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            } else {
                unset($data['password']); // Ensure password key is removed if not updating it
            }

            Admin::query()->where('id', $id)->update($data);

            // Update roles
            DB::table('model_has_roles')->where('model_id', $id)->delete();
            $admin->assignRole($request->input('type'));

            return response()->json(['success' => "The process has successfully"]);
        }
    }

    public function update_password(Request $request, $id)
    {
        $messages = [
            'password_confirmation.same' => __('Password does not match'),
            'password.required' => __('Please enter the password'),
        ];

        $validator = Validator::make($request->all(), [
            'password' => 'required|same:password|min:6',
            'password_confirmation' => 'required|same:password|min:6',
        ], $messages);
        $admin = Admin::query()->where('id', $id)->first();
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input = $request->all();
            return response(["responseJSON" => $errors, "input" => $input, "message" => __('Verify that the data is correct, fill in all fields')], 422);
        }

        if ($validator->passes()) {

            $admin->password = Hash::make($request->password);
            $admin->save();

            return response()->json(['success' => __('The process has successfully')]);
        }
    }
    public function delete($id)
    {
        $admin = Admin::find($id);
        $admin->delete();
        $arr = array('msg' => 'There are some errors, try again', 'status' => false);
        if ($admin) {
            $arr = array('msg' => "operation accomplished successfully", 'status' => true);
        }
        return Response()->json($arr);
    }
}
