<?php

namespace App\Http\Controllers;

use App\Model\Product;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{   
    private $payload_list=array("list_product"=> null,'page'=>1);
    private $payload_info=array("product"=> null);
    private $relation_table=array('category','user');
    public function data(Request $request)
    {
      
/*      \Event::listen('Illuminate\Database\Events\QueryExecuted', function ($query) {
            var_dump($query->sql);
        });
*/    
        $product=new Product();
        $option_list=optionList($product,$request,$this->relation_table);
        
        $this->payload_list['list_product']=$option_list['data'];
        $this->payload_list['page']=$option_list['page'];

        if(count($option_list['data'])>0){
            $results=successResult("List of Product",$this->payload_list);
        }else{
            $results=errorResult("Product Not Found",$this->payload_list);
        }

        return $results;
    }

     public function my_product(Request $request)
    {
      
/*      \Event::listen('Illuminate\Database\Events\QueryExecuted', function ($query) {
            var_dump($query->sql);
        });
*/    

        $product=new Product();

        $custom_filters_and=array(
            'users_id'=>Auth::id() 
            );
        $option_list=optionList($product,$request,$this->relation_table,$custom_filters_and);
        
 
        
        $this->payload_list['list_product']=$option_list['data'];
        $this->payload_list['page']=$option_list['page'];

        if(count($option_list['data'])>0){
            $results=successResult("List of Product",$this->payload_list);
        }else{
            $results=errorResult("Product Not Found",$this->payload_list);
        }

        return $results;
    }

    public function create(Request $request)
    {


        $validator=Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required',
            'categories_id' => 'required',
        ]);
        

        if($validator->fails()){
            $results=errorResult($validator->messages()->first(),$this->payload_info);
        }else{
            $data_input = $request->all();
            $data_input['users_id']=Auth::id();
            $created=Product::create($data_input);        
            $info_product=Product::with($this->relation_table)->find($created->id);
            $this->payload_info['product']=$info_product;
            $results=successResult("Create Product Successfully",$this->payload_info);
        }

        return $results;
    }

    public function detail($id)
    {
        $row=Product::with($this->relation_table)->find($id);

        if(count($row)>0){
           $this->payload_info['product']=$row;
           $results=successResult("Detail of Product",$this->payload_info);

        }else{
           $results=errorResult("Product not found",$this->payload_info);
        }

        return $results;
    }

    public function update(Request $request, Product $product)
    {
        $validator=Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if($validator->fails()){
               $results=errorResult($validator->messages()->first(),$this->payload_info);
        }else{

            $data_input = $request->all();
            $product_id=$request->input('id');
            $updated=Product::where('id', $product_id)
                                  ->update($data_input);

            if($updated){
               $this->payload_info['product']=Product::with($this->relation_table)->find($product_id);
               $results=successResult("Update Product Successfully",$this->payload_info);

            }else{
               $results=errorResult("Update Product Failed",$this->payload_info);
            }

        }

        return $results;
    }

    public function delete($id)
    {
        $this->payload_info=array(
            "product"=> new \stdClass()
            );
        
        $product=Product::with($this->relation_table)->find($id);
        if($product!=null){
            $product->delete();
            if ($product->trashed()) {
               $this->payload_info['product']=$product;
               $results=successResult("Delete Product Successfully",$this->payload_info);
            }else{
               $results=errorResult("Delete Product Failed",$this->payload_info);
            }
        }else{
               $results=errorResult("Product not found or already removed.",$this->payload_info);
        }

        return $results;
    }

    public function force_delete($id){        

        $product=Product::onlyTrashed()->with($this->relation_table)->find($id);
        ($product!==null) ? '' : $product=Product::with($this->relation_table)->find($id);;
        if($product!=null){
            if ($product->forceDelete()) {
               $this->payload['product']=$product;
               $results=successResult("Force Delete Product Successfully",$this->payload_info);
            }else{
               $results=errorResult("Force Delete Product Failed",$this->payload_info);
            }
        }else{
               $results=errorResult("Product not found or already removed.",$this->payload_info);
        }
        return $results;
    }

}