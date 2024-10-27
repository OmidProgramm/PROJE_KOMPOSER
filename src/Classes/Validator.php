<?php 
    namespace App\Classes;

    class Validator{
        private $request;
        private $errors = [];
        public function __construct($request){
            $this->request = $request;
        }
        public function validate($array)
        {
            foreach($array as $field => $rules){
                if(in_array('nullable',$rules) and !$this->request->{$field}->isFile()){
                    continue;
                }
                foreach($rules as $rule){
                    if($rule == 'nullable'){
                        continue;
                    }
                    if(str_contains($rule, ':')){
                        $rule = explode(':',$rule);
                        $ruleName = $rule[0];
                        $ruleValue = $rule[1];
                        if($error = $this->{$ruleName}($field, $ruleValue)){
                            $this->errors[$field] = $error;
                            break;
                        }
                    }else{
                        if($error = $this->{$rule}($field)){
                            $this->errors[$field] = $error;
                            break;
                        }
                    }
                }
            }
            return $this;
        }
        public function hasError(){
            return count($this->errors)? true: false;
        }
        public function getErrors(){ 
            return $this->errors;
        }
        private function required($field){
            if(is_null($this->request->get($field)))
                return "Please fill $field"; 

            if(empty($this->request->get($field)))
                return "Please fill `$field`";

            return false;
        }
        private function email($field){
            if(!filter_var($this->request->{$field}, FILTER_VALIDATE_EMAIL))
                return "`$field` is invalid email";

            return false;
        }
        private function min($field, $value){
            if(strlen($this->request->{$field}) < $value)
                return "`$field` chars length is smaller than $value";
            
            return false;
        }
        private function max($field, $value){
            if(strlen($this->request->{$field}) > $value)
                return "`$field` chars length is bigger than $value";
            
            return false;
        }
        public function in($field, $items){
            $items = explode(',', $items);
            if(!in_array($this->request->{$field},$items))
                return "selected $field is invalid";
        
            return false;
        }
        public function size($field, $len){
            if($this->request->{$field}->getSize() > $len)
                return "`$field` must be smaller than $len KB";

            return false;
        }
        public function type($field, $types){
            $types = explode(',', $types);
            if(!in_array($this->request->{$field}->getExtension(),$types))
                return "`$field` format is invalid";

            return false;
        }
        public function file($field){
            if(!$this->request->{$field} instanceof Upload){
                return "`$field` is not a file";
            }
                       
            if(!$this->request->{$field}->isFile()){
                return "`$field` is not a file";
            }else{
                return false;
            }
 
        }
    }