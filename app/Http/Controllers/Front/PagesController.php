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

class PagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $company= Company::all('id');
        $company_id = $company[0]['id'];
        $type = $request->get('type','PAGE');
        $posts = Post::with('post_category')
            ->where('company_id','=', $company_id)
            ->where('status','=', 1)
            ->where('type','=',$type)
            ->orderBy('created_at','desc');
        $blogs = $posts->paginate(5);
        $recent_blogs = $posts->offset(0)->limit(3)->get();
        $post_category = PostCategory::with('parent')
            ->where('company_id', '=', $company_id)
            ->get()
        ;
        $tags = Tags::where('company_id','=',$company_id)->get();
        $rate = InterestRate::where('company_id', '=', $company_id)->get();

        return view('front/blog', compact('blogs', 'post_category', 'recent_blogs', 'tags', 'rate'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $id = $request->id;
        $title = str_replace('-', ' ', $id);
        $company= Company::all('id');
        $company_id = $company[0]['id'];
        $type = $request->get('type','PAGE');
        $posts = Post::with('post_category')
            ->where('company_id','=', $company_id)
            ->where('status','=', 1)
            ->where('type','=',$type)
            ->orderBy('created_at','desc');
        $blogs = $posts->get();
        $relateds = $posts->where('post_category_id', '=', $blogs[0]['post_category_id'])->offset(0)->limit(5)->get();
        $recent_blogs = $posts->offset(0)->limit(3)->get();
        $post = $posts->where('name', '=', $title)->get();
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
            return view('front/page', compact('relateds', 'post', 'post_category', 'recent_blogs', 'tags', 'post_tags', 'tags_id', 'rate'));
        }else{
            return abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
