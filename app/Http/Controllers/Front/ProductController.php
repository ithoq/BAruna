<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Company;
use App\Product;
use App\Category;
use App\Gallery;
use App\InterestRate;
use App\Post;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $company= Company::all('id');
        $company_id = $company[0]['id'];
        $products = Product::with('category', 'product_gallery')
            ->where('company_id','=',$company_id)
            ->orderBy('created_at','desc')
            ->paginate(9);
        // dd($products);
        // return response()->json($products);
        $rate = InterestRate::where('company_id', '=', $company_id)->get();
        $type = $request->get('type','BLOG');
        $posts = Post::with('post_category')
            ->where('company_id','=', $company_id)
            ->where('status','=', 1)
            ->where('type','=',$type)
            ->orderBy('created_at','desc');
        $recent_blogs = $posts->offset(0)->limit(3)->get();

        if (count($products) > 0) {
            return view('front/products', compact('products', 'rate', 'recent_blogs'));
        }
    }

    public function category(Request $request)
    {
        $company= Company::all('id');
        $company_id = $company[0]['id'];
        $value = $request->id;
        $products = Product::with('category', 'product_gallery')
            ->where('company_id','=',$company_id)
            ->whereHas('category', function($q) use($value) {
                   $q->where('name', '=', $value);
            })
            ->orderBy('created_at','desc')
            ->paginate(9);
        // return response()->json($products);
        $rate = InterestRate::where('company_id', '=', $company_id)->get();
        $type = $request->get('type','BLOG');
        $posts = Post::with('post_category')
            ->where('company_id','=', $company_id)
            ->where('status','=', 1)
            ->where('type','=',$type)
            ->orderBy('created_at','desc');
        $recent_blogs = $posts->offset(0)->limit(3)->get();
        $category = Category::with('parent')
            ->where('company_id','=',$company_id)
            ->where('name', '=', $value)
            ->get();

        if (count($products) > 0) {
            return view('front/products', compact('products', 'rate', 'recent_blogs', 'value', 'category'));
        }
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
        $products = Product::with('category', 'product_gallery')
            ->where('company_id','=',$company_id)
            ->orderBy('created_at','desc')
            ->where('slug', '=', $id)
            ->get();
        $product = Product::with('category', 'product_gallery')
            ->where('company_id','=',$company_id)
            // ->where('category_id', '=', $products[0]['category_id'])
            ->orderBy('created_at','desc')
            ->where('slug', '!=', $id)
            ->get();

        $value = 'product';
        $gallery = Gallery::with('gallery_category')
                    ->whereHas('gallery_category', function($q) use($value) {
                           // Query the name field in status table
                           $q->where('name', '=', $value); // '=' is optional
                    })
                    ->where('company_id','=',$company_id)
                    ->get();

        // return response()->json($products);
        $rate = InterestRate::where('company_id', '=', $company_id)->get();
        $type = $request->get('type','BLOG');
        $posts = Post::with('post_category')
            ->where('company_id','=', $company_id)
            ->where('status','=', 1)
            ->where('type','=',$type)
            ->orderBy('created_at','desc');
        $recent_blogs = $posts->offset(0)->limit(3)->get();

        if (count($products) > 0) {
            return view('front/product', compact('products', 'product', 'gallery', 'rate', 'recent_blogs'));
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
