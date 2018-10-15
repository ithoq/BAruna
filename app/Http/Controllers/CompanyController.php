<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Uuid;
use Log;
use DB;
use Hash;
use Validator;
use GuzzleHttp\Client;
use JWTAuth;
use Image;
use DateTime;
use Crypt;
use Storage;
use CpanelWhm;
use App\Category;
use App\Facilities;
use App\GalleryCategory;
use App\Gallery;
use App\Setting;
use App\Slider;
use App\Tags;
use App\PostCategory;
use App\Post;
use App\PostTags;
use App\Product;
use App\ProductFacilities;
use App\ProductTags;
use App\ProductCategory;
use App\ProductGallery;
use App\Theme;
use AppHelper;



class CompanyController extends Controller
{

    public static $rules = array(
    'code'=>'required|unique:company',
    'name'=>'required',
    'business_type'=>'required',
    'email'=>'required|email',
    'username' => 'required',
    'password'=>'required',
    );

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
             if ($request->get('criteria_key')=='name'){
                $size = $request->get('size', 10);
                $criteria_key = $request->get('criteria_key');
                $criteria_value = $request->get('criteria_value');
                $sql="select * from company where type='SINGLE' order by name asc";

                   return  response()->json(DB::select($sql), 200, []) ;

                  // return  response()->json(Company::select('name','cpanel_username')->where($criteria_key,"like","%".$criteria_value."%")
                  //               ->paginate($size) , 200, [], JSON_NUMERIC_CHECK) ;
            }

            if ($request->get('all')==true){
                    return response()->json(Company::where('type','=','SINGLE')->get(), 200, []) ;
            }

            if ($request->get('company_id')){
                $company=Company::find($request->get('company_id'));
                    return response()->json($company, 200, []) ;
            }

           // return response()->json(Company::find(JWTAuth::parseToken()->toUser()->company_id), 200, [], JSON_NUMERIC_CHECK) ;

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function group_company(Request $request)
    {
      
          return response()->json(Company::where('type','=','GROUP')->get(), 200, []) ;

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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $company=Company::find($id);
        return response()->json($company, 200, []);
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

    public function test(){
        $data="eyJpdiI6IkZMeVV1YzU1U2pXU1F5YUN4dEtKR3c9PSIsInZhbHVlIjoibU12ZzNGOUdEeXRkcVg0YUI2OUlOUT09IiwibWFjIjoiNzRkZmJmY2E3YWExNTFlOGQ3MzA0NDAwZmZkYTdkMjdmNGIxYzJkMmIzZGI4YjFkMzEyOWJhN2I4ZjVkM2NiNSJ9";
        echo Crypt::decrypt($data) ;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_by_superadmin(Request $request, $id)
    {


        $obj=Company::find($id);
        $obj->code=$request->json('code');
        $obj->name=$request->json('name');
        $obj->address=$request->json('address');
        $obj->phone=$request->json('phone');
        $obj->tlp=$request->json('tlp');
        $obj->email=$request->json('email');
        $obj->base_url=$request->json('base_url');
        $obj->youtube_url=$request->json('youtube_url');
        $obj->description=$request->json('description');
        $obj->meta_title=$request->json('meta_title');
        $obj->meta_keyword=$request->json('meta_keyword');
        $obj->meta_description=$request->json('meta_description');
        $obj->business_type=$request->json('business_type');
        $obj->theme_id=$request->json('theme_id');
        $obj->cpanel_username=$request->json('cpanel_username');
        $obj->cpanel_subdomain=$request->json('cpanel_subdomain');
        $obj->cpanel_password=$request->json('cpanel_password');

        $obj->google_captcha_site_key=$request->json('google_captcha_site_key');
        $obj->google_captcha_secret_key=$request->json('google_captcha_secret_key');



        if ($request->json('active')==true){
            $obj->active=1;
        }else{
            $obj->active=0;
        }
        $obj->save();

        return response()->json(array(
        'error' => false,
        'message' => 'Company  updated'),
        200
        );
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


        $obj=Company::find($id);
        $obj->name=$request->json('name');
        $obj->address=$request->json('address');
        $obj->phone=$request->json('phone');
        $obj->tlp=$request->json('tlp');
        $obj->email=$request->json('email');
        $obj->base_url=$request->json('base_url');
        $obj->youtube_url=$request->json('youtube_url');
        $obj->description=$request->json('description');
        $obj->meta_title=$request->json('meta_title');
        $obj->meta_keyword=$request->json('meta_keyword');
        $obj->meta_description=$request->json('meta_description');

        if ($request->json('active')==true){
            $obj->active=1;
        }else{
            $obj->active=0;
        }
        $obj->save();

        return response()->json(array(
        'error' => false,
        'message' => 'Company  updated'),
        200
        );
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function change_theme(Request $request, $id)
    {


        $obj=Company::find($id);

        if($request->json('theme_id')!="")
        {
            $obj->theme_id=$request->json('theme_id');
        }
        $theme=Theme::find($obj->theme_id);
        $setting  = json_decode($theme->setting);
        $theme_setting=json_encode($setting->style_config);
        $obj->theme_setting=$theme_setting;
        $obj->save();

        //cek apakah diberikan cpanel_username?
        //jika ada cpanel_username
        if($obj->cpanel_username!="")
        {
            $cpanel_username = $obj->cpanel_username;

            //clear Directory untuk menghindari space full karena backup teplate
            $rmdir = CpanelWhm::execute_action('2',
                'Fileman', 'fileop', $cpanel_username,
                    array(
                    'op'                => 'unlink',
                    'sourcefiles'       => '/home/'.$cpanel_username.'/public_html/public/theme/default',
                )
            );
            //create Directory untuk simpan core
            $mkdir = CpanelWhm::execute_action('2',
                'Fileman', 'mkdir', $cpanel_username,
                    array(
                    "path"          =>       '/home/'.$cpanel_username.'/public_html/public/theme',
                    "name"          =>       'default',
                    "permissions"   =>       "0755"
                  )
            );
            //end directory clear

            $data = array(
              'target'        => '/home/'.$cpanel_username.'/public_html/public/theme/default',
              'source'        => '/usr/local/cpanel/etc/site_templates/airybook/website-cloud-frontend/production/themes/'.$theme->code,
              'sitename'      => $obj->meta_title,
              'description'   => $obj->meta_description,
              'COMPANY'       => $obj->code,
              'TEMPLATENAME'  => 'default',
              'API_URL'       => env('API_URL'),
              'domain_url'    => $obj->base_url,
            );
        }
        //jika tidak ada cpanel_username maka menggunakan subdomain demo dari airybook
        else
        {

              $cpanel_username        = "airybook";
              $obj->cpanel_subdomain  = $obj->code;
              $obj->base_url          = "http://".$obj->cpanel_subdomain.".airybook.com/";

              //clear Directory untuk menghindari space full karena backup teplate
              $rmdir = CpanelWhm::execute_action('2',
                  'Fileman', 'fileop', $cpanel_username,
                      array(
                      'op'                => 'unlink',
                      'sourcefiles'       => '/home/'.$cpanel_username.'/public_html/'.$obj->cpanel_subdomain.'/public/theme/default',
                  )
              );
              //create Directory untuk simpan core
              $mkdir = CpanelWhm::execute_action('2',
                  'Fileman', 'mkdir', $cpanel_username,
                      array(
                      "path"          =>       '/home/'.$cpanel_username.'/public_html/'.$obj->cpanel_subdomain.'/public/theme',
                      "name"          =>       'default',
                      "permissions"   =>       "0755"
                    )
              );
              //end Directory clear

              $data = array(
                'target'        => '/home/'.$cpanel_username.'/public_html/'.$obj->cpanel_subdomain.'/public/theme/default',
                'source'        => '/usr/local/cpanel/etc/site_templates/airybook/website-cloud-frontend/production/themes/'.$theme->code,
                'sitename'      => $obj->meta_title,
                'description'   => $obj->meta_description,
                'COMPANY'       => $obj->code,
                'TEMPLATENAME'  => 'default',
                'API_URL'       => env('API_URL'),
                'domain_url'    => $obj->base_url,
              );

        }

        //call cpanel API
        $requestAPI = CpanelWhm::execute_action('3', 'SiteTemplates', 'publish', $cpanel_username,$data);

        //check apakah sukses
        if($requestAPI && $requestAPI!= null){
            $result = json_decode($requestAPI);
            //dd($result);
            if(!isset($result->error)){

                return response()->json(array(
                'error' => false,
                'message' => 'Theme  updated'),
                200
                );

            }
        }

        return response()->json(array(
        'error' => true,
        'message' => 'Theme updat failed : '.$result->error),
        200
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function install_frontend(Request $request, $id)
    {

        /* logic
        check apakah ada company
        check cpanel_username dan cpanel_password jika cpanel_username tidak kosong
        jika cpanel_username kosong maka subdomain adalah company code
        jika cpanel_username
          - wipe root data
          - create new directory
          - copy .htaccess
        jika subdomain
          - wipe subdomain data
          - create new directory
          - copy .htaccess
        run API call to cPanel
        */


        $obj=Company::find($id);
        //$obj->theme_id="8173a970-c19a-11e6-8d84-876aa7a34176";//$request->json('theme_id');
        $theme=Theme::find($obj->theme_id);
        $setting  = json_decode($theme->setting);
        $theme_setting=json_encode($setting->style_config);
        $obj->theme_setting=$theme_setting;

        //cek apakah diberikan cpanel_username?
        //jika ada cpanel_username
        if($obj->cpanel_username!="")
        {

            $cpanel_username = $obj->cpanel_username;


            // check apakah eksisting airybook dir atau baru
            $search_mdir = CpanelWhm::execute_action('3',
              'Fileman', 'list_files', $cpanel_username,
                      array(
                      'dir'                               => '/home/'.$cpanel_username.'/public_html/public/theme',
                      'types'                             => 'dir',
                      'limit_to_list'                     => '0',
                      'show_hidden'                       => '1',
                      'check_for_leaf_directories'        => '1',
                      'include_mime'                      => '1',
                      'include_hash'                      => '0',
                      'include_permissions'               => '0',
                 )
            );

            $search_mdir = json_decode($search_mdir);

            //jika terdapat direktory airybook maka hapus root direktory untuk menghemat space
            if($search_mdir->result->status==1)
            {
              //clear Directory untuk menghindari space full karena backup teplate
              $rmdir = CpanelWhm::execute_action('2',
                  'Fileman', 'fileop', $cpanel_username,
                      array(
                      'op'                => 'unlink',
                      'sourcefiles'       => '/home/'.$cpanel_username.'/public_html/application,/home/'.$cpanel_username.'/public_html/public',
                  )
              );
              //end Directory clear
            }
            else {
              //do nothing let's cpanel backup the root directory
            }

            //data untuk sitepubliser
            $data = array(
              'target'        => '/home/'.$cpanel_username.'/public_html/',
              'source'        => '/usr/local/cpanel/etc/site_templates/airybook/website-cloud-frontend/production/core',
              'sitename'      => $obj->meta_title,
              'description'   => $obj->meta_description,
              'COMPANY'       => $obj->code,
              'TEMPLATENAME'  => 'default',
              'API_URL'       => env('API_URL'),
              'domain_url'    => $obj->base_url,
            );
        }

        //jika tidak ada cpanel_username maka menggunakan subdomain demo dari airybook
        else
        {
            $cpanel_username = "airybook";
            $obj->cpanel_subdomain = $obj->code;
            $obj->base_url   = "http://".$obj->cpanel_subdomain.".airybook.com/";

            // Create a subdomain.
            $rmdir = CpanelWhm::execute_action('2',
                'SubDomain', 'addsubdomain', $cpanel_username,
                    array(
                    'domain'                => $obj->cpanel_subdomain,
                    'rootdomain'            => 'airybook.com',
                    'dir'                   => '/public_html/'.$obj->cpanel_subdomain,
                    'disallowdot'           => '1',
                )
            );

            $rmdir = json_decode($rmdir);

            //adding subdmian to cloudflare
            //add if subdomain doesn't exist
            if(!isset($rmdir->cpanelresult->error)){

                $url = 'https://api.cloudflare.com/client/v4/zones/'.env('ZONE_ID').'/dns_records';

                $data = [
                  "type" => 'A',
                  "name" => $obj->cpanel_subdomain,
                  "content" => env('SERVER_IP'),
                  "ttl" => '1',
                  "proxied" => true
                ];

                $jsonData = json_encode($data);
                $headers = [
                  'X-Auth-Email' => env('CF_EMAIL'),
                  'X-Auth-Key' => env('CF_AUTH'),
                  'Content-Type' => 'application/json'
                ];

                $client = new Client();
                $res = $client->post($url, ['headers'=>$headers,'body' => $jsonData]);
           }

            //clear Directory untuk menghindari space full karena backup teplate
            $rmdir = CpanelWhm::execute_action('2',
                'Fileman', 'fileop', $cpanel_username,
                    array(
                    'op'                => 'unlink',
                    'sourcefiles'       => '/home/'.$cpanel_username.'/public_html/'.$obj->cpanel_subdomain,
                )
            );
            //create Directory untuk simpan core
            $mkdir = CpanelWhm::execute_action('2',
                'Fileman', 'mkdir', $cpanel_username,
                    array(
                    "path"          =>       '/home/'.$cpanel_username.'/public_html',
                    "name"          =>       $obj->cpanel_subdomain,
                    "permissions"   =>       "0755"
                  )
            );
            //end Directory clear

            //data untuk sitepubliser
            $data = array(
              'target'        => '/home/'.$cpanel_username.'/public_html/'.$obj->cpanel_subdomain,
              'source'        => '/usr/local/cpanel/etc/site_templates/airybook/website-cloud-frontend/production/core',
              'sitename'      => $obj->meta_title,
              'description'   => $obj->meta_description,
              'COMPANY'       => $obj->code,
              'TEMPLATENAME'  => 'default',
              'API_URL'       => env('API_URL'),
              'domain_url'    => $obj->base_url,
            );
        }

        //call cpanel API
        $requestAPI = CpanelWhm::execute_action('3', 'SiteTemplates', 'publish', $cpanel_username,$data);

        //check apakah SiteTemplates publish sukses
        if($requestAPI && $requestAPI!= null){

            $result = json_decode($requestAPI);

            if(!isset($result->error)){

                //copy .htaccess
                if($obj->cpanel_username!="") //cpanel_username
                {
                  // copy .htaccess
                  $cphtaccess = CpanelWhm::execute_action('2',
                      'Fileman', 'fileop', $cpanel_username,
                          array(
                          'op'                => 'copy',
                          'sourcefiles'       => '/home/'.$cpanel_username.'/public_html/BALIT0l4kr3kL4m4s1-merdeka-root.php',
                          'destfiles'         => '/home/'.$cpanel_username.'/public_html/.htaccess',
                          'doubledecode'      => '1'
                      )
                  );
                }
                else // without cpanel_username
                {
                  // copy .htaccess
                  $cphtaccess = CpanelWhm::execute_action('2',
                      'Fileman', 'fileop', $cpanel_username,
                          array(
                          'op'                => 'copy',
                          'sourcefiles'       => '/home/'.$cpanel_username.'/public_html/'.$obj->cpanel_subdomain.'/BALIT0l4kr3kL4m4s1-merdeka-subdomain.php',
                          'destfiles'         => '/home/'.$cpanel_username.'/public_html/'.$obj->cpanel_subdomain.'/.htaccess',
                          'doubledecode'      => '1'
                      )
                  );
                }
                //save obj change
                $obj->save();

                //run theme change at initial
                $this->change_theme($request, $id);

                return response()->json(array(
                'error' => false,
                'message' => 'Install Core successfuly'),
                200
                );

            }
        }

        return response()->json(array(
        'error' => true,
        'message' => 'Install Core failed : '.$result->error),
        200
        );
    }


     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function change_theme_setting(Request $request, $id)
    {


        $obj=Company::find($id);
        $obj->theme_setting=$request->json('theme_setting');
        $obj->save();



        return response()->json(array(
        'error' => false,
        'message' => 'Theme  setting updated'),
        200
        );
    }



    public function store(Request $request){


            $data['code']=$request->json('code');
            $data['name']=$request->json('name');
            $data['address']=$request->json('address');
            $data['phone']=$request->json('phone');
            $data['tlp']=$request->json('tlp');
            $data['email']=$request->json('email');
            $data['base_url']=$request->json('base_url');
            $data['username']=$request->json('username');
            $data['password']=$request->json('password');
            $data['business_type']=$request->json('business_type');


            $validator = Validator::make($data, CompanyController::$rules);

                if ($validator->fails()) {


                    return response()->json(array(
                        'error' => true,
                        'message' => $validator->errors()->all()),
                        200
                        );
                }else{

                DB::beginTransaction();

                try {

                    $obj=new Company;
                    $company_id=Uuid::generate();
                    $obj->id=$company_id;
                    $obj->code=$request->json('code');
                    $obj->name=$request->json('name');
                    $obj->address=$request->json('address');
                    $obj->type='SINGLE';
                    $obj->phone=$request->json('phone');
                    $obj->tlp=$request->json('tlp');
                    $obj->email=$request->json('email');
                    $obj->base_url=$request->json('base_url');
                    $obj->tripadvisor_logo="https://s3-ap-southeast-1.amazonaws.com/gp-static-website/images/tripadvisor.png";
                    $obj->created_by = 'ADMIN';
                    $obj->email_format = '';
                    $obj->business_type=$request->json('business_type');
                    $obj->cpanel_username=$request->json('cpanel_username');
                    $obj->cpanel_subdomain=$request->json('cpanel_subdomain');
                    $obj->cpanel_password=$request->json('cpanel_password');
                    $obj->theme_id=$request->json('theme_id');
                    $obj->google_captcha_site_key=$request->json('google_captcha_site_key');
                    $obj->google_captcha_secret_key=$request->json('google_captcha_secret_key');

                    if ($request->json('active')==true){
                        $obj->active=1;
                    }else{
                        $obj->active=0;
                    }
                    $obj->save();

                    $user = new User;
                    $user->id=Uuid::generate();
                    $user->name=$request->json('username');
                    $user->role_id='1s';
                    $user->username=$request->json('username');
                    $user->password=Hash::make($request->json('password'));
                    $user->created_by = 'ADMIN';
                    $user->company_id =$company_id->__toString();
                    $user->save();

                    if ($request->json('use_data')==true){
                        $use_data=1;
                    }else{
                        $use_data=0;
                    }

                    if ($use_data==1){
                        $company_code_from='demo';
                        //$company_from=Company::where('code','=',$company_code_from)->first();

                        $sql_company="select * from company where code='".$company_code_from."'";
                        $data_company=DB::select($sql_company);
                        $company_from=$data_company[0];
                        $new_company=Company::find($company_id->__toString());

                        $new_company->description=$company_from->description;
                        $new_company->meta_title=$company_from->meta_title;
                        $new_company->meta_keyword=$company_from->meta_keyword;
                        $new_company->meta_description=$company_from->meta_description;
                        $new_company->image=$company_from->image;
                        $new_company->logo=$company_from->logo;
                        $new_company->tripadvisor_logo=$company_from->tripadvisor_logo;
                        $new_company->save();
                        //Copy Setting
                        $from=Setting::where('company_id','=',$company_from->id)->get();
                        foreach ($from as $key => $value) {
                            $newObj = $value->replicate();
                            $id=Uuid::generate();
                            $newObj->id=$id;
                            $newObj->company_id=$company_id->__toString();
                            $newObj->save();
                        }


                        //Copy Slider
                        $from=Slider::where('company_id','=',$company_from->id)->get();
                        foreach ($from as $key => $value) {
                            $newObj = $value->replicate();
                            $id=Uuid::generate();
                            $newObj->id=$id;
                            $newObj->company_id=$company_id->__toString();
                            $newObj->save();
                        }

                        //Copy Tags
                        $from=Tags::where('company_id','=',$company_from->id)->get();
                        foreach ($from as $key => $value) {
                            $newObj = $value->replicate();
                            $id=Uuid::generate();
                            $newObj->id=$id;
                            $newObj->company_id=$company_id->__toString();
                            $newObj->save();
                        }

                         //Copy Facilities
                        $from=Facilities::where('company_id','=',$company_from->id)->get();
                        foreach ($from as $key => $value) {
                            $newObj = $value->replicate();
                            $id=Uuid::generate();
                            $newObj->id=$id;
                            $newObj->company_id=$company_id->__toString();
                            $newObj->save();
                        }


                        //Copy Category
                        $from=Category::where('company_id','=',$company_from->id)->get();
                        foreach ($from as $key => $value) {
                            $newObj = $value->replicate();
                            $id=Uuid::generate();
                            $newObj->id=$id;
                            $newObj->company_id=$company_id->__toString();
                            $newObj->save();
                        }

                        //Copy Post Category
                        $from=PostCategory::where('company_id','=',$company_from->id)->get();
                        foreach ($from as $key => $value) {
                            $newObj = $value->replicate();
                            $id=Uuid::generate();
                            $newObj->id=$id;
                            $newObj->company_id=$company_id->__toString();
                            $newObj->save();
                        }



                        //Copy Gallery Category
                        $from=GalleryCategory::where('company_id','=',$company_from->id)->get();
                        foreach ($from as $key => $value) {
                            $newObj = $value->replicate();
                            $id=Uuid::generate();
                            $newObj->id=$id;
                            $newObj->company_id=$company_id->__toString();
                            $newObj->save();
                        }

                        //Copy Gallery
                        $from_gallery=Gallery::where('company_id','=',$company_from->id)->get();
                        foreach ($from_gallery as $key => $value) {
                            $newObj = $value->replicate();
                            $newObj->id=Uuid::generate();
                            $gallery_category_from=GalleryCategory::find($newObj->gallery_category_id);
                            $gallery_category_to=GalleryCategory::where('company_id','=',$company_id->__toString())
                                                                    ->where('name','=',$gallery_category_from->name)
                                                                    ->first();
                            $newObj->gallery_category_id=$gallery_category_to->id;
                            $newObj->company_id=$company_id->__toString();
                            $newObj->save();
                        }


                         //Copy Post
                        $from=Post::where('company_id','=',$company_from->id)->get();
                        foreach ($from as $key => $value) {
                            $newObj = $value->replicate();
                            $id=Uuid::generate();
                            $newObj->id=$id;
                            if ($newObj->post_category_id){
                                $category_from=PostCategory::find($newObj->post_category_id);
                                $category_to=PostCategory::where('company_id','=',$company_id->__toString())
                                                                        ->where('slug','=',$category_from->slug)
                                                                        ->first();
                                $newObj->post_category_id=$category_to->id;
                            }


                            $newObj->company_id=$company_id->__toString();
                            $newObj->save();
                        }


                        //Copy Post Tags
                        $from=PostTags::where('company_id','=',$company_from->id)->get();
                        foreach ($from as $key => $value) {
                            $newObj = $value->replicate();
                            $newObj->id=Uuid::generate();

                            $tags_category_from=Tags::find($newObj->tags_id);
                            $tags_category_to=Tags::where('company_id','=',$company_id->__toString())
                                                                    ->where('slug','=',$tags_category_from->slug)
                                                                    ->first();
                            $newObj->tags_id=$tags_category_to->id;

                            $post_from=Post::find($newObj->post_id);
                            $post_to=Post::where('company_id','=',$company_id->__toString())
                                                                    ->where('slug','=',$post_from->slug)
                                                                    ->first();
                            $newObj->post_id=$post_to->id;

                            $newObj->company_id=$company_id->__toString();
                            $newObj->save();
                        }


                         //Copy Product
                        $from=Product::where('company_id','=',$company_from->id)->get();
                        foreach ($from as $key => $value) {
                            $newObj = $value->replicate();
                            $id=Uuid::generate();
                            $newObj->id=$id;
                            if ($newObj->category_id){
                                $category_from=Category::find($newObj->category_id);
                                $category_to=Category::where('company_id','=',$company_id->__toString())
                                                                        ->where('slug','=',$category_from->slug)
                                                                        ->first();
                                $newObj->category_id=$category_to->id;
                            }


                            $newObj->company_id=$company_id->__toString();
                            $newObj->save();
                        }


                        //Copy Product Tags
                        $from=ProductTags::where('company_id','=',$company_from->id)->get();
                        foreach ($from as $key => $value) {
                            $newObj = $value->replicate();
                            $newObj->id=Uuid::generate();

                            $tags_category_from=Tags::find($newObj->tags_id);
                            $tags_category_to=Tags::where('company_id','=',$company_id->__toString())
                                                                    ->where('slug','=',$tags_category_from->slug)
                                                                    ->first();
                            $newObj->tags_id=$tags_category_to->id;

                            if ($newObj->product_id){
                                $product_from=Product::find($newObj->product_id);
                                $product_to=Product::where('company_id','=',$company_id->__toString())
                                                                        ->where('slug','=',$product_from->slug)
                                                                        ->first();
                                if (is_object($product_to)){
                                    $newObj->product_id=$product_to->id;
                                }

                            }


                            $newObj->company_id=$company_id->__toString();
                            $newObj->save();
                        }


                         //Copy Product Gallery
                        $from=ProductGallery::where('company_id','=',$company_from->id)->get();
                        foreach ($from as $key => $value) {
                            $newObj = $value->replicate();
                            $id=Uuid::generate();
                            $newObj->id=$id;

                            if ($newObj->product_id){

                                $product_from=Product::find($newObj->product_id);
                                if (is_object($product_from)){
                                    $product_to=Product::where('company_id','=',$company_id->__toString())
                                                                        ->where('slug','=',$product_from->slug)
                                                                        ->first();
                                    if (is_object($product_to)){
                                        $newObj->product_id=$product_to->id;
                                         if ($product_from->image_id==$value->id){
                                            $product_to->image_id=$id->__toString();
                                            $product_to->save();
                                        }
                                    }
                                }

                           }


                            $newObj->company_id=$company_id->__toString();
                            $newObj->save();
                        }


                        // Copy Product Facilities
                        $from=ProductFacilities::where('company_id','=',$company_from->id)->get();
                        foreach ($from as $key => $value) {
                            $newObj = $value->replicate();
                            $newObj->id=Uuid::generate();

                            if ($newObj->product_id){
                                $product_from=Product::find($newObj->product_id);

                                if (is_object($product_from)){
                                    $product_to=Product::where('company_id','=',$company_id->__toString())
                                                                        ->where('slug','=',$product_from->slug)
                                                                        ->first();
                                  if (is_object($product_to)){
                                        $newObj->product_id=$product_to->id;
                                    }
                                }

                            }

                            if ($newObj->facilities_id){
                                $facilities_from=Facilities::find($newObj->facilities_id);
                                $facilities_to=Facilities::where('company_id','=',$company_id->__toString())
                                                                        ->where('name','=',$facilities_from->name)
                                                                        ->first();

                                if (is_object($facilities_to)){
                                    $newObj->facilities_id=$facilities_to->id;
                                }

                            }

                            $newObj->company_id=$company_id->__toString();
                            $newObj->save();
                        }



                    }else{

                        //Menu Header
                            $newObj = new Setting;
                            $id=Uuid::generate();
                            $newObj->id=$id;
                            $newObj->name='menu_header';
                            $newObj->value='[
                                                    {"label":"Home","type":"HOME"},
                                                    {"label":"Contact Us","type":"CONTACT"}
                                             ]
                                            ';
                            $newObj->group='menu_navigation';
                            $newObj->company_id=$company_id->__toString();
                            $newObj->save();


                        //Menu Side Bar
                            $newObj = new Setting;
                            $id=Uuid::generate();
                            $newObj->id=$id;
                            $newObj->name='menu_sidebar';
                            $newObj->value='[]';
                            $newObj->group='menu_navigation';
                            $newObj->company_id=$company_id->__toString();
                            $newObj->save();

                         //Menu Footer
                            $newObj = new Setting;
                            $id=Uuid::generate();
                            $newObj->id=$id;
                            $newObj->name='menu_footer';
                            $newObj->value='[]';
                            $newObj->group='menu_navigation';
                            $newObj->company_id=$company_id->__toString();
                            $newObj->save();


                        //Home Setting
                            $newObj = new Setting;
                            $id=Uuid::generate();
                            $newObj->id=$id;
                            $newObj->name='home_setting';
                            $newObj->value='{"slider":{"status":true},
                                                 "data":[
                                                        {"name":"company_profile","label":"Company Profile","status":true,"data":[]},
                                                        {"name":"category","label":"Category","status":true,"data":[]},
                                                        {"name":"review","label":"Review","status":true,"data":[]}
                                                        ]
                                                }';
                            $newObj->company_id=$company_id->__toString();
                            $newObj->save();

                        //social_media
                            $newObj = new Setting;
                            $id=Uuid::generate();
                            $newObj->id=$id;
                            $newObj->name='social_media';
                            $newObj->value='[{"code":"g-plus","name":"Google plus","url":"","status":true,"open":true},{"code":"fb","name":"Facebook","url":"","status":true,"open":false},{"code":"instagram","name":"Instagram","url":"","status":true,"open":false},{"code":"twiter","name":"Twiter","url":"","status":true,"open":false},{"code":"path","name":"Path","url":"","status":true,"open":false},{"code":"pinterest","name":"Pinterest","url":"","status":true,"open":false},{"code":"linkedIn","name":"LinkedIn","url":"","status":true,"open":false}]';
                            $newObj->company_id=$company_id->__toString();
                            $newObj->save();




                    }






                  //  $status=$this->sentInvoiceEmailRegistration($company_id->__toString());
                     DB::commit();
            // all good

                          return response()->json(array(
                             'error' => false,
                             'company_id' =>$company_id->__toString(),
                             'message' => 'Register Success'),
                             200
                          );


                    } catch (\Exception $e) {
                        DB::rollback();
                                 return response()->json(array(
                            'error' => true,
                            'message' => $e->getMessage()),
                            200
                            );
                    }



                }

    }


    public function store_group_company(Request $request){


            $data['code']=$request->json('code');
            $data['name']=$request->json('name');
            $data['address']=$request->json('address');
            $data['phone']=$request->json('phone');
            $data['tlp']=$request->json('tlp');
            $data['email']=$request->json('email');
            $data['base_url']=$request->json('base_url');
            $data['username']=$request->json('username');
            $data['password']=$request->json('password');
            $data['business_type']='HOTEL';


            $validator = Validator::make($data, CompanyController::$rules);

                if ($validator->fails()) {


                    return response()->json(array(
                        'error' => true,
                        'message' => $validator->errors()->all()),
                        200
                        );
                }else{

                DB::beginTransaction();

                try {

                    $obj=new Company;
                    $company_id=Uuid::generate();
                    $obj->id=$company_id;
                    $obj->code=$request->json('code');
                    $obj->name=$request->json('name');
                    $obj->address=$request->json('address');
                    $obj->phone=$request->json('phone');
                    $obj->tlp=$request->json('tlp');
                    $obj->email=$request->json('email');
                    $obj->base_url=$request->json('base_url');
                    $obj->tripadvisor_logo="https://s3-ap-southeast-1.amazonaws.com/gp-static-website/images/tripadvisor.png";
                    $obj->created_by = 'ADMIN';
                    $obj->email_format = '';
                    $obj->type='GROUP';
                    // $obj->business_type=$request->json('business_type');
                    // $obj->cpanel_username=$request->json('cpanel_username');
                    // $obj->cpanel_subdomain=$request->json('cpanel_subdomain');
                    // $obj->cpanel_password=$request->json('cpanel_password');
                    // $obj->theme_id=$request->json('theme_id');
                    // $obj->google_captcha_site_key=$request->json('google_captcha_site_key');
                    // $obj->google_captcha_secret_key=$request->json('google_captcha_secret_key');

                    if ($request->json('active')==true){
                        $obj->active=1;
                    }else{
                        $obj->active=0;
                    }
                    $obj->save();

                    $user = new User;
                    $user->id=Uuid::generate();
                    $user->name=$request->json('username');
                    $user->role_id='1s';
                    $user->username=$request->json('username');
                    $user->password=Hash::make($request->json('password'));
                    $user->created_by = 'ADMIN';
                    $user->company_id =$company_id->__toString();
                    $user->save();




                     DB::commit();
            // all good

                          return response()->json(array(
                             'error' => false,
                             'company_id' =>$company_id->__toString(),
                             'message' => 'Register Success'),
                             200
                          );


                    } catch (\Exception $e) {
                        DB::rollback();
                                 return response()->json(array(
                            'error' => true,
                            'message' => $e->getMessage()),
                            200
                            );
                    }



                }

    }



    public function activate(Request $request,$id){
        $company=Company::find($id);
        $company->active=1;
        $company->save();
        $data['company']=$company;
        // $data['link_login']='http://localhost/guestpro-cloud-pms-frontend';
        $data['link_login']='https://cloud-pms.guestpro.id';

        return response()->view('confirmation', $data);
    }



    public function sentInvoiceEmailRegistration($company_id){

            $company=Company::find($company_id);
            $key=Uuid::generate();

             $data = [
             "key_hash" => $key->__toString(),
             "to_email" => $company->email,
            "to_name" => $company->contact_name,
            "from_email" => "no-reply@guestpro.id",
            "from_name" => "AiryBook | ".$company->name,
            "replyto_email" => "info@guestpro.id",
            "subject" => "[AiryBook] Activation instructions",
            "body" => "<html></html>",
            "attachment_url" => "",
            "client_name" => $company->contact_name,
            "client_company_name" => $company->name,
            "product_name" => "AiryBook",
            "token" => $company_id,
            "url_activation" => "https://cloud-pms-api.guestpro.id/api/activate/".$company_id,
            "template_id" => "0ba2f13e-efbf-444b-a39a-3cf10db34a87"
             ];

            $url = 'https://gp-mailer.guestpro.id/api/v1/mail/pms/activation';
            $jsonData = json_encode($data);
            $headers = ['Content-Type' => 'application/json',
                                        //'X-Authorization' => '0a2f06f5515133c875126cdb862b3eefab1e5c60'
                                     ];

         $client = new Client();
         $res = $client->post($url, ['headers'=>$headers,'body' => $jsonData]);
           return $res->getStatusCode();
    }





   public function change_logo(Request $request,$id){
        $company=Company::find($id);
        $base64_str = substr($request->json('image'), strpos($request->json('image'), ",")+1);
        $image = base64_decode($base64_str);
        function base64_to_jpeg($base64_string, $output_file) {
          // open the output file for writing
          $ifp = fopen( $output_file, 'wb' ); 

          // split the string on commas
          // $data[ 0 ] == "data:image/png;base64"
          // $data[ 1 ] == <actual base64 string>
          $data = explode( ',', $base64_string );

          // we could add validation here with ensuring count( $data ) > 1
          fwrite( $ifp, base64_decode( $data[ 1 ] ) );

          // clean up the file resource
          fclose( $ifp ); 

          return $output_file; 
        }
        $response=AppHelper::upload_image_crop($image, $company->code);
         if ($response['error']==true){
                 return response()->json(array(
                        'error' => true,
                        'message' => $response['message']),
                        200
                        );
        }else{

                $company->logo= base64_to_jpeg($request->json('image'), str_replace(' ', '-', 'Logo '.$company->name.'.png'));
                $company->save();

                return response()->json(array(
                'error' => false,
                'message' => 'Change Logo success'),
                200
                );

        }


    }


     public function change_image(Request $request,$id){
        $company=Company::find($id);
        $base64_str = substr($request->json('image'), strpos($request->json('image'), ",")+1);
        $image = base64_decode($base64_str);
        function base64_to_jpeg($base64_string, $output_file) {
          // open the output file for writing
          $ifp = fopen( $output_file, 'wb' ); 

          // split the string on commas
          // $data[ 0 ] == "data:image/png;base64"
          // $data[ 1 ] == <actual base64 string>
          $data = explode( ',', $base64_string );

          // we could add validation here with ensuring count( $data ) > 1
          fwrite( $ifp, base64_decode( $data[ 1 ] ) );

          // clean up the file resource
          fclose( $ifp ); 

          return $output_file; 
        }
        $response=AppHelper::upload_image_crop($image,$company->code);
         if ($response['error']==true){
                 return response()->json(array(
                        'error' => true,
                        'message' => $response['message']),
                        200
                        );
        }else{

                $company->image= base64_to_jpeg($request->json('image'), str_replace(' ', '-', 'Image '.$company->name.' .png'));
                $company->save();

                return response()->json(array(
                'error' => false,
                'message' => 'Change Image success'),
                200
                );

        }


    }


    public function change_tripadvisor(Request $request,$id){

       $company=Company::find($id);
        $base64_str = substr($request->json('image'), strpos($request->json('image'), ",")+1);
        $image = base64_decode($base64_str);

        $response=AppHelper::upload_image_crop($image,$company->code);
         if ($response['error']==true){
                 return response()->json(array(
                        'error' => true,
                        'message' => $response['message']),
                        200
                        );
        }else{

                $company->tripadvisor_logo= $request->json('image');
                $company->save();

                return response()->json(array(
                'error' => false,
                'message' => 'Change Tripadvisor success'),
                200
                );

        }

    }




    public function images($name,$size){
        try{
            $size = explode('x', $size);
        $cache_image = Image::cache(function($image) use($size, $name){
           return $image->make(public_path().'/images/'.$name);

        }, 10); // cache for 10 minutes
        return response()->make($cache_image, 200, ['Content-Type' => 'image']);
        } catch (\Exception $e) {
                $cache_image = Image::cache(function($image) use($size){
                   return $image->make(public_path().'/images/no_images.jpg')->resize($size[0], $size[1]);

                }, 10);
               return response()->make($cache_image, 200, ['Content-Type' => 'image']);
        }


    }




    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {


            $obj=Company::find($id);
            if ($obj){
               $obj->delete();
            }



             DB::commit();
            // all good

           return response()->json(array(
                        'error' => false,
                        'message' => 'Rollback Application Date Success'),
                        200
                        );

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(array(
                'error' => true,
                'message' => $e->getMessage()),
                200
                );
        }



        return response()->json(array(
        'error' => false,
        'message' => 'Company deleted'),
        200
        );
    }

}
