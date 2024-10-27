<?php 
    namespace App\Models;
    use App\Classes\Database;
    use App\Exceptions\DoesNotExistException;

    abstract class Model{
        protected $database;
        protected $fileName;
        protected $entityClass;
        public function __construct(){
            $this->database = new Database($this->fileName, $this->entityClass);
        }
        public function getAllData(){
            return $this->database-> getData();
        }

        public function getDataById($id){
            $data = $this ->database->getData();
            $array = array_filter($data,function($item)use($id){
                return $item->getId() == $id;
            });
            
            $array = array_values($array);
            if(count($array))
                return $array[0];
            throw new DoesNotExistException("Does not exist any {$this->entityClass}");
        }

        public function getLastData(){
            $data = $this->database->getData();
            uasort($data, function($first, $second){
                return $first->getId() > $second->getId()? -1 : 1;
            });
            $data = array_values($data);
            if(count($data))
                return $data[0];
            throw new DoesNotExistException("Does not exist any {$this->entityClass}");
        }
        public function getFirstData(){

            $data = $this->database->getData();
            uasort($data, function($first, $second){
                return $first->getId() < $second->getId()? -1 : 1;
            });
            $data = array_values($data);
            if(count($data))
                return $data[0];
            throw new DoesNotExistException("Does not exist any {$this->entityClass}");
        }
        public function sortData($callback){
            $data = $this->database->getData();
            uasort($data, $callback);

            $data = array_values($data);

            if(count($data))
                return $data;

            throw new DoesNotExistException("Does not exist any {$this->entityClass}");
        }
        public function filterData($callback)
        {
            $data = $this->database->getData();
            $data = array_filter($data, $callback);

            $data = array_values($data);

            if(count($data))
                return $data;

            throw new DoesNotExistException("Does not exist any {$this->entityClass}");
        }
        public function createData($new){
            $data = $this->database->getData();
            $data[]= $new;

            $this->database->setData($data);
            return true;
        }
        public function deleteData($id)
        {
            $data = $this->database->getData();
            $newData = array_filter($data,function($item)use($id){
                return $item->getId() == $id? false : true;
            });

            $newData = array_values($newData);
            $this->database->setData($newData);
            return true;
        }
        public function editData($new)
        {
            $data = $this->database->getData();

            $newData = array_map(function($item)use($new){
                return $item->getId() == $new->getId()? $new : $item;
            },$data);
            $newData = array_values($newData);
            $this->database->setData($newData);
            return true;
        }
    }