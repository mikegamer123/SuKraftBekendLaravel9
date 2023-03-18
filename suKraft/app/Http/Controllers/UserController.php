<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Str;

class UserController extends Controller
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

    public function getUsers(Request $request,$id = 0){
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }

        if($id == 0){
            $models = User::all();
            $allModels = [];
            $i = 0;
            foreach ($models as $model){
                $allModels[$i]["image"] = Media::where("id",$model->mediaId)->first();
                $allModels[$i]["user"] = $model;
                $i++;
            }
            return $allModels;
        }
        else{
            $user["user"] = User::where('id', $id)->firstOrFail();
            $user["image"] = Media::where('id',$user["user"]->mediaId)->first();
            return $user;
        }
    }

    public function putUsers($id,Request $request){
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }

        $user = User::where('id',$id)->first();

        if($request->firstName){
            $user->firstName = $request->firstName;
        }
        if($request->lastName){
            $user->lastName = $request->lastName;
        }
        if($request->username){
            $user->username = $request->username;
        }
        if($request->phoneNo){
            $user->phoneNo = $request->phoneNo;
        }
        if($request->email){
            $user->email = $request->email;
        }
        if(isset($request->verified)){
            $user->verified = $request->verified;
        }

        $user->updated_at = now()->toDateTimeString();
        $user->save();
        return response()->json(["User ".$user->email. " updated successfully"]);
    }

    public function deleteUsers($id,Request $request){
        if(!$this->declareAdmin($request)){
            return "Unathorized";
        }

        $user = User::where('id',$id)->first();
        $user->delete();
        return "Deleted user ".$user->email." by id of ".$id;
    }
}
