<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;

use App\Mail\GeneralEmail;
use App\Models\City;
use App\Models\Dashboard\Agent;
use App\Models\Dashboard\Benefit;
use App\Models\Dashboard\Blog;
use App\Models\Category;
use App\Models\Dashboard\Faqs;
use App\Models\Dashboard\Feature;
use App\Models\Dashboard\FeatureCategory;
use App\Models\Dashboard\Partner;
use App\Models\Dashboard\PeopleSay;
use App\Models\Dashboard\Plan;
use App\Models\Dashboard\Policy;
use App\Models\FaqCategory;
use App\Models\PolicyCategory;
use App\Models\Property;
use App\Models\Dashboard\Service;
use App\Models\Dashboard\Setting;
use App\Models\Slider;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

class SiteController extends Controller
{

    public function main_page()
    {
       $categories = Category::query()
            ->where('status', 1)->whereHas('properties')
            ->with(['properties' => function ($query) {
                $query->where('moderation_status', 1)
                    ->take(6)
                    ->with([
                        'images' => function ($query) {
                            $query->take(1); // Limit to the first image
                        },
                        'price', // Include the related price
                        'more_info', // Include the related more_info
                        'address', // Include the related address
                        'user' // Include the related user
                    ]);
            }])
            ->get();
        $top_properties=Property::query()->where(['moderation_status'=>1,'status_top'=>1])
            ->with([
                'images' => function ($query) {
                    $query->take(1); // Limit to the first image
                },
                'price', // Include the related price
                'more_info', // Include the related more_info
                'address', // Include the related address
                'user' // Include the related user
            ])->take(4)->get();
        $features = Feature::all();
        $agents = Agent::all();
        $people_says = PeopleSay::all();
        $benefits = Benefit::query()->take(3)->get();
        $services = Service::query()->take(3)->oldest()->get();
        $cities = Property::query()
            ->where(['moderation_status' => 1])
            ->with([

               // Include the related more_info
                'address', // Include the related address

            ])
            ->get()
            ->pluck('address.city')
            ->unique(); // Get unique cities

// Convert to array if needed
         $locations = $cities->toArray();
         $partners=Partner::all();
          $blogs=Blog::query()->with(['user','category'])->take(3)->get();
        $sliders= Slider::where('page', 'slider')->get();
        return view('site.index',compact('categories','top_properties','features','agents',
        'people_says','benefits','services','locations','partners','blogs','sliders'));
    }
    public function privacy_policy()
    {

        $categories = PolicyCategory::all();
          $policies = Policy::all();
        return view('site.privacy-policy.index',compact('policies','categories'));
    }


    public function properties(Request $request)
    {
        $categories = Category::where('status', 1)->get();

        // Retrieve filters from the request
        $perPage = $request->input('per_page', 10);
        $sortField = $request->input('sort_field', 'created_at'); // New: 'price' or 'created_at'
        $sortDirection = $request->input('sort_by', 'desc'); // New: 'asc' or 'desc'
        $keyword = $request->input('keyword');
        $location = $request->input('location');
        $categoryId = $request->input('category_id');
        $rooms = $request->input('rooms');
        $bathrooms = $request->input('bathrooms');
        $bedrooms = $request->input('bedrooms');
        $minPrice = $request->input('min-value', 0);
        $maxPrice = $request->input('max-value', 1000000);
        $minSize = $request->input('min-value2', 0);
        $maxSize = $request->input('max-value2', 10000);
        $features = $request->input('features', []);
        $selectedTab = $request->input('tab', [1, 0]);

        $propertiesQuery = Property::query()
            ->where('moderation_status', 1)
            ->with([
                'images' => fn($q) => $q->take(1),
                'price',
                'more_info',
                'address',
                'user',
                'features'
            ]);

        // Filters
        if ($keyword) {
            $propertiesQuery->where('title', 'like', "%{$keyword}%");
        }

        if ($selectedTab) {
            is_array($selectedTab)
                ? $propertiesQuery->whereIn('type', $selectedTab)
                : $propertiesQuery->where('type', $selectedTab);
        }

        if ($categoryId) {
            $propertiesQuery->where('category_id', $categoryId);
        }

        if ($rooms) {
            $propertiesQuery->whereHas('more_info', fn($q) => $q->where('rooms', $rooms));
        }

        if ($bathrooms) {
            $propertiesQuery->whereHas('more_info', fn($q) => $q->where('bathrooms', $bathrooms));
        }

        if ($bedrooms) {
            $propertiesQuery->whereHas('more_info', fn($q) => $q->where('bedrooms', $bedrooms));
        }

        if (!is_null($minPrice) && !is_null($maxPrice)) {
            $propertiesQuery->whereHas('price', function ($q) use ($minPrice, $maxPrice) {
                $q->whereBetween('price', [(float) $minPrice, (float) $maxPrice]);
            });
        }

        if ($minSize || $maxSize) {
            $propertiesQuery->whereHas('more_info', function ($q) use ($minSize, $maxSize) {
                $q->whereBetween('size', [$minSize, $maxSize]);
            });
        }

        if (!empty($features)) {
            $propertiesQuery->whereHas('features', fn($q) => $q->whereIn('id', $features));
        }

        if ($location) {
            $propertiesQuery->whereHas('address', function ($q) use ($location) {
                $q->where('full_address', 'like', "%{$location}%")
                    ->orWhereHas('country', fn($q) => $q->where('name', 'like', "%{$location}%"))
                    ->orWhereHas('state', fn($q) => $q->where('name', 'like', "%{$location}%"))
                    ->orWhereHas('city', fn($q) => $q->where('name', 'like', "%{$location}%"));
            });
        }

        // Sorting
        if ($sortField === 'price') {
            // Sort by related table: property_prices.price
            $propertiesQuery->leftJoin('property_prices', 'properties.id', '=', 'property_prices.property_id')
                ->orderBy('property_prices.price', $sortDirection)
                ->select('properties.*'); // To avoid ambiguous column error
        } else {
            $propertiesQuery->orderBy($sortField, $sortDirection);
        }

        $properties = $propertiesQuery->paginate($perPage);

        $latestProperties = Property::query()
            ->where('moderation_status', 1)
            ->with([
                'images' => fn($q) => $q->take(1),
                'price',
                'more_info',
                'address',
                'user'
            ])
            ->latest()
            ->take(5)
            ->get();

        $features = Feature::all();

        return view('site.properties.index', compact('properties', 'latestProperties', 'categories', 'features'));
    }
    public function properties_city(Request $request,$slug_city)
    {

        $categories = Category::where('status', 1)->get();

//        return$request;
        // Retrieve filters from the request
        $perPage = $request->input('per_page', 10); // Default to 10 items per page
        $sortBy = $request->input('sort_by', 'desc'); // Default to 'new' (Newest)
        $keyword = $request->input('keyword');
        $location = $request->input('location');
        $categoryId = $request->input('category_id'); // This is the category_id
        $rooms = $request->input('rooms');
        $bathrooms = $request->input('bathrooms');
        $bedrooms = $request->input('bedrooms');
        $minPrice = $request->input('min-value', 0);
        $maxPrice = $request->input('max-value', 1000000);
        $minSize = $request->input('min-value2', 0);
        $maxSize = $request->input('max-value2', 10000);
        $features = $request->input('features', []);
        $selectedTab = $request->input('tab', [1,0]); // Default to 'rent' if no tab is selected

        $propertiesQuery = Property::query()
            ->where(['moderation_status'=>1])
            ->with([
                'images' => function ($query) {
                    $query->take(1); // Limit to the first image
                },
                'price', // Include the related price
                'more_info', // Include the related more_info
                'address', // Include the related address
                'user', // Include the related user
                'features' // Include the related features
            ])
            ->whereHas('address.city', function ($query) use ($slug_city) {
                $query->where('slug', $slug_city);
            });

        if ($keyword) {
            $propertiesQuery->where('title', 'like', "%{$keyword}%");
        }
        if ($selectedTab) {
            if (is_array($selectedTab)) {
                // Use whereIn if $selectedTab is an array
                $propertiesQuery->whereIn('type', $selectedTab);
            } else {
                // Use where if $selectedTab is a single value
                $propertiesQuery->where('type', $selectedTab);
            }
        }
        if ($categoryId) {
            $propertiesQuery->where('category_id', $categoryId);
        }
        if ($sortBy) {
            $propertiesQuery->orderBy('created_at', $sortBy);
        }

        if ($rooms) {
            $propertiesQuery->whereHas('more_info', function ($query) use ($rooms) {
                $query->where('rooms', $rooms);
            });
        }

        if ($bathrooms) {
            $propertiesQuery->whereHas('more_info', function ($query) use ($bathrooms) {
                $query->where('bathrooms', $bathrooms);
            });
        }

        if ($bedrooms) {
            $propertiesQuery->whereHas('more_info', function ($query) use ($bedrooms) {
                $query->where('bedrooms', $bedrooms);
            });
        }

        if (!is_null($minPrice) && !is_null($maxPrice)) {
            $propertiesQuery->whereHas('price', function ($query) use ($minPrice, $maxPrice) {
                // Ensure both minPrice and maxPrice are valid
                if (is_numeric($minPrice) && is_numeric($maxPrice)) {
                    $query->whereBetween('price', [(float) $minPrice, (float) $maxPrice]);
                }
            });
        }

        if ($minSize || $maxSize) {
            $propertiesQuery->whereHas('more_info', function ($query) use ($minSize, $maxSize) {
                $query->whereBetween('size', [$minSize, $maxSize]);
            });
        }

        if ($features) {
            $propertiesQuery->whereHas('features', function ($query) use ($features) {
                $query->whereIn('id', $features);
            });
        }
        if ($location) {
            $propertiesQuery->whereHas('address', function ($query) use ($location) {
                $query->where('full_address', 'like', "%{$location}%")
                    ->orWhereHas('country', function ($countryQuery) use ($location) {
                        $countryQuery->where('name', 'like', "%{$location}%");
                    })
                    ->orWhereHas('state', function ($stateQuery) use ($location) {
                        $stateQuery->where('name', 'like', "%{$location}%");
                    })
                    ->orWhereHas('city', function ($cityQuery) use ($location) {
                        $cityQuery->where('name', 'like', "%{$location}%");
                    });
            });
        }
        // If tab is "sale", add specific logic here if needed


        $properties = $propertiesQuery->paginate($perPage);

        $latestProperties = Property::query()
            ->with([
                'images' => function ($query) {
                    $query->take(1); // Limit to the first image
                },
                'price', // Include the related price
                'more_info', // Include the related more_info
                'address', // Include the related address
                'user' // Include the related user
            ])
            ->latest()
            ->take(5)
            ->get();

        $features = Feature::all();

        return view('site.properties.index', compact('properties', 'latestProperties', 'categories', 'features'));
    }
    public function show_properties($slug)
    {

        $categories = Category::query()->where('status', 1)->get();




        $property = Property::query()
            ->where(function ($query) use ($slug) {
                $query->whereJsonContains('slug->en', $slug) // Check for English slug
                ->orWhereJsonContains('slug->ar', $slug); // Check for Arabic slug
            })
            ->with([
            'images' => function ($query) {
                $query->take(5); // Limit to the first image
            },
            'price', // Include the related price
            'more_info', // Include the related more_info
            'address', // Include the related address
            'reviews.user', // Include the related address
            'facilities.facility', // Include the related facilities
             'features.feature.featureCategory', // Load the feature category through the feature
            'user' // Include the related user
        ])->first();
        $sessionKey = 'property_' . $slug . '_viewed';

        if (!session()->has($sessionKey)) {
            $property->increment('views');
            session()->put($sessionKey, true);
        }
        $latestProperties = Property::query()
            ->where(['moderation_status'=>1])
            ->with([
                'images' => function ($query) {
                    $query->take(1); // Limit to the first image
                },
                'price', // Include the related price
                'more_info', // Include the related more_info
                'address', // Include the related address
                'user' // Include the related user
            ])
            ->latest()
            ->take(5)
            ->get();
        $categoriesWithFeatures = $property->features->groupBy(function ($feature) {
            return $feature->feature->featureCategory->name;
        })->map(function ($features) {
            return $features->map(function ($feature) {
                return [
                    'id' => $feature->feature->id,
                    'name' => $feature->feature->name,
                    'category_id' => $feature->feature->feature_category_id,
                    'icon' => $feature->feature->icon,
                    'created_at' => $feature->feature->created_at,
                    'updated_at' => $feature->feature->updated_at,
                    'deleted_at' => $feature->feature->deleted_at,

                ];
            });
        });

//        return $categoriesWithFeatures;
        $all_features = Feature::all();

        return view('site.properties.show', compact('property', 'latestProperties',
            'categories', 'all_features','categoriesWithFeatures'));
    }

    public function send_email_to_seller(Request $request,$seller_id=null)
    {
         $name=$request->name;
        $phone=$request->phone;
        $email=$request->email;
        $subject=$request->subject;
        $user=User::query()->where(['id'=>$seller_id])->first();
        if ($user){
            Mail::to($user->email)->send(new GeneralEmail($name,$phone,$email,$subject));
        }else{
            $email_seller= Setting::query()->where('key','email')->first()->value;
            Mail::to($email_seller)->send(new GeneralEmail($name,$phone,$email,$subject));
        }


        return response()->json(['success'=>"The process has successfully"]);


    }


    public function services(Request $request)
    {

        $services=Service::all();
        $people_says = PeopleSay::all();
        $faqs = Faqs::all();
        return view('site.services.index', compact('services','people_says','faqs'));
    }
    public function faqs(Request $request)
    {
        $categories=FaqCategory::all();


         $faqs = Faqs::all();
        return view('site.faqs.index', compact('faqs','categories'));
    }
    public function about_us(Request $request)
    {

        $features = Feature::all();
        $agents = Agent::all();
        $people_says = PeopleSay::all();
        $benefits = Benefit::all();
        $services = Service::all();
        $partners=Partner::all();
//        $description=Setting::query()->where(['page'=>'about_us','key'=>'description'])->first();
       $aboutUs=Setting::query()->where('page','about_us')->get();
        return view('site.about_us.index', compact('services','features','agents','people_says'
        ,'benefits','partners','aboutUs'));
    }
    public function contact_us(Request $request)
    {

        $features = Feature::all();
        $agents = Agent::all();
        $people_says = PeopleSay::all();
        $benefits = Benefit::all();
        $services = Service::all();
        $partners=Partner::all();
//        $description=Setting::query()->where(['page'=>'about_us','key'=>'description'])->first();
       $aboutUs=Setting::query()->where('page','about_us')->get();
        return view('site.contact_us.index', compact('services','features','agents','people_says'
        ,'benefits','partners','aboutUs'));
    }
    public function pricing_plans(Request $request)
    {

        $faqs = Faqs::all();
        $plans = Plan::query()->where('status',1)->with('features')->get();

        return view('site.pricing_plans.index', compact('faqs','plans'));
    }

    public function send_email_to_site(Request $request)
    {
        $name=$request->name;
        $phone=$request->phone;
        $email=$request->email;
        $subject=$request->subject;
        $text=$request->message;

            Mail::to('hazem1fadil@gmail.com')->send(new GeneralEmail($name,$phone,$email,$subject,$text));



        return response()->json(['success'=>"The process has successfully"]);


    }
    public function blogs(Request $request)
    {
        $blogs=Blog::query()->with(['user','category'])->paginate(10);

        return view('site.blogs.index', compact('blogs'));
    }
    public function show_blogs($slug)
    {

        $blog= Blog::where('slug', $slug)->first();
        $previous = Blog::where('id', '<', $blog->id)->orderBy('id', 'desc')->first();
        $next = Blog::where('id', '>', $blog->id)->orderBy('id', 'asc')->first();
        $blogs=Blog::query()->with(['user','category'])->take(3)->get();

        $shareButtons = \Share::page(
            route('site.blog.show',$slug),
            'Your share text comes here',
        )
            ->facebook()
            ->twitter()
            ->linkedin()

             ;


        return view('site.blogs.show', compact('blog','blogs','shareButtons'
        ,'next','previous'));
    }
}
