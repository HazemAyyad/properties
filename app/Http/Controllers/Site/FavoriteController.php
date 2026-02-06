<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;

use App\Mail\GeneralEmail;
use App\Models\City;
use App\Models\Dashboard\Agent;
use App\Models\Dashboard\Benefit;
use App\Models\Dashboard\Blog;
use App\Models\Dashboard\Category;
use App\Models\Dashboard\Faqs;
use App\Models\Dashboard\Feature;
use App\Models\Dashboard\FeatureCategory;
use App\Models\Dashboard\Partner;
use App\Models\Dashboard\PeopleSay;
use App\Models\Dashboard\Property;
use App\Models\Dashboard\Service;
use App\Models\Dashboard\Setting;
use App\Models\Favorite;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

class FavoriteController extends Controller
{
    public function index(){
        $user = auth()->user(); // Get the currently authenticated user

        // Assuming the favorites are stored in a 'favorites' relationship or table
        $favoritePropertyIds = $user->favorites()->pluck('property_id');

        // Fetch properties based on the favorite IDs
        $properties = Property::query()
            ->whereIn('id', $favoritePropertyIds) // Filter properties that are in the favorites list
            ->with([
                'images' => function ($query) {
                    $query->take(1); // Limit to the first image
                },
                'price', // Include the related price
                'more_info', // Include the related more_info
                'address', // Include the related address
                'user' // Include the related user
            ])->paginate(10);

        return view('user_dashboard.favorites.index', compact('properties'));
    }

    public function toggleFavorite(Request $request)
    {
        $propertyId = $request->input('property_id');
        $userId = Auth::id();

        // Check if the property is already in the user's favorites
        $favorite = Favorite::where('user_id', $userId)
            ->where('property_id', $propertyId)
            ->first();

        if ($favorite) {
            // Property is already in favorites, so remove it
            $favorite->delete();
            return response()->json(['status' => 'removed']);
        } else {
            // Property is not in favorites, so add it
            Favorite::create([
                'user_id' => $userId,
                'property_id' => $propertyId,
            ]);
            return response()->json(['status' => 'added']);
        }
    }
    public function delete($id)
    {
        $favorites =Favorite::query()->where(['property_id' => $id, 'user_id' => Auth::id()])->first();
        $favorites->delete();
        $arr = array('msg' => 'There are some errors, try again', 'status' => false);
        if($favorites){
            $arr = array('msg' => "operation accomplished successfully", 'status' => true);
        }
        return Response()->json($arr);

    }
}
