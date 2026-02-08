<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\Dashboard\Service;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ServiceController extends Controller
{

    public function index(){

                return view('dashboard.services.index');

    }
    public function get_services(Request $request)
    {


        return DataTables::of(Service::query())
            ->addIndexColumn()
            ->addColumn('action', function($row) use ($request){

                    $url=route('admin.services.edit',$row->id);

                $btn = '<a href="'.$url .'" class="ms-2"><i class="fas fa-edit text-success"></i></a>
                        <a href="javascript:void(0)" onclick="deleteItem('.$row->id.')" class=""><i class="fas fa-trash-alt text-danger"></i></a>';

                return $btn;
            })

            ->editColumn('title', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->title . '</strong>';
            })

//            ->escapeColumns('name')
            ->rawColumns(['title','action'])
            ->make(true);

    }
    public function create(){



                return view('dashboard.services.add');

    }
    public function edit($id){
        $service=Service::query()->findOrFail($id);



                return view('dashboard.services.edit',compact('service'));



    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required',

            'description' => 'required',
            'slug' => 'required',
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
                $image_name ='/public/uploads/services/' . time() . '.' . $image_url->getClientOriginalExtension();
                $image_url->move(env('PATH_FILE_URL').'/uploads/services/', $image_name);
                $data['photo'] = $image_name;
            }
            $service = Service::query()->create([
                'title'=> [
                    'en' => $request->input('title.en'),
                    'ar' => $request->input('title.ar'),
                ],

                'description'=> [
                    'en' => $request->input('description.en'),
                    'ar' => $request->input('description.ar'),
                ],
                'slug'=> [
                    'en' => $request->input('slug.en'),
                    'ar' => $request->input('slug.ar'),
                ],
                'photo'=>$data['photo'] ,

            ]);

            return response()->json(['success'=>"The process has successfully"]);
        }
    }
    public function update(Request $request,$id){
        $service = Service::find($id);
        $validator = Validator::make($request->all(), [
            'title' => 'required',
             'description' => 'required',
             'slug' => 'required',


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
                $image_name ='/public/uploads/services/' . time() . '.' . $image_url->getClientOriginalExtension();
                $image_url->move(env('PATH_FILE_URL').'/uploads/services/', $image_name);
                $data['photo'] = $image_name;
            }
            $service->update([
                'title'=> [
                    'en' => $request->input('title.en'),
                    'ar' => $request->input('title.ar'),
                ],

                'description'=> [
                    'en' => $request->input('description.en'),
                    'ar' => $request->input('description.ar'),
                ],
                'slug'=> [
                    'en' => $request->input('slug.en'),
                    'ar' => $request->input('slug.ar'),
                ],
                'photo'=>$data['photo']?? $service->photo ,

            ]);
            return response()->json(['success'=>"The process has successfully"]);
        }
    }
    public function delete($id)
    {
        $service =Service::find($id);
        $service->delete();
        $arr = array('msg' => 'There are some errors, try again', 'status' => false);
        if($service){
            $arr = array('msg' => "operation accomplished successfully", 'status' => true);
        }
        return Response()->json($arr);

    }
}
