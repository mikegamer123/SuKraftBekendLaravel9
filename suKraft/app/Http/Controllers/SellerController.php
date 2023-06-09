<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Media;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Post;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Seller;
use App\Models\SellerCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use function GuzzleHttp\Promise\all;

class SellerController extends Controller
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
            $models = Seller::all();
            $allModels = [];
            $i = 0;
            foreach ($models as $model) {
                $allModels[$i]["posts"] = Post::where("id", $model->sellerID)->get();
                $allModels[$i]["imageSeller"] = Media::where("id", $model->mediaID)->first();
                $allModels[$i]["user"] = User::where("id", $model->userID)->first();
                $allModels[$i]["imageUser"] = Media::where("id", $allModels[$i]["user"]->mediaID)->first();
                $conn = SellerCategory::where('sellerID', $model->id)->get();
                foreach ($conn as $connModel) {
                    $allModels[$i]["categories"][] = Category::where("id", $connModel->categoryID)->first();
                }
                $allModels[$i]["seller"] = $model;
                $i++;
            }
            return $allModels;
        } else {
            $model["seller"] = Seller::where('id', $id)->firstOrFail();
            $model["posts"] = Post::where("id", $model["seller"]->sellerID)->get();
            $model["imageSeller"] = Media::where("id", $model["seller"]->mediaID)->first();
            $model["user"] = User::where("id", $model["seller"]->userID)->first();
            $model["imageUser"] = Media::where("id", $model["user"]->mediaId)->first();
            $conn = SellerCategory::where('sellerID', $model["seller"]->id)->get();
            foreach ($conn as $connModel) {
                $model["categories"][] = Category::where("id", $connModel->categoryID)->first();
            }
            return $model;
        }
    }

    public function put($id, Request $request)
    {
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }

        $model = Seller::where('id', $id)->firstOrFail();

        if ($request->userID) {
            $model->userID = $request->userID;
        }
        if ($request->name) {
            $model->name = $request->name;
        }
        if ($request->description) {
            $model->description = $request->description;
        }
        if ($request->phoneNo) {
            $model->phoneNo = $request->phoneNo;
        }
        if ($request->brandColors) {
            $model->brandColors = $request->brandColors;
        }
        if ($request->categories) {
            $modelDel = SellerCategory::where('sellerID', $model->id)->get();
            foreach ($modelDel as $delete) {
                $delete->delete();
            }
            foreach ($request->categories as $category) {
                SellerCategory::create([
                    'categoryID' => $category,
                    'sellerID' => $model->id,
                ]);
            }
        }
        $model->updated_at = now()->toDateTimeString();
        $model->save();
        return response()->json(["Seller " . $model->id . " updated successfully"]);
    }

    public function delete($id, Request $request)
    {
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }

        $model = Seller::where('id', $id)->first();
        $modelDel = SellerCategory::where('sellerID', $model->id)->get();
        foreach ($modelDel as $delete) {
            $delete->delete();
        }
        foreach ($request->categories as $category) {
            SellerCategory::create([
                'categoryID' => $category,
                'sellerID' => $model->id,
            ]);
        }
        $model->delete();
        return "Deleted Seller by id of " . $id;
    }

    public function add(Request $request)
    {
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }

        $validator = Validator::make($request->all(), [
            'userID' => 'required',
            'name' => 'required',
            'description' => 'required',
            'brandColors' => 'required|numeric',
            'phoneNo' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $model = Seller::create([
            'userID' => $request->userID,
            'description' => $request->description,
            'name' => $request->name,
            'brandColors' => $request->brandColors,
            'phoneNo' => $request->phoneNo,
        ]);

        if ($request->categories) {
            foreach ($request->categories as $category) {
                SellerCategory::create([
                    'categoryID' => $category,
                    'sellerID' => $model->id,
                ]);
            }
        }

        return $model;
    }

    public function search(Request $request)
    {
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }
        $models = Seller::where('name', 'LIKE', '%' . $request->querySearch . '%')->get();

        if (empty($models)) {
            return [];
        }
        $categoryIDS = [];
        foreach ($models as $model) {
            $categoryIDS[] = SellerCategory::where('sellerID', $model->id)->first();
        }

        if (empty($categoryIDS)) {
            return [];
        }
        $returnValue = [];
        if ($request->categoryID) {
            foreach ($categoryIDS as $categoryID) {
                if(isset($categoryID))
                    if ($categoryID->categoryID == $request->categoryID) {
                        $returnValue['sellers'][] = Seller::where('id', $categoryID->sellerID)->first();
                    }
            }
        }
        else{
            $returnValue['sellers'] = $models;
        }
        return $returnValue;
    }

    public function getOrders(Request $request, int $id)
    {
        $seller = Seller::where('id', $id)->firstOrfail();
        $allOrders = Order::where('sellerID', $seller->id)->get();
        $returnValue = [];
        $i = 0;
        $result = [];
        foreach ($allOrders as $order) {
            $productsIDS = OrderProduct::where('orderID', $order->id)->get('productID')->toArray();
            $count = OrderProduct::where('orderID', $order->id)->get('count');
            $allProducts = [];
            foreach ($productsIDS as $key => $productID) {
                $product = Product::where('id', $productID)->first();
                $product['count'] = $count[$key]->count;
                $allProducts[] = $product;
            }
            $returnValue[$i]['order'] = $order->toArray();
            $returnValue[$i]['products'] = $allProducts;
            $returnValue[$i]['order'] = array_merge($returnValue[$i]['order'], ["products" => $returnValue[$i]['products']]);
            $result [$i] = array_merge($returnValue[$i]['order'], ["products" => $returnValue[$i]['products']]);
            $i++;
        }
        return $result;
    }
}
