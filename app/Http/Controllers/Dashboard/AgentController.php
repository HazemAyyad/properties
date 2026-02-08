<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\Dashboard\agent;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class AgentController extends Controller
{

    public function index(){

                return view('dashboard.agents.index');

    }
    public function get_agents(Request $request)
    {


        return DataTables::of(Agent::query())
            ->addIndexColumn()
            ->addColumn('action', function($row) use ($request){

                    $url=route('admin.agents.edit',$row->id);

                $btn = '<a href="'.$url .'" class="ms-2"><i class="fas fa-edit text-success"></i></a>
                        <a href="javascript:void(0)" onclick="deleteItem('.$row->id.')" class=""><i class="fas fa-trash-alt text-danger"></i></a>';

                return $btn;
            })

            ->editColumn('name', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->name . '</strong>';
            })
            ->editColumn('position', function ($row) {
                return '<strong class="Titillium-font text-danger">' . $row->position . '</strong>';
            })
//            ->escapeColumns('name')
            ->rawColumns(['name','position','action'])
            ->make(true);

    }
    public function create(){



                return view('dashboard.agents.add');

    }
    public function edit($id){
        $agent=Agent::query()->findOrFail($id);



                return view('dashboard.agents.edit',compact('agent'));



    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'position' => 'required',
            'facebook' => 'required',
            'twitter' => 'required',
            'instagram' => 'required',
            'linkedin' => 'required',
            'phone' => 'required',
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
                $image_name ='/public/uploads/agents/' . time() . '.' . $image_url->getClientOriginalExtension();
                $image_url->move(env('PATH_FILE_URL').'/uploads/agents/', $image_name);
                $data['photo'] = $image_name;
            }
            $agent = Agent::query()->create([
                'name'=> [
                    'en' => $request->input('name.en'),
                    'ar' => $request->input('name.ar'),
                ],

                'position'=> [
                    'en' => $request->input('position.en'),
                    'ar' => $request->input('position.ar'),
                ],
                'photo'=>$data['photo'] ,
                'facebook'=>$request->facebook,
                'twitter'=>$request->twitter,
                'instagram'=>$request->instagram,
                'linkedin'=>$request->linkedin,
                'phone'=>$request->phone,
            ]);

            return response()->json(['success'=>"The process has successfully"]);
        }
    }
    public function update(Request $request,$id){
        $agent = Agent::find($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'position' => 'required',
            'facebook' => 'required',
            'twitter' => 'required',
            'instagram' => 'required',
            'linkedin' => 'required',
            'phone' => 'required',
//            'photo' => 'required',


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
                $image_name ='/public/uploads/agents/' . time() . '.' . $image_url->getClientOriginalExtension();
                $image_url->move(env('PATH_FILE_URL').'/uploads/agents/', $image_name);
                $data['photo'] = $image_name;
            }
            $agent->update([
                'name'=> [
                    'en' => $request->input('name.en'),
                    'ar' => $request->input('name.ar'),
                ],

                'position'=> [
                    'en' => $request->input('position.en'),
                    'ar' => $request->input('position.ar'),
                ],
                'photo'=>$data['photo'] ,
                'facebook'=>$request->facebook,
                'twitter'=>$request->twitter,
                'instagram'=>$request->instagram,
                'linkedin'=>$request->linkedin,
                'phone'=>$request->phone,
            ]);
            return response()->json(['success'=>"The process has successfully"]);
        }
    }
    public function delete($id)
    {
        $agent =Agent::find($id);
        $agent->delete();
        $arr = array('msg' => 'There are some errors, try again', 'status' => false);
        if($agent){
            $arr = array('msg' => "operation accomplished successfully", 'status' => true);
        }
        return Response()->json($arr);

    }
}
