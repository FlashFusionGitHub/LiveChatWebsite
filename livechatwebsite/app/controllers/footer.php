<?php

class footer extends Controller {

    public function index() {
        
        $this->view('/footer');

        $this->view->render();
    }
}

?>