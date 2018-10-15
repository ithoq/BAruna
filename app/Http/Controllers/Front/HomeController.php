<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Post;
use App\Company;
use App\PostCategory;
use App\Tags;
use App\Slider;
use App\Images;

class HomeController extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}
    public function index(Request $request){
    	$company= Company::all();
    	$company_id = $company[0]['id'];
    	$type = $request->get('type','BLOG');
    	$posts = Post::with('post_category')
    		->where('company_id','=', $company_id)
    		->where('status','=', 1)
    		->where('type','=',$type)
    		->orderBy('created_at','desc');
    	$recent_blogs = $posts->offset(0)->limit(4)->get();
    	$post_category = PostCategory::with('parent')
    		->where('company_id', '=', $company_id)
    		->get()
    	;
    	$tags = Tags::where('company_id','=',$company_id)->get();
        $Slider = Slider::where('company_id','=',$company_id)->get();
    	return view('front/index', compact('post_category', 'recent_blogs', 'tags', 'Slider', 'company'));
    }
}
