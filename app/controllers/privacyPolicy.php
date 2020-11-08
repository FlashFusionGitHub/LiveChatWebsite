<?php
class privacyPolicy extends Controller {

    public function index() {

        $this->view('/privacyPolicy');

        $this->view->render();
    }
}
?>