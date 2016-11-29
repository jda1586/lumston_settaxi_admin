<?php

class AppFiles{
    
    public static function saveFile($data, $extension, $folder = false){
        $exists = true;
        while($exists){
            $filename = number_format(microtime(true) * 10000, 0, '.', '') . "." . $extension;
            $exists = file_exists(public_path() . Config::get('app.uploads_path') . "/" . (($folder) ? ($folder . "-") : "" ) . $filename);
        }
        
        file_put_contents(public_path() . Config::get('app.uploads_path') . "/" . (($folder) ? ($folder . "-") . "" : "" ) . $filename, $data);
        return $filename; 
    }
    
    public static function removeFile($filename){
        unlink(public_path() . Config::get('app.uploads_path') . "/" . $filename);
    }
    
    public static function saveFileFromFacebook($fbuserid){
        $filename = uniqid("fbuser_") . ".jpg";
        $url = "http://graph.facebook.com/" . $fbuserid . "/picture?width=200&height=200";
        $data = file_get_contents($url);
        
        file_put_contents(public_path() . Config::get('app.uploads_path') . "/" . $filename, $data);
        return $filename;
    }
}
