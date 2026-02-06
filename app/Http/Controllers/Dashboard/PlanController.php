<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\Dashboard\Plan;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class PlanController extends Controller
{

    public function index(){

                return view('dashboard.plans.index');

    }
    public function get_plans(Request $request)
    {


        return DataTables::of(Plan::query())
            ->addIndexColumn()
            ->addColumn('action', function($row) use ($request){

                    $url=route('admin.plans.edit',$row->id);

                $btn = '<a href="'.$url .'" class="ms-2"><i class="fas fa-edit text-success"></i></a>
                        <a href="javascript:void(0)" onclick="deleteItem('.$row->id.')" class=""><i class="fas fa-trash-alt text-danger"></i></a>';

                return $btn;
            })

            ->editColumn('title', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->title . '</strong>';
            })
            ->editColumn('description', function ($row) {
                return '<strong class="Titillium-font text-danger">' . $row->description . '</strong>';
            })
//            ->escapeColumns('name')
            ->rawColumns(['title','description','action'])
            ->make(true);

    }
    public function create(){



                return view('dashboard.plans.add');

    }
    public function edit($id){
        $plan=Plan::query()->findOrFail($id);



                return view('dashboard.plans.edit',compact('plan'));



    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'price_monthly' => 'required',
            'price_yearly' => 'required',
            
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input=$request->all();
            return response(["responseJSON" => $errors,"input"=>$input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        if ($validator->passes()) {
            $data=$request->all();

            $plan = Plan::query()->create([
                'title'=> [
                    'en' => $request->input('title.en'),
                    'ar' => $request->input('title.ar'),
                ],

                'description'=> [
                    'en' => $request->input('description.en'),
                    'ar' => $request->input('description.ar'),
                ],

                'price_monthly'=>$request->price_monthly,
                'price_yearly'=>$request->price_yearly,

            ]);

            return response()->json(['success'=>"The process has successfully"]);
        }
    }
    public function update(Request $request,$id){
        $agent = Plan::find($id);
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'price_monthly' => 'required',
            'price_yearly' => 'required',
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

                'description'=> [
                    'en' => $request->input('description.en'),
                    'ar' => $request->input('description.ar'),
                ],

                'price_monthly'=>$request->price_monthly,
                'price_yearly'=>$request->price_yearly,
                'status'=>$request->status,

            ]);
            return response()->json(['success'=>"The process has successfully"]);
        }
    }
    public function delete($id)
    {
        $plan =Plan::find($id);
        $plan->delete();
        $arr = array('msg' => 'There are some errors, try again', 'status' => false);
        if($plan){
            $arr = array('msg' => "operation accomplished successfully", 'status' => true);
        }
        return Response()->json($arr);

    }
}
