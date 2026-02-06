<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Dashboard\Admin;
use App\Models\Dashboard\Faqs;
use App\Models\FaqCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class FaqsController extends Controller
{

    public function index(){

                return view('dashboard.faqs.index');

    }
    public function get_faqs(Request $request)
    {


        return DataTables::of(Faqs::query()->get())
            ->addIndexColumn()
            ->addColumn('action', function($row) use ($request){

                    $url=route('admin.faqs.edit',$row->id);

                $btn = '<a href="'.$url .'" class="ms-2"><i class="fas fa-edit text-success"></i></a>
                        <a href="javascript:void(0)" onclick="deleteItem('.$row->id.')" class=""><i class="fas fa-trash-alt text-danger"></i></a>';

                return $btn;
            })

            ->editColumn('title_en', function ($row) {
                return '<strong class="Titillium-font danger">' . $row->title_en . '</strong>';
            })
            ->addColumn('category', function ($row) {

                $type = $row->category_id;
                if ($type == 0) {
                    $type='Overview';
                    $class = 'text-success';
                    $tooltipetitle =$type;
                }
                elseif ($type == 1) {
                    $type='Costs and Payments';
                    $class = 'text-primary';
                    $tooltipetitle =$type;
                }
                elseif ($type == 2) {
                    $type='Safety and Security';
                    $class = 'text-warning';
                    $tooltipetitle =$type;
                }
                 elseif ($type == 3) {
                    $type='Other';
                    $class = 'text-danger';
                    $tooltipetitle =$type;
                }

                else {
                    $type='Unknown';
                    $class = 'text-danger';
                    $tooltipetitle = $type;
                }
                return '<strong  class="' . $class . ' " tabindex="0" data-toggle="tooltip" title="' . $tooltipetitle . '" >' . $row->category->title . '</strong>';

                // return '<button class="btn btn-sm ' . $class . ' round" type="button"  data-toggle="tooltip" data-placement="top" title=" ' . $tooltipetitle . ' .">' . $status . '<i class="fa fa-info-circle" ></i></button>';
            })
//            ->escapeColumns('name')
            ->rawColumns(['title_en','category','action'])
            ->make(true);

    }
    public function create(){
        $categories=FaqCategory::all();


                return view('dashboard.faqs.add',compact('categories'));

    }
    public function edit($id){
        $faqs=Faqs::query()->findOrFail($id);
        $categories=FaqCategory::all();


                return view('dashboard.faqs.edit',compact('faqs','categories'));



    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'title_en' => 'required',
            'title_ar' => 'required',
            'answer_en' => 'required',
            'answer_ar' => 'required',
            'category_id' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input=$request->all();
            return response(["responseJSON" => $errors,"input"=>$input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        if ($validator->passes()) {
            $data=$request->all();
            $faq = Faqs::query()->create($data);

            return response()->json(['success'=>"The process has successfully"]);
        }
    }
    public function update(Request $request,$id){
        $faqs = Faqs::find($id);
        $validator = Validator::make($request->all(), [
            'title_en' => 'required',
            'title_ar' => 'required',
            'answer_en' => 'required',
            'answer_ar' => 'required',
            'category_id' => 'required',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input=$request->all();
            return response(["responseJSON" => $errors,"input"=>$input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        $data = $request->all();

        if ($validator->passes()) {

            $faqs->update($data);
            return response()->json(['success'=>"The process has successfully"]);
        }
    }
    public function delete($id)
    {
        $faqs =Faqs::find($id);
        $faqs->delete();
        $arr = array('msg' => 'There are some errors, try again', 'status' => false);
        if($faqs){
            $arr = array('msg' => "operation accomplished successfully", 'status' => true);
        }
        return Response()->json($arr);

    }
}
