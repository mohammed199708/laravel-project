<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



class productsController extends Controller
{
    //for testing some functionalities
    public function testFuncs(){
        return response()->json(['status'=>'True']);
    }

    //add a product to the DB
    public function addProduct(Request $product){
        $product->validate([
            'productName' => 'required',
            'image' => 'required',
            'expirDate' => 'required',
            'type'=>'required',
            'contactInformation'=>'required',
            'avaliableAmount'=>'required',
            'price'=>'required'
        ]);
        try {
            //get the user info
            $user = Auth::user();
        DB::table('products')->insert(
            array(
                'productName' => $product->input('productName'),
                'image' => $product->input('image'),
                'expirDate' => $product->input('expirDate'),
                'type'=> $product->input('type'),
                'contactInformation'=> $product->input('contactInformation'),
                'avaliableAmount'=>$product->input('avaliableAmount'),
                'price'=>$product->input('price'),
                'owner'=>$user->name
            )
       );
       return response()->json(['status'=>'success!']);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status'=>'error: '.$th]);

        }
    }
    //delete owned product by productName
    public function deleteProduct(Request $request){
        //get user info
        $user=Auth::user();
        //get productName
        $productName = $request->input('productName');
        //compare the product owner with the username
        $owner = DB::table('products')->select('owner')->where('productName',$productName)->get();
        if ($owner[0]->owner === $user->name) {
            DB::table('products')->where('productName',$productName)->delete();
            return response()->json(['status'=>'success!']);
        }
        else{
            return response()->json(['status'=>'you are not permitted for this action','owner'=>$owner[0]->owner,'you'=>$user->name]);
        }
    }
    //update product owned by user
    public function updateProduct(Request $request){
        //check the params
        $request->validate([
            'productName' => 'required',
            'image' => 'required',
            'type'=>'required',
            'contactInformation'=>'required',
            'avaliableAmount'=>'required',
            'price'=>'required'
        ]);
        //get user info
        $user=Auth::user();
        //get productName
        $productName = $request->input('productName');
        //compare the product owner with the username
        $owner = DB::table('products')->select('owner')->where('productName',$productName)->get();
        if ($owner[0]->owner === $user->name) {
            DB::table('products')->where('productName',$productName)->update([
                'productName' => $request->input('productName'),
                'image' => $request->input('image'),
                'type'=> $request->input('type'),
                'contactInformation'=> $request->input('contactInformation'),
                'avaliableAmount'=>$request->input('avaliableAmount'),
                'price'=>$request->input('price')
            ]);
            return response()->json(['status'=>'updated!']);
        }
        else{
            return response()->json(['status'=>'you are not permitted for this action','owner'=>$owner[0]->owner,'you'=>$user->name]);
        }
    }
    //get all products
    public function getProducts(){
        $products = DB::table('products')->select('productName','image','expirDate','type','contactInformation','avaliableAmount','price')->get();
        $products_array = array();
        $i=0;
        foreach ($products as $product) {
            $products_array[$i] = ['productName'=>$product['productName'],
                                   'image'=>$product['image'],
                                   'expirDate'=>$product['expirDate'],
                                   'type'=>$product['type'],
                                   'contactInformation'=>$product['contactInformation'],
                                   'avaliableAmount'=>$product['avaliableAmount'],
                                   'price'=>$product['price']];
            $i++;
        }
        return response()->json(['products_list' => $products_array]);
    }

    //get user products
    public function getUserProducts(){
            //get his/her products
            $user=Auth::user();
            // get owned products only
            $products = DB::table('products')->select('productName','image','expirDate','type','contactInformation','avaliableAmount','price')->where('owner',$user->name )->get();
            $products_array = array();
            $i=0;
            foreach ($products as $product) {
                $products_array[$i] = ['productName'=>$product->productName,
                                       'image'=>$product->image,
                                       'expirDate'=>$product->expirDate,
                                       'type'=>$product->type,
                                       'contactInformation'=>$product->contactInformation,
                                       'avaliableAmount'=>$product->avaliableAmount,
                                       'price'=>$product->price];
                $i++;
            }
            return response()->json(['products_list' => $products_array]);
    }

