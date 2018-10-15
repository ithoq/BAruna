<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Company;
use App\Product;
use App\Category;
use App\ReportCategory;
use App\Setting;
use App\Gallery;
use App\Images;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $company= Company::all('id');
        $company_id = $company[0]['id'];
    	if (isset($company_id)) {
            $company_data = Company::all();

            $categorys = Category::with('parent')->where('company_id','=',$company_id)->get();

            $products = Product::with('category')
                ->where('company_id','=',$company_id)
                ->orderBy('created_at','desc')
                ->get();

            $reports = ReportCategory::where('company_id','=',$company_id)->get();

            $social_media = Setting::where('name', '=', 'social_media')
                            ->where('company_id', '=', $company_id)
                            ->get();
            $gallery = Gallery::with('gallery_category')->where('company_id','=',$company_id)->get();

            $images = array('breadcumb-bpr-aruna.jpg', 'breadcumb-bpr-aruna-1.jpg', 'breadcumb-bpr-aruna-2.jpg', 'breadcumb-bpraruna-6.jpg', 'breadcumb-bpraruna-7.jpg', 'breadcumb-bpraruna-8.jpg');

            $random_image = array_rand($images);

            view()->share('categorys', $categorys);
            view()->share('products_menu', $products);
            view()->share('company_data', $company_data);
            view()->share('ReportCategory', $reports);
            view()->share('social_media', $social_media);
            view()->share('gallery', $gallery);
            view()->share('images', $images);
            view()->share('random_image', $random_image);
        }
    }



    
}
