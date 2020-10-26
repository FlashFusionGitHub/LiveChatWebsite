<?php
class profile extends Controller {

    private $friends = '';
    private $pendingRequest = '';
    private $userid = '';
    private $profileId = '';
    private $profileImage = '';
    private $requestReceiver = '';

    public function user($username = '') {

        $username = implode(" ", explode('%20', $username));
        
        $this->model('LoginToken');

        if(!$this->model->verifyLoginToken()) {

            $this->view('/error', ['error_title' => 'We don\'t do that here', 'error_message' => 'Error: Not logged in...']);

            $this->view->render();

            return;
        }

        if(isset($username)) {

            $this->userid = $this->model->verifyLoginToken();

            $this->model('User');

            if($this->model->getUserByUsername($username)) {

                $this->profileId = $this->model->getUserByUsername($username)[0]['id'];

                $this->profileImage = $this->model->getUser($this->profileId)[0]['profile_image'];

                $myUsername = $this->model->getUser($this->userid)[0]['id'];
                $this->profileUsername = $this->model->getUser($this->profileId)[0]['username'];

                if($this->profileUsername == $myUsername) {

                    header('Location: /home');
                }
            }
            else {

                $this->view('/error', ['error_title' => 'This is not the friend you are looking for', 'error_message' => 'User ' . $username . ' does not exist...']);

                $this->view->render();

                return;
            }

            if(isset($_POST['sendFriendRequest'])) {

                $this->model('FriendRequest');

                $this->model->sendRequest($this->userid, $this->profileId);

                header('Location:' . $_SERVER['REQUEST_URI']);
            }

            if(isset($_POST['cancelFriendRequest'])) {

                $this->model('FriendRequest');

                $this->model->cancelRequest($this->userid, $this->profileId);

                header('Location:' . $_SERVER['REQUEST_URI']);
            }

            if(isset($_POST['removeFriend'])) {

                $this->model('Friend');

                $this->model->removeFriend($this->userid, $this->profileId);

                header('Location:' . $_SERVER['REQUEST_URI']);
            }

            $this->model('Friend');
    
            $this->friends = $this->model->alreadyFriends($this->userid, $this->profileId);

            $this->model('FriendRequest');

            $this->pendingRequest = $this->model->requestSent($this->userid, $this->profileId);

            $this->requestReceiver = $this->model->requestReceiver($this->userid);
        }

        $this->view('profile', ['username' => $this->profileUsername,
        'pending' => $this->pendingRequest,
        'request_receiver' => $this->requestReceiver,
        'friends' => $this->friends,
        'image' => $this->profileImage]);

        $this->view->render();
    }
}
?>