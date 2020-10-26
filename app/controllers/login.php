<?php

class login extends Controller {

    public function index() {

        $this->model('LoginToken');

        if($this->model->verifyLoginToken()) {

            $this->view('/error', ['error_title' => 'Error 404', 'error_message' => 'Error: Already Logged In']);

            $this->view->render();

            return;
        }

        if(isset($_POST['login'])) {

            $this->model('User');

            if($this->model->loginCheckEmail($_POST['email'])) {
                if($this->model->loginCheckPassword($_POST['password'], $_POST['email'])) {

                    $this->model('LoginToken');

                    $token = $this->model->generateLoginToken($_POST['email']);

                    setcookie("JAR", $token, time() + 60 * 60 * 24 * 7, '/', null, null, false);

                    setcookie("JAR_", '1', time() + 60 * 60 * 24 * 2, '/', null, null, false);

                    header("Location: /home");

                    return;
                }
            }
        }

        $this->view('/login');

        $this->view->render();
    }
}
?>