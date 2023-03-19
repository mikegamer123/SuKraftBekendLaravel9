<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
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
            $models = Review::all();
            $allModels = [];
            $i = 0;
            foreach ($models as $model) {
                $allModels[$i]["product"] = Product::where("id", $model->productID)->first();
                $allModels[$i]["user"] = User::where("id", $model->userID)->first();
                $allModels[$i]["review"] = $model;
                $i++;
            }
            return $allModels;
        } else {
            $model["review"] = Review::where('id', $id)->firstOrFail();
            $model["product"] = Product::where("id", $model["review"]->productID)->first();
            $model["image"] = User::where("id", $model["review"]->userID)->first();
            return $model;
        }
    }

    public function getByProductId(Request $request, $id = 0)
    {
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }

        $models = Review::where('productID', $id)->get();
        $allModels = [];
        $i = 0;
        foreach ($models as $model) {
            $allModels[$i]["user"] = User::where("id", $model->userID)->first();
            $allModels[$i]["review"] = $model;
            $i++;
        }
        return $allModels;
    }

    public function put($id, Request $request)
    {
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }

        $model = Review::where('id', $id)->firstOrFail();

        if ($request->productID) {
            $model->productID = $request->productID;
        }
        if ($request->userID) {
            $model->userID = $request->userID;
        }
        if ($request->reviewText) {
            $model->reviewText = $request->reviewText;
        }
        if ($request->rating) {
            $model->rating = $request->rating;
        }
        $model->updated_at = now()->toDateTimeString();
        $model->save();
        return response()->json(["Review " . $model->id . " updated successfully"]);
    }

    public function delete($id, Request $request)
    {
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }

        $model = Review::where('id', $id)->first();
        $model->delete();
        return "Deleted Review by id of " . $id;
    }

    public function add(Request $request)
    {
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }

        $validator = Validator::make($request->all(), [
            'productID' => 'required',
            'userID' => 'required',
            'reviewText' => 'required',
            'rating' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $model = Review::create([
            'productID' => $request->productID,
            'userID' => $request->userID,
            'reviewText' => $request->reviewText,
            'rating' => $request->rating,
        ]);

        return $model;
    }
}
