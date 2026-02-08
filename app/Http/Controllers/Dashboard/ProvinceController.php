<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\Dashboard\Province;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ProvinceController extends Controller
{

    public function index(){

                return view('dashboard.provinces.index');

    }
    public function get_provinces(Request $request)
    {


        return DataTables::of(Province::query())
            ->addIndexColumn()
            ->addColumn('action', function($row) use ($request){

                    $url=route('admin.provinces.edit',$row->id);

                $btn = '<a href="'.$url .'" class="ms-2"><i class="fas fa-edit text-success"></i></a>
                        <a href="javascript:void(0)" onclick="deleteItem('.$row->id.')" class=""><i class="fas fa-trash-alt text-danger"></i></a>';

                return $btn;
            })

            ->editColumn('title', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->title . '</strong>';
            })
            ->addColumn('directorates', function ($row) {
                $url_directorates=route('admin.directorates.index',$row->id);
                return '<a href="'.$url_directorates.'" target="_blank"><strong class="Titillium-font text-primary">' . $row->directorates->count() . '</strong></a>';
            })
//            ->escapeColumns('name')
            ->rawColumns(['title','directorates','action'])
            ->make(true);

    }
    public function create(){



                return view('dashboard.provinces.add');

    }
    public function edit($id){
        $province=Province::query()->findOrFail($id);



                return view('dashboard.provinces.edit',compact('province'));



    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required',


        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input=$request->all();
            return response(["responseJSON" => $errors,"input"=>$input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        if ($validator->passes()) {
            $data=$request->all();

            $province = Province::query()->create([
                'title'=> [
                    'en' => $request->input('title.en'),
                    'ar' => $request->input('title.ar'),
                ],



            ]);

            return response()->json(['success'=>"The process has successfully"]);
        }
    }
    public function update(Request $request,$id){
        $province= Province::find($id);
        $validator = Validator::make($request->all(), [
            'title' => 'required',



        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input=$request->all();
            return response(["responseJSON" => $errors,"input"=>$input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        $data = $request->all();

        if ($validator->passes()) {

            $province->update([
                'title'=> [
                    'en' => $request->input('title.en'),
                    'ar' => $request->input('title.ar'),
                ]

            ]);
            return response()->json(['success'=>"The process has successfully"]);
        }
    }
    public function delete($id)
    {
        $province =Province::find($id);
        $province->delete();
        $arr = array('msg' => 'There are some errors, try again', 'status' => false);
        if($province){
            $arr = array('msg' => "operation accomplished successfully", 'status' => true);
        }
        return Response()->json($arr);

    }
}
