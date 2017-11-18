<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Model\Image;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

use Validator;

class ImageController extends Controller
{
    private $payload_list = array("list_image" => null, "page" => 1);
    private $payload_info = array("image" => null);

	function upload(Request $request)
	{
        $v = Validator::make($request->all(), [
            'image' => 'required'
        ]);

        if ($v->fails())
        {
        	$results = errorResult("Image required", $this->payload_list);
        } else 
        {
            if (Input::file('image')->isValid()) 
            {
                $destinationPath = getenv('CDN_PATH');
                $extension = Input::file('image')->getClientOriginalExtension();
                $filesize = Input::file('image')->getSize();
                $filename = Auth::id() . '_' . md5(time()) . '.' . strtolower($extension);
                try
                {
                    if ( is_writable($destinationPath) )
                    {
                        if ( Input::file('image')->move($destinationPath, $filename) )
                        {
                            $images = ['users_id' => Auth::id(), 'filename' => $filename, 'filetype' => $extension, 'filesize' => $filesize ];
                            

                            //upload to cloud
                            $cloudinary_config = \Cloudinary::config(array( 
                                "cloud_name" => getenv('CLOUDINARY'), 
                                "api_key" => getenv('CLOUDINARY_KEY'), 
                                "api_secret" => getenv('CLOUDINARY_SECRET') 
                            ));
                            if ( $cloudinary_upload = \Cloudinary\Uploader::upload( $destinationPath . $filename ) )
                            {
                                $images['url_cloud'] = $cloudinary_upload['url'];
                            }

                            Image::create($images);
                            
                            $this->payload_info['image'] = $images;

                            $results = successResult("Upload success", $this->payload_info);
                        } else
                        {
                            $results = errorResult("Can't process file, upload failed", $this->payload_info);
                        }
                        
                    } else
                    {
                        $results = errorResult("Can't process file, destination ($destinationPath) is not writeable", $this->payload_info);
                    }
                } catch (\Exception $e)
                {
                	$results = errorResult("Can't process file, please try again later", $this->payload_info);
                }
            } else 
            {
        		$results = errorResult("Can't process file, image not valid", $this->payload_info);
            }
        }

        return $results;
	}
}

        