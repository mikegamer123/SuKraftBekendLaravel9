<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Media;
use App\Models\Post;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function declareAdmin(Request $request){

        $token = $request->bearerToken();
        $user = User::where('api_token', $token)->firstOrFail();
        if($user->userType == 'admin'){
            return true;
        }
        else{
            return false;
        }
    }

    public function get(Request $request,$id = 0){
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }

        if($id == 0){
            $models = Post::all();
            $allModels = [];
            $i = 0;
            foreach ($models as $model){
                $allModels[$i]["seller"] = Seller::where("id",$model->sellerID)->first();
                $allModels[$i]["user"] = User::where("id",$allModels[$i]["seller"]->userID)->first();
                $allModels[$i]["imageUser"] = Media::where("id",$allModels[$i]["user"]->mediaID)->first();
                $allModels[$i]["imageSeller"] = Media::where("id",$allModels[$i]["seller"]->mediaID)->first();
                $allModels[$i]["imagePost"] = Media::where("id",$model->mediaID)->first();
                $allModels[$i]["post"] = $model;
                $i++;
            }
            return $allModels;
        }
        else{
            $model["post"] = Post::where('id', $id)->firstOrFail();
            $model["seller"] = Seller::where("id",$model['post']->sellerID)->first();
            $model["user"] = User::where("id",$model["seller"]->userID)->first();
            $model["imageUser"] = Media::where("id",$model["user"]->mediaID)->first();
            $model["imageSeller"] = Media::where("id",$model["seller"]->mediaID)->first();
            $model["imagePost"] = Media::where("id",$model["post"]->mediaID)->first();
            return $model;
        }
    }

    public function put($id,Request $request){
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }

        $model = Post::where('id',$id)->first();

        if($request->description){
            $model->description = $request->description;
        }
        if($request->sellerID){
            $model->sellerID = $request->sellerID;
        }
        $model->updated_at = now()->toDateTimeString();
        $model->save();
        return response()->json(["Post ".$model->id. " updated successfully"]);
    }

    public function delete($id,Request $request){
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }

        $comment = Comment::where('id',$id)->first();
        $comment->delete();
        return "Deleted Post by id of ".$id;
    }
    public function add(Request $request){
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }

        $validator = Validator::make($request->all(),[
            'sellerID' => 'required',
            'description' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $model = Post::create([
            'sellerID' => $request->sellerID,
            'description' => $request->description,
        ]);

        return $model;
    }
}
