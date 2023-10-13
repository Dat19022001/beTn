<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CateController extends Controller
{
    public function addCate(Request $request){
        $data = $request -> json() -> all();
        $validator = Validator::make($data, [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        try {
            $category = Categories::create([
                "name" => $data["name"],
            ]);
            return response()->json(['message' => 'Add category successfully', 'category' => $category], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'add fail product'], 400);
        }
    }
    public function getCate(){
        $categories = Categories::all();
        return response()-> json(['categories'=> $categories]);
    }
}
