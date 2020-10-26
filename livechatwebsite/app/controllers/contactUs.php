<?php
class contactUs extends Controller {

    public function index() {

        $this->view('/contactUs');

        $this->view->render();
    }
}
?>