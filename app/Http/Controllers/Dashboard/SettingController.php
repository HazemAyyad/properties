<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Dashboard\Setting;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class SettingController extends Controller
{
    public function index()
    {
        $keys = [
            'whatsapp', 'youtube', 'twitter', 'facebook', 'instagram', 'linkedin',
            'slogan', 'contact_us', 'main_logo', 'secondary_logo',
            'site_name', 'favicon', 'address', 'phone', 'email',
        ];
        $setting = [];
        $rows = Setting::query()->whereIn('key', $keys)->get()->keyBy('key');

        foreach ($keys as $key) {
            $row = $rows->get($key);
            if ($row) {
                $setting[$key] = $row->value ?? '';
                if (in_array($key, ['slogan', 'contact_us', 'address', 'site_name'])) {
                    $setting[$key . '_ar'] = $row->value_ar ?? '';
                }
            } else {
                $setting[$key] = '';
                if (in_array($key, ['slogan', 'contact_us', 'address', 'site_name'])) {
                    $setting[$key . '_ar'] = '';
                }
            }
        }

        $settings = Setting::all();

        return view('dashboard.settings.index', compact('setting', 'settings'));

    }
    public function page($page_name)
    {
        $all_pages = Setting::pluck('page')->toArray();

        if (in_array($page_name, $all_pages)) {
            // Retrieve settings for the specific page
               $settings = Setting::where('page', $page_name)->get();

            // Pass the settings to the view
            return view('dashboard.settings.' . $page_name, compact('settings'));
        } else {
            abort(404);
        }
    }
    public function page_slider()
    {

            // Retrieve settings for the specific page
               $settings = Slider::where('page', 'slider')->get();

            // Pass the settings to the view
            return view('dashboard.settings.slider', compact('settings'));

    }
    public function update_about_us(Request $request, $page_name)
    {
        // Fetch the settings based on the page ID or other identifier
        $settings = Setting::where('page', $page_name)->get();

        // Loop through each setting and update it
        foreach ($settings as $setting) {
            switch ($setting->key) {
                case 'description':
                    $setting->value = $request->input('description_en');
                    $setting->value_ar = $request->input('description_ar');
                    break;
                case 'img-video':
                    if ($request->hasFile('img-video')) {

                        $image_url = $request->file('img-video');
                        $image_name ='/public/uploads/img-videos/' . time() . '.' . $image_url->getClientOriginalExtension();
                        $image_url->move(env('PATH_FILE_URL').'/uploads/img-videos/', $image_name);

                        $setting->value = $image_name; // Save the path in the database
                    }

                    break;
                case 'video':
                    $setting->value = $request->input('video');
                    break;
                case 'why_choose_us':
                    $setting->value = $request->input('why_choose_us');
                    $setting->value_ar = $request->input('why_choose_us_ar');
                    break;
            }

            $setting->save(); // Save the updated setting
        }

        return response()->json(['success' => __('Settings updated successfully!')]);
    }
    public function update_slider(Request $request)
    {
        $settings = Slider::where('page', 'slider')->get();

        foreach ($settings as $setting) {
            // Handle text fields (description, slider_text_1, slider_text_2, slider_text_3)
            if ($request->has($setting->key) && !str_contains($setting->key, 'slider_img')) {
                $translations = [];

                // Iterate over the locales (ar, en) for text translations
                foreach ($request->input($setting->key) as $locale => $value) {
                    if (is_string($locale)) {
                        $translations[$locale] = $value;  // Store each locale value
                    }
                }

                // Update translations for the text fields
                $setting->setTranslations('value', $translations);
                $setting->save();
            }

            // Handle image uploads (slider_img)
            if ($setting->key == 'slider_img') {
                // Ensure that the request has a file for each locale
                if ($request->hasFile('slider_img')) {
                    foreach ($request->file('slider_img') as $locale => $file) {
                        if ($file && $file->isValid()) {
                            // Store the image with the locale-specific name
                            $image_url = $file;
                            $image_name = '/uploads/sliders/' . time() . '_' . $locale . '.' . $image_url->getClientOriginalExtension();
                            $image_url->move(env('PATH_FILE_URL') . '/uploads/sliders/', $image_name);

                            // Retrieve the current translations for slider_img
                            $currentTranslations = $setting->getTranslations('value');
                            $currentTranslations[$locale] = $image_name;  // Save image path for the respective locale

                            // Update translations for the slider image
                            $setting->setTranslations('value', $currentTranslations);
                            $setting->save();
                        }
                    }
                }
            }
        }

        // Return success response
        return response()->json(['success' => __('Slider updated successfully')]);
    }


    public function update_settings(Request $request, $page_name)
    {
         // Fetch the settings based on the page identifier (e.g., page name)
        $settings = Setting::where('page', $page_name)->get();

        // Loop through each setting and update it based on the key
        foreach ($settings as $setting) {
            switch ($setting->key) {
                case 'description':
                    $setting->value = $request->input('description_en');
                    $setting->value_ar = $request->input('description_ar');
                    break;

                case 'img-video':
                    if ($request->hasFile('img-video')) {
                        $image_url = $request->file('img-video');
                        $image_name = '/uploads/img-videos/' . time() . '.' . $image_url->getClientOriginalExtension();
                        $image_url->move(public_path('uploads/img-videos'), $image_name);
                        $setting->value = $image_name; // Save the file path in the database
                    }
                    break;

                case 'video':
                    $setting->value = $request->input('video');
                    break;

                case 'why_choose_us':
                    $setting->value = $request->input('why_choose_us');
                    $setting->value_ar = $request->input('why_choose_us_ar');
                    break;

                case 'whatsapp':
                    $setting->value = $request->input('whatsapp');
                    break;

                case 'youtube':
                    $setting->value = $request->input('youtube');
                    break;

                case 'twitter':
                    $setting->value = $request->input('twitter');
                    break;

                case 'facebook':
                    $setting->value = $request->input('facebook');
                    break;

                case 'instagram':
                    $setting->value = $request->input('instagram');
                    break;

                case 'linkedin':
                    $setting->value = $request->input('linkedin');
                    break;

                case 'slogan':
                    $setting->value = $request->input('slogan');
                    $setting->value_ar = $request->input('slogan_ar');
                    break;

                case 'contact_us':
                    $setting->value = $request->input('contact_us');
                    $setting->value_ar = $request->input('contact_us_ar');
                    break;

                case 'address':
                    $setting->value = $request->input('address');
                    $setting->value_ar = $request->input('address_ar');
                    break;

                case 'main_logo':
                    if ($request->hasFile('main_logo')) {
                        $image_url = $request->file('main_logo');
                        $image_name = '/uploads/logos/' . Uuid::uuid4() . '.' . $image_url->getClientOriginalExtension();
                        $image_url->move(public_path('uploads/logos'), $image_name);
                        $setting->value = $image_name; // Save the file path in the database
                    }
                    break;

                case 'secondary_logo':
                    if ($request->hasFile('secondary_logo')) {
                        $image_url = $request->file('secondary_logo');
                        $image_name = '/uploads/logos/' . Uuid::uuid4() . '.' . $image_url->getClientOriginalExtension();
                        $image_url->move(public_path('uploads/logos'), $image_name);
                        $setting->value = $image_name; // Save the file path in the database
                    }
                    break;

                case 'featured_listing_price':
                    $setting->value = $request->input('featured_listing_price', 50);
                    break;

                case 'featured_3d_tour_price':
                    $setting->value = $request->input('featured_3d_tour_price', 30);
                    break;

                // Handle sections visibility checkboxes
                case 'gallary_properties':
                case 'cities':
                case 'services':
                case 'statistics':
                case 'benefits':
                case '4_top':
                case 'people_says':
                case 'agents':
                case 'blogs':
                case 'partners':
                case 'vision_goals':
                    $setting->value = $request->input($setting->key, 0); // Set to 0 if unchecked
                    break;
            }

            $setting->save(); // Save the updated setting
        }

        return response()->json(['success' => __('Settings updated successfully!')]);
    }


    public function update(Request $request)
    {
        $arKeys = ['slogan_ar', 'contact_us_ar', 'address_ar', 'site_name_ar'];

        foreach ($request->all() as $name => $value) {
            if ($name === '_token') {
                continue;
            }
            // Arabic values: update value_ar on the main key row (e.g. slogan_ar -> row key "slogan")
            if (in_array($name, $arKeys)) {
                $mainKey = str_replace('_ar', '', $name);
                $setting = Setting::query()->where('key', $mainKey)->first();
                if ($setting) {
                    $setting->value_ar = $value;
                    $setting->save();
                } else {
                    Setting::query()->updateOrInsert(
                        ['key' => $mainKey, 'page' => 'settings'],
                        ['value' => '', 'value_ar' => $value, 'name' => $mainKey, 'name_ar' => $mainKey, 'created_at' => now(), 'updated_at' => now()]
                    );
                }
                continue;
            }
            // Check if the input is a file and handle file uploads
            if ($request->hasFile($name)) {
                $file = $request->file($name);
                $file_name = '/uploads/settings/' . Uuid::uuid4() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/settings/'), $file_name);

                Setting::query()->updateOrInsert(
                    ['key' => $name, 'page' => 'settings'],
                    ['value' => $file_name, 'value_ar' => null, 'name' => $name, 'name_ar' => $name, 'created_at' => now(), 'updated_at' => now()]
                );
            } else {
                $setting = Setting::query()->where('key', $name)->first();
                if ($setting) {
                    $setting->value = $value;
                    $setting->updated_at = now();
                    $setting->save();
                } else {
                    \DB::table('settings')->insert([
                        'key' => $name,
                        'value' => $value,
                        'value_ar' => null,
                        'name' => $name,
                        'name_ar' => $name,
                        'page' => 'settings',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        return response()->json(['success' => "The process has been successfully completed."]);
    }

    public function page_update(Request $request){
        // return $request;
        $name_input=[];
        foreach($request->all() as $name => $value)
        {
            if (str_contains($name, '_ar')) {
                $name = str_replace('_ar','',$name);
               $setting= Setting::query()->where('key', $name)->first();

               $setting->update([
                            'value_ar' => $value,
                        ]);
            }else{

           $setting= Setting::query()->where('key', $name)->first();
            $setting->update([
                            'value' => $value,
                        ]);
            }

        }

        return response()->json(['success'=>"The process has successfully"]);

    }
}
