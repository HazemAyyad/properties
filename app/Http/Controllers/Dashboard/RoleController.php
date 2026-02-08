<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use App\Models\Dashboard\Permissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    function __construct()
    {
//        $this->middleware('auth');
//        $this->middleware('permission:Role List|', ['only' => ['index']]);
//        $this->middleware('permission:Role Create', ['only' => ['create','store']]);
//        $this->middleware('permission:Role Edit', ['only' => ['edit','update']]);
//        $this->middleware('permission:Role Delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $roles = Role::all();
        return view('dashboard.roles.index', compact('roles'));
    }
    public function data(Request $request)
    {


        return DataTables::of(Role::all())
            ->addIndexColumn()
            ->addColumn('action', function($row) use ($request){

                    $url=route('admin.roles.edit',$row->id);
                $btn = '<a href="'.$url .'" class="edit btn btn-primary btn-sm">Edit</a>';

                return $btn;
            })

            ->editColumn('name', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->name . '</strong>';
            })
//            ->escapeColumns('name')
            ->rawColumns(['name','action'])
            ->make(true);

    }

    public function create()
    {
        $permissions = Permissions::query()->where('parent', 0)->with('children')->get();
//        return $permissions;
        return view('dashboard.roles.add', compact('permissions'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name',
            'permission' => 'required|array',
            'permission.*' => 'exists:permissions,id', // Use id to validate
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $input = $request->all();

            return response(
                ["responseJSON" => $errors,
                    "input" => $input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }

        DB::beginTransaction();
        try {
            $role = Role::create([
                'name' => $request->name,
                'guard_name' => 'admin',
            ]);

            // Synchronize permissions
            $role->permissions()->sync($request->permission);

            DB::commit();
            return response()->json(['success' => "The process has successfully"]);
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

     public function show($id)
    {
        $role = Role::query()->find($id);
        $rolePermissions = Permission::query()->join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$id)
            ->get();
        return view('roles.show',compact('role','rolePermissions'));
    }

    public function edit($id)
    {
        $role = Role::query()->find($id);
        $permissions = Permissions::query()->where('parent', 0)->with('children')->get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
//        return $rolePermissions;
        return view('dashboard.roles.edit',compact('role','permissions','rolePermissions'));
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name,'. $request->role_id,
            'role_id' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();

            $input = $request->all();

            return response(
                ["responseJSON" => $errors,
                    "input" => $input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        DB::beginTransaction();
        try {
//            return $request->permission;
            $role = Role::query()->find($request->role_id);
            $role->name = $request->name;
            $role->save();
            $data = array();
            $permissions=$request->permission;
            foreach ($permissions as $key => $item)
            {
                $data[$key] = (int)$item;
            }

            if (!empty($data))
            {
                $role->syncPermissions($data);
            }
//            $role->syncPermissions($request->permission);
            DB::commit();
            return response()->json(['success' => "operation accomplished successfully"]);
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public function destroy($id)
    {
        $role=Role::query()->where('id',$id)->delete();
        $arr = array('msg' => 'There are some errors, try again', 'status' => false);
        if($role){
            $arr = array('msg' => "operation accomplished successfully", 'status' => true);
        }
        return Response()->json($arr);
    }
}
