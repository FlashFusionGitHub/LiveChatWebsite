<?php
class aboutUs extends Controller {

    public function index() {

        $this->view('/aboutUs');

        $this->view->render();
    }
}
?>