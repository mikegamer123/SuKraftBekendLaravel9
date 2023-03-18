<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Post;
use App\Models\PostProduct;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostProductController extends Controller
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
            $models = PostProduct::all();
            $allModels = [];
            $i = 0;
            foreach ($models as $model) {
                $allModels[$i]["postProducts"] = PostProduct::where("id", $model->id)->first();
                $allModels[$i]["posts"] = Post::where("id", $allModels[$i]["postProducts"]->postID)->first();
                $allModels[$i]["products"] = Product::where("id", $allModels[$i]["postProducts"]->productID)->first();
                $allModels[$i]["seller"] = Seller::where("id", $allModels[$i]["products"]->sellerID)->first();
                $allModels[$i]["media"] = Media::where("id", $allModels[$i]["posts"]->mediaID)->first();

                $i++;
            }
            return $allModels;
        } else {
            $model["postProducts"] = PostProduct::where('id', $id)->firstOrFail();
            $model["posts"] = Post::where("id", $model["postProducts"]->postID)->first();
            $model["products"] = Product::where("id", $model["postProducts"]->productID)->first();
            $model["seller"] = Seller::where("id", $model["products"]->sellerID)->first();
            $model["media"] = Media::where("id", $model["seller"]->mediaID)->first();
            return $model;
        }
    }

    public function put($id, Request $request)
    {
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }

        $model = PostProduct::where('id', $id)->firstOrFail();

        if ($request->postID) {
            $model->postID = $request->postID;
        }
        if ($request->productID) {
            $model->productID = $request->productID;
        }

        $model->updated_at = now()->toDateTimeString();
        $model->save();
        return response()->json(["Order " . $model->id . " updated successfully"]);
    }

    public function delete($id, Request $request)
    {
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }
        $model = PostProduct::where('id', $id)->firstOrfail();
        $model->delete();
        return "Deleted PostProduct by id of " . $id;
    }

    public function add(Request $request)
    {
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }

        $validator = Validator::make($request->all(), [
            'postID' => 'required',
            'productID' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $model = PostProduct::create([
            'postID' => $request->postID,
            'productID' => $request->productID,
        ]);

        return $model;
    }



}
