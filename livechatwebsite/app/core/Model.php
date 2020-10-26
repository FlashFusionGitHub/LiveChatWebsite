<?php
class Model extends Database {

    protected $model;
    protected $data;
    
    public function __construct($model, $data) {

        $this->model = $model;
        $this->data = $data;
    }

}
?>