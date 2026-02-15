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
            'price_monthly' => 'required|numeric',
            'price_yearly' => 'nullable|numeric',
            'slug' => 'nullable|string|max:80|unique:plans,slug',
            'duration_months' => 'nullable|integer|min:0',
            'number_of_properties' => 'nullable|integer',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input=$request->all();
            return response(["responseJSON" => $errors,"input"=>$input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        if ($validator->passes()) {
            $plan = Plan::query()->create([
                'slug' => $request->filled('slug') ? $request->slug : null,
                'title'=> [
                    'en' => $request->input('title.en'),
                    'ar' => $request->input('title.ar'),
                ],
                'description'=> [
                    'en' => $request->input('description.en'),
                    'ar' => $request->input('description.ar'),
                ],
                'duration_months' => $request->filled('duration_months') ? (int) $request->duration_months : null,
                'number_of_properties' => $request->filled('number_of_properties') ? (int) $request->number_of_properties : 1,
                'price_monthly'=>$request->price_monthly,
                'price_yearly'=>$request->filled('price_yearly') ? $request->price_yearly : 0,
                'extra_support' => [
                    'en' => $request->input('extra_support.en'),
                    'ar' => $request->input('extra_support.ar'),
                ],
                'status' => 1,
            ]);

            return response()->json(['success'=>"The process has successfully"]);
        }
    }
    public function update(Request $request,$id){
        $plan = Plan::find($id);
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'price_monthly' => 'required|numeric',
            'price_yearly' => 'nullable|numeric',
            'status' => 'required',
            'slug' => 'nullable|string|max:80|unique:plans,slug,' . $id,
            'duration_months' => 'nullable|integer|min:0',
            'number_of_properties' => 'nullable|integer',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input=$request->all();
            return response(["responseJSON" => $errors,"input"=>$input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        if ($validator->passes()) {
            $plan->update([
                'slug' => $request->filled('slug') ? $request->slug : $plan->slug,
                'title'=> [
                    'en' => $request->input('title.en'),
                    'ar' => $request->input('title.ar'),
                ],
                'description'=> [
                    'en' => $request->input('description.en'),
                    'ar' => $request->input('description.ar'),
                ],
                'duration_months' => $request->filled('duration_months') ? (int) $request->duration_months : null,
                'number_of_properties' => $request->filled('number_of_properties') ? (int) $request->number_of_properties : 1,
                'price_monthly'=>$request->price_monthly,
                'price_yearly'=>$request->filled('price_yearly') ? $request->price_yearly : 0,
                'extra_support' => [
                    'en' => $request->input('extra_support.en'),
                    'ar' => $request->input('extra_support.ar'),
                ],
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
