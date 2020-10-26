<?php
class changePassword extends Controller {

    public function index() {

        $this->model('LoginToken');

        if($this->model->verifyLoginToken()) {

            $this->view('/error', ['error_title' => 'Error 404', 'error_message' => 'Error: Already Logged In']);

            $this->view->render();

            return;
        }

        if(isset($_GET['token'])) {

            $this->model('PasswordToken');

            if($this->model->verifyPasswordResetToken($_GET['token'])) {

                if(isset($_POST['edit-password'])) {

                    if($_POST['password'] != null) {
                    
                        $userid = $this->model->verifyPasswordResetToken($_GET['token']);

                        $this->model->deleteToken($userid);
                    
                        $this->model('User');
                    
                        $this->model->changePassword($userid, $_POST['password']);
                    }
                
                    header("Location: /home");
                }
            }
        }
        else {

            header('Location: /home');
        }

        $this->view('/changePassword');

        $this->view->render();
    }
}
?>