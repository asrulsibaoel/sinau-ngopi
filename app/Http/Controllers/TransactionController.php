<?php

namespace App\Http\Controllers;

use App\Model\Transaction;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{   
    private $payload_list=array("list_transaction"=> null,'page'=>1);
    private $payload_info=array("transaction"=> null);
    private $relation_table=array('user');
    public function data(Request $request)
    {
      
/*      \Event::listen('Illuminate\Database\Events\QueryExecuted', function ($query) {
            var_dump($query->sql);
        });
*/    
        $transaction=new Transaction();
        $option_list=optionList($transaction,$request,$this->relation_table);
        
        $this->payload_list['list_transaction']=$option_list['data'];
        $this->payload_list['page']=$option_list['page'];

        if(count($option_list['data'])>0){
            $results=successResult("List of Transaction",$this->payload_list);
        }else{
            $results=errorResult("Transaction Not Found",$this->payload_list);
        }

        return $results;
    }

    public function create(Request $request)
    {


         $validator=Validator::make($request->all(), [
            'total' => 'required',

        ]);
        

        if($validator->fails()){
            $results=errorResult($validator->messages()->first(),$this->payload_info);
        }else{
            $data_input = $request->all();
            $data_input['users_id']=Auth::id();
            $transID="TRX".date("ymdhis").rand(0,10);
            $data_input['id']=$transID;

            $created=Transaction::create($data_input);        
            $info_transaction=Transaction::with($this->relation_table)->find($created->id);
            $this->payload_info['transaction']=$info_transaction;
            $results=successResult("Create Transaction Successfully",$this->payload_info);
        }

        return $results;
    }

    public function detail($id)
    {
        $row=Transaction::with($this->relation_table)->find($id);

        if(count($row)>0){
           $this->payload_info['transaction']=$row;
           $results=successResult("Detail of Transaction",$this->payload_info);

        }else{
           $results=errorResult("Transaction not found",$this->payload_info);
        }

        return $results;
    }

    public function update(Request $request, Transaction $transaction)
    {

       $validator=Validator::make($request->all(), [
            'id' => 'required',
            'total' => 'required',
        ]);


        if($validator->fails()){
               $results=errorResult($validator->messages()->first(),$this->payload_info);
        }else{

            $data_input = $request->all();
              $users_id=Auth::id();
            $transactions_id=$request->input('id');
           $updated=Transaction::where('users_id',$users_id)
          ->where('id', $transactions_id)
          ->update($data_input);

            if($updated){
               $this->payload_info['transaction']=Transaction::with($this->relation_table)->find($transaction_id);
               $results=successResult("Update Transaction Successfully",$this->payload_info);

            }else{
               $results=errorResult("Update Transaction Failed",$this->payload_info);
            }

        }

        return $results;
    }

    public function delete($id)
    {
        $this->payload_info=array(
            "transaction"=> new \stdClass()
            );
        
        $transaction=Transaction::with($this->relation_table)->find($id);
        if($transaction!=null){
            $transaction->delete();
            if ($transaction->trashed()) {
               $this->payload_info['transaction']=$transaction;
               $results=successResult("Delete Transaction Successfully",$this->payload_info);
            }else{
               $results=errorResult("Delete Transaction Failed",$this->payload_info);
            }
        }else{
               $results=errorResult("Transaction not found or already removed.",$this->payload_info);
        }

        return $results;
    }

    public function force_delete($id){        

        $transaction=Transaction::onlyTrashed()->with($this->relation_table)->find($id);
        ($transaction!==null) ? '' : $transaction=Transaction::with($this->relation_table)->find($id);;
        if($transaction!=null){
            if ($transaction->forceDelete()) {
               $this->payload['transaction']=$transaction;
               $results=successResult("Force Delete Transaction Successfully",$this->payload_info);
            }else{
               $results=errorResult("Force Delete Transaction Failed",$this->payload_info);
            }
        }else{
               $results=errorResult("Transaction not found or already removed.",$this->payload_info);
        }
        return $results;
    }

}