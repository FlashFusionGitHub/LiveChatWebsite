<?php
class disclaimer extends Controller {

    public function index() {

        $this->view('/disclaimer');

        $this->view->render();
    }
}
?>