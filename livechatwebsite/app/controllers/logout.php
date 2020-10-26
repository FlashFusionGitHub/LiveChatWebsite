<?php

class logout extends Controller {

    public function index() {

        $this->model('LoginToken');

        if(!$this->model->verifyLoginToken()) {

            $this->view('/error', ['error_title' => 'Error 404', 'error_message' => 'Error: Not logged in...']);

            $this->view->render();

            return;
        }

        $this->model('User');

        if(isset($_POST['logout'])) {

            $this->model->logoutUser();

            header("Location: /home");

            return;
        }

        if(isset($_POST['logoutAllDevices'])) {

            $this->model->logoutAllDevices();

            header("Location: /home");

            return;
        }

        $this->view('/logout');

        $this->view->render();
    }
}

?>