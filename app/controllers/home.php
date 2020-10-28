<?php

class home extends Controller {

    private $friends;
    private $user;
    private $activeChats;
    private $requests;

    private $selected_chat;

    public function index() {

        $this->model('LoginToken');

        if(!$this->model->verifyLoginToken()){

            header("Location: /login/");

            return;
        }

        $userid = $this->model->verifyLoginToken();

        $this->model('Friend');

        $friends = $this->model->getAllFriends($userid);

        if($friends != null) {

            $this->model('User');

            foreach($friends as $value => $friend) {

                if($friend['user1_id'] != $userid) {
                    $this->friends[$value]['name'] = htmlspecialchars($this->model->getUser($friend['user1_id'])[0]['username']);
                    $this->friends[$value]['image'] = htmlspecialchars($this->model->getUser($friend['user1_id'])[0]['profile_image']);
                }
                else {
                    $this->friends[$value]['name'] = htmlspecialchars($this->model->getUser($friend['user2_id'])[0]['username']);
                    $this->friends[$value]['image'] = htmlspecialchars($this->model->getUser($friend['user2_id'])[0]['profile_image']);
                }

            }
        }

        $this->model('FriendRequest');

        $requests = $this->model->requestReceiver($userid);

        if($requests != null) {

            $this->model('User');

            foreach($requests as $value => $request) {

                if($request['sender_id'] != $userid) {
                    $this->requests[$value]['id'] = $this->model->getUser($request['sender_id'])[0]['id'];
                    $this->requests[$value]['name'] = htmlspecialchars($this->model->getUser($request['sender_id'])[0]['username']);
                    $this->requests[$value]['image'] = htmlspecialchars($this->model->getUser($request['sender_id'])[0]['profile_image']);
                }
            }
        }

        if(isset($_POST['acceptBtn'])) {

            $this->model('FriendRequest');

            if($this->model->requestSent($userid, $_POST['acceptBtn'])) {
    
                $this->model('Friend');
                
                if(!$this->model->alreadyFriends($userid, $_POST['acceptBtn'])) {
                
                    $this->model->addFriend($_POST['acceptBtn'], $userid);

                    header('Location:' . $_SERVER['REQUEST_URI']);
                }
            }
        }

        if(isset($_POST['declineBtn'])) {
    
            $this->model('FriendRequest');

            if($this->model->requestSent($userid, $_POST['declineBtn'])) {
    
                $this->model->cancelRequest($userid, $_POST['declineBtn']);

                header('Location:' . $_SERVER['REQUEST_URI']);
            }
        }

        if(isset($_POST['create-chat-button'])) {

            if($_POST['chat-group-name'] != null) {

                $this->model('ChatGroup');

                $this->model->createChatGroup($userid, $_POST['chat-group-name']);

                $group_chat = $this->model->getChatGroupByCreatorId($userid);

                $this->model('ChatGroupMember');

                $this->model->createChatGroupMember($group_chat[count($group_chat) - 1]['id'], $userid);
            }
        }

        if(isset($_REQUEST['group_chat'])) {

            $this->model('Chat');

            $this->selected_chat = $this->model->getChat($_REQUEST['group_chat']);

            $username_array = array();

            $this->model('User');

            foreach($this->selected_chat as $key => $sc) {
                
                $this->selected_chat[$key]['username'] = $this->model->getUser($sc['user_id'])[0]['username'];
            }
            
            echo json_encode($this->selected_chat);

            return;
        }

        /*
        if(isset($_REQUEST['chat'])) {

            $this->model('Chat');

            $this->selected_chat = $this->model->getChat($_REQUEST['chat']);
        }
        
        if(isset($_POST['chat-group-btn'])) {

            header('Location: /home/index/activechat?chat=' . $_POST['chat-group-btn']);
        }*/

        if(isset($_POST['send-message-button'])) {

            $this->model('Chat');

            $this->model->createChat($_POST['send-message-button'], $userid, 'NAME', $_POST['message']);
        }

        $this->model('ChatGroupMember');

        $active_chats = $this->model->getChatGroupByMemberId($userid);

        $this->model('User');

        $this->view('home',
        ['username' => htmlspecialchars($this->model->getUser($userid)[0]['username']),
        'profile_img' => htmlspecialchars($this->model->getUser($userid)[0]['profile_image']),
        'friends' => $this->friends,
        'requests' => $this->requests,
        'active_chats' => htmlspecialchars($this->activeChats),
        'admin' => $this->model->getUser($userid)[0]['admin'],
        'active_chats' => $active_chats,
        'selected_chat' => $this->selected_chat]);

        $this->view->render();
    }
}

?>