<?php
namespace App\Http\Controllers\Dashboard;
use Illuminate\Http\Request;
class CKEditorController extends Controller
{
    public function upload(Request $request)
    {

        if($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload');
            $image_name ='/public/uploads/photos/' . time() . '.' . $extension->getClientOriginalExtension();
            $extension->move(env('PATH_FILE_URL').'/uploads/photos/', $image_name);


            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = $image_name;
            $msg = 'Image successfully uploaded';
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
    }
    public function upload_by_email(Request $request)
    {

        if($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload');
            $image_name = env('USER_URL') . '/uploads/email/' . time() . '.' . $extension->getClientOriginalExtension();
            $extension->move(env('PATH_EMAIL_URL'), $image_name);


            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = $image_name;
            $msg = 'Image successfully uploaded';
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
    }
}
