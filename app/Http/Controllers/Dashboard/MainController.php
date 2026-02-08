<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\City;
use App\Models\Dashboard\Property;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

class MainController extends Controller
{
    public function index()
    {

        return view('dashboard.index');
    }
    public function get_users(Request $request)
    {

//        $query = $request->get('query');
//        $users = User::where('name', 'LIKE', '%' . $query . '%')->get();
//        return response()->json($users);
        $query = $request->get('query');
        $users = User::where('name', 'LIKE', '%' . $query . '%')->get();
        $formattedUsers = [];

        foreach ($users as $user) {
            $formattedUsers[] = ['id' => $user->id, 'text' => $user->name];
        }

        return response()->json($formattedUsers);
    }
    public function checkSlug(Request $request)
    {
        $slug = $request->query('slug');
        $exists = Property::where('slug', $slug)->exists();
        return response()->json(['exists' => $exists]);
    }
    public function getStates($country_id)
    {
        $states = State::where('country_id', $country_id)->get();
        return response()->json($states);
    }

    public function getCities($state_id)
    {
        $cities = City::where('state_id', $state_id)->get();
        return response()->json($cities);
    }
    function upload($file)
    {

        $image_name ='/public/uploads/photos/' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(env('PATH_FILE_URL').'/uploads/photos/', $image_name);
        return $image_name;

} 
    public function saveProjectImages(Request $request)
    {

        $file = $request->file('dzfile');
         $filename = $this->upload($file);
         return response()->json([
            'name' => $filename,
            'original_name' => $file->getClientOriginalName(),
        ]);

    }
}
