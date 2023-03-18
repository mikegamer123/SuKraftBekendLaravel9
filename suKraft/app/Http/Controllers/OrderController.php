<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
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
            $models = Order::all();
            $allModels = [];
            $i = 0;
            foreach ($models as $model) {
                $allModels[$i]["user"] = User::where("id", $model->userID)->first();
                $allModels[$i]["seller"] = Seller::where("id", $model->sellerID)->first();
                $conn = OrderProduct::where('productID', $model->id)->get();
                foreach ($conn as $connModel) {
                    $allModels[$i]["product"][] = Product::where("id", $connModel->productID)->first();
                }
                $allModels[$i]["order"] = $model;
                $i++;
            }
            return $allModels;
        } else {
            $model["order"] = Order::where('id', $id)->firstOrFail();
            $model["user"] = User::where("id", $model["order"]->userID)->first();
            $model["seller"] = Seller::where("id", $model["order"]->sellerID)->first();
            $conn = OrderProduct::where('orderID', $model["order"]->id)->get();
            foreach ($conn as $connModel) {
                $model["product"][] = Product::where("id", $connModel->productID)->first();
            }
            return $model;
        }
    }

    public function put($id, Request $request)
    {
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }

        $model = Order::where('id', $id)->firstOrFail();

        if ($request->address) {
            $model->address = $request->address;
        }
        if ($request->email) {
            $model->email = $request->email;
        }
        if ($request->firstName) {
            $model->firstName = $request->firstName;
        }
        if ($request->lastName) {
            $model->lastName = $request->lastName;
        }
        if ($request->phone) {
            $model->phone = $request->phone;
        }
        if ($request->userID) {
            $model->userID = $request->userID;
        }
        if ($request->sellerID) {
            $model->sellerID = $request->sellerID;
        }
        if ($request->description) {
            $model->description = $request->description;
        }
        if ($request->products) {
            $modelDel = OrderProduct::where('orderID', $model->id)->get();
            foreach ($modelDel as $delete) {
                $delete->delete();
            }
            $i = 0;
            foreach ($request->products as $product) {
                OrderProduct::create([
                    'orderID' => $model->id,
                    'productID' => $product,
                    'count' => $request->counts[$i],
                ]);
                $i++;
            }
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
        $order = Order::where('id', $id)->first();
        $modelDel = OrderProduct::where('orderID', $order->id)->get();
        foreach ($modelDel as $delete) {
            $delete->delete();
        }
        $order->delete();
        return "Deleted order by id of " . $id;
    }

    public function add(Request $request)
    {
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }

        $validator = Validator::make($request->all(), [
            'address' => 'required',
            'email' => 'required',
            'firstName' => 'required',
            'lastName' => 'required',
            'phone' => 'required',
            'userID' => 'required',
            'sellerID' => 'required',
            'description' => 'required',
            'counts' => 'required',
            'products' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $model = Order::create([
            'address' => $request->address,
            'email' => $request->email,
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'phone' => $request->phone,
            'userID' => $request->userID,
            'sellerID' => $request->sellerID,
            'description' => $request->description,
        ]);

        if ($request->products and $request->counts) {
            $i = 0;
            foreach ($request->products as $product) {
                OrderProduct::create([
                    'productID' => $product,
                    'orderID' => $model->id,
                    'count' => $request->counts[$i],
                ]);
                $i++;
            }
        }

        return $model;
    }
}
