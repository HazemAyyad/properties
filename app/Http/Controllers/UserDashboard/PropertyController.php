<?php

namespace App\Http\Controllers\UserDashboard;

use App\Events\NotificationEvent;
use App\Http\Controllers\Controller;

use App\Models\Country;
use App\Models\Dashboard\Category;
use App\Models\Dashboard\FeatureCategory;
use App\Models\Dashboard\Icon;
use App\Models\Dashboard\Facility;
use App\Models\Dashboard\Feature;
use App\Models\Dashboard\Property;
use App\Models\Dashboard\PropertyAddress;
use App\Models\Dashboard\PropertyFacility;
use App\Models\Dashboard\PropertyFeature;
use App\Models\Dashboard\PropertyImage;
use App\Models\Dashboard\PropertyInformation;
use App\Models\Dashboard\PropertyPrice;
use App\Models\Dashboard\PropertyReviews;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class PropertyController extends Controller
{
    protected function generateArabicSlug($title)
    {
        // Replace spaces with hyphens, keeping Arabic characters intact
        $slug = preg_replace('/\s+/u', '-', trim($title));

        // Optionally, remove any characters that aren't letters, numbers, or hyphens
        $slug = preg_replace('/[^\p{L}\p{N}\-]+/u', '', $slug);

        return $slug;
    }

    public function generateUniqueSlug($title, $column = 'slug', $lang = 'ar')
    {
        if ($lang == 'ar') {
            $slug = $this->generateArabicSlug($title);
        } else {
            $slug = Str::slug($title);
        }

        $originalSlug = $slug;
        $count = 1;

        while (Property::where($column, $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    public function generateSlug(Request $request)
    {
        $lang = $request->get('lang', 'en'); // Default to 'en' if no language is provided
        $slug = $this->generateUniqueSlug($request->name, 'slug', $lang);
        return response()->json(['slug' => $slug]);
    }
    public function index(){
        $properties=Property::query()
            ->where('user_id',Auth::id())
            ->with([
                'images' => function ($query) {
                    $query->take(1); // Limit to the first image
                },
                'price',
                'more_info',
                'address' => function ($q) {
                    $q->with(['governorate', 'department', 'village', 'city', 'state', 'country']);
                },
                'user'
            ])->paginate(10);
                return view('user_dashboard.properties.index',compact('properties'));

    }
    public function reviews(){
        $user = Auth::user(); // Get the authenticated user

        // Fetch all properties owned by the authenticated user
        $properties = $user->properties()->pluck('id'); // Get all property IDs owned by the user

        // Fetch all reviews for the user's properties
        $reviews = PropertyReviews::whereIn('property_id', $properties) // Match reviews by property_id
        ->with('property','user') // Eager load the property relationship
        ->paginate(10);

        return view('user_dashboard.reviews.index',compact('reviews'));


    }
    public function update_status_review(Request $request, $review)
    {
        $validated = $request->validate([
            'status' => 'required|integer|in:0,1,2',
        ]);
        $review=PropertyReviews::query()->findOrFail($review);
        $review->status = $validated['status'];
        $review->save();

        return response()->json(['status' => true, 'msg' => "operation accomplished successfully"]);
    }
    public function delete_review($id)
    {
        $review =PropertyReviews::query()->where(['id' => $id])->first();
        $review->delete();
        $arr = array('msg' => 'There are some errors, try again', 'status' => false);
        if($review){
            $arr = array('msg' => "operation accomplished successfully", 'status' => true);
        }
        return Response()->json($arr);

    }

    public function create(){

        $categories=Category::all();
        $facilities=Facility::all();
        $feature_categories=FeatureCategory::query()->with('features')->get();
        $icons=Icon::all();
        $countries = Country::all();
        // Collect unique currencies and remove empty ones
        $uniqueCurrencies = $countries->map(function ($country) {
            return $country->currency;
        })->filter(function ($currency) {
            return !empty($currency); // Filter out empty currencies
        })->unique()->values(); // Remove duplicates and reindex


                return view('user_dashboard.properties.create',compact('icons','categories',
                    'countries','feature_categories','facilities','uniqueCurrencies'));

    }
    public function edit($id){
        $property=Property::query()->findOrFail($id);
        $categories=Category::all();
        $facilities=Facility::all();
        $feature_categories=FeatureCategory::query()->with('features')->get();
        $icons=Icon::all();
        $countries = Country::all();
        // Collect unique currencies and remove empty ones
        $uniqueCurrencies = $countries->map(function ($country) {
            return $country->currency;
        })->filter(function ($currency) {
            return !empty($currency); // Filter out empty currencies
        })->unique()->values(); // Remove duplicates and reindex


        return view('user_dashboard.properties.edit',compact('icons','categories',
            'countries','feature_categories','facilities','uniqueCurrencies','property'));

    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'contact_name' => 'required',
            'contact_phone' => 'required',
            'registration_document' => 'required',
//            'video_url' => 'required',
            'content' => 'required',
            'images' => 'required',
            'governorate_id' => 'required',
            'department_id' => 'required',
            'village_id' => 'required',
            'hod_id' => 'required',
            'hay_id' => 'required',
            'plot_number' => 'required',
//            'latitude' => 'required',
//            'longitude' => 'required',
            'price' => 'required',
            'currency' => 'required',
//            'period' => 'required',

            'size' => 'required',
            'land_area' => 'required',
            'rooms' => 'required',
            'bedrooms' => 'required',
            'bathrooms' => 'required',
            'garages' => 'required',
            'garages_size' => 'required',
            'floors' => 'required',
            'year_built' => 'required',
            'property_features' => 'required',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input=$request->all();
            return response(["responseJSON" => $errors,"input"=>$input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        if ($validator->passes()) {
            $data=$request->all();
            DB::beginTransaction();
            try {
                if ($request->hasfile('registration_document')) {
                    $image_url = $request->file('registration_document');
                    $registration_document ='/public/uploads/registration_document/' . time() . '.' . $image_url->getClientOriginalExtension();
                    $image_url->move(env('PATH_FILE_URL').'/uploads/registration_document/', $registration_document);

                }
                $property = Property::query()->create([
                    'title' => $request->name,
                    'user_id' => Auth::guard('web')->user()->id,
                    'description' => $request->description,
                    'slug' => $request->slug,
                    'type' => $request->type,
                    'status' => $request->status,
//                    'moderation_status' => $request->moderation_status??1,
                    'category_id' => $request->category_id,
                    'contact_name' => $request->contact_name,
                    'contact_phone' => $request->contact_phone,
                    'registration_document' => $registration_document,
                ]);
                $information = PropertyInformation::query()->create([
                    'property_id' => $property->id,
                    'content' => $request['content'],
                    'video_url' => $request->video_url,
                    'size' => $request->size,
                    'land_area' => $request->land_area,
                    'rooms' => $request->rooms,
                    'bedrooms' => $request->bedrooms,
                    'bathrooms' => $request->bathrooms,
                    'garages' => $request->garages,
                    'garages_size' => $request->garages_size,
                    'floors' => $request->floors,
                    'year_built' => $request->year_built,
                ]);
                if ($request->auto_renew=='on'){
                    $auto_renew=1;
                }else{
                    $auto_renew=0;
                }
                if ($request->never_expired=='on'){
                    $never_expired=1;
                }else{
                    $never_expired=0;
                }
                $price = PropertyPrice::query()->create([
                    'property_id' => $property->id,

                    'price' => $request->price,
                    'currency' => $request->currency,
                    'period' => $request->period,
                    'private_notes' => $request->private_notes,
                    'never_expired' => $never_expired,
                    'auto_renew' => $auto_renew,

                ]);
                $address = PropertyAddress::query()->create([
                    'property_id' => $property->id,
                    'full_address' => $request->full_address ?? '',
                    'governorate_id' => $request->governorate_id,
                    'department_id' => $request->department_id,
                    'village_id' => $request->village_id,
                    'hod_id' => $request->hod_id,
                    'hay_id' => $request->hay_id,
                    'plot_number' => $request->plot_number,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]);
                if (count($request->property_features) != 0) {
                    foreach ($request->property_features as $feature) {
                        PropertyFeature::query()->create([
                            'property_id' => $property->id,
                            'feature_id' => $feature,
                        ]);
                    }
                }
                if (count($request->facilities) != 0) {
                    foreach ($request->facilities as $facility) {
                        PropertyFacility::query()->create([
                            'property_id' => $property->id,
                            'facility_id' => $facility['facility_id'],
                            'distance' => $facility['distance'],
                        ]);
                    }
                }
                if (count($request->images) != 0) {
                    foreach ($request->images as $image) {
                        PropertyImage::query()->create([
                            'property_id' => $property->id,
                            'img' => $image,

                        ]);
                    }
                }
                $data = [
                    'user_id' => Auth::guard('web')->user()->id,
                    'author'=>Auth::guard('web')->user()->name,
                    'url' => url('/admin/property/edit/'.$property->id),
                    'title'=>__('New Aqar by ').$request->name,
                    'timestamp' => now()->toDateTimeString(),
                ];

                // Dispatch the event
                event(new NotificationEvent($data));
                DB::commit();
                return response()->json(['success'=>"The process has successfully"]);
            }catch (\Throwable $e) {
                DB::rollBack();
                throw $e;
            }


        }
    }
    public function store_reviews(Request $request,$property_id){
        $validator = Validator::make($request->all(), [
            'rating' => 'required',
            'comment' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input=$request->all();
            return response(["responseJSON" => $errors,"input"=>$input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        if ($validator->passes()) {
             $data=$request->all();
            DB::beginTransaction();
            try {
                 $property=Property::query()->where('id',$property_id)->first();
                 $property_review=PropertyReviews::query()->create([
                     'user_id'=>Auth::id(),
                     'property_id'=>$property_id,
                     'star'=>$request->rating,
                     'comment'=>$request->comment,
                 ]);
                DB::commit();
                return response()->json(['success'=>"The process has successfully"]);
            }catch (\Throwable $e) {
                DB::rollBack();
                throw $e;
            }


        }
    }
    public function update(Request $request,$id){
        $property = Property::find($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'contact_name' => 'required',
            'contact_phone' => 'required',
//            'video_url' => 'required',
            'content' => 'required',
            'images' => 'required',
            'governorate_id' => 'required',
            'department_id' => 'required',
            'village_id' => 'required',
            'hod_id' => 'required',
            'hay_id' => 'required',
            'plot_number' => 'required',
//            'latitude' => 'required',
//            'longitude' => 'required',
            'price' => 'required',
            'currency' => 'required',
//            'period' => 'required',

            'size' => 'required',
            'land_area' => 'required',
            'rooms' => 'required',
            'bedrooms' => 'required',
            'bathrooms' => 'required',
            'garages' => 'required',
            'garages_size' => 'required',
            'floors' => 'required',
            'year_built' => 'required',
            'property_features' => 'required',


        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input=$request->all();
            return response(["responseJSON" => $errors,"input"=>$input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        $data = $request->all();

        if ($validator->passes()) {

            DB::beginTransaction();
            try {
                $property->update([
                    'title' => $request->name,
                     'description' => $request->description,
                    'slug' => $request->slug,
                    'type' => $request->type,
                    'status' => $request->status,
                    'moderation_status' => $request->moderation_status??1,
                    'category_id' => $request->category_id,
                    'contact_name' => $request->contact_name,
                    'contact_phone' => $request->contact_phone,
                ]);
                $information = PropertyInformation::query()->where('property_id',$id)->first();
                $information->update([

                    'content' => $request['content'],
                    'video_url' => $request->video_url,
                    'size' => $request->size,
                    'land_area' => $request->land_area,
                    'rooms' => $request->rooms,
                    'bedrooms' => $request->bedrooms,
                    'bathrooms' => $request->bathrooms,
                    'garages' => $request->garages,
                    'garages_size' => $request->garages_size,
                    'floors' => $request->floors,
                    'year_built' => $request->year_built,
                ]);
                if ($request->auto_renew=='on'){
                    $auto_renew=1;
                }else{
                    $auto_renew=0;
                }
                if ($request->never_expired=='on'){
                    $never_expired=1;
                }else{
                    $never_expired=0;
                }
                $price = PropertyPrice::query()->where('property_id',$id)->first();
                $price ->update([


                    'price' => $request->price,
                    'currency' => $request->currency,
                    'period' => $request->period,
                    'private_notes' => $request->private_notes,
                    'never_expired' => $never_expired,
                    'auto_renew' => $auto_renew,

                ]);
                $address = PropertyAddress::query()->where('property_id',$id)->first();
                $address->update([
                    'full_address' => $request->full_address ?? '',
                    'governorate_id' => $request->governorate_id,
                    'department_id' => $request->department_id,
                    'village_id' => $request->village_id,
                    'hod_id' => $request->hod_id,
                    'hay_id' => $request->hay_id,
                    'plot_number' => $request->plot_number,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]);
                if (count($request->property_features) != 0) {
                      PropertyFeature::query()->where('property_id',$id)->delete();
                    foreach ($request->property_features as $feature) {
                        PropertyFeature::query()->create([
                            'property_id' => $property->id,
                            'feature_id' => $feature,
                        ]);
                    }
                }
                if (count($request->facilities) != 0) {
                    PropertyFacility::query()->where('property_id',$id)->delete();
                    foreach ($request->facilities as $facility) {
                        PropertyFacility::query()->create([
                            'property_id' => $property->id,
                            'facility_id' => $facility['facility_id'],
                            'distance' => $facility['distance'],
                        ]);
                    }
                }
                if (count($request->images) != 0) {
                    // Delete old images first
                    PropertyImage::query()->where('property_id', $id)->delete();
                    foreach ($request->images as $image) {
                        PropertyImage::query()->create([
                            'property_id' => $property->id,
                            'img' => $image,

                        ]);
                    }
                }
                DB::commit();
                return response()->json(['success'=>"The process has successfully"]);
            }catch (\Throwable $e) {
                DB::rollBack();
                throw $e;
            }
        }
    }
    public function delete($id)
    {
        $category =Property::find($id);
        $category->delete();
        $arr = array('msg' => 'There are some errors, try again', 'status' => false);
        if($category){
            $arr = array('msg' => "operation accomplished successfully", 'status' => true);
        }
        return Response()->json($arr);

    }
    public function sold($id)
    {
        $category =Property::find($id);
        $category->update(['status'=>3]);
        $arr = array('msg' => 'There are some errors, try again', 'status' => false);
        if($category){
            $arr = array('msg' => "operation accomplished successfully", 'status' => true);
        }
        return Response()->json($arr);

    }
}
