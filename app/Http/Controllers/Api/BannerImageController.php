<?php

namespace App\Http\Controllers\Api;

use App\BannerImage;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BannerImageController extends Controller
{

    public  function getAllBannerImages(){
        $bannerImages=BannerImage::all();
        if(!count($bannerImages)==0) {
            foreach ($bannerImages as $banner) {
                $response[] = ['image' => asset('/').$banner->image];
            }
            return \Response::json(['data'=>$response]);
        }
        return \Response::json(['errors'=>['Banners Not Found']],404);


    }
}
