<?php
class account extends Controller {

    //access token: 6ae957e9f426bdcbfc0f04e2c5cbaabf6eae716f
    //refresh token: b29f68e457d1d91c34094bdd14809d0aa25f2446

    private $user;

    public function index() {

        $this->model('LoginToken');

        $userid = $this->model->verifyLoginToken();

        if(!$this->model->verifyLoginToken()) {

            $this->view('/error', ['error_title' => 'Error 404', 'error_message' => 'Error: Not Logged In']);

            $this->view->render();

            return;
        }

        $this->model('User');

        $this->user = $this->model->getUser($userid);

        if(isset($_POST['upload_profile_image'])) {

            $file = base64_encode(file_get_contents($_FILES['profile_image']['tmp_name']));

            $options = array('http' => array(
                'method' => "POST",
                'header' => "Authorization: Bearer 6ae957e9f426bdcbfc0f04e2c5cbaabf6eae716f\n" .
                "Content-Type: application/x-www-form-urlencode",
                'content' => $file
            ));
    
            $context = stream_context_create($options);

            $file_ext = strtolower($_FILES['profile_image']['type']);

            $response = file_get_contents('https://api.imgur.com/3/image', false, $context);

            $this->model->setProfileImage(json_decode($response)->data->link, $userid);

            $this->image_successful = 'Profile image updated';

            header("Location: /account/index/");
        }

        if(isset($_POST['edit-name'])) {

            if($_POST['firstname'] != null) {
                
                $this->model->changeFirstname($userid, $_POST['firstname']);
            }

            if($_POST['lastname'] != null) {

                $this->model->changeLastname($userid, $_POST['lastname']);
            }

            header("Location: /account/index/update?field=Name&" . "update_successful=1");
        }

        if(isset($_POST['edit-username'])) {

            $this->model->changeUsername($userid, $_POST['edit-username-text']);

            header("Location: /account/index/update?field=Username&" . "update_successful=1");
        }


        if(isset($_POST['edit-email'])) {

            $this->model->changeEmail($userid, $_POST['edit-email-text']);

            header("Location: /account/index/update?field=Email&" . "update_successful=1");
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
                
                $this->update_email = true;
            }
            else {

                echo json_encode(['valid'=>false, 'message'=>'Email invalid']);
            }

            return;
        }


        if(isset($_POST['edit_password'])) {

            if($this->model->loginCheckPassword($_POST['current-account-password'], $this->model->getUser($userid)[0]['email'])) {

                $this->model->changePassword($userid, $_POST['password']);

                header("Location: /account/index/update?field=Password&" . "update_successful=1");
            }
            else {

                header("Location: /account/index/update?field=Password&" . "update_failed=1");
            }
        }

        $this->view('/account',
        ['firstname' => $this->user[0]['firstname'],
         'lastname' => $this->user[0]['lastname'],
         'email' => $this->user[0]['email'],
         'username' => $this->user[0]['username'],
         'profile_image' =>$this->user[0]['profile_image']]);

        $this->view->render();
    }
}
?>