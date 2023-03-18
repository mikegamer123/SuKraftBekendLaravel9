<?php

namespace App\Http\Controllers;

use App\Models\Follower;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FollowerController extends Controller
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

    public function get(int $id)
    {
        //        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }
        if ($id == 0) {
            $models = Follower::all();
            $allModels = [];
            $i = 0;
            foreach ($models as $model) {
                $allModels[$i]["user"] = User::where("id", $model->userID)->first();
                $allModels[$i]["seller"] = Seller::where("id", $model->userID)->first();
                $i++;
            }
            return $allModels;
        } else {
            $model["user"] = User::where('id', $id)->firstOrFail();
            $model["seller"] = seller::where('id', $model['user']->id)->first();
            return $model;
        }
    }

    public function put(int $id, Request $request)
    {
        //        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }
        $follower = Follower::with('user')->where('id', $id)->firstOrFail();

        if ($request->userID) {
            $follower->userID = $request->userID;
        }
        if ($request->sellerID) {
            $follower->sellerID = $request->sellerID;
        }
        $follower->updated_at = now()->toDateTimeString();
        $follower->save();
        return "Follower " . $follower->user->firstName . " " . $follower->user->lastName . " updated successfully";
    }

    public function delete(int $id)
    {
        //        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }
        $follower = Follower::with('user')->where('id', $id)->firstOrFail();
        $follower->delete();
        return "Deleted follower " . $follower->user->firstName . " " . $follower->user->lastName . " with id of " . $id;
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userID' => 'required',
            'sellerID' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $model = Follower::create([
            'userId' => $request->userID,
            'sellerID' => $request->sellerID,
        ]);
        return $model;
    }
}
