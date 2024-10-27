<?php 
    const BASE_URL = 'http://localhost/TUTOR/PROJE_KOMPOSER/';
    function dd($data){
        die('<pre>'.print_r($data,true).'</pre>');
    }
    function asset($file){
        return BASE_URL.'assets/'.$file;
    }
    function url($path, $query = []){
        if(!count($query)){
            return BASE_URL.$path;
        }else{
            return BASE_URL.$path.'?'.http_build_query($query);
        }
    }
    function redirect($path, $query = []){
        $url = url($path, $query);
        header("location: $url");
        exit;
    }
    function deleteFile($file){
        if(file_exists('./assets/'.$file)){
            unlink('./assets/'.$file);
            return true;
        }else{
            return false;
        }
    }