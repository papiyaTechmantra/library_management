<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
// use App\Models\Product;
// use App\Models\Permission;
// use App\Models\SocialMedia;
// use App\Models\PageContent;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        view::composer('*', function($view) {
            $ip = $_SERVER['REMOTE_ADDR'];
            // cart count
            $permissionsTableExists = Schema::hasTable('permissions');
            // if ($permissionsTableExists) {
            //    $admin_id = Auth::check() ? Auth::user()->id : "";
            //     $RolePass = Permission::where('admin_id', $admin_id)->get()->pluck('value')->toArray();
            // }
            $settingsTableExists = Schema::hasTable('settings');
            if ($settingsTableExists) {
                $settings = Setting::get();
            }
            // Page Content
            // $page_contentTableExists = Schema::hasTable('page_content');
            // if ($page_contentTableExists) {
            //     $pageContent = PageContent::where('custom_field',1)->latest()->limit(10)->get();
            // }
            // // Social Media
            // $SocialMediaTableExists = Schema::hasTable('social_media');
            // if ($SocialMediaTableExists) {
            //     $SocialMedia = SocialMedia::get();
            // }
            // $ProductTableExists = Schema::hasTable('products');
            // if ($ProductTableExists) {
            //     $Products = Product::with('categoryDetails')  
            //     ->latest()                               
            //     ->where('status', 1)                     
            //     ->get()
            //     ->groupBy('categoryDetails.title');
            // }
            // view()->share('products_category', $Products);
            // view()->share('social_media', $SocialMedia);
            // view()->share('RolePass', $RolePass);
            view()->share('settings', $settings);
            // view()->share('pageContent', $pageContent);
        });
    }
}
