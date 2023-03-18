<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Media;
use App\Models\Post;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
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
            $models = Product::all();
            $allModels = [];
            $i = 0;
            foreach ($models as $model) {
                $allModels[$i]["seller"] = Seller::where("id", $model->sellerID)->first();
                $allModels[$i]["image"] = Media::where("id", $model->mediaID)->first();
                $conn = ProductCategory::where('productID', $model->id)->get();
                foreach ($conn as $connModel) {
                    $allModels[$i]["categories"][] = Category::where("id", $connModel->categoryID)->first();
                }
                $allModels[$i]["product"] = $model;
                $i++;
            }
            return $allModels;
        } else {
            $model["product"] = Product::where('id', $id)->firstOrFail();
            $model["seller"] = Seller::where("id", $model["product"]->sellerID)->first();
            $model["image"] = Media::where("id", $model["product"]->mediaID)->first();
            $conn = ProductCategory::where('productID', $model["product"]->id)->get();
            foreach ($conn as $connModel) {
                $model["categories"][] = Category::where("id", $connModel->categoryID)->first();
            }
            return $model;
        }
    }

    public function getBySeller(Request $request, $id)
    {
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }
            $models = Product::where('sellerID',$id)->get();
            $allModels = [];
            $i = 0;
            foreach ($models as $model) {
                $allModels[$i]["seller"] = Seller::where("id", $model->sellerID)->first();
                $allModels[$i]["image"] = Media::where("id", $model->mediaID)->first();
                $conn = ProductCategory::where('productID', $model->id)->get();
                foreach ($conn as $connModel) {
                    $allModels[$i]["categories"][] = Category::where("id", $connModel->categoryID)->first();
                }
                $allModels[$i]["product"] = $model;
                $i++;
            }
            return $allModels;
    }

    public function put($id, Request $request)
    {
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }

        $model = Product::where('id', $id)->firstOrFail();

        if ($request->sellerID) {
            $model->sellerID = $request->sellerID;
        }
        if ($request->mediaID) {
            $model->mediaID = $request->mediaID;
        }
        if ($request->name) {
            $model->name = $request->name;
        }
        if ($request->description) {
            $model->description = $request->description;
        }
        if ($request->price) {
            $model->price = $request->price;
        }
        if ($request->salePrice) {
            $model->salePrice = $request->salePrice;
        }
        if ($request->isAvailable) {
            $model->isAvailable = $request->isAvailable;
        }
        if ($request->categories) {
            $modelDel = ProductCategory::where('productID', $model->id)->get();
            foreach ($modelDel as $delete) {
                $delete->delete();
            }
            foreach ($request->categories as $category) {
                ProductCategory::create([
                    'categoryID' => $category,
                    'productID' => $model->id,
                ]);
            }
        }
        $model->updated_at = now()->toDateTimeString();
        $model->save();
        return response()->json(["Product " . $model->id . " updated successfully"]);
    }

    public function delete($id, Request $request)
    {
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }

        $model = Product::where('id', $id)->first();
        $modelDel = ProductCategory::where('productID', $model->id)->get();
        foreach ($modelDel as $delete) {
            $delete->delete();
        }
        foreach ($request->categories as $category) {
            ProductCategory::create([
                'categoryID' => $category,
                'productID' => $model->id,
            ]);
        }
        $model->delete();
        return "Deleted Product by id of " . $id;
    }

    public function search(Request $request)
    {
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }
        $models = Product::where('name', 'LIKE', '%'.$request->querySearch.'%')->get();
        $categoryIDS = "";
        foreach ($models as $model){
            $categoryIDS = ProductCategory::where('productID',$model->id)->get();
        }
        $returnValue = [];
        if ($request->categoryID){
            foreach ($categoryIDS as $categoryID){
                if ($categoryID->categoryID == $request->categoryID){
                    $returnValue ['products'] = $models;
                }
            }
            return $returnValue;
        }
        $returnValue['products']=$models;
        return $returnValue;
    }

    public function add(Request $request)
    {
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }

        $validator = Validator::make($request->all(), [
            'sellerID' => 'required',
            'description' => 'required',
            'name' => 'required',
            'price' => 'required|numeric',
            'salePrice' => 'required|numeric',
            'isAvailable' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $model = Product::create([
            'sellerID' => $request->sellerID,
            'description' => $request->description,
            'name' => $request->name,
            'price' => $request->price,
            'salePrice' => $request->salePrice,
            'isAvailable' => $request->isAvailable,
        ]);

        if ($request->categories) {
            foreach ($request->categories as $category) {
                ProductCategory::create([
                    'categoryID' => $category,
                    'productID' => $model->id,
                ]);
            }
        }

        return $model;
    }
}
