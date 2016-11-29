<?php

function admin_url($file = null){
    if(substr(Config::get('app.admin_path'), 0, 1) == '/'){
        $url = Config::get('app.url') . Config::get('app.admin_path');
    }else{
        $url = Config::get('app.url') . '/' . Config::get('app.admin_path');
    } 
    
    if($file != null){
        if(substr($file, 0, 1) == '/'){
            $url.= $file;
        }else{
            $url.= '/' . $file;
        } 
    }
    
    return url($url);
    
} 


function admin_asset($file){
    
    if(substr($file, 0, 1) == '/'){
        $url = Config::get('app.admin_assets_path') . $file;
    }else{
        $url = Config::get('app.admin_assets_path') . '/' . $file;
    }
    
    return url($url);
}

function uploads_url($file){
    
    $url = Config::get('app.uploads_path') . $file;
    
    return url($url);
}

