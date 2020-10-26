<?php
class Controller {

    protected $view;
    protected $model;

    protected function model($model, $data = array()) {

        if(file_exists(MODEL . $model . '.php')) {
            
            require_once MODEL . $model . '.php';

            $this->model = new $model($model, $data);
        }
    }

    public function view($view, $data = array()) {

        $this->view = new View($view, $data);

        return $this->view;
    }
}
?>