<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\City;
use App\Models\Dashboard\Country;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class CityController extends Controller
{


    public function index($country_id){
        $country=Country::query()->where('id',$country_id)->first();
                return view('dashboard.cities.index',compact('country'));

    }
    public function get_cities(Request $request,$country_id)
    {


        return DataTables::of(City::query()->where('country_id',$country_id))
            ->addIndexColumn()
            ->addColumn('action', function($row) use ($request){

                    $url=route('admin.cities.edit',$row->id);

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
    public function create($country_id){
        $country=Country::query()->where('id',$country_id)->first();



                return view('dashboard.cities.add',compact('country'));

    }
    public function edit($id){
        $city=City::query()->findOrFail($id);
        $country=Country::query()->where('id',$city->country_id)->first();

                return view('dashboard.cities.edit',compact('city','country'));



    }
     public function store(Request $request,$country_id){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
//            'slug' => 'required',

            'photo' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input=$request->all();
            return response(["responseJSON" => $errors,"input"=>$input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        if ($validator->passes()) {
            $data=$request->all();
            if ($request->hasfile('photo')) {
                $image_url = $request->file('photo');
                $image_name = '/public/uploads/cities/' . time() . '.' . $image_url->getClientOriginalExtension();
                $image_url->move(env('PATH_FILE_URL').'/uploads/cities/', $image_name);
                $data['photo'] = $image_name;
            }

            $data['country_id'] = $country_id;
            $city= City::query()->create($data);

            return response()->json(['success'=>"The process has successfully"]);
        }
    }
    public function update(Request $request,$id){
        $city = City::find($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required',


        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input=$request->all();
            return response(["responseJSON" => $errors,"input"=>$input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        $data = $request->all();

        if ($validator->passes()) {
            if ($request->hasfile('photo')) {
                $image_url = $request->file('photo');
                $image_name = '/public/uploads/cities/' . time() . '.' . $image_url->getClientOriginalExtension();
                $image_url->move(env('PATH_FILE_URL').'/uploads/cities/', $image_name);
                $data['photo'] = $image_name;
            }
            $city->update($data);
            return response()->json(['success'=>"The process has successfully"]);
        }
    }

    public function city($id)
    {
        $city =City::find($id);
        $city->delete();
        $arr = array('msg' => 'There are some errors, try again', 'status' => false);
        if($city){
            $arr = array('msg' => "operation accomplished successfully", 'status' => true);
        }
        return Response()->json($arr);

    }
}
