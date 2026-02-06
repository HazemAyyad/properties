<?php

namespace App\Http\Controllers\Dashboard;

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
use App\Traits\Sluggable;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
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
    public function index($status){

                return view('dashboard.properties.index',compact('status'));

    }
    public function get_properties(Request $request, $status)
    {
        // Get the preferred language or default to 'en'
        $preferredLang = app()->getLocale() ?? 'en';

        return DataTables::of(Property::query()->where(['moderation_status' => $status]))
            ->addIndexColumn()
            ->addColumn('action', function($row) use ($request) {
                $url = route('admin.properties.edit', $row->id);

                $btn = '<a href="'.$url .'" class="ms-2"><i class="fas fa-edit text-success"></i></a>
                    <a href="javascript:void(0)" onclick="deleteItem('.$row->id.')" class=""><i class="fas fa-trash-alt text-danger"></i></a>';

                return $btn;
            })
            ->editColumn('title', function ($row) use ($preferredLang) {
                // Get the translated title
                $title = $row->getTranslation('title', $preferredLang);
                // Use default title if translation is not available
                return '<strong class="Titillium-font danger">' . htmlspecialchars($title ?? $row->title) . '</strong>';
            })
            ->editColumn('views', function ($row) {
                return '<strong class="Titillium-font text-primary">' . htmlspecialchars($row->views) . '</strong>';
            })
            ->editColumn('created_at', function ($row) {
                return '<strong class="Titillium-font danger">' . htmlspecialchars($row->created_at->format('m/d/Y - H:i')) . '</strong>';
            })
            ->editColumn('status', function ($row) {
                $status = $row->status;
                $statusMap = [
                    0 => ['class' => 'text-secondary', 'label' => __('Not available')],
                    1 => ['class' => 'text-primary', 'label' => __('Preparing selling')],
                    2 => ['class' => 'text-success', 'label' => __('Selling')],
                    3 => ['class' => 'text-warning', 'label' => __('Sold')],
                    4 => ['class' => 'text-info', 'label' => __('Renting')],
                    5 => ['class' => 'text-dark', 'label' => __('Rented')],
                    6 => ['class' => 'text-primary', 'label' => __('Building')],
                ];

                $statusClass = $statusMap[$status]['class'] ?? 'text-danger';
                $statusLabel = $statusMap[$status]['label'] ?? __('Unknown');

                return '<strong class="' . $statusClass . '" tabindex="0" data-toggle="tooltip" title="' . $statusLabel . '">' . $statusLabel . '</strong>';
            })
            ->editColumn('moderation_status', function ($row) {
                $moderationStatus = $row->moderation_status;
                $statusMap = [
                    0 => ['class' => 'text-warning', 'label' => __('Pending')],
                    1 => ['class' => 'text-success', 'label' => __('Approved')],
                    2 => ['class' => 'text-danger', 'label' => __('Rejected')],
                ];

                $statusClass = $statusMap[$moderationStatus]['class'] ?? 'text-danger';
                $statusLabel = $statusMap[$moderationStatus]['label'] ?? __('Unknown');

                return '<strong class="' . $statusClass . '" tabindex="0" data-id="' . $row->id . '" data-status="' . $moderationStatus . '" onclick="changeModerationStatus(this)" data-toggle="tooltip" title="' . $statusLabel . '">' . $statusLabel . '</strong>';
            })
            ->rawColumns(['title', 'views', 'status', 'moderation_status', 'created_at', 'action'])
            ->make(true);
    }

    public function updateModerationStatus(Request $request)
    {
        $property = Property::findOrFail($request->id);

        // Validate the status value (0, 1, 2)
        $request->validate([
            'moderation_status' => 'required|in:0,1,2'
        ]);

        // Update the moderation status
        $property->moderation_status = $request->moderation_status;
        $property->save();

        return response()->json(['success' => true]);
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


                return view('dashboard.properties.add',compact('icons','categories',
                    'countries','feature_categories','facilities','uniqueCurrencies'));

    }
    public function edit($id){

               $property=Property::query()->where('id',$id)
                ->with([
                    'images',
                    'features',
                    'facilities',
                    'price', // Include the related price
                    'more_info', // Include the related more_info
                    'address', // Include the related address
                    'user' // Include the related user
                ])->first();
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


        return view('dashboard.properties.edit',compact('icons','categories',
            'countries','feature_categories','facilities','uniqueCurrencies','property'));


    }
    public function store_old(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required',
            'description' => 'required',
            'category_id' => 'required',
//            'video_url' => 'required',
            'content' => 'required',
            'images' => 'required',
            'country_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            'full_address' => 'required',
//            'latitude' => 'required',
//            'longitude' => 'required',
            'price' => 'required',
            'currency' => 'required',


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
                $property = Property::query()->create([
                    'title' => $request->name,
                    'user_id' => $request->user_id,
                    'description' => $request->description,
                    'slug' => $request->slug,
                    'type' => $request->type,
                    'status' => $request->status,
                    'moderation_status' => $request->moderation_status,
                    'category_id' => $request->category_id,
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
                    'full_address' => $request->full_address,
                    'country_id' => $request->country_id,
                    'state_id' => $request->state_id,
                    'city_id' => $request->city_id,
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
                DB::commit();
                return response()->json(['success'=>"The process has successfully"]);
            }catch (\Throwable $e) {
                DB::rollBack();
                throw $e;
            }


        }
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'content' => 'required',
            'images' => 'required',
            'country_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            'full_address' => 'required',
            'price' => 'required',
            'currency' => 'required',
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
            return response([
                "responseJSON" => $validator->errors(),
                "input" => $request->all(),
                "message" => 'Verify that the data is correct, fill in all fields'
            ], 422);
        }

        if ($validator->passes()) {
            DB::beginTransaction();
            try {
                // Create the property with translations for title, description, and slug
                $property = Property::create([
                    'user_id' => $request->user_id,
                    'type' => $request->type,
                    'status' => $request->status,
                    'moderation_status' => $request->moderation_status,
                    'category_id' => $request->category_id,
                ]);

                // Store translations for 'title', 'description', and 'slug'
                $property->setTranslations('title', [
                    'en' => $request->input('name.en'),
                    'ar' => $request->input('name.ar'),
                ]);

                $property->setTranslations('description', [
                    'en' => $request->input('description.en'),
                    'ar' => $request->input('description.ar'),
                ]);
                $property->setTranslations('slug', [
                    'en' => $request->input('slug.en'),
                    'ar' => $request->input('slug.ar'),
                ]);
                $property->save();

                // Property Information
                $information = PropertyInformation::create([
                    'property_id' => $property->id,
                    'content' => [
                        'en' => $request->input('content.en'),
                        'ar' => $request->input('content.ar'),
                     ],
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

                // Property Price
                $price = PropertyPrice::create([
                    'property_id' => $property->id,
                    'price' => $request->price,
                    'currency' => $request->currency,
                    'period' => $request->period,
                    'private_notes' => $request->private_notes,
                    'never_expired' => $request->never_expired == 'on' ? 1 : 0,
                    'auto_renew' => $request->auto_renew == 'on' ? 1 : 0,
                ]);

                // Property Address with translations for 'full_address'
                $address = PropertyAddress::create([
                    'property_id' => $property->id,
                    'full_address' => [
                        'en' => $request->full_address_en,
                        'ar' => $request->full_address_ar,
                    ],
                    'country_id' => $request->country_id,
                    'state_id' => $request->state_id,
                    'city_id' => $request->city_id,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]);

                // Property Features
                if (count($request->property_features) != 0) {
                    foreach ($request->property_features as $feature) {
                        PropertyFeature::create([
                            'property_id' => $property->id,
                            'feature_id' => $feature,
                        ]);
                    }
                }

                // Property Facilities
                if (count($request->facilities) != 0) {
                    foreach ($request->facilities as $facility) {
                        PropertyFacility::create([
                            'property_id' => $property->id,
                            'facility_id' => $facility['facility_id'],
                            'distance' => $facility['distance'],
                        ]);
                    }
                }

                // Property Images
                if (count($request->images) != 0) {
                    foreach ($request->images as $image) {
                        PropertyImage::create([
                            'property_id' => $property->id,
                            'img' => $image,
                        ]);
                    }
                }

                DB::commit();
                return response()->json(['success' => "The process has successfully"]);
            } catch (\Throwable $e) {
                DB::rollBack();
                throw $e;
            }
        }
    }

    public function update(Request $request, $id)
    {
        $property = Property::find($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'content' => 'required',
            'images' => 'required',
            'country_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            'full_address' => 'required',
            'price' => 'required',
            'currency' => 'required',
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
            return response([
                "responseJSON" => $validator->errors(),
                "input" => $request->all(),
                "message" => 'Verify that the data is correct, fill in all fields'
            ], 422);
        }

        DB::beginTransaction();
        try {
//            return $request->all();
            // Update the property with translations for title, description, and slug
            $property->update([
                'user_id' => $request->user_id,
                'type' => $request->type,
                'status' => $request->status,
                'moderation_status' => $request->moderation_status,
                'category_id' => $request->category_id,

            ]);


            // Update translations for 'title', 'description', and 'slug'
            $property->setTranslations('title', [
                'en' => $request->input('name.en'),
                'ar' => $request->input('name.ar'),
            ]);

            $property->setTranslations('description', [
                'en' => $request->input('description.en'),
                'ar' => $request->input('description.ar'),
            ]);
            $property->setTranslations('slug', [
                'en' => $request->input('slug.en'),
                'ar' => $request->input('slug.ar'),
            ]);
             $property->save();

            // Property Information
            $information = PropertyInformation::where('property_id', $id)->first();
            $information->update([
                'content' => [

                    'en' => $request->input('content.en'),
                    'ar' => $request->input('content.ar'),
                ],
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

            // Property Price
            $price = PropertyPrice::where('property_id', $id)->first();
            $price->update([
                'price' => $request->price,
                'currency' => $request->currency,
                'period' => $request->period,
                'private_notes' => $request->private_notes,
                'never_expired' => $request->never_expired == 'on' ? 1 : 0,
                'auto_renew' => $request->auto_renew == 'on' ? 1 : 0,
            ]);

            // Property Address with translations for 'full_address'
            $address = PropertyAddress::where('property_id', $id)->first();
            $address->update([
                'full_address' => [
                    'en' => $request->input('full_address.en'),
                    'ar' => $request->input('full_address.ar'),

                ],
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
                'city_id' => $request->city_id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);

            // Property Features
            if (count($request->property_features) != 0) {
                PropertyFeature::where('property_id', $id)->delete();
                foreach ($request->property_features as $feature) {
                    PropertyFeature::create([
                        'property_id' => $property->id,
                        'feature_id' => $feature,
                    ]);
                }
            }

            // Property Facilities
            if (count($request->facilities) != 0) {
                PropertyFacility::where('property_id', $id)->delete();
                foreach ($request->facilities as $facility) {
                    PropertyFacility::create([
                        'property_id' => $property->id,
                        'facility_id' => $facility['facility_id'],
                        'distance' => $facility['distance'],
                    ]);
                }
            }

            // Property Images
            if (count($request->images) != 0) {
                PropertyImage::where('property_id', $id)->delete();
                foreach ($request->images as $image) {
                    PropertyImage::create([
                        'property_id' => $property->id,
                        'img' => $image,
                    ]);
                }
            }

            DB::commit();
            return response()->json(['success' => "The process has successfully"]);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
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
}
