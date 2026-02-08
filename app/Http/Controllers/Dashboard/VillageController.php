<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\City;
use App\Models\Dashboard\Country;
use App\Models\Dashboard\Directorate;
use App\Models\Dashboard\Province;
use App\Models\Dashboard\Village;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class VillageController extends Controller
{


    public function index($directorate_id){
        $directorate=Directorate::query()->where('id',$directorate_id)->first();
        $province=Directorate::query()->where('id',$directorate->province_id)->first();
                return view('dashboard.villages.index',compact('province','directorate'));

    }
    public function get_villages(Request $request,$directorate_id)
    {


        return DataTables::of(Village::query()->where('directorate_id',$directorate_id))
            ->addIndexColumn()
            ->addColumn('action', function($row) use ($request){

                    $url=route('admin.villages.edit',$row->id);

                $btn = '<a href="'.$url .'" class="ms-2"><i class="fas fa-edit text-success"></i></a>
                        <a href="javascript:void(0)" onclick="deleteItem('.$row->id.')" class=""><i class="fas fa-trash-alt text-danger"></i></a>';

                return $btn;
            })

            ->editColumn('name', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->name . '</strong>';
            })

//            ->escapeColumns('name')
            ->rawColumns(['name','action'])
            ->make(true);

    }
    public function create($directorate_id){
        $directorate=Directorate::query()->where('id',$directorate_id)->first();
        $province=Directorate::query()->where('id',$directorate->province_id)->first();


                return view('dashboard.directorates.add',compact('province','directorate'));

    }
    public function edit($id){
        $village=Village::query()->findOrFail($id);
        $directorate=Directorate::query()->where('id',$$village->directorate_id)->first();
         $province=Province::query()->where('id',$directorate->province_id)->first();

        return view('dashboard.directorates.edit',compact('directorate','province','village'));



    }
     public function store(Request $request,$province_id){
        $validator = Validator::make($request->all(), [
            'name' => 'required',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input=$request->all();
            return response(["responseJSON" => $errors,"input"=>$input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        if ($validator->passes()) {



             Directorate::query()->create([
                'name'=> [
                    'en' => $request->input('name.en'),
                    'ar' => $request->input('name.ar'),
                ],
                 'province_id'=> $province_id
             ]);
            return response()->json(['success'=>"The process has successfully"]);
        }
    }
    public function update(Request $request,$id){
    $directorate=Directorate::find($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required',


        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input=$request->all();
            return response(["responseJSON" => $errors,"input"=>$input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }

        if ($validator->passes()) {

            $directorate->update([
                'name'=> [
                    'en' => $request->input('name.en'),
                    'ar' => $request->input('name.ar'),
                ],
            ]);
            return response()->json(['success'=>"The process has successfully"]);
        }
    }

    public function city($id)
    {
        $directorate=Directorate::find($id);
        $directorate->delete();
        $arr = array('msg' => 'There are some errors, try again', 'status' => false);
        if($directorate){
            $arr = array('msg' => "operation accomplished successfully", 'status' => true);
        }
        return Response()->json($arr);

    }
}
