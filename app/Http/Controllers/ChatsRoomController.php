<?php

namespace App\Http\Controllers;

use App\Model\ChatsRoom;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatsRoomController extends Controller
{   
    private $payload_list=array("list_chatsroom"=> null,'page'=>1);
    private $payload_info=array("chatsroom"=> null);

    private $relation_table=array('sender','receiver');
    public function data(Request $request)
    {
      
/*      \Event::listen('Illuminate\Database\Events\QueryExecuted', function ($query) {
            var_dump($query->sql);
        });
*/    
        $chatsroom=new ChatsRoom();
        $custom_filters_or=array(
            'sender_id'=>Auth::id(),
            'receiver_id'=>Auth::id()
            );
        $option_list=optionList($chatsroom,$request,$this->relation_table,null,$custom_filters_or);
        
        $this->payload_list['list_chatsroom']=$option_list['data'];
        $this->payload_list['page']=$option_list['page'];
        
        if(count($option_list['data'])>0){
            $results=successResult("List of ChatsRoom",$this->payload_list);
        }else{
            $results=errorResult("ChatsRoom Not Found",$this->payload_list);
        }

        return $results;
    }

    public function create(Request $request)
    {
        $validator=Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if($validator->fails()){
            $results=errorResult($validator->messages()->first(),$this->payload_info);
        }else{
            
            $data_input = $request->all();
            $created=ChatsRoom::create($data_input);        
            $info_chats_room=ChatsRoom::with($this->relation_table)->find($created->id);

            $this->payload_info['chatsroom']=$info_chats_room;
            $results=successResult("Create Chats Room Successfully",$this->payload_info);
        }

        return $results;

    }

    public function detail($id)
    {
        $row=ChatsRoom::with($this->relation_table)->find($id); 

        if(count($row)>0){
           $this->payload_info['chatsroom']=$row;
           $results=successResult("Detail of Chats Room",$this->payload_info);

        }else{
           $results=errorResult("Chats Room not found",$this->payload_info);
        }

        return $results;
    }

    public function update(Request $request, ChatsRoom $chatsroom)
    {
        $validator=Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if($validator->fails()){
               $results=errorResult($validator->messages()->first(),$this->payload_info);
        }else{

            $data_input = $request->all();
            $chatsroom_id=$request->input('id');
            $updated=ChatsRoom::where('id', $chatsroom_id)
                                  ->update($data_input);

            if($updated){
                $this->payload_info['chatsroom']=Chat::with($this->relation_table)->find($chat_id);
              $results=successResult("Update Chats Room Successfully",$this->payload_info);

            }else{
               $results=errorResult("Update Chats Room Failed",$this->payload_info);
            }

        }

        return $results;
    }

    public function delete($id)
    {
        $this->payload_info=array(
            "chatsroom"=> new \stdClass()
            );
        
        $chatsroom=ChatsRoom::find($id);
        if($chatsroom!=null){
            $chatsroom->delete();
            if ($chatsroom->trashed()) {
               $this->payload_info['chatsroom']=$chatsroom;
               $results=successResult("Delete ChatsRoom Successfully",$this->payload_info);
            }else{
               $results=errorResult("Delete Chats Room Failed",$this->payload_info);
            }
        }else{
               $results=errorResult("Chats Room not found or already removed.",$this->payload_info);
        }

        return $results;
    }

    public function force_delete($id){        

        $chatsroom=ChatsRoom::onlyTrashed()->find($id);
        ($chatsroom!==null) ? '' : $chatsroom=ChatsRoom::find($id);;
        if($chatsroom!=null){
            if ($chatsroom->forceDelete()) {
               $this->payload['chatsroom']=$chatsroom;
               $results=successResult("Force Delete Chats Room Successfully",$this->payload_info);
            }else{
               $results=errorResult("Force Delete Chats Room Failed",$this->payload_info);
            }
        }else{
               $results=errorResult("Chats Room not found or already removed.",$this->payload_info);
        }
        return $results;
    }

}