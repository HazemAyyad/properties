<?php


namespace App\Http\View\Composers;



 use App\Models\Dashboard\Setting;
 use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\View\View;


class MainData
{
    public function compose(View $view){

        $settings_all= Setting::query()->whereIn('key',
            [

                'email',
                'phone',
                'whatsapp',
                'currency',
                'min_currency',
                'max_currency',
                'unit_size',
                'min_size',
                'max_size',
                'youtube',
                'twitter',
                'facebook',
                'instagram',
                'slogan',
                'slogan_ar',
                'contact_us',
                'contact_us_ar',
                'linkedin',
                'main_logo',
                'secondary_logo',
                'favicon',
                'site_name',
                'address',
                'gallary_properties',
                'cities',
                'services',
                'statistics',
                'benefits',
                '4_top',
                'people_says',
                'agents',
                'blogs',
                'partners',
                'vision_goals',
                'featured_listing_price',
                'featured_3d_tour_price',

            ])->get();
        $data_settings=[];
        foreach ($settings_all as $item){
            $data_settings[$item->key]=$item->value;
            if (!empty($item->value_ar)) {
                $data_settings[$item->key.'_ar']=$item->value_ar;
            }
        }
            $view->with('lang', ['ar','en'] );
            $view->with('data_settings', $data_settings);

    if (Auth::guard('admin')->check()) {
        $admin = Auth::guard('admin')->user();
        if ($admin) {
            $view->with('notifications', $admin->notifications ?? collect());
            $view->with('notifications_all', $admin->unreadNotifications ? $admin->unreadNotifications->count() : 0);
        } else {
            $view->with('notifications', collect());
            $view->with('notifications_all', 0);
        }
    }


    }
}
