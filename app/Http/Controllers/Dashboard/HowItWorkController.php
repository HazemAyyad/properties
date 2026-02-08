<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\Dashboard\HowItWork;
use App\Models\Dashboard\Slider;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class HowItWorkController extends Controller
{

    public function index(){

                return view('dashboard.how-it-work.index');

    }
    public function get_how_it_works(Request $request)
    {


        return DataTables::of(HowItWork::query())
            ->addIndexColumn()
            ->addColumn('action', function($row) use ($request){

                    $url=route('how-it-work.edit',$row->id);

                $btn = '<a href="'.$url .'" class="ms-2"><i class="fas fa-edit text-success"></i></a>';

                return $btn;
            })

            ->editColumn('description', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->description . '</strong>';
            })
//            ->escapeColumns('name')
            ->rawColumns(['description','action'])
            ->make(true);

    }
    public function create(){



                return view('dashboard.how-it-work.add');

    }
    public function edit($id){
        $how_it_work=HowItWork::query()->findOrFail($id);



                return view('dashboard.how-it-work.edit',compact('how_it_work'));



    }
    public function update(Request $request,$id){
        $how_it_work = HowItWork::find($id);
        $validator = Validator::make($request->all(), [

            'description' => 'required',
            'description_ar' => 'required',
            'photo' => 'required',

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
                $image_name ='/public/uploads/how_it_work/' . time() . '.' . $image_url->getClientOriginalExtension();
                $image_url->move(env('PATH_FILE_URL').'/uploads/how_it_work/', $image_name);
                $data['photo'] = $image_name;
            }
            $how_it_work->update($data);
            return response()->json(['success'=>"The process has successfully"]);
        }
    }
}
