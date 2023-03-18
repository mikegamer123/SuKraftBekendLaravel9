<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Media;
use App\Models\Post;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LikeController extends Controller
{
    public function declareAdmin(Request $request)
    {

        $token = $request->bearerToken();
        $user = User::where('api_token', $token)->firstOrFail();
        if ($user->userType == 'admin') {
            return true;
        } else {
            return false;
        }
    }

    public function get(Request $request, $id = 0)
    {
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }

        if ($id == 0) {
            $models = Like::all();
            $allModels = [];
            $i = 0;
            foreach ($models as $model) {
                $allModels[$i]["user"] = User::where("id", $model->userID)->first();
                $allModels[$i]["seller"] = Seller::where("userID", $allModels[$i]["user"]->id ?? 0)->first();
                $allModels[$i]["post"] = Post::where("sellerID", $allModels[$i]["seller"]->id ?? 0)->first();
                $allModels[$i]["media"] = Media::where("id", $allModels[$i]["seller"]->userID ?? 0)->first();
                $allModels[$i]["like"] = $model;
                $i++;
            }
            return $allModels;
        } else {
            $model["like"] = Like::where('id', $id)->firstOrFail();
            $model["user"] = User::where("id", $model['like']->userID ?? 0)->first();
            $model["seller"] = Seller::where("id", $model['like']->id ?? 0)->first();
            $model["media"] = Media::where('id', $model["seller"]->mediaID ?? 0)->first();
            return $model;
        }
    }

    public function put($id, Request $request)
    {
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }

        $model = Like::where('id', $id)->first();

        if ($request->userID) {
            $model->userID = $request->userID;
        }
        if ($request->postID) {
            $model->postID = $request->postID;
        }

        $model->updated_at = now()->toDateTimeString();
        $model->save();
        return response()->json(["Like " . $model->id . " updated successfully"]);
    }

    public function add(Request $request)
    {
        //        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }

        $validator = Validator::make($request->all(), [
            'userID' => 'required',
            'postID' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $model = Like::create([
            'userId' => $request->userID,
            'postId' => $request->postID,
        ]);

        return $model;
    }

    public function delete($id, Request $request)
    {
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }

        $comment = Like::where('id', $id)->first();
        $comment->delete();
        return "Deleted comment by id of " . $id;
    }

}
