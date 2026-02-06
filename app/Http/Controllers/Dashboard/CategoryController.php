<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Dashboard\Facility;
use App\Models\Dashboard\Category;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{

    public function index(){

                return view('dashboard.properties.categories.index');

    }
    public function get_categories(Request $request)
    {


        return DataTables::of(Category::query())
            ->addIndexColumn()
            ->addColumn('action', function($row) use ($request){

                    $url=route('admin.categories.edit',$row->id);
                $link_cat=route('admin.categories.edit',$row->slug);

                $btn = '<a href="'.$url .'" class="ms-2"><i class="fas fa-edit text-success"></i></a>
                            <a href="'.$link_cat .'" class="me-2 ms-2"><i class="fas fa-globe  text-primary"></i></a>
                        <a href="javascript:void(0)" onclick="deleteItem('.$row->id.')" class=""><i class="fas fa-trash-alt text-danger"></i></a>';

                return $btn;
            })

            ->editColumn('name', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->name . '</strong>';
            })

            ->editColumn('description', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->description . '</strong>';
            })
            ->editColumn('status', function ($row) {

                $status= $row->status;
                if ($status == 0) {
                    $class = 'text-danger';
                    $tooltipetitle =__('Pending');
                } elseif ($status == 1) {
                    $class = 'text-success';
                    $tooltipetitle = __('Published');
                }
                elseif ($status == 2) {
                    $class = 'text-warning';
                    $tooltipetitle = __('draft');
                }else {
                    $class = 'text-danger';
                    $tooltipetitle = __('Unknown');
                }
                return '<strong  class="' . $class . ' " tabindex="0" data-toggle="tooltip" title="' . $tooltipetitle . '" >' . $tooltipetitle . '</strong>';

            })
//            ->escapeColumns('name')
            ->rawColumns(['name','description','status','action'])
            ->make(true);

    }
    public function create(){
        return view('dashboard.properties.categories.add');
    }
    public function edit($id){
        $category=Category::query()->findOrFail($id);



                return view('dashboard.properties.categories.edit',compact('category'));



    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required',
            'status' => 'required',
            'description' => 'required',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input=$request->all();
            return response(["responseJSON" => $errors,"input"=>$input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        if ($validator->passes()) {


                $category= Category::query()->create([
                    'name'=> [
                        'en' => $request->input('name.en'),
                        'ar' => $request->input('name.ar'),
                    ],
                    'description'=> [
                        'en' => $request->input('description.en'),
                        'ar' => $request->input('description.ar'),
                    ],
                    'slug'=> [
                        'en' => $request->input('slug.en'),
                        'ar' => $request->input('slug.ar'),
                    ],
                    'status'=>$request->status
                ]);


            return response()->json(['success'=>"The process has successfully"]);
        }
    }
    public function update(Request $request,$id){
        $category = Category::find($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required',
            'status' => 'required',
            'description' => 'required',

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
                 'description'=> [
                     'en' => $request->input('description.en'),
                     'ar' => $request->input('description.ar'),
                 ],
                 'slug'=> [
                     'en' => $request->input('slug.en'),
                     'ar' => $request->input('slug.ar'),
                 ],
                 'status'=>$request->status
             ]);
            return response()->json(['success'=>"The process has successfully"]);
        }
    }
    public function delete($id)
    {
        $category =Category::find($id);
        $category->delete();
        $arr = array('msg' => 'There are some errors, try again', 'status' => false);
        if($category){
            $arr = array('msg' => "operation accomplished successfully", 'status' => true);
        }
        return Response()->json($arr);

    }
}
