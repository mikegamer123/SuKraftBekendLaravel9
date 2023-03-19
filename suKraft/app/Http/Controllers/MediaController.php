<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Media;
use App\Http\Requests\StoreMediaRequest;
use App\Http\Requests\UpdateMediaRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{

    public function get(Request $request, $id){
        $media = Media::where('id',$id)->firstOrFail();
        return $media;
    }

    public function mediaCreate(Request $request, $type, $id)
    {
        if ($request->hasFile('mediaUpload')) {
            $request->validate(['mediaUpload' => 'mimes:jpg,jpeg,png,mp4|max:20000'], ['mediaUpload.*mimes' => 'Dozvoljeni formati su: jpg, jpeg, png, mp4.', 'mediaUpload.*max' => 'Velicina fajla je prevelika, max je 20MB']);
            $path = Storage::disk('local')->put('/public', $request->file("mediaUpload"));
            $split_path = explode("/", $path);
            $typeOfMedia = explode(".", $split_path[1])[1];

            $image = Media::create([
                'srcUrl' => "storage/" . $split_path[1],
                'name' => $split_path[1],
                'type' => $typeOfMedia == 'mp4' ? "video" : 'image',
            ]);
            $imgModel = null;
            switch ($type) {
                case 'posts':
                    $imgModel = Post::where('id', $id)->first();
                    $imgModel->mediaID = $image->id;
                    $imgModel->save();
                    break;
                case 'users':
                    $imgModel = User::where('id', $id)->first();
                    $imgModel->mediaId = $image->id;
                    $imgModel->save();
                    break;
                case 'sellers':
                    $imgModel = Seller::where('id', $id)->first();
                    $imgModel->mediaID = $image->id;
                    $imgModel->save();
                    break;
                case 'products':
                    $imgModel = Product::where('id', $id)->first();
                    $imgModel->mediaID = $image->id;
                    $imgModel->save();
                    break;
                default:
                    return "not valid type for image";
            }
            return $image;
        }
        return "no file";
    }
}
