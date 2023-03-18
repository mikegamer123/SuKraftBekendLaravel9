<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
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
            $models = Comment::all();
            $allModels = [];
            $i = 0;
            foreach ($models as $model){
                $allModels[$i]["user"] = User::where("id",$model->userID)->first();
                $allModels[$i]["image"] = Media::where("id",$allModels[$i]["user"]->mediaID)->first();
                $allModels[$i]["post"] = Post::where("id",$model->postID)->first();
                $allModels[$i]["comment"] = $model;
                $i++;
            }
            return $allModels;
        }
        else{
            $model["comment"] = Comment::where('id', $id)->firstOrFail();
            $model["user"] = User::where("id",$model['comment']->userID)->first();
            $model["post"] = Post::where("id",$model['comment']->postID)->first();
            $model["image"] = Media::where('id',$model["user"]->mediaID)->first();
            return $model;
        }
    }

    public function put($id,Request $request){
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }

        $model = Comment::where('id',$id)->first();

        if($request->text){
            $model->text = $request->text;
        }
        if($request->userID){
            $model->userID = $request->userID;
        }
        if($request->postID){
            $model->postID = $request->postID;
        }
        $model->updated_at = now()->toDateTimeString();
        $model->save();
        return response()->json(["Comment ".$model->id. " updated successfully"]);
    }

    public function delete($id,Request $request){
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }

        $comment = Comment::where('id',$id)->first();
        $comment->delete();
        return "Deleted comment by id of ".$id;
    }
    public function add(Request $request){
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }

        $validator = Validator::make($request->all(),[
            'userID' => 'required',
            'postID' => 'required',
            'text' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $model = Comment::create([
            'userId' => $request->userID,
            'postId' => $request->postID,
            'text' => $request->text,
        ]);

        return $model;
    }


}
