<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Dashboard\Admin;
use App\Models\Dashboard\Policy;
use App\Models\PolicyCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class PolicyController extends Controller
{

    public function index(){

                return view('dashboard.policies.index');

    }
    public function get_policies(Request $request)
    {


        return DataTables::of(Policy::query()->with('category')->get())
            ->addIndexColumn()
            ->addColumn('action', function($row) use ($request){

                    $url=route('admin.policies.edit',$row->id);

                $btn = '<a href="'.$url .'" class="ms-2"><i class="fas fa-edit text-success"></i></a>
                        <a href="javascript:void(0)" onclick="deleteItem('.$row->id.')" class=""><i class="fas fa-trash-alt text-danger"></i></a>';

                return $btn;
            })

            ->editColumn('title_en', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->title_en . '</strong>';
            })
            ->addColumn('category', function ($row) {

                $type = $row->category_id;
                if ($type == 1) {
                    $type='Terms';
                    $class = 'text-success';
                    $tooltipetitle =$type;
                }
                elseif ($type == 2) {
                    $type='Limitations';
                    $class = 'text-primary';
                    $tooltipetitle =$type;
                }
                elseif ($type == 3) {
                    $type='Revisions and errata';
                    $class = 'text-warning';
                    $tooltipetitle =$type;
                }
                 elseif ($type == 4) {
                    $type='Site terms of use modifications';
                    $class = 'text-danger';
                    $tooltipetitle =$type;
                } elseif ($type == 5) {
                    $type='Risks';
                    $class = 'text-danger';
                    $tooltipetitle =$type;
                }

                else {
                    $type='Unknown';
                    $class = 'text-danger';
                    $tooltipetitle = $type;
                }
                return '<strong  class="' . $class . ' " tabindex="0" data-toggle="tooltip" title="' . $tooltipetitle . '" >' . $row->category->title . '</strong>';

                // return '<button class="btn btn-sm ' . $class . ' round" type="button"  data-toggle="tooltip" data-placement="top" title=" ' . $tooltipetitle . ' .">' . $status . '<i class="fa fa-info-circle" ></i></button>';
            })
//            ->escapeColumns('name')
            ->rawColumns(['title_en','category','action'])
            ->make(true);

    }
    public function create(){

        $categories = PolicyCategory::all();

                return view('dashboard.policies.add',compact('categories'));

    }
    public function edit($id){
        $policy=Policy::query()->findOrFail($id);
        $categories = PolicyCategory::all();


                return view('dashboard.policies.edit',compact('policy','categories'));



    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'title_en' => 'required',
            'title_ar' => 'required',
            'answer_en' => 'required',
            'answer_ar' => 'required',
            'category_id' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input=$request->all();
            return response(["responseJSON" => $errors,"input"=>$input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        if ($validator->passes()) {
            $data=$request->all();
             Policy::query()->create($data);

            return response()->json(['success'=>"The process has successfully"]);
        }
    }
    public function update(Request $request,$id){
        $policy = Policy::find($id);
        $validator = Validator::make($request->all(), [
            'title_en' => 'required',
            'title_ar' => 'required',
            'answer_en' => 'required',
            'answer_ar' => 'required',
            'category_id' => 'required',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input=$request->all();
            return response(["responseJSON" => $errors,"input"=>$input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        $data = $request->all();

        if ($validator->passes()) {

            $policy->update($data);
            return response()->json(['success'=>"The process has successfully"]);
        }
    }
    public function delete($id)
    {
        $policy =Policy::find($id);
        $policy->delete();
        $arr = array('msg' => 'There are some errors, try again', 'status' => false);
        if($policy){
            $arr = array('msg' => "operation accomplished successfully", 'status' => true);
        }
        return Response()->json($arr);

    }
}
