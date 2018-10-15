<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Company;
use App\Report;
use App\ReportCategory;
use App\InterestRate;
use App\Post;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $id = $request->id;
        $title = str_replace('-', ' ', $id);
        $ReportCategory = ReportCategory::where('name', '=', $title)->get();
        $category_id = $ReportCategory[0]['id'];
        $category_name = $ReportCategory[0]['name'];
        $company= Company::all('id');
        $company_id = $company[0]['id'];
        $products = Report::where('company_id','=',$company_id)
            ->orderBy('created_at','desc')
            ->where('report_category_id', '=', $category_id)
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->date)->format('Y'); // grouping by years
            });
        $rate = InterestRate::where('company_id', '=', $company_id)->get();
        $type = $request->get('type','BLOG');
        $posts = Post::with('post_category')
            ->where('company_id','=', $company_id)
            ->where('status','=', 1)
            ->where('type','=',$type)
            ->orderBy('created_at','desc');
        $recent_blogs = $posts->offset(0)->limit(3)->get();

        return view('front/report', compact('products', 'category_name', 'rate', 'recent_blogs'));
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
    public function show(Request $request, $id, $detail)
    {
        $title = str_replace('-', ' ', $id);
        $ReportCategory = ReportCategory::where('name', '=', $title)->get();
        $category_id = $ReportCategory[0]['id'];
        $category_name = $detail;
        $company= Company::all('id');
        $company_id = $company[0]['id'];
        $products = Report::where('company_id','=',$company_id)
            ->orderBy('created_at','desc')
            ->where('report_category_id', '=', $category_id)
            ->whereYear('date', '=', $detail)
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->date)->format('m'); // grouping by years
            });
        $rate = InterestRate::where('company_id', '=', $company_id)->get();
        $type = $request->get('type','BLOG');
        $posts = Post::with('post_category')
            ->where('company_id','=', $company_id)
            ->where('status','=', 1)
            ->where('type','=',$type)
            ->orderBy('created_at','desc');
        $recent_blogs = $posts->offset(0)->limit(3)->get();

        return view('front/report', compact('products', 'category_name', 'rate', 'recent_blogs', 'months'));
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
