<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\Dashboard\Partner;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class PartnerController extends Controller
{

    public function index(){

                return view('dashboard.partners.index');

    }
    public function get_partners(Request $request)
    {


        return DataTables::of(Partner::query())
            ->addIndexColumn()
            ->addColumn('action', function($row) use ($request){

                    $url=route('admin.partners.edit',$row->id);

                $btn = '<a href="'.$url .'" class="ms-2"><i class="fas fa-edit text-success"></i></a>
                        <a href="javascript:void(0)" onclick="deleteItem('.$row->id.')" class=""><i class="fas fa-trash-alt text-danger"></i></a>';

                return $btn;
            })

            ->editColumn('title', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->title . '</strong>';
            })
            ->editColumn('description', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->description . '</strong>';
            })
//            ->escapeColumns('name')
            ->rawColumns(['title','description','action','cities_count'])
            ->make(true);

    }
    public function create(){



                return view('dashboard.partners.add');

    }
    public function edit($id){
        $partner=Partner::query()->findOrFail($id);



                return view('dashboard.partners.edit',compact('partner'));



    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'link' => 'required',
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
                $image_name ='/public/uploads/partners/' . time() . '.' . $image_url->getClientOriginalExtension();
                $image_url->move(env('PATH_FILE_URL').'/uploads/partners/', $image_name);
                $data['photo'] = $image_name;
            }
            $partner = Partner::query()->create($data);

            return response()->json(['success'=>"The process has successfully"]);
        }
    }
    public function update(Request $request,$id){
        $partner = Partner::find($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'link' => 'required',
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
                $image_name ='/public/uploads/partners/' . time() . '.' . $image_url->getClientOriginalExtension();
                $image_url->move(env('PATH_FILE_URL').'/uploads/partners/', $image_name);
                $data['photo'] = $image_name;
            }
            $partner->update($data);
            return response()->json(['success'=>"The process has successfully"]);
        }
    }
    public function delete($id)
    {
        $partner =Partner::find($id);
        $partner->delete();
        $arr = array('msg' => 'There are some errors, try again', 'status' => false);
        if($partner){
            $arr = array('msg' => "operation accomplished successfully", 'status' => true);
        }
        return Response()->json($arr);

    }
}
