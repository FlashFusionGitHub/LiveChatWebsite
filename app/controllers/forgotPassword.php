<?php
class forgotPassword extends Controller {

    private $error_message;

    public function index() {

        $this->model('LoginToken');
        
        if($this->model->verifyLoginToken()) {

            $this->view('/error', ['error_title' => 'Error 404', 'error_message' => 'Error: Already Logged In']);

            $this->view->render();

            return;
        }

        if(isset($_POST['resetPassword']) && $_POST['email'] != null) {

            //When on a live server use PHP send email
            //for demo we are simply printing a rest token to the screen

            $this->model('PasswordToken');
            
            $token = $this->model->generatePasswordToken($_POST['email']);

            if($token != null) {

                header('Location: /changePassword/index' . '/?token=' . $token);

                return;
            }
            else {

                $this->error_message = 'An account with that email address doesn\'t exist';
            }
        }

        $this->view('forgotPassword', ['error_message' => $this->error_message]);

        $this->view->render();
    }
}
?>