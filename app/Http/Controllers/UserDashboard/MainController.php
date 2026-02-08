<?php

namespace App\Http\Controllers\UserDashboard;

use App\Http\Controllers\Controller;

use App\Models\City;
use App\Models\Dashboard\Property;
use App\Models\Dashboard\PropertyReviews;
use App\Models\Jordan\Department;
use App\Models\Jordan\Governorate;
use App\Models\Jordan\Hay;
use App\Models\Jordan\Hod;
use App\Models\Jordan\Village;
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
        $query = Property::query()->with([
            'reviews',
            'address' => fn($q) => $q->with(['governorate', 'department', 'village', 'city', 'state', 'country']),
        ])->where('user_id', Auth::id());

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

    /**
     * Jordan location hierarchy - for property address
     */
    public function getGovernorates()
    {
        $items = Governorate::orderBy('governorate_name_ar')->get();
        return response()->json($items->map(fn ($g) => [
            'id' => $g->governorate_id,
            'name' => $g->governorate_name_ar,
        ]));
    }

    public function getDepartments($governorate_id)
    {
        $items = Department::where('governorate_id', $governorate_id)
            ->orderBy('department_name_ar')
            ->get();
        return response()->json($items->map(fn ($d) => [
            'id' => $d->department_id,
            'name' => $d->department_name_ar,
        ]));
    }

    public function getVillages($department_id)
    {
        $items = Village::where('department_id', $department_id)
            ->orderBy('village_name_ar')
            ->get();
        return response()->json($items->map(fn ($v) => [
            'id' => $v->village_id,
            'name' => $v->village_name_ar,
        ]));
    }

    public function getHods($department_id, $village_id)
    {
        $items = Hod::where('department_id', $department_id)
            ->where('village_id', $village_id)
            ->orderBy('hod_name_ar')
            ->get();
        return response()->json($items->map(fn ($h) => [
            'id' => $h->hod_id,
            'name' => $h->hod_name_ar,
        ]));
    }

    public function getHays($department_id, $village_id, $hod_id)
    {
        $items = Hay::where('department_id', $department_id)
            ->where('village_id', $village_id)
            ->where('hod_id', $hod_id)
            ->orderBy('hay_name_ar')
            ->get();
        return response()->json($items->map(fn ($h) => [
            'id' => $h->hay_id,
            'name' => $h->hay_name_ar,
        ]));
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
