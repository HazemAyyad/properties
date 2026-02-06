<?php

namespace App\Http\Controllers\UserDashboard;

use App\Http\Controllers\Controller;

use App\Models\City;
use App\Models\Dashboard\Property;
use App\Models\Dashboard\PropertyReviews;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Ramsey\Uuid\Uuid;

class MainController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::query()->with(['reviews'])->where('user_id', Auth::id());

        // Search Filter
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Date Range Filter
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }

        // Date Filter (Today/Yesterday)
        if ($request->date_filter == 'today') {
            $query->whereDate('created_at', today());
        } elseif ($request->date_filter == 'yesterday') {
            $query->whereDate('created_at', today()->subDay());
        }

        // Paginate the results
        $properties = $query->paginate(10);


// Fetch reviews for each property
          $propertyIds = $properties->pluck('id');
          $reviews = PropertyReviews::query()
            ->whereIn('property_id', $propertyIds)
            ->with('user')
            ->get()
            ->groupBy('property_id'); 


        return view('user_dashboard.index',compact('properties','reviews'));
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

        $image_name = '/public/upload/properties/' . Uuid::uuid4() . '.' . $file->getClientOriginalExtension();
        $file->move(env('PATH_FILE_URL').'/upload/properties/', $image_name);
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
