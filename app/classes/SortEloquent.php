<?php

class SortEloquent
{
       
   public static function sort($modelName, $ids){
       
        if(is_subclass_of($modelName, 'Eloquent')){
            $ids = explode(",", $ids);
            
            foreach($ids as $k => $id){
                $model = $modelName::find($id);
                $model->sort = $k;
                $model->save();
            }
            
            return array('status' => "ok", "message" => "sorted");
        }else{
            return array('status' => "error", "message" => "illegal operation");
        }
   } 
   
}