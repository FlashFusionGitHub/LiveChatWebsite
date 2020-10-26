<?php

class createAccount extends Controller {

    public function index() {

        $this->model('LoginToken');
        
        if($this->model->verifyLoginToken()) {

            $this->view('/error', ['error_title' => 'Error 404', 'error_message' => 'Error: Already Logged In']);

            $this->view->render();

            return;
        }

        $this->model('User');

        if(isset($_POST['create-account'])) {

            if($_POST['firstname'] && $_POST['lastname'] && $_POST['username'] && $_POST['email'] && $_POST['password']) {

                $this->model->createAccount($_POST['firstname'], $_POST['lastname'], $_POST['username'],
                $_POST['email'], $_POST['password']);

                header('Location: /login/index/?account-created=1', false);
            }
        }

        if(isset($_REQUEST['un'])) {

            $usernameLength = strlen($_REQUEST['un']);

            if($usernameLength == 0) {

                echo json_encode(['valid'=>false, 'message'=>'Enter a Username']);
                return;
            }

            if(preg_match('/^[a-zA-Z0-9_]*[a-zA-Z0-9_]$/', $_REQUEST['un'])) {
                
                if($usernameLength <= 1) {

                    echo json_encode(['valid'=>false, 'message'=>'Username too short']);
                    return;
                }

                if($usernameLength > 20) {

                    echo json_encode(['valid'=>false, 'message'=>'Username too long']);
                    return;
                }

                if(!$this->model->getUserByUsername($_REQUEST['un'])) {

                    echo json_encode(['valid'=>true, 'message'=>'Username valid']);
                }
                else {

                    echo json_encode(['valid'=>false, 'message'=>'Username taken']);
                }
            }
            else {

                echo json_encode(['valid'=>false, 'message'=>'Username invalid']);
            }

            return;
        }

        if(isset($_REQUEST['em'])) {

            if($this->model->getUserByEmail($_REQUEST['em'])) {

                echo json_encode(['valid'=>false, 'message'=>'Email taken']);

                return;
            }

            if(filter_var($_REQUEST['em'], FILTER_VALIDATE_EMAIL)) {

                echo json_encode(['valid'=>true, 'message'=>'Email valid']);
            }
            else {

                echo json_encode(['valid'=>false, 'message'=>'Email invalid']);
            }

            return;
        }

        $this->view('/createAccount');

        $this->view->render();
    }


    public function test() {

        echo 'username valid';
    }
}
?>