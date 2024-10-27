<?php 

    namespace App\Classes;

    class Upload{
        private const UPLOAD_DIR = './assets/images/';
        private $name;
        private $type;
        private $size;
        private $tmp;
        private $extension;
        public function __construct($array){
            $this->name = $array['name'];
            $this->type = $array['type'];
            $this->size = $array['size'];
            $this->tmp = $array['tmp_name'];
            $this->extension = pathinfo($this->name, PATHINFO_EXTENSION);
        }
        public function upload(){
            $newName = time().'.'.$this->extension;
            $address = self::UPLOAD_DIR.$newName;
            if(move_uploaded_file($this->tmp,$address)){
                return "images/$newName";
            }else{
                return false;
            }
        }

        public function getName(){ return $this->name; }
        public function getType(){ return $this->type; }
        public function getSize(){ return $this->size /1024; }
        public function getTmp(){ return $this->tmp; }
        public function getExtension(){ return $this->extension; }
        public function isFile(){
            return $this->name != '';
        }
    }