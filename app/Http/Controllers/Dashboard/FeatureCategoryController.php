<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\Dashboard\FeatureCategory;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class FeatureCategoryController extends Controller
{

    public function index(){

                return view('dashboard.properties.feature_categories.index');

    }
    public function get_feature_categories(Request $request)
    {


        return DataTables::of(FeatureCategory::query())
            ->addIndexColumn()
            ->addColumn('action', function($row) use ($request){

                    $url=route('admin.feature_categories.edit',$row->id);

                $btn = '<a href="'.$url .'" class="ms-2"><i class="fas fa-edit text-success"></i></a>

                        <a href="javascript:void(0)" onclick="deleteItem('.$row->id.')" class=""><i class="fas fa-trash-alt text-danger"></i></a>';

                return $btn;
            })

            ->editColumn('name', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->name . '</strong>';
            })


            ->editColumn('status', function ($row) {

                $status= $row->status;
                if ($status == 0) {
                    $class = 'text-danger';
                    $tooltipetitle =__('Pending');
                } elseif ($status == 1) {
                    $class = 'text-success';
                    $tooltipetitle = __('Published');
                }
                elseif ($status == 2) {
                    $class = 'text-warning';
                    $tooltipetitle = __('draft');
                }else {
                    $class = 'text-danger';
                    $tooltipetitle = __('Unknown');
                }
                return '<strong  class="' . $class . ' " tabindex="0" data-toggle="tooltip" title="' . $tooltipetitle . '" >' . $tooltipetitle . '</strong>';

            })
//            ->escapeColumns('name')
            ->rawColumns(['name','status','action'])
            ->make(true);

    }
    public function create(){



                return view('dashboard.properties.feature_categories.add');

    }
    public function edit($id){
        $category=FeatureCategory::query()->findOrFail($id);



                return view('dashboard.properties.feature_categories.edit',compact('category'));



    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
             'status' => 'required',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input=$request->all();
            return response(["responseJSON" => $errors,"input"=>$input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        if ($validator->passes()) {


            $category = FeatureCategory::query()->create([
                'name'=> [
                        'en' => $request->input('name.en'),
                        'ar' => $request->input('name.ar'),
                    ],
                'status'=>$request->status
            ]);

            return response()->json(['success'=>"The process has successfully"]);
        }
    }
    public function update(Request $request,$id){
        $category = FeatureCategory::find($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required',
             'status' => 'required',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input=$request->all();
            return response(["responseJSON" => $errors,"input"=>$input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        $data = $request->all();

        if ($validator->passes()) {
             $category->update([
                 'name'=> [
                     'en' => $request->input('name.en'),
                     'ar' => $request->input('name.ar'),
                 ],
                 'status'=>$request->status
             ]);
            return response()->json(['success'=>"The process has successfully"]);
        }
    }
    public function delete($id)
    {
        $category =FeatureCategory::find($id);
        $category->delete();
        $arr = array('msg' => 'There are some errors, try again', 'status' => false);
        if($category){
            $arr = array('msg' => "operation accomplished successfully", 'status' => true);
        }
        return Response()->json($arr);

    }
}
