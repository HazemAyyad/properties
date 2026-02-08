<?php
namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;
use App\Models\NewsletterSubscriber;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'email' => 'required|email|unique:newsletter_subscribers,email',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input=$request->all();
            return response(["responseJSON" => $errors,"input"=>$input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        if ($validator->passes()) {
            NewsletterSubscriber::create([
                'email' => $request->email,
            ]);

            return response()->json(['success'=>"The process has successfully"]);
        }

    }
}
