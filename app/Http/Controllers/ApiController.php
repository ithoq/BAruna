<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use App\Slider;
use App\Setting;
use App\Theme;
use App\Tags;
use App\Room;
use App\RoomGallery;
use App\RoomTags;
use App\Category;
use App\GalleryCategory;
use App\ProductTags;
use App\Gallery;
use App\ProductGallery;
use App\Product;
use App\Booking;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Uuid;
use JWTAuth;
use DB;
use App\Post;
use App\PostCategory;
use App\PostTags;
use App\Custom\CustomPaginationLinks;
use Validator;
use GuzzleHttp\Client;
use Mail;
use Illuminate\Mail\Message;

use Carbon\Carbon;

class ApiController extends Controller
{

    public static $rules_contact = array(
    'name'=>'required',
    'message'=>'required',
    'subject'=>'required',
    'email'=>'required|email',
    );

    private $reviews_limit=5;

    private function getData(Request $request){
        $company_code = $request->get('company');
        $company=Company::where('code','=',$company_code)->first();
        $data['company_profile'] = $company;
        $social_media = Setting::where('company_id','=',$data['company_profile']->id)
                                ->where('name','=','social_media')->first();
        $social_media_decode=json_decode( $social_media)->value;
        $data['social_media']=$social_media_decode;
        $setting_menu_header = Setting::where('company_id','=',$company->id)
                                            ->where('name','=','menu_header')
                                            ->first();
        $setting_menu_footer = Setting::where('company_id','=',$company->id)
                                            ->where('name','=','menu_footer')
                                            ->first();

        $data['style_config'] = json_decode($company->theme_setting);



        $data['menu_header'] = [];
        $data['menu_footer'] = [];
        $theme=Theme::find($company->theme_id);
        $menu_config  = json_decode($theme->setting);
        $menu_header_config=$menu_config->menu_header_config;
        if (is_object($setting_menu_header)){

            $menu=$menu_header_config->container_before;
            foreach (json_decode( $setting_menu_header)->value as $key => $value) {
              if (is_object($value)){
                        if ($value->type=='CUSTOM_LINK'){
                            $label = str_replace("HREF","href='".$value->data->url."'",$menu_header_config->link_before );
                            $label = str_replace("LABEL",$value->label,$label );
                             $menu=$menu
                                   .$menu_header_config->before
                                   .$label
                                   .$menu_header_config->link_after
                                  .$menu_header_config->after;
                        }else  if ($value->type=='BLOG'){
                            $label = str_replace("HREF","href='".$company->base_url.'blog/category/all'."'",$menu_header_config->link_before );
                            $label = str_replace("LABEL",$value->label,$label );
                             $menu=$menu
                                   .$menu_header_config->before
                                   .$label
                                   .$menu_header_config->link_after
                                  .$menu_header_config->after;
                        } else if ($value->type=='CATEGORIES'){
                              $label = str_replace("HREF","href='".$company->base_url.'category/'.$value->slug."'",$menu_header_config->link_before );
                              $label = str_replace("LABEL",$value->label,$label );
                              $menu=$menu
                                   .$menu_header_config->before
                                   .$label
                                   .$menu_header_config->link_after
                                  .$menu_header_config->after;
                       } else if ($value->type=='GALLERY'){
                              $label = str_replace("HREF","href='".$company->base_url.'gallery/'.$value->slug."'",$menu_header_config->link_before );
                              $label = str_replace("LABEL",$value->label,$label );
                          $menu=$menu
                                   .$menu_header_config->before
                                   .$label
                                   .$menu_header_config->link_after
                                   .$menu_header_config->after;

                       }else if ($value->type=='PAGES'){
                              $label = str_replace("HREF","href='".$company->base_url.'page/'.$value->slug."'",$menu_header_config->link_before );
                              $label = str_replace("LABEL",$value->label,$label );
                          $menu=$menu
                                   .$menu_header_config->before
                                   .$label
                                   .$menu_header_config->link_after
                                   .$menu_header_config->after;

                       }else if ($value->type=='MODULE'){

                                $label = str_replace("HREF","href='".$company->base_url.$value->data->module,$menu_header_config->link_before );
                                $label = str_replace("LABEL",$value->label,$label );
                                $menu=$menu
                                     .$menu_header_config->before
                                     .$label
                                     .$menu_header_config->link_after
                                     .$menu_header_config->after;




                       }else if ($value->type=='CONTACT'){
                              $label = str_replace("HREF","href='".$company->base_url."contact'",$menu_header_config->link_before );
                              $label = str_replace("LABEL",$value->label,$label );
                          $menu=$menu
                                   .$menu_header_config->before
                                   .$label
                                   .$menu_header_config->link_after
                                   .$menu_header_config->after;

                       }  else {
                           $label = str_replace("HREF","href='".$company->base_url."'",$menu_header_config->link_before );
                              $label = str_replace("LABEL",$value->label,$label );

                          $menu=$menu
                                   .$menu_header_config->before
                                   .$label
                                   .$menu_header_config->link_after
                                   .$menu_header_config->after;

                    }
              }
             }
             $menu=$menu.$menu_header_config->container_after;
             $data['menu_header'] =$menu;

        }



        $menu_footer_config=$menu_config->menu_footer_config;

        if (is_object($setting_menu_footer)){

            $menu=$menu_footer_config->container_before;
            foreach (json_decode( $setting_menu_footer)->value as $key => $value) {
              if (is_object($value)){
                          if ($value->type=='CUSTOM_LINK'){
                              $label = str_replace("HREF","href='".$value->data->url."'",$menu_footer_config->link_before );
                              $label = str_replace("LABEL",$value->label,$label );
                               $menu=$menu
                                     .$menu_footer_config->before
                                     .$label
                                     .$menu_footer_config->link_after
                                    .$menu_footer_config->after;
                          }else  if ($value->type=='BLOG'){
                              $label = str_replace("HREF","href='".$company->base_url.'blog/category/all'."'",$menu_footer_config->link_before );
                              $label = str_replace("LABEL",$value->label,$label );
                               $menu=$menu
                                     .$menu_footer_config->before
                                     .$label
                                     .$menu_footer_config->link_after
                                    .$menu_footer_config->after;
                          } else if ($value->type=='CATEGORIES'){
                                $label = str_replace("HREF","href='".$company->base_url.'category/'.$value->slug."'",$menu_footer_config->link_before );
                                $label = str_replace("LABEL",$value->label,$label );
                                $menu=$menu
                                     .$menu_footer_config->before
                                     .$label
                                     .$menu_footer_config->link_after
                                    .$menu_footer_config->after;
                         } else if ($value->type=='GALLERY'){
                                $label = str_replace("HREF","href='".$company->base_url.'gallery/'.$value->slug."'",$menu_footer_config->link_before );
                                $label = str_replace("LABEL",$value->label,$label );
                            $menu=$menu
                                     .$menu_footer_config->before
                                     .$label
                                     .$menu_footer_config->link_after
                                     .$menu_footer_config->after;

                         }else if ($value->type=='PAGES'){
                                $label = str_replace("HREF","href='".$company->base_url.'page/'.$value->slug."'",$menu_footer_config->link_before );
                                $label = str_replace("LABEL",$value->label,$label );
                            $menu=$menu
                                     .$menu_footer_config->before
                                     .$label
                                     .$menu_footer_config->link_after
                                     .$menu_footer_config->after;

                         } else {
                             $label = str_replace("HREF","href='".$company->base_url."'",$menu_footer_config->link_before );
                                $label = str_replace("LABEL",$value->label,$label );

                            $menu=$menu
                                     .$menu_footer_config->before
                                     .$label
                                     .$menu_footer_config->link_after
                                     .$menu_footer_config->after;

                      }
              }
             }
             $menu=$menu.$menu_footer_config->container_after;
             $data['menu_footer'] =$menu;

        }

        return $data;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function home(Request $request)
    {
        $data=$this->getData($request);
        $data['sliders'] = [];

        $home_setting = Setting::where('company_id','=',$data['company_profile']->id)
                                        ->where('name','=','home_setting')->first();
        $home_setting_decode=json_decode( $home_setting)->value;
        $s3_url=env('S3_URL');
        foreach ($home_setting_decode->data as $key => $value) {
            if ($value->name=='category'){
                $data_category=[];
                foreach ($value->data as $key_category => $value_category) {
                     $sql="select category.id,
                            category.slug,
                            category.name,
                            concat('".$s3_url."',product_gallery.image) as image,
                             concat('".$s3_url."',product_gallery.image_thumb) as image_thumb
                     from category
                     join product on product.category_id=category.id
                     join product_gallery on product_gallery.product_id=product.id
                     where category.deleted_at is null
                     and product.deleted_at is null
                     and category.id='".$value_category->category_id."'
                     group by category.id
                     having max(product.created_at)";
                     $cat=DB::select($sql);
                     if (sizeof($cat)>0){
                        $data_category[]=$cat[0];
                     }

                }
                $value->data=$data_category;

            }

            if ($value->name=='product'){
                $category_id=$value->data->category_id;
               $value->data=Product::with('product_gallery')->where('company_id','=',$data['company_profile']->id)
                                                ->where('category_id','=',$value->data->category_id)
                                                ->orderBy('created_at','desc')
                                                ->limit($value->data->number_of_product)
                                                ->get();
                $value->title = Category::find($category_id)->name;
            }

            if ($value->name=='room'){
                foreach ($value->data as $key_room => $value_room) {
                  $value_room=Room::with('room_gallery')->find($value_room->id);
                }
            }

            if ($value->name=='gallery'){
                $value->data=Gallery::where('company_id','=',$data['company_profile']->id)
                                               ->where('gallery_category_id','=',$value->data->gallery_category_id)
                                               ->get();
            }

            if ($value->name=='blog'){
                $post_category_id=$value->data->post_category_id;
               $value->data=Post::where('company_id','=',$data['company_profile']->id)
                                                ->where('post_category_id','=',$value->data->post_category_id)
                                                ->orderBy('created_at','desc')
                                                ->limit($value->data->number_of_post)
                                                ->get();
                $value->title = PostCategory::find($post_category_id)->name;
            }

            if ($value->name=='review'){
               $value->data = Post::where('company_id','=',$data['company_profile']->id)
                          ->where('type','=','TESTIMONIAL')
                          ->orderBy('created_at','desc')
                          ->where('status','=',1)
                          ->limit($this->reviews_limit)
                          ->get();


            }


            if ($value->name=='company_profile'){
               $value->data=$data['company_profile'];
            }


        }

        $data['home_setting'] =  $home_setting_decode;
        if ($home_setting_decode->slider->status==true){
           $data['sliders'] = Slider::where('company_id','=',$data['company_profile']->id)->get();
        }


        return response()->json($data, 200, []);
    }


     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function category(Request $request)
    {
        $data=$this->getData($request);
        $data['category']=Category::where('slug','=',$request->get('slug'))
                                ->where('company_id','=',$data['company_profile']->id)->first();
        if (is_object($data['category'])){
              $data['product']=Product::with('product_gallery')->where('category_id','=',$data['category']->id)->get();
              $data['sidebar'] =[];

             $setting_menu_sidebar = Setting::where('company_id','=',$data['company_profile']->id)
                                                ->where('name','=','menu_sidebar')
                                                ->first();

            if (is_object($setting_menu_sidebar)){
                $data['sidebar'] = json_decode( $setting_menu_sidebar)->value;

                   foreach ($data['sidebar'] as $key => $value) {
                        if ($value->type=='CATEGORIES'){
                            $value->product=Product::with('product_gallery')->where('category_id','=',$value->reference_id)->get();
                        }
                    }


            }
        }





        return response()->json($data, 200, []);
    }


     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pages(Request $request)
    {
        $data=$this->getData($request);
        $data['page']=Post::where('slug','=',$request->get('slug'))
                            ->where('type','=','PAGE')
                            ->where('status','=',1)
                            ->where('company_id','=',$data['company_profile']->id)->first();


        $data['sidebar'] =[];

         $setting_menu_sidebar = Setting::where('company_id','=',$data['company_profile']->id)
                                            ->where('name','=','menu_sidebar')
                                            ->first();

        if (is_object($setting_menu_sidebar)){
            $data['sidebar'] = json_decode( $setting_menu_sidebar)->value;

               foreach ($data['sidebar'] as $key => $value) {
                    if ($value->type=='CATEGORIES'){
                        $value->product=Product::with('product_gallery')->where('category_id','=',$value->reference_id)->get();
                    }
                }


        }





        return response()->json($data, 200, []);
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function product(Request $request)
    {
        $data=$this->getData($request);
        $data['product']=Product::with('category','product_gallery')->where('slug','=',$request->get('slug'))
                                ->where('company_id','=',$data['company_profile']->id)->first();

        $data['other_product']=[];
        if ($data['product']->category->id){
            $data['other_product']=Product::with('category','product_gallery')
                                ->where('category_id','=',$data['product']->category->id)
                                ->where('company_id','=',$data['company_profile']->id)
                                ->limit(4)->get();

        }

        $data['product_gallery']=ProductGallery::where('product_id','=',$data['product']->id)->get();
        $data['product_tags']=ProductTags::with('tags')->where('product_id','=',$data['product']->id)->get();
        $data['sidebar'] =[];
         $setting_menu_sidebar = Setting::where('company_id','=',$data['company_profile']->id)
                                            ->where('name','=','menu_sidebar')
                                            ->first();

        if (is_object($setting_menu_sidebar)){
            $data['sidebar'] = json_decode( $setting_menu_sidebar)->value;

               foreach ($data['sidebar'] as $key => $value) {
                    if ($value->type=='CATEGORIES'){
                        $value->product=Product::with('product_gallery')->where('category_id','=',$value->reference_id)
                                                                     ->where('company_id','=',$data['company_profile']->id)
                                                                     ->get();
                    }
                }


        }
        return response()->json($data, 200, []);
    }




    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function room_detail(Request $request)
    {
        $data=$this->getData($request);
        $data['room']=Room::with('room_gallery')->where('slug','=',$request->get('slug'))
                                ->where('company_id','=',$data['company_profile']->id)->first();
        $data['room_gallery']=RoomGallery::where('room_id','=',$data['room']->id)->get();
        $data['room_tags']=RoomTags::with('tags')->where('room_id','=',$data['room']->id)->get();
        return response()->json($data, 200, []);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function accommodation(Request $request)
    {
        $data=$this->getData($request);
        $data['rooms']=Room::with('room_gallery')->where('company_id','=',$data['company_profile']->id)->get();
        return response()->json($data, 200, []);
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $data=$this->getData($request);
        $criteria_value=$request->get('criteria_value');
        $s3_url=env('S3_URL');
        $sql="SELECT product.name ,
                   product.slug ,
                   'link' AS link_type,
                   concat('".$s3_url."',product_gallery.image) as image,
                   concat('".$s3_url."',product_gallery.image_thumb) as  image_thumb ,
                   product.description
            FROM product
            JOIN product_gallery ON product.image_id=product_gallery.id
            where product.deleted_at is null
            and product.company_id='".$data['company_profile']->id."'
            and (
                product.name like '%".$criteria_value."%'
                or product.description like '%".$criteria_value."%'
            )
            ";
        $result=DB::select($sql);

        foreach ($result as $key => $value) {
            $value->litle_description = str_limit( strip_tags($value->description), $limit = 150, $end = '...');
        }


        $data['data']=$result;
        $data['sidebar'] =[];
         $setting_menu_sidebar = Setting::where('company_id','=',$data['company_profile']->id)
                                            ->where('name','=','menu_sidebar')
                                            ->first();

        if (is_object($setting_menu_sidebar)){
            $data['sidebar'] = json_decode( $setting_menu_sidebar)->value;

               foreach ($data['sidebar'] as $key => $value) {
                    if ($value->type=='CATEGORIES'){
                        $value->product=Product::with('product_gallery')->where('category_id','=',$value->reference_id)
                                                ->where('company_id','=',$data['company_profile']->id)
                                                ->get();
                    }
                }


        }
        return response()->json($data, 200, []);
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function tags(Request $request)
    {
        $data=$this->getData($request);
        $data['tags']=Tags::where('slug','=',$request->get('slug'))
                                ->where('company_id','=',$data['company_profile']->id)->first();

        $data['sidebar'] =[];
         $s3_url=env('S3_URL');
         $setting_menu_sidebar = Setting::where('company_id','=',$data['company_profile']->id)
                                            ->where('name','=','menu_sidebar')
                                            ->first();

        if (is_object($setting_menu_sidebar)){
            $data['sidebar'] = json_decode( $setting_menu_sidebar)->value;

               foreach ($data['sidebar'] as $key => $value) {
                    if ($value->type=='CATEGORIES'){
                        $value->product=Product::with('product_gallery')->where('category_id','=',$value->reference_id)
                        ->where('company_id','=',$data['company_profile']->id)
                        ->get();
                    }
                }


        }

        $sql="select *
                from (
                         select 'link' as type,
                                  product.slug,
                                  product.name,
                                  concat('".$s3_url."',product_gallery.image) as image,
                                  concat('".$s3_url."',product_gallery.image_thumb) as  image_thumb ,
                                  product.description
                          from product_tags
                          join product on product.id=product_tags.product_id
                          join product_gallery on product_gallery.id = product.image_id
                          where product_tags.tags_id='".$data['tags']->id."'
                          and product.company_id='".$data['company_profile']->id."'
                          union
                          select 'blog' as type,
                                 post.slug,
                                 post.name,
                                 concat('".$s3_url."',post.image) as image,
                                 concat('".$s3_url."',post.image_thumb) as image_thumb,
                                 post.description
                          from post_tags
                          join post on post.id=post_tags.post_id
                          where post_tags.tags_id='".$data['tags']->id."'
                          and post.company_id='".$data['company_profile']->id."'
              ) t_maint
              order by t_maint.name asc
              ";

        $data['data_tags']=DB::select($sql);

         foreach ($data['data_tags'] as $key => $value) {
            $value->litle_description = str_limit( strip_tags($value->description), $limit = 150, $end = '...');
        }

        return response()->json($data, 200, []);
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function gallery(Request $request)
    {
        $slug=$request->get('slug');
        $data=$this->getData($request);
        $setting=Setting::where('company_id','=',$data['company_profile']->id)
                         ->where('name','=','menu_header')->first();
        $setting_decode=json_decode( $setting)->value;
        $data_gallery_category = [];
        if (is_object($setting)){
            $setting_decode=json_decode( $setting)->value;
            foreach ($setting_decode as $key => $value) {
                if ($value->type=='GALLERY'){

                        $data_gallery_category=$value->data;
                        $data['title'] = $value->label;
                        break;

                }
            }
        }
        //
        //$data['gallery_category']=GalleryCategory::whereIn('id',$data_gallery_category)->get();
        $data['gallery_category']=GalleryCategory::where('company_id',$data['company_profile']->id)->get();

        $data_gallery_category = $data['gallery_category'];
				$dataGallery=Gallery::with('gallery_category');
        $dataGallery->where('company_id','=',$data['company_profile']->id);
				$dataGallery->where(function ($dataGallery) use ($data_gallery_category)
				{
				    foreach ($data_gallery_category as $key)
				    {
						        $dataGallery->orWhere('gallery_category_id','=',$key->id);
				    }
				});

				$data['gallery'] = $dataGallery->get();

        return response()->json($data, 200, []);
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function blog(Request $request)
    {
        $slug=$request->get('slug');
        $page=$request->get('page');
        $data=$this->getData($request);
        $data['post_category']=PostCategory::where('company_id','=',$data['company_profile']->id)->orderBy('name','asc')->get();

        if ($slug=='all'){
            $post=Post::with('post_category')->where('company_id','=',$data['company_profile']->id)
                            ->where('type','=','BLOG')
                            ->where('status','=',1)
                            ->orderBy('name','asc')
                            ->paginate(10);
            $post->setPath($data['company_profile']->base_url.'blog/'.$slug);

            $data['post'] =$post->items();
            $data['post_paging'] = (new CustomPaginationLinks( $post))->render();
            // $data['post_paging'] =$post->render();
            // var_dump($data['post']);
        }else{
            $post_category=PostCategory::where('slug','=',$slug)->first();
            $post=Post::with('post_category')->where('company_id','=',$data['company_profile']->id)
                            ->where('status','=',1)
                            ->where('post_category_id','=',$post_category->id)
                            ->where('type','=','BLOG')
                            ->orderBy('name','asc')
                            ->paginate(10);
             $data['post'] =$post->items();
            $data['post_paging'] = (new CustomPaginationLinks( $post))->render();
        }

        $data['sidebar'] =[];
        $setting_menu_sidebar = Setting::where('company_id','=',$data['company_profile']->id)
                                            ->where('name','=','menu_sidebar')
                                            ->first();

        if (is_object($setting_menu_sidebar)){
            $data['sidebar'] = json_decode( $setting_menu_sidebar)->value;

               foreach ($data['sidebar'] as $key => $value) {
                    if ($value->type=='CATEGORIES'){
                        $value->product=Product::with('product_gallery')->where('category_id','=',$value->reference_id)
                                                                     ->where('company_id','=',$data['company_profile']->id)
                                                                     ->get();
                    }
                }

        }

        return response()->json($data, 200, []);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function blog_detail(Request $request)
    {
        $slug=$request->get('slug');
        $data=$this->getData($request);
        $data['post_category']=PostCategory::where('company_id','=',$data['company_profile']->id)->orderBy('name','asc')->get();
        $data['post']=Post::with('post_category')
                        ->where('company_id','=',$data['company_profile']->id)
                        ->where('slug','=',$slug)
                        ->where('status','=',1)
                        ->where('type','=','BLOG')
                        ->first();
        if (is_object($data['post'])){
            $data['post_tags']=PostTags::with('tags')->where('post_id','=',$data['post']->id)->get();
             $data['related_post']=[];
            if ($data['post']->post_category->id){
                $data['related_post']=Post::with('post_category')
                                     ->where('post_category_id','=',$data['post']->post_category->id)
                                    ->where('company_id','=',$data['company_profile']->id)
                                    ->where('status','=',1)
                                    ->where('type','=','BLOG')
                                    ->limit(4)->get();

            }
        }

        $data['sidebar'] =[];
        $setting_menu_sidebar = Setting::where('company_id','=',$data['company_profile']->id)
                                            ->where('name','=','menu_sidebar')
                                            ->first();

        if (is_object($setting_menu_sidebar)){
            $data['sidebar'] = json_decode( $setting_menu_sidebar)->value;

               foreach ($data['sidebar'] as $key => $value) {
                    if ($value->type=='CATEGORIES'){
                        $value->product=Product::with('product_gallery')->where('category_id','=',$value->reference_id)
                                                                     ->where('company_id','=',$data['company_profile']->id)
                                                                     ->get();
                    }
                }

        }


        return response()->json($data, 200, []);
    }


    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function booking(Request $request)
   {
       $data=$this->getData($request);
       $data['booking_data']=Booking::where('id','=',$request->get('booking_id'))
                             ->where('company_id','=',$data['company_profile']->id)->first();
       $data['product_data']=Product::where('id','=',$data['booking_data']->product_id)
                             ->where('company_id','=',$data['company_profile']->id)->first();

       return response()->json($data, 200, []);
   }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function contact(Request $request)
    {
        $data=$this->getData($request);
        return response()->json($data, 200, []);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function contact_store(Request $request)
    {
            $data['name']=$request->json('name');
            $data['email']=$request->json('email');
            $data['phone']=$request->json('phone');
            $data['country']=$request->json('country');
            $data['subject']=$request->json('subject');
            $data['message']=$request->json('message');
            $company=Company::where('code','=',$request->json('company'))->first();
            $data['company_id']=$company->id;
            $data['company_name']=$company->name;
            $data['company_email']=$company->email;
            $data['company_address']=$company->address;
            $data['company_phone']=$company->tlp." / ".$company->phone;
            $data['company_logo']=$company->logo;
            $data['company_domain']=$company->base_url;

            $validator = Validator::make($data, ApiController::$rules_contact);

                if ($validator->fails()) {
                    return response()->json(array(
                        'error' => true,
                        'message' => $validator->errors()->all()),
                        200
                        );
                }else{

                DB::beginTransaction();

                try {



                  //  $status=$this->sentEmail($id->__toString());
                  //email data
                  $data['body']           = 'Booking';
                  $data['to_email']       = $data['email'];
                  $data['to_name']        = $data['name'];
                  $data['from_email']     = env('SENDGRID_FROM_EMAIL');
                  $data['from_name']      = $data['company_name'];
                  $data['replyto_email']  = $data['company_email'];
                  $data['replyto_name']   = $data['company_name'];
                  $data['subject']        = '['.$data['company_name'].'] Contact Us : '.$data['subject'];
                  $data['template_id']    = env('SENDGRID_TEMPLATE_CONTACTUS');
                  $data['link_logo']      = $data['company_logo'];
                  $data['link_click']     = $data['company_domain'];
                  $data['client_domain_name']  = $data['company_domain'];
                  //$data['client_domain_name']  = str_replace("http://","",$data['company_domain']);
                  //$data['client_domain_name']  = str_replace("https://","",$data['client_domain_name']);

                  //avoid null : convert null to ""
                  $data = array_map(function($v){
                      return (is_null($v)) ? "-" : $v;
                  },$data);

                  $data = array_map(function($v){
                      return (is_string($v)) ? $v : (string)$v;
                  },$data);

                  $send_00 = false;

                  //send email
                    $response = Mail::send('email.plain', ['data' => $data['body']], function (Message $message) use ($data) {
                      $message->to($data['to_email'], $data['to_name']);
                      $message->from($data['from_email'], $data['from_name']);
                      $message->setReplyTo($data['replyto_email'], $data['replyto_name']);
                      $message->subject($data['subject']);
                      $message->embedData([
                            'personalizations' => [
                                [
                                    'to' => [
                                        'email' => $data['to_email'],
                                        'name' => $data['to_name'],
                                    ],
                                    'cc' => [
                                        'email' => $data['replyto_email'],
                                        'name' => $data['replyto_name'],
                                    ],
                                    'substitutions' => [
                                        '%msg_name%'            => $data['name'],
                                        '%msg_email%'           => $data['email'],
                                        '%msg_phone%'           => $data['phone'],
                                        '%msg_country%'         => $data['country'],
                                        '%msg_body%'            => $data['message'],
                                        '%client_company_name%' => $data['company_name'],
                                        '%client_email%'        => $data['company_email'],
                                        '%client_address%'      => $data['company_address'],
                                        '%client_phone%'        => $data['company_phone'],
                                        '%client_domain_name%'  => $data['client_domain_name'],
                                        '%link_click%'          => $data['link_click'],
                                        '%link_logo%'           => $data['link_logo'],

                                    ],
                                ],
                            ],
                            'categories' => ['airybook'],
                            'template_id' => $data['template_id'],
                            'custom_args' => [
                                'user_id' => '123'
                            ]
                        ], 'sendgrid/x-smtpapi');
                    });

                    if($response->getStatusCode()=='202' || $response->getStatusCode()=='200'){
                      $send_00 = true;
                    }
                    //end send mail

                     DB::commit();

                     return response()->json(array(
                        'error' => false,
                        'message' => ['Sent Email Success']),
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function booking_page(Request $request)
    {

        $data=$this->getData($request);
        $data['rooms']=Room::with('room_gallery')->where('company_id','=',$data['company_profile']->id)->get();
        $check_in_date  = $request->get('check_in_date');
        $check_out_date = $request->get('check_out_date');
        foreach ($data['rooms'] as $room) {
            $room->room_available = $this->get_room_available($data['company_profile']->id,$room->id,$check_in_date,$check_out_date);
        }
        return response()->json($data, 200, []);
    }


    public static function get_room_available($company_id,$room_type_id,$start_date,$end_date){


      if ($start_date==$end_date){
          $datetime1 = Carbon::parse($end_date);
          $end_date=   $datetime1->addDays(1)->toDateString();
      }

      return  DB::select(" SELECT DISTINCT
                                t_rates.rate_name,
                                t_rates.rates_room_id,
                                tmp_date.datenew,
                                t_room_total.id,
                                COALESCE(t_available.total_available,t_room_total.total_room) AS total_room_available,
                                COALESCE(t_rates_detail.rates,t_rates.rates) AS room_rates
                            FROM tmp_date

                            LEFT JOIN(
                                SELECT *
                            FROM room_available
                            WHERE company_id='".$company_id."'
                            AND room_id='".$room_type_id."'
                            AND dates>='".$start_date."' AND dates <'".$end_date."'
                            ) t_available ON t_available.dates=tmp_date.datenew
                            LEFT JOIN (
                                SELECT room.id,
                                       room.total_available AS total_room
                                FROM room
                                WHERE room.company_id='".$company_id."'
                                AND room.deleted_at IS NULL
                                AND room.id='".$room_type_id."'

                            ) t_room_total ON t_room_total.id='".$room_type_id."'

                            left join (
                            select rates_room.room_type_id, rates.name as rate_name , rates_room.rates, rates_room.id as rates_room_id
                            from rates_room, rates
                            where rates.company_id ='".$company_id."' and rates.id = rates_room.rates_id and
                            rates_room.room_type_id='".$room_type_id."'
                            ) t_rates on t_rates.room_type_id='".$room_type_id."'

                            LEFT JOIN(
                            SELECT * FROM rates_room_detail
                            WHERE dates>='".$start_date."' AND dates <'".$end_date."'
                            ) t_rates_detail ON t_rates_detail.rates_room_id=t_rates.rates_room_id and t_rates_detail.dates=tmp_date.datenew

                            WHERE datenew>='".$start_date."' AND datenew <'".$end_date."'
                            ORDER BY t_rates.rates_room_id, tmp_date.datenew ASC
                                ");
    }

    public static function get_room_rates($company_id,$room_type_id,$start_date,$end_date){


      if ($start_date==$end_date){
          $datetime1 = Carbon::parse($end_date);
          $end_date=   $datetime1->addDays(1)->toDateString();
      }

      /*  */

      return  DB::select(" SELECT DISTINCT tmp_date.datenew,
                                   t_room_total.id,
                                   COALESCE(t_available.total_available,t_room_total.total_room) AS total_room_available
                            FROM tmp_date
                            LEFT JOIN(
                                SELECT *
                            FROM room_available
                            WHERE company_id='".$company_id."'
                            AND room_id='".$room_type_id."'
                            AND dates>='".$start_date."' AND dates <'".$end_date."'
                            ) t_available ON t_available.dates=tmp_date.datenew
                            LEFT JOIN (
                                SELECT room.id,
                                       room.total_available AS total_room
                                FROM room
                                WHERE room.company_id='".$company_id."'
                                AND room.deleted_at IS NULL
                                AND room.id='".$room_type_id."'

                            ) t_room_total ON t_room_total.id='".$room_type_id."'
                            WHERE datenew>='".$start_date."' AND datenew <'".$end_date."'
                            ORDER BY tmp_date.datenew ASC
                                ");
    }



}
