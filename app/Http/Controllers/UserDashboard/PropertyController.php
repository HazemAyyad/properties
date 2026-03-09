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
        $property = Property::with(['address', 'more_info'])->findOrFail($id);
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

            'size' => 'nullable',
            'land_area' => 'nullable',
            'rooms' => 'nullable',
            'bedrooms' => 'nullable',
            'bathrooms' => 'nullable',
            'garages' => 'nullable',
            'garages_size' => 'nullable',
            'floors' => 'nullable',
            'year_built' => 'nullable',
            'property_features' => 'nullable',
            'building_age' => 'nullable',
            'floor' => 'nullable',
            'furnished' => 'nullable',
            'size_max' => 'nullable',
            'land_area_min' => 'nullable',
            'land_area_max' => 'nullable',
            'zoning' => 'nullable',
            'land_type' => 'nullable',
            'services' => 'nullable',
            'price_min' => 'nullable',
            'price_max' => 'nullable',
            'extra_features' => 'nullable',
            'featured_listing' => 'nullable',
            'featured_listing_receipt' => 'required_if:featured_listing,1|nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
            'featured_3d_tour' => 'nullable',
            'featured_3d_tour_receipt' => 'required_if:featured_3d_tour,1|nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',

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
                $featuredListingReceipt = null;
                $featuredListingUntil = null;
                $isFeatured = 0;
                if ($request->filled('featured_listing') && $request->hasFile('featured_listing_receipt')) {
                    $receiptFile = $request->file('featured_listing_receipt');
                    $receiptName = time() . '_featured.' . $receiptFile->getClientOriginalExtension();
                    $uploadPath = public_path('uploads/featured_listing_receipts');
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }
                    $receiptFile->move($uploadPath, $receiptName);
                    $featuredListingReceipt = '/public/uploads/featured_listing_receipts/' . $receiptName;
                    $featuredListingUntil = null;
                    $isFeatured = 1;
                }
                $featured3dTourReceipt = null;
                $featured3dTourUntil = null;
                $is3dTourFeatured = 0;
                if ($request->filled('featured_3d_tour') && $request->hasFile('featured_3d_tour_receipt')) {
                    $receiptFile = $request->file('featured_3d_tour_receipt');
                    $receiptName = time() . '_3dtour.' . $receiptFile->getClientOriginalExtension();
                    $uploadPath = public_path('uploads/featured_3d_tour_receipts');
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }
                    $receiptFile->move($uploadPath, $receiptName);
                    $featured3dTourReceipt = '/public/uploads/featured_3d_tour_receipts/' . $receiptName;
                    $featured3dTourUntil = null;
                    $is3dTourFeatured = 1;
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
                    'registration_document' => $registration_document ?? null,
                    'is_featured' => $isFeatured,
                    'featured_listing_receipt' => $featuredListingReceipt,
                    'featured_listing_until' => $featuredListingUntil,
                    'is_3d_tour_featured' => $is3dTourFeatured,
                    'featured_3d_tour_receipt' => $featured3dTourReceipt,
                    'featured_3d_tour_until' => $featured3dTourUntil,
                ]);
                $information = PropertyInformation::query()->create([
                    'property_id' => $property->id,
                    'content' => $request['content'],
                    'video_url' => $request->video_url,
                    'size' => $request->size ?? 0,
                    'land_area' => $request->land_area ?? $request->land_area_min ?? $request->land_area_max ?? 0,
                    'rooms' => $request->rooms ?? 0,
                    'bedrooms' => $request->bedrooms ?? 0,
                    'bathrooms' => $request->bathrooms ?? 0,
                    'garages' => $request->garages ?? 0,
                    'garages_size' => $request->garages_size ?? 0,
                    'floors' => $request->floors ?? 0,
                    'year_built' => $request->year_built ?? '',
                    'building_age' => $request->building_age,
                    'floor' => $request->floor,
                    'furnished' => $request->furnished,
                    'size_max' => $request->size_max,
                    'land_area_min' => $request->land_area_min,
                    'land_area_max' => $request->land_area_max,
                    'zoning' => $request->zoning,
                    'land_type' => $request->land_type,
                    'services' => $request->services,
                    'price_min' => $request->price_min,
                    'price_max' => $request->price_max,
                    'extra_features' => $request->has('extra_features') ? $request->extra_features : null,
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
                if (!empty($request->property_features) && is_array($request->property_features)) {
                    foreach ($request->property_features as $feature) {
                        PropertyFeature::query()->create([
                            'property_id' => $property->id,
                            'feature_id' => $feature,
                        ]);
                    }
                }
                if (!empty($request->facilities) && is_array($request->facilities)) {
                    foreach ($request->facilities as $facility) {
                        // Skip if facility_id is empty or null
                        if (empty($facility['facility_id']) || $facility['facility_id'] == '') {
                            continue;
                        }
                        PropertyFacility::query()->create([
                            'property_id' => $property->id,
                            'facility_id' => $facility['facility_id'],
                            'distance' => $facility['distance'] ?? null,
                        ]);
                    }
                }
                if (!empty($request->images) && is_array($request->images)) {
                    foreach ($request->images as $image) {
                        // Skip empty images
                        if (empty($image) || trim($image) === '') {
                            continue;
                        }
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
        $property = Property::with('address')->find($id);
        // عند التعديل: إذا name أو slug فاضيين استخدم القيمة الحالية (تجنب فشل التحقق بسبب الترجمة أو الـ form)
        if ($property) {
            $currentTitle = is_string($property->title) ? $property->title : ($property->getTranslation('title', app()->getLocale()) ?: $property->getTranslation('title', 'en'));
            $currentSlug = is_string($property->slug) ? $property->slug : ($property->getTranslation('slug', app()->getLocale()) ?: $property->getTranslation('slug', 'en'));
            if (empty(trim((string) $request->input('name')))) {
                $request->merge(['name' => $currentTitle ?: '']);
            }
            if (empty(trim((string) $request->input('slug')))) {
                $request->merge(['slug' => $currentSlug ?: '']);
            }
            // إذا حقول العنوان فاضية أو غير صالحة (مثلاً "undefined" من الجافا) استخدم قيم العنوان الحالية
            $addr = $property->address;
            $isEmpty = function ($v) {
                if ($v === null || $v === '') return true;
                $s = trim((string) $v);
                return $s === '' || $s === 'undefined' || strtolower($s) === 'null';
            };
            if ($addr) {
                if ($isEmpty($request->input('governorate_id')) && $addr->governorate_id !== null) {
                    $request->merge(['governorate_id' => $addr->governorate_id]);
                }
                if ($isEmpty($request->input('department_id')) && $addr->department_id !== null) {
                    $request->merge(['department_id' => $addr->department_id]);
                }
                if ($isEmpty($request->input('village_id')) && $addr->village_id !== null) {
                    $request->merge(['village_id' => $addr->village_id]);
                }
                if ($isEmpty($request->input('hod_id')) && $addr->hod_id !== null) {
                    $request->merge(['hod_id' => $addr->hod_id]);
                }
                if ($isEmpty($request->input('hay_id')) && $addr->hay_id !== null) {
                    $request->merge(['hay_id' => $addr->hay_id]);
                }
                if ($isEmpty($request->input('plot_number'))) {
                    $request->merge(['plot_number' => (string) ($addr->plot_number ?? '')]);
                }
            }
        }
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
            'hod_id' => 'nullable',
            'hay_id' => 'nullable',
            'plot_number' => 'required',
//            'latitude' => 'required',
//            'longitude' => 'required',
            'price' => 'required',
            'currency' => 'required',
//            'period' => 'required',

            'size' => 'nullable',
            'land_area' => 'nullable',
            'rooms' => 'nullable',
            'bedrooms' => 'nullable',
            'bathrooms' => 'nullable',
            'garages' => 'nullable',
            'garages_size' => 'nullable',
            'floors' => 'nullable',
            'year_built' => 'nullable',
            'property_features' => 'nullable',
            'featured_listing' => 'nullable',
            'featured_listing_receipt' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
            'featured_3d_tour' => 'nullable',
            'featured_3d_tour_receipt' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input=$request->all();
            return response(["responseJSON" => $errors,"input"=>$input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        $data = $request->all();

        if ($validator->passes()) {
            // عند التعديل: إذا hod_id أو hay_id فارغان استخدم قيم العنوان الحالية
            $hodId = $request->input('hod_id');
            $hayId = $request->input('hay_id');
            if ($property && $property->address) {
                if ($hodId === '' || $hodId === null) $hodId = $property->address->hod_id;
                if ($hayId === '' || $hayId === null) $hayId = $property->address->hay_id;
            }
            $hodId = ($hodId === '' || $hodId === null) ? null : $hodId;
            $hayId = ($hayId === '' || $hayId === null) ? null : $hayId;

            DB::beginTransaction();
            try {
                $updateData = [
                    'title' => $request->name,
                    'description' => $request->description,
                    'slug' => $request->slug,
                    'type' => $request->type,
                    'status' => $request->status,
                    'moderation_status' => $request->moderation_status ?? $property->moderation_status,
                    'category_id' => $request->category_id,
                    'contact_name' => $request->contact_name,
                    'contact_phone' => $request->contact_phone,
                ];
                $hasReceipt = !empty($property->featured_listing_receipt);
                $hasUntil = !empty($property->featured_listing_until);
                $isPendingFeatured = $hasReceipt && !$hasUntil && $property->is_featured;
                if ($isPendingFeatured) {
                    $updateData['is_featured'] = 1;
                } elseif ($request->filled('featured_listing')) {
                    $updateData['is_featured'] = 1;
                    if ($request->hasFile('featured_listing_receipt')) {
                        $receiptFile = $request->file('featured_listing_receipt');
                        $receiptName = time() . '_featured.' . $receiptFile->getClientOriginalExtension();
                        $uploadPath = public_path('uploads/featured_listing_receipts');
                        if (!is_dir($uploadPath)) {
                            mkdir($uploadPath, 0755, true);
                        }
                        $receiptFile->move($uploadPath, $receiptName);
                        $updateData['featured_listing_receipt'] = '/public/uploads/featured_listing_receipts/' . $receiptName;
                        $updateData['featured_listing_until'] = null;
                    }
                } else {
                    $updateData['is_featured'] = 0;
                    $updateData['featured_listing_receipt'] = null;
                    $updateData['featured_listing_until'] = null;
                }
                $has3dReceipt = !empty($property->featured_3d_tour_receipt);
                $has3dUntil = !empty($property->featured_3d_tour_until);
                $isPending3dTour = $has3dReceipt && !$has3dUntil && $property->is_3d_tour_featured;
                $isActive3dTour = $has3dReceipt && $has3dUntil && \Carbon\Carbon::parse($property->featured_3d_tour_until)->isFuture();
                if ($isPending3dTour || $isActive3dTour) {
                    $updateData['is_3d_tour_featured'] = 1;
                } elseif ($request->filled('featured_3d_tour')) {
                    $updateData['is_3d_tour_featured'] = 1;
                    if ($request->hasFile('featured_3d_tour_receipt')) {
                        $receiptFile = $request->file('featured_3d_tour_receipt');
                        $receiptName = time() . '_3dtour.' . $receiptFile->getClientOriginalExtension();
                        $uploadPath = public_path('uploads/featured_3d_tour_receipts');
                        if (!is_dir($uploadPath)) {
                            mkdir($uploadPath, 0755, true);
                        }
                        $receiptFile->move($uploadPath, $receiptName);
                        $updateData['featured_3d_tour_receipt'] = '/public/uploads/featured_3d_tour_receipts/' . $receiptName;
                        $updateData['featured_3d_tour_until'] = null;
                    }
                } else {
                    $updateData['is_3d_tour_featured'] = 0;
                    $updateData['featured_3d_tour_receipt'] = null;
                    $updateData['featured_3d_tour_until'] = null;
                }
                $property->update($updateData);
                $information = PropertyInformation::query()->where('property_id',$id)->first();
                $information->update([
                    'content' => $request['content'],
                    'video_url' => $request->video_url,
                    'size' => $request->size ?? $information->size,
                    'land_area' => $request->land_area ?? $request->land_area_min ?? $information->land_area,
                    'rooms' => $request->rooms ?? $information->rooms,
                    'bedrooms' => $request->bedrooms ?? $information->bedrooms,
                    'bathrooms' => $request->bathrooms ?? $information->bathrooms,
                    'garages' => $request->garages ?? $information->garages,
                    'garages_size' => $request->garages_size ?? $information->garages_size,
                    'floors' => $request->floors ?? $information->floors,
                    'year_built' => $request->year_built ?? $information->year_built,
                    'building_age' => $request->building_age,
                    'floor' => $request->floor,
                    'furnished' => $request->furnished,
                    'size_max' => $request->size_max,
                    'land_area_min' => $request->land_area_min,
                    'land_area_max' => $request->land_area_max,
                    'zoning' => $request->zoning,
                    'land_type' => $request->land_type,
                    'services' => $request->services,
                    'price_min' => $request->price_min,
                    'price_max' => $request->price_max,
                    'extra_features' => $request->has('extra_features') ? $request->extra_features : $information->extra_features,
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
                    'hod_id' => $hodId,
                    'hay_id' => $hayId,
                    'plot_number' => $request->plot_number,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]);
                if (!empty($request->property_features) && is_array($request->property_features)) {
                    PropertyFeature::query()->where('property_id',$id)->delete();
                    foreach ($request->property_features as $feature) {
                        PropertyFeature::query()->create([
                            'property_id' => $property->id,
                            'feature_id' => $feature,
                        ]);
                    }
                }
                if (!empty($request->facilities) && is_array($request->facilities)) {
                    PropertyFacility::query()->where('property_id',$id)->delete();
                    foreach ($request->facilities as $facility) {
                        // Skip if facility_id is empty or null
                        if (empty($facility['facility_id']) || $facility['facility_id'] == '') {
                            continue;
                        }
                        PropertyFacility::query()->create([
                            'property_id' => $property->id,
                            'facility_id' => $facility['facility_id'],
                            'distance' => $facility['distance'] ?? null,
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
