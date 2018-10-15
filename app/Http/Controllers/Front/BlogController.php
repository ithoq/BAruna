<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Post;
use App\Company;
use App\PostCategory;
use App\PostTags;
use App\Tags;
use App\InterestRate;

class BlogController extends Controller
{

	public function __construct()
	{
		parent::__construct();
	}

    public function index(Request $request){
    	$company= Company::all('id');
    	$company_id = $company[0]['id'];
    	$type = $request->get('type','BLOG');
    	$posts = Post::with('post_category')
    		->where('company_id','=', $company_id)
    		->where('status','=', 1)
    		->where('type','=',$type)
    		->orderBy('created_at','desc');
    	$blogs = $posts->paginate(4);
    	$recent_blogs = $posts->offset(0)->limit(3)->get();
    	$post_category = PostCategory::with('parent')
    		->where('company_id', '=', $company_id)
    		->get()
    	;
    	$tags = Tags::where('company_id','=',$company_id)->get();
        $rate = InterestRate::where('company_id', '=', $company_id)->get();

    	return view('front/blog', compact('blogs', 'post_category', 'recent_blogs', 'tags', 'rate'));
    }

    public function show(Request $request){
    	$id = $request->id;
    	$title = str_replace('-', ' ', $id);
    	$company= Company::all('id');
    	$company_id = $company[0]['id'];
    	$type = $request->get('type','BLOG');
    	$posts = Post::with('post_category')
    		->where('company_id','=', $company_id)
    		->where('status','=', 1)
    		->where('type','=',$type)
    		->orderBy('created_at','desc');
    	$blogs = $posts->get();
    	$relateds = $posts->where('post_category_id', '=', $blogs[0]['post_category_id'])->offset(0)->limit(8)->get();
        $recent_blogs = $posts->offset(0)->limit(3)->get();
    	$post = $posts->where('slug', '=', $id)->get();
    	$post_category = PostCategory::with('parent')
    		->where('company_id', '=', $company_id)
    		->get()
    	;
        $post_tags = PostTags::with('tags')->where('company_id','=',$company_id)
                        ->where('post_id','=', $post[0]['id'])
                        ->get();
    	$tags_id = PostTags::with('tags')->where('company_id','=',$company_id)->get();
        $tags = Tags::where('company_id','=',$company_id)->get();
        $rate = InterestRate::where('company_id', '=', $company_id)->get();

    	if (count($blogs) > 0) {
    		return view('front/post', compact('relateds', 'post', 'post_category', 'recent_blogs', 'tags', 'post_tags', 'tags_id', 'rate'));
    	}else{
    		return abort(404);
    	}
    }

    public function tag(Request $request){
        $id = $request->id;
        $company= Company::all('id');
        $company_id = $company[0]['id'];
        $type = $request->get('type','BLOG');
        $tags = Tags::where('company_id','=',$company_id)
                    ->where('slug', '=', $id)
                    ->get();
        $value = 1;
        $post_tags = PostTags::with('tags', 'post')
                        ->whereHas('post', function($q) use($value) {
                               // Query the name field in status table
                               $q->where('status', '=', $value); // '=' is optional
                        })
                        ->where('company_id','=',$company_id)
                        ->where('tags_id','=', $tags[0]['id'])
                        ->orderBy('created_at','desc');
        $posts = Post::with('post_category')
            ->where('company_id','=', $company_id)
            ->where('status','=', 1)
            ->where('type','=',$type)
            ->orderBy('created_at','desc');
        $recent_blogs = $posts->offset(0)->limit(3)->get();
        $post_category = PostCategory::with('parent')
            ->where('company_id', '=', $company_id)
            ->get();
        $blogs = $post_tags->paginate(5);
        $tags_name = Tags::where('company_id','=',$company_id)
                    ->where('slug', '=', $id)
                    ->get();
        $rate = InterestRate::where('company_id', '=', $company_id)->get();            

        if (count($post_tags) > 0) {
            return view('front/blogtag', compact('blogs', 'recent_blogs', 'post_category', 'tags', 'tags_name', 'rate'));
        }else{
            return abort(404);
        }
    }
}
