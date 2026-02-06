<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\City;
use App\Models\Dashboard\Country;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class CountryController extends Controller
{

    public function index(){

                return view('dashboard.countries.index');

    }

    public function get_countries(Request $request)
    {


        return DataTables::of(Country::query())
            ->addIndexColumn()
            ->addColumn('action', function($row) use ($request){

                    $url=route('admin.countries.edit',$row->id);

                $btn = '<a href="'.$url .'" class="ms-2"><i class="fas fa-edit text-success"></i></a>
                        <a href="javascript:void(0)" onclick="deleteItem('.$row->id.')" class=""><i class="fas fa-trash-alt text-danger"></i></a>';

                return $btn;
            })

            ->editColumn('name', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->name . '</strong>';
            })
            ->addColumn('cities', function ($row) {
                $url_cities=route('admin.cities.index',$row->id);
                return '<a href="'.$url_cities.'" target="_blank"><strong class="Titillium-font text-primary">' . $row->cities->count() . '</strong></a>';
            })
//            ->escapeColumns('name')
            ->rawColumns(['name','cities','action'])
            ->make(true);

    }
    public function create(){



                return view('dashboard.countries.add');

    }
    public function edit($id){
        $country=Country::query()->findOrFail($id);



                return view('dashboard.countries.edit',compact('country'));



    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'iso' => 'required',
            'iso3' => 'required',
            'currency' => 'required',
            'phone_code' => 'required',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input=$request->all();
            return response(["responseJSON" => $errors,"input"=>$input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        if ($validator->passes()) {
            $data=$request->all();

            $country = Country::query()->create($data);

            return response()->json(['success'=>"The process has successfully"]);
        }
    }

    public function update(Request $request,$id){
        $country = Country::find($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'iso' => 'required',
            'iso3' => 'required',
            'currency' => 'required',
            'phone_code' => 'required',


        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input=$request->all();
            return response(["responseJSON" => $errors,"input"=>$input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        $data = $request->all();

        if ($validator->passes()) {

            $country->update($data);
            return response()->json(['success'=>"The process has successfully"]);
        }
    }

    public function delete($id)
    {
        $country =Country::find($id);
        $country->delete();
        $arr = array('msg' => 'There are some errors, try again', 'status' => false);
        if($country){
            $arr = array('msg' => "operation accomplished successfully", 'status' => true);
        }
        return Response()->json($arr);

    }

}
