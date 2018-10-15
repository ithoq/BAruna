<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Company;
use App\InterestRate;
use App\Post;

class AboutController extends Controller
{
    public function index(Request $request){
    	$company= Company::all('id');
        $company_id = $company[0]['id'];
    	$rate = InterestRate::where('company_id', '=', $company_id)->get();
        $type = $request->get('type','BLOG');
        $posts = Post::with('post_category')
            ->where('company_id','=', $company_id)
            ->where('status','=', 1)
            ->where('type','=',$type)
            ->orderBy('created_at','desc');
        $recent_blogs = $posts->offset(0)->limit(3)->get();
    	return view('front/about', compact('rate', 'recent_blogs'));
    }
    
    public function profile(Request $request){
      $id = $request->id;
    	$title = str_replace('-', '_', $id);
      $company= Company::all('id');
        $company_id = $company[0]['id'];
    	$rate = InterestRate::where('company_id', '=', $company_id)->get();
        $type = $request->get('type','BLOG');
        $posts = Post::with('post_category')
            ->where('company_id','=', $company_id)
            ->where('status','=', 1)
            ->where('type','=',$type)
            ->orderBy('created_at','desc');
        $recent_blogs = $posts->offset(0)->limit(3)->get();
    	return view('front/profile/'.$title, compact('rate', 'recent_blogs'));
    }
}