    //search for product by product Name|expirDate|type
    public function search(Request $request){
        //create the query function
        function productListQuery($searchTerm, $operator){
            $products = DB::table('products')->select('productName','image','expirDate','type','contactInformation','avaliableAmount','price')->where($operator,$searchTerm)->get();
                $products_array = array();
                $i=0;
                foreach ($products as $product) {
                    $products_array[$i] = ['productName'=>$product->productName,
                                        'image'=>$product->image,
                                        'expirDate'=>$product->expirDate,
                                        'type'=>$product->type,
                                            'contactInformation'=>$product->contactInformation,
                                        'avaliableAmount'=>$product->avaliableAmount,
                                        'price'=>$product->price];
                    $i++;
                }
                return $products_array;
        }
        
        if ($request->input('productName') != null) {
            $searchTerm=$request->input('productName');
            $products = DB::table('products')->select('productName','image','expirDate','type','contactInformation','avaliableAmount','price')->where('productName',$searchTerm)->get();
        }
        elseif ($request->input('expirDate') != null) {
            $searchTerm=$request->input('expirDate');
            $products = DB::table('products')->select('productName','image','expirDate','type','contactInformation','avaliableAmount','price')->where('expirDate',$searchTerm)->get();
        }
        elseif ($request->input('type') != null) {
            $searchTerm=$request->input('type');
            $products = DB::table('products')->select('productName','image','expirDate','type','contactInformation','avaliableAmount','price')->where('type',$searchTerm)->get();
        }
        else{
            return response()->json(['error' => 'please specifiy a search operator']);
        }
        $products_array = array();
            $i=0;
            foreach ($products as $product) {
                $products_array[$i] = ['productName'=>$product->productName,
                                    'image'=>$product->image,
                                    'expirDate'=>$product->expirDate,
                                    'type'=>$product->type,
                                    'contactInformation'=>$product->contactInformation,
                                    'avaliableAmount'=>$product->avaliableAmount,
                                    'price'=>$product->price];
                $i++;
            }
        return response()->json(['products_list' => $products_array]);
        //return response()->json(['productName'=>$productsList->productName,'image'=>$productsList->image,'expirDate'=>$productsList->expirDate,'type'=>$productsList->type,'contactInformation'=>$productsList->contactInformation,'avaliableAmount'=>$productsList->avaliableAmount,'price'=>$productsList->price]);
    }
    //add comment on a product
    public function addComment(Request $request){
        $user=Auth::user();
        //validate the input
        $request->validate(['productName'=>'required|string',
                            'comment'=>'required|string|min:6'
        ]);
        $comment = $request->input('comment');
        $productName = $request->input('productName');
        //get the serialized comments array
        $product = DB::table('products')->select('comments')->where('productName',$productName)->get();
        $commentsSerialized = $product[0]->comments;
        //unserialize
        $comments = unserialize($commentsSerialized);
        //add comment to array
        array_push($comments,['user'=>$user->name,'comment'=>$comment]);
        //Reserialize the array and update the column on DB
        $commentsSerialized = serialize($comments);
        // try {
        DB::table('products')->where('productName',$productName)->update(['comments'=>$commentsSerialized]);
        return response()->json(['status' => 'comment added successfully!']);
        // } catch (\Throwable $th) {
            // return response()->json(['status' => 'error: '.$th]);
        // }
    }
    //add view
    public function addView(Request $request){
        $request->validate(['productName'=>'required|string']);
        $productName = $request->input('productName');
        $viewsNum = DB::table('products')->select('views')->where('productName',$productName)->get();
        $newViews = $viewsNum[0]->views + 1;
        // return response()->json(['status' => 'success']);
        // try {
        DB::table('products')->where('productName',$productName)->update(['views'=>$newViews]);
        return response()->json(['status' => 'success']);
        // } catch (\Throwable $th) {
        //      return response()->json(['status' => 'error: '.$th]);
        // }
    }
}
