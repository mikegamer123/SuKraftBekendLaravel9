<?php

namespace App\Http\Controllers;

use App\Models\Messages;
use App\Models\Seller;
use App\Models\User;
use http\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class MessagesController extends Controller
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
            $models = Messages::all();
            $allModels = [];
            $i = 0;
            foreach ($models as $model) {
                $allModels[$i]["user"] = User::where("id", $model->userID)->first();
                $allModels[$i]["seller"] = Seller::where("id", $model->sellerID)->first();
                $allModels[$i]["message"] = $model;
                $i++;
            }
            return $allModels;
        } else {
            $model["message"] = Messages::where('id', $id)->firstOrFail();
            $model["user"] = User::where("id", $model['message']->userID)->first();
            $model["seller"] = Seller::where("id", $model['message']->sellerID)->first();
            return $model;
        }
    }

    public function put($id, Request $request)
    {
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }

        $model = Messages::where('id', $id)->first();

        if ($request->text) {
            $model->text = $request->text;
        }
        if ($request->userID) {
            $model->userID = $request->userID;
        }
        if ($request->sellerID) {
            $model->sellerID = $request->sellerID;
        }
        $model->updated_at = now()->toDateTimeString();
        $model->save();
        return response()->json(["Comment " . $model->id . " updated successfully"]);
    }

    public function delete($id, Request $request)
    {
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }

        $comment = Messages::where('id', $id)->first();
        $comment->delete();
        return "Deleted comment by id of " . $id;
    }

    public function add(Request $request)
    {
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }

        $validator = Validator::make($request->all(), [
            'userID' => 'required',
            'sellerID' => 'required',
            'text' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $model = Messages::create([
            'userId' => $request->userID,
            'sellerID' => $request->sellerID,
            'text' => $request->text,
        ]);

        return $model;
    }
}
