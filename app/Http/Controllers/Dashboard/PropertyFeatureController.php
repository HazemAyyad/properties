<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\Dashboard\FeatureCategory;
use App\Models\Dashboard\Icon;
use App\Models\Dashboard\Feature;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class PropertyFeatureController extends Controller
{

    public function index(){

                return view('dashboard.properties.features.index');

    }
    public function get_property_features(Request $request)
    {


        return DataTables::of(Feature::query())
            ->addIndexColumn()
            ->addColumn('action', function($row) use ($request){

                    $url=route('admin.property_features.edit',$row->id);

                $btn = '<a href="'.$url .'" class="ms-2"><i class="fas fa-edit text-success"></i></a>
                        <a href="javascript:void(0)" onclick="deleteItem('.$row->id.')" class=""><i class="fas fa-trash-alt text-danger"></i></a>';

                return $btn;
            })


            ->editColumn('name', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->name . '</strong>';
            })
            ->editColumn('category_id', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->featureCategory->name . '</strong>';
            })
//            ->escapeColumns('name')
            ->rawColumns(['name','category_id','action'])
            ->make(true);

    }
    public function create(){

        $categories=FeatureCategory::all();
        $icons=Icon::all();
                return view('dashboard.properties.features.add',compact('icons','categories'));

    }
    public function edit($id){
        $feature=Feature::query()->findOrFail($id);

        $categories=FeatureCategory::all();
        $icons=Icon::all();

                return view('dashboard.properties.features.edit',compact('feature','categories','icons'));



    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'icon' => 'required',
            'category_id' => 'required',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input=$request->all();
            return response(["responseJSON" => $errors,"input"=>$input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        if ($validator->passes()) {
            $data=$request->all();

            $category= Feature::query()->create([
                'name'=> [
                    'en' => $request->input('name.en'),
                    'ar' => $request->input('name.ar'),
                ],
                'icon'=>$request->icon,
                'category_id'=>$request->category_id,
            ]);

            return response()->json(['success'=>"The process has successfully"]);
        }
    }
    public function update(Request $request,$id){
        $category = Feature::find($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'icon' => 'required',
            'category_id' => 'required',


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
                'icon'=>$request->icon,
                'category_id'=>$request->category_id,
            ]);
            return response()->json(['success'=>"The process has successfully"]);
        }
    }
    public function delete($id)
    {
        $category =Feature::find($id);
        $category->delete();
        $arr = array('msg' => 'There are some errors, try again', 'status' => false);
        if($category){
            $arr = array('msg' => "operation accomplished successfully", 'status' => true);
        }
        return Response()->json($arr);

    }
}
