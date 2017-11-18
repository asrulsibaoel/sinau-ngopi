<?php

namespace App\Http\Controllers;

use App\Model\TransactionsDetail;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionsDetailController extends Controller
{   
    private $payload_list=array("list_transactions_detail"=> null,'page'=>1);
    private $payload_info=array("transactions_detail"=> null);
    private $relation_table=array('product','transaction');
    public function data(Request $request)
    {
      
/*      \Event::listen('Illuminate\Database\Events\QueryExecuted', function ($query) {
            var_dump($query->sql);
        });
*/    
        $transactions_detail=new TransactionsDetail();
        $option_list=optionList($transactions_detail,$request,$this->relation_table);
        
        $this->payload_list['list_transactions_detail']=$option_list['data'];
        $this->payload_list['page']=$option_list['page'];

        if(count($option_list['data'])>0){
            $results=successResult("List of TransactionsDetail",$this->payload_list);
        }else{
            $results=errorResult("TransactionsDetail Not Found",$this->payload_list);
        }

        return $results;
    }

    public function create(Request $request)
    {

        $validator=Validator::make($request->all(), [
            'bargain_price' => 'required',
            'transactions_id' => 'required',
            'products_id' => 'required'
        ]);            

        if($validator->fails()){
            $results=errorResult($validator->messages()->first(),$this->payload_info);
        }else{
            $data_input = $request->all();
            $data_input['users_id']=Auth::id();
            $created=TransactionsDetail::create($data_input);        
            $info_transactions_detail=TransactionsDetail::with($this->relation_table)->find($created->id);
            $this->payload_info['transactions_detail']=$info_transactions_detail;
            $results=successResult("Create TransactionsDetail Successfully",$this->payload_info);
        }

        return $results;
    }

    public function detail($id)
    {
        $row=TransactionsDetail::with($this->relation_table)->find($id);

        if(count($row)>0){
           $this->payload_info['transactions_detail']=$row;
           $results=successResult("Detail of TransactionsDetail",$this->payload_info);

        }else{
           $results=errorResult("TransactionsDetail not found",$this->payload_info);
        }

        return $results;
    }

    public function update(Request $request, TransactionsDetail $transactions_detail)
    {

        $validator=Validator::make($request->all(), [
            'bargain_price' => 'required',
            'transactions_id' => 'required',
            'products_id' => 'required'
        ]);


        if($validator->fails()){
               $results=errorResult($validator->messages()->first(),$this->payload_info);
        }else{

            $data_input = $request->all();
            $transactions_detail_id=$request->input('id');
            $updated=TransactionsDetail::where('id', $transactions_detail_id)
                                  ->update($data_input);

            if($updated){
               $this->payload_info['transactions_detail']=TransactionsDetail::with($this->relation_table)->find($transactions_detail_id);
               $results=successResult("Update TransactionsDetail Successfully",$this->payload_info);

            }else{
               $results=errorResult("Update TransactionsDetail Failed",$this->payload_info);
            }

        }

        return $results;
    }

    public function delete($id)
    {
        $this->payload_info=array(
            "transactions_detail"=> new \stdClass()
            );
        
        $transactions_detail=TransactionsDetail::with($this->relation_table)->find($id);
        if($transactions_detail!=null){
            $transactions_detail->delete();
            if ($transactions_detail->trashed()) {
               $this->payload_info['transactions_detail']=$transactions_detail;
               $results=successResult("Delete TransactionsDetail Successfully",$this->payload_info);
            }else{
               $results=errorResult("Delete TransactionsDetail Failed",$this->payload_info);
            }
        }else{
               $results=errorResult("TransactionsDetail not found or already removed.",$this->payload_info);
        }

        return $results;
    }

    public function force_delete($id){        

        $transactions_detail=TransactionsDetail::onlyTrashed()->with($this->relation_table)->find($id);
        ($transactions_detail!==null) ? '' : $transactions_detail=TransactionsDetail::with($this->relation_table)->find($id);;
        if($transactions_detail!=null){
            if ($transactions_detail->forceDelete()) {
               $this->payload['transactions_detail']=$transactions_detail;
               $results=successResult("Force Delete TransactionsDetail Successfully",$this->payload_info);
            }else{
               $results=errorResult("Force Delete TransactionsDetail Failed",$this->payload_info);
            }
        }else{
               $results=errorResult("TransactionsDetail not found or already removed.",$this->payload_info);
        }
        return $results;
    }

}