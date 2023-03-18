<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
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
    public function get(Request $request, $id = 0){
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }

        if($id == 0){
            $model = Category::all();
            return $model;
        }
        else{
            $model = Category::where('id', $id)->firstOrFail();
            return $model;
        }
    }

    public function put($id,Request $request){
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }

        $model = Category::where('id',$id)->first();

        if($request->name){
            $model->name = $request->name;
        }
        if($request->type){
            $model->type = $request->type;
        }

        $model->updated_at = now()->toDateTimeString();
        $model->save();
        return "Category ".$model->name. " updated successfully";
    }

    public function add(Request $request){
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }

        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $model = Category::create([
            'name' => $request->name,
            'type' => $request->type,
        ]);

        return $model;
    }

    public function delete($id,Request $request){
//        if(!$this->declareAdmin($request)){
//            return "Unathorized";
//        }

        $model = Category::where('id',$id)->first();
        $model->delete();
        return "Deleted category ".$model->name." by id of ".$id;
    }
}
