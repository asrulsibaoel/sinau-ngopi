<?php

namespace App\Http\Controllers;

use App\Model\Comment;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{   
    private $payload_list=array("list_comment"=> null,'page'=>1);
    private $payload_info=array("comment"=> null);
    private $relation_table=array('product','user');
    public function data(Request $request)
    {
      
/*      \Event::listen('Illuminate\Database\Events\QueryExecuted', function ($query) {
            var_dump($query->sql);
        });
*/    
        $comment=new Comment();
        $option_list=optionList($comment,$request,$this->relation_table);
        
        $this->payload_list['list_comment']=$option_list['data'];
        $this->payload_list['page']=$option_list['page'];

        if(count($option_list['data'])>0){
            $results=successResult("List of Comment",$this->payload_list);
        }else{
            $results=errorResult("Comment Not Found",$this->payload_list);
        }

        return $results;
    }

    public function create(Request $request)
    {


         $validator=Validator::make($request->all(), [
            'messages' => 'required',
            'products_id' => 'required',
        ]);


        

        if($validator->fails()){
            $results=errorResult($validator->messages()->first(),$this->payload_info);
        }else{
            $data_input = $request->all();
            $data_input['users_id']=Auth::id();
            $created=Comment::create($data_input);        
            $info_comment=Comment::with($this->relation_table)->find($created->id);
            $this->payload_info['comment']=$info_comment;
            $results=successResult("Create Comment Successfully",$this->payload_info);
        }

        return $results;
    }

    public function detail($id)
    {
        $row=Comment::with($this->relation_table)->find($id);

        if(count($row)>0){
           $this->payload_info['comment']=$row;
           $results=successResult("Detail of Comment",$this->payload_info);

        }else{
           $results=errorResult("Comment not found",$this->payload_info);
        }

        return $results;
    }

    public function update(Request $request, Comment $comment)
    {

        $validator=Validator::make($request->all(), [
            'messages' => 'required',
            'products_id' => 'required',
        ]);


        if($validator->fails()){
               $results=errorResult($validator->messages()->first(),$this->payload_info);
        }else{

            $data_input = $request->all();
            $comment_id=$request->input('id');
            $updated=Comment::where('id', $comment_id)
                                  ->update($data_input);

            if($updated){
               $this->payload_info['comment']=Comment::with($this->relation_table)->find($comment_id);
               $results=successResult("Update Comment Successfully",$this->payload_info);

            }else{
               $results=errorResult("Update Comment Failed",$this->payload_info);
            }

        }

        return $results;
    }

    public function delete($id)
    {
        $this->payload_info=array(
            "comment"=> new \stdClass()
            );
        
        $comment=Comment::with($this->relation_table)->find($id);
        if($comment!=null){
            $comment->delete();
            if ($comment->trashed()) {
               $this->payload_info['comment']=$comment;
               $results=successResult("Delete Comment Successfully",$this->payload_info);
            }else{
               $results=errorResult("Delete Comment Failed",$this->payload_info);
            }
        }else{
               $results=errorResult("Comment not found or already removed.",$this->payload_info);
        }

        return $results;
    }

    public function force_delete($id){        

        $comment=Comment::onlyTrashed()->with($this->relation_table)->find($id);
        ($comment!==null) ? '' : $comment=Comment::with($this->relation_table)->find($id);;
        if($comment!=null){
            if ($comment->forceDelete()) {
               $this->payload['comment']=$comment;
               $results=successResult("Force Delete Comment Successfully",$this->payload_info);
            }else{
               $results=errorResult("Force Delete Comment Failed",$this->payload_info);
            }
        }else{
               $results=errorResult("Comment not found or already removed.",$this->payload_info);
        }
        return $results;
    }

}