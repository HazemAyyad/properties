<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\Dashboard\FeatureCategory;
use App\Models\Dashboard\Icon;
use App\Models\Dashboard\Facility;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class PropertyFacilityController extends Controller
{

    public function index(){

                return view('dashboard.properties.facilities.index');

    }
    public function get_property_facilities(Request $request)
    {


        return DataTables::of(Facility::query())
            ->addIndexColumn()
            ->addColumn('action', function($row) use ($request){

                    $url=route('admin.property_facilities.edit',$row->id);

                $btn = '<a href="'.$url .'" class="ms-2"><i class="fas fa-edit text-success"></i></a>
                        <a href="javascript:void(0)" onclick="deleteItem('.$row->id.')" class=""><i class="fas fa-trash-alt text-danger"></i></a>';

                return $btn;
            })

            ->editColumn('name', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->name . '</strong>';
            })
            ->editColumn('description', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->description . '</strong>';
            })
//            ->escapeColumns('name')
            ->rawColumns(['name','description','action'])
            ->make(true);

    }
    public function create(){

         $icons=Icon::all();
                return view('dashboard.properties.facilities.add',compact('icons'));

    }
    public function edit($id){
        $facility=Facility::query()->findOrFail($id);

        $icons=Icon::all();

                return view('dashboard.properties.facilities.edit',compact('facility','icons'));



    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'icon' => 'required',
            'status' => 'required',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input=$request->all();
            return response(["responseJSON" => $errors,"input"=>$input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        if ($validator->passes()) {
            $data=$request->all();

            $category= Facility::query()->create([
                'name'=> [
                    'en' => $request->input('name.en'),
                    'ar' => $request->input('name.ar'),
                ],
                'icon'=>$request->icon,
                'status'=>$request->status,
            ]);

            return response()->json(['success'=>"The process has successfully"]);
        }
    }
    public function update(Request $request,$id){
        $category = Facility::find($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'icon' => 'required',
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
                'icon'=>$request->icon,
                'status'=>$request->status,
            ]);
            return response()->json(['success'=>"The process has successfully"]);
        }
    }
    public function delete($id)
    {
        $category =Facility::find($id);
        $category->delete();
        $arr = array('msg' => 'There are some errors, try again', 'status' => false);
        if($category){
            $arr = array('msg' => "operation accomplished successfully", 'status' => true);
        }
        return Response()->json($arr);

    }
}
