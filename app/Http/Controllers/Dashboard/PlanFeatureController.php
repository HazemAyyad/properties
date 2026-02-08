<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\Dashboard\Plan;
use App\Models\Dashboard\PlanFeature;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class PlanFeatureController extends Controller
{

    public function index(){

                return view('dashboard.plan_features.index');

    }
    public function get_plan_features(Request $request)
    {


        return DataTables::of(PlanFeature::query())
            ->addIndexColumn()
            ->addColumn('action', function($row) use ($request){

                    $url=route('admin.plan_features.edit',$row->id);

                $btn = '<a href="'.$url .'" class="ms-2"><i class="fas fa-edit text-success"></i></a>
                        <a href="javascript:void(0)" onclick="deleteItem('.$row->id.')" class=""><i class="fas fa-trash-alt text-danger"></i></a>';

                return $btn;
            })

            ->editColumn('title', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->title . '</strong>';
            })

//            ->escapeColumns('name')
            ->rawColumns(['title','action'])
            ->make(true);

    }
    public function create(){

        $plans=Plan::query()->where('status',1)->get();
                return view('dashboard.plan_features.add',compact('plans'));

    }
    public function edit($id){
        $plan_feature=PlanFeature::query()->findOrFail($id);

        $plans=Plan::query()->where('status',1)->get();


                return view('dashboard.plan_features.edit',compact('plan_feature','plans'));



    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'plan_id' => 'required',
            'status' => 'required',


        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input=$request->all();
            return response(["responseJSON" => $errors,"input"=>$input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        if ($validator->passes()) {
            $data=$request->all();

            $plan_feature = PlanFeature::query()->create([
                'title'=> [
                    'en' => $request->input('title.en'),
                    'ar' => $request->input('title.ar'),
                ],
                'plan_id'=>$request->plan_id,
                'status'=>$request->status




            ]);

            return response()->json(['success'=>"The process has successfully"]);
        }
    }
    public function update(Request $request,$id){
        $agent = PlanFeature::find($id);
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'plan_id' => 'required',
            'status' => 'required',



        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input=$request->all();
            return response(["responseJSON" => $errors,"input"=>$input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        $data = $request->all();

        if ($validator->passes()) {

            $agent->update([
                'title'=> [
                    'en' => $request->input('title.en'),
                    'ar' => $request->input('title.ar'),
                ],
                'plan_id'=>$request->plan_id,
                'status'=>$request->status,



            ]);
            return response()->json(['success'=>"The process has successfully"]);
        }
    }
    public function delete($id)
    {
        $plan_feature =PlanFeature::find($id);
        $plan_feature->delete();
        $arr = array('msg' => 'There are some errors, try again', 'status' => false);
        if($plan_feature){
            $arr = array('msg' => "operation accomplished successfully", 'status' => true);
        }
        return Response()->json($arr);

    }
}
