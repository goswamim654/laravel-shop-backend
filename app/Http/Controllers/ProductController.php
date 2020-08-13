<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductsImage;
use Image;
use App\User;
use DB;

class ProductController extends Controller
{
    // public function __construct() {
    //     $this->middleware('auth');
    // }

    public function uploadMultipleImages(Request $request) {

        $upload_path_original = storage_path('/app/public/uploads/products/Original');
        $upload_path_thumbnail = storage_path('/app/public/uploads/products/Thumbnail');
        // $upload_path_original = public_path('/uploads/products/Original');
        // $upload_path_thumbnail = public_path('/uploads/products/Thumbnail');

        $uploaded_files = $request->images;
        $request->validate([
            'images.*' => 'required|mimes:jpg,jpeg,png,bmp|max:2048'
        ]);

        foreach($uploaded_files as $image) {
          
            $file_name = $image->getClientOriginalName();
            $resize_image = Image::make($image->getRealPath());
            $resize_image->fit(1980,1280)->save($upload_path_original.'/'.$file_name);    //resizing the original image to get a smaller image with fixed dimension

            $resize_image->resize(300,null,function($constraint)
            {
                $constraint->aspectRatio();
            })->save($upload_path_thumbnail.'/'.$file_name);    //resizing the original image for use as a thumbnail
            // $image->move($upload_path_original, $file_name);            
            
            $prod_img = new ProductsImage();
            $prod_img->image_name = $file_name;
            $prod_img->save();
        }
       return response()->json(['message'=>"File Uploaded"]);

     
    }

    public function getImages() {
        
        $images = ProductsImage::all();
        $imageNames = [];

        foreach($images as $image) {
        //    print_r($image['image_name']);
            // $imageNames[] = asset('storage/uploads/products/Thumbnail').'/'.$image['image_name'];
            $imageNames[] = $image['image_name'];

        }

        // print_r($imageNames);
        return response()->json($imageNames);

    }

    public function getImage($image_name) {
        $image = DB::table('products_images')->where('image_name',$image_name)->get();
        return response()->json($image);
    }

}
