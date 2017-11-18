<?php 
/**
* custom helper global function
*/

	function successResult($message,$payload){
		return jsonResulter(false,$message,$payload);
	}	
	
	function errorResult($message,$payload){
		return jsonResulter(true,$message,$payload);
	}	

	function jsonResulter($error=false,$message="",$payload=array()){
		foreach ($payload as $key => $value) {
			if($value===null){
				$payload[$key]=new \stdClass();
			}
		}
		
		$result=array(
			"error"=>$error,
			"message"=>$message,
			"data"=>$payload
		);
		return	response()->json($result);
		// return $result;			
	}

	function optionList($object,$request,$with=""){
	    $fillable=$object->fillable;

        $query_object=$object;
        if($with!=""){
            $query_object=$query_object->with($with);
        }
        // filters
        $filters=$request->input('filters');
        if(is_array($filters))
        foreach ($filters as $field=>$filter) {
            if(!empty($filter))
            $query_object=$query_object->Where($field,$filter);
        }

        // searching
        if($request->input('q')){
            $q=$request->input('q');
            if(is_array($fillable))
                foreach ($fillable as $field) {
                    if(!empty($q))
                    $query_object=$query_object->orWhere($field,'like','%'.$q.'%');
                }
        }

        // sorting
        if($request->input('sortby')){
            $sortby=$request->input('sortby');
            $order=($request->input('order')) ? $request->input('order') : 'asc';
                    $query_object=$query_object->orderBy($sortby,$order);
        }

        // paging
        $page=($request->input('page')>0) ? $request->input('page') : '1' ;
        if($page){
            $take=20;
            $skip=$take*($page-1);
            $query_object=$query_object->skip($skip)->take($take);
        }
        try {
        $data=$query_object->get();
            
        } catch (Exception $e) {
            // print_r($e);
        $data=[];           
        }
       return array(
       		'page'=>$page,
       		'data'=>$data
       	);

	}
 ?>
