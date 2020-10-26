<?php
class forgotPassword extends Controller {

    private $error_message = '';

    public function index() {

        $this->model('LoginToken');
        
        if($this->model->verifyLoginToken()) {

            $this->view('/error', ['error_title' => 'Error 404', 'error_message' => 'Error: Already Logged In']);

            $this->view->render();

            return;
        }

        if(isset($_POST['edit-password'])) {

            //When on a live server use PHP send email
            //for demo we are simply printing a rest token to the screen

            $this->model('PasswordToken');
            
            $token = $this->model->generatePasswordToken($_POST['forgot-password-email']);

            if($token != null) {

                header('Location: /changePassword/index' . '/?token=' . $token);

                return;
            }

            $this->error_message = 'An account with that email address doesn\'t exist';
        }

        if(isset($_REQUEST['em'])) {

            if(filter_var($_REQUEST['em'], FILTER_VALIDATE_EMAIL)) {

                echo json_encode(['valid'=>true, 'message'=>'Email valid']);

            }
            else {

                echo json_encode(['valid'=>false, 'message'=>'Email invalid']);
            }

            return;
        }

        $this->view('forgotPassword', ['error_message' => $this->error_message]);

        $this->view->render();
    }
}
?>