<?php

namespace App\Helpers;
use App\Eom;
use App\Company;
use DB;
use Carbon\Carbon;
use Uuid;
use Storage;
use Image;
use App\Http\Requests;
use Cloudinary\Api;
use Cloudinary\Uploader;
use Cloudinary;

class PdfHelper {

    
    public static function upload_image($request, $imagename,$company_code,$isResize=true) {

        require_once(dirname(__FILE__) . '/../../cloudinary_php-master/src/Cloudinary.php');
        require_once(dirname(__FILE__) . '/../../cloudinary_php-master/src/Uploader.php');
        require_once(dirname(__FILE__) . '/../../cloudinary_php-master/src/Api.php');
        // $a = 'Alhamdulillah.. :)';

        try{
            $image = $request->file('file');
            // $filename  = $imagename. '.' . $image->getClientOriginalExtension();
            $path = str_replace(' ', '-', $imagename);
            $filename  = $path;
            // $img = Image::make($image->getRealPath());
            // $path =$image->getRealPath();
            $temp_path =$image->getRealPath();

            Cloudinary::config(array( 
                "cloud_name" => getenv('CLOUDINARY_CLOUD_NAME'), 
                "api_key" => getenv('CLOUDINARY_API_KEY'), 
                "api_secret" => getenv('CLOUDINARY_API_SECRET') 
              ));
            // $a = Uploader::upload($path, array("public_id" => "sample_id"));
            $company_id=$request->company_id;
            // $a = Uploader::upload($path, array('folder' => "upload/".$company_id.'/' , "use_filename" => TRUE));
            
            // ($path.$filename);
            // $a = $path.$filename;
            // $a = $path;

            // $image_normal =$img;
            // $image_thumb = Image::make($path)->fit(360,260, function ($constraint) {
            //     $constraint->aspectRatio();
            //     $constraint->upsize();
            // });
            // $image_normal = $image_normal->stream();
            // $image_thumb = $image_thumb->stream();

            $now = Carbon::now();
            // $upload_path = 'uploads/'.$company_code.'/'.$now->year.'/'.$now->month.'/';
            $upload_path = $company_id.'-'.$now->year.'-'.$now->month.'-';
            // Storage::disk('s3')->getDriver()->put($path.$filename, $image_normal->__toString(), [ 'visibility' => 'public', 'CacheControl' => 'max_age=2592000']);
            // Storage::disk('s3')->getDriver()->put($path.'thumbnails/'.$filename, $image_thumb->__toString(), [ 'visibility' => 'public', 'CacheControl' => 'max_age=2592000']);

            // Storage::disk('s3')->put($path.$filename, $image_normal->__toString(), 'public');
            // Storage::disk('s3')->put($path.'thumbnails/'.$filename, $image_thumb->__toString(), 'public');
           
            $uploader = $image->move('uploads/pdf', 'v-'.$upload_path.$filename.'.'.$image->getClientOriginalExtension());
            
            // $a = Uploader::upload($image_normal, array("public_id" => "sample_id"));
            

            // $a = $image_normal;
           
            $obj['error']=false;
            $obj['image']='/uploads/pdf/v-'.$upload_path.$filename.'.'.$image->getClientOriginalExtension();
            $obj['image_thumb']='/uploads/pdf/v-'.$upload_path.$filename.'.'.$image->getClientOriginalExtension();
            $obj['original_image_id']="sample_id";
            $obj['dev_data']=$uploader;
            $obj['company_id_data']=$company_id;

            return $obj;

        } catch (\Exception $e) {
            $data['error']=true;
            $data['message']=$e->getMessage();
            return $data;
        }


    }


    public static function deleteimage($image_public_id = '') {

        require_once(dirname(__FILE__) . '/../../cloudinary_php-master/src/Cloudinary.php');
        require_once(dirname(__FILE__) . '/../../cloudinary_php-master/src/Uploader.php');
        require_once(dirname(__FILE__) . '/../../cloudinary_php-master/src/Api.php');
        // $a = 'Alhamdulillah.. :)';

        Cloudinary::config(array( 
            "cloud_name" => getenv('CLOUDINARY_CLOUD_NAME'), 
            "api_key" => getenv('CLOUDINARY_API_KEY'), 
            "api_secret" => getenv('CLOUDINARY_API_SECRET') 
          ));

        try{
            $api = new Api;
            $test = $api->delete_resources(array($image_public_id));
            $obj['error']=false;
            $obj['test'] = $test;
            return $obj;
        }
        catch (\Exception $e) {
            $data['error']=true;
            $data['message']=$e->getMessage();
            return $data;
        }
    }

    public static function get_cdi_base(){
        return getenv('S3_URL');
    }
}
