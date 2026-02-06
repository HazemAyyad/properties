<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Dashboard\Admin;
use App\Models\Dashboard\Blog;
use App\Models\Dashboard\BlogCategory;
use App\Models\Dashboard\BlogTag;
use App\Models\Dashboard\Faqs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class BlogController extends Controller
{

    public function index(){

                return view('dashboard.blogs.index');

    }
    public function get_blogs(Request $request)
    {


        return DataTables::of(Blog::query()->get())
            ->addIndexColumn()
            ->addColumn('action', function($row) use ($request){

                    $url=route('admin.blogs.edit',$row->id);

                $btn = '<a href="'.$url .'" class="ms-2"><i class="fas fa-edit text-success"></i></a>
                        <a href="javascript:void(0)" onclick="deleteItem('.$row->id.')" class=""><i class="fas fa-trash-alt text-danger"></i></a>';

                return $btn;
            })

            ->editColumn('title_en', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->title_en . '</strong>';
            })
//            ->escapeColumns('name')
            ->rawColumns(['title_en','action'])
            ->make(true);

    }
    public function create(){

        $categories=BlogCategory::all();
        $tags=BlogTag::all();
                return view('dashboard.blogs.add',compact('categories','tags'));

    }
    public function edit($id){
        $blog=Blog::query()->findOrFail($id);
        $categories=BlogCategory::all();
        $tags=BlogTag::all();


                return view('dashboard.blogs.edit',compact('blog','categories','tags'));



    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
             'title_en' => 'required',
            'title_ar' => 'required',
            'short_description_en' => 'required',
            'short_description_ar' => 'required',
            'description_en' => 'required',
            'description_ar' => 'required',
            'category_id' => 'required',
            'tags' => 'required',
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
                $image_name ='/public/uploads/blogs/' . time() . '.' . $image_url->getClientOriginalExtension();
                $image_url->move(env('PATH_FILE_URL').'/uploads/blogs/', $image_name);
                $data['photo'] = $image_name;
            }

                $data['tags'] = json_encode($request->tags,true);
                $data['user_id'] = Auth::guard('admin')->id();
             Blog::query()->create($data);

            return response()->json(['success'=>"The process has successfully"]);
        }
    }
    public function status(Request $request,$id){
          Blog::find($id);
        $validator = Validator::make($request->all(), [
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response(["responseJSON" => $errors, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        $data = $request->all();
        unset($data['_token']);

          Blog::query()->where('id', $id)
            ->update($data);

        if ($validator->passes()) {
            return response()->json(['success'=>"The process has successfully"]);
        }

    }
    public function update(Request $request,$id){
        $blog = Blog::find($id);
        $validator = Validator::make($request->all(), [
            'title_en' => 'required',
            'title_ar' => 'required',
            'short_description_en' => 'required',
            'short_description_ar' => 'required',
            'description_en' => 'required',
            'description_ar' => 'required',
            'category_id' => 'required',
            'tags' => 'required',

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
                $image_name ='/public/uploads/blogs/' . time() . '.' . $image_url->getClientOriginalExtension();
                $image_url->move(env('PATH_FILE_URL').'/uploads/blogs/', $image_name);
                $data['photo'] = $image_name;
            }
            $data['tags'] = json_encode($request->tags,true);


            $blog->update($data);
            return response()->json(['success'=>"The process has successfully"]);
        }
    }
    public function delete($id)
    {
        $blog =Blog::find($id);
        $blog->delete();
        $arr = array('msg' => 'There are some errors, try again', 'status' => false);
        if($blog){
            $arr = array('msg' => "operation accomplished successfully", 'status' => true);
        }
        return Response()->json($arr);

    }
}
