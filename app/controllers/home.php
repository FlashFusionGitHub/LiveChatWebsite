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
                    $this->friends[$value]["name"] = htmlspecialchars($this->model->getUser($friend['user1_id'])[0]['username']);
                    $this->friends[$value]["image"] = htmlspecialchars($this->model->getUser($friend['user1_id'])[0]['profile_image']);
                    $this->friends[$value]["user_id"] = $this->model->getUser($friend['user1_id'])[0]['id'];
                }
                else {
                    $this->friends[$value]["name"] = htmlspecialchars($this->model->getUser($friend['user2_id'])[0]['username']);
                    $this->friends[$value]["image"] = htmlspecialchars($this->model->getUser($friend['user2_id'])[0]['profile_image']);
                    $this->friends[$value]["user_id"] = $this->model->getUser($friend['user2_id'])[0]['id'];
                }

            }
        }

        $this->model('FriendRequest');

        $requests = $this->model->requestReceiver($userid);

        if($requests != null) {

            $this->model('User');

            foreach($requests as $value => $request) {

                if($request['sender_id'] != $userid) {
                    $this->requests[$value]["id"] = $this->model->getUser($request['sender_id'])[0]['id'];
                    $this->requests[$value]["name"] = htmlspecialchars($this->model->getUser($request['sender_id'])[0]['username']);
                    $this->requests[$value]["image"] = htmlspecialchars($this->model->getUser($request['sender_id'])[0]['profile_image']);
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

            $group_members = null;

            $this->model('Chat');

            if($this->model->getChat($_REQUEST['group_chat'])) {
                
                $this->selected_chat = $this->model->getChat($_REQUEST['group_chat']);

                $this->model('ChatGroupMember');

                $group_members = $this->model->getChatGroupMembers($_REQUEST['group_chat']);

                $this->model('User');

                foreach($this->selected_chat as $key => $sc) {
                
                    $this->selected_chat[$key]['username'] = $this->model->getUser($sc['user_id'])[0]['username'];
                }

                foreach($group_members as $key => $gm) {

                    $group_members[$key]['username'] = $this->model->getUser($gm['user_id'])[0]['username'];
                }
            }
            else {

                $this->model('ChatGroupMember');

                $group_members = $this->model->getChatGroupMembers($_REQUEST['group_chat']);

                $this->model('User');

                foreach($group_members as $key => $gm) {

                    $group_members[$key]['username'] = $this->model->getUser($gm['user_id'])[0]['username'];
                }
            }

            echo json_encode(["chat-window" => $this->selected_chat]);

            return;
        }

        if(isset($_REQUEST['message_form'])) {

            $datetime_formatted = date_format(new DateTime(), 'Y-m-d H:i:s');

            $message = json_decode($_REQUEST['message_form']);

            $this->model('Chat');

            $this->model->createChat($message->chat_id, $userid, htmlspecialchars($message->message));

            $this->model('User');

            echo json_encode(["username"=>$this->model->getUser($userid)[0]['username'], "message"=>htmlspecialchars($message->message), "posted"=>$datetime_formatted]);

            return;
        }

        if(isset($_REQUEST['add_user_to_chat'])) {

            $add_to_chat = json_decode($_REQUEST['add_user_to_chat']);

            //Am I friends with the user im inviting, am I a member of this chat group

            $this->model('Friend');

            $friend = $this->model->alreadyFriends($userid, $add_to_chat->user_id);

            $this->model('ChatGroupMember');

            $member = $this->model->getChatGroupMember($userid, $add_to_chat->group_id);

            if($friend == true && $member == true) {

                $this->model->createChatGroupMember($add_to_chat->group_id, $add_to_chat->user_id);

                $group_members = $this->model->getChatGroupMembers($add_to_chat->group_id);

                $this->model('User');

                foreach($group_members as $key => $gm) {

                    $group_members[$key]["username"] = $this->model->getUser($gm['user_id'])[0]['username'];
                }

                echo json_encode(["lobby-list" => $group_members]);

                return;
            }
            else {

                echo json_encode(["lobby-list" => null]);

                return;
            }
        }

        if(isset($_REQUEST['remove_user_from_chat'])) {

            $remove_from_chat = json_decode($_REQUEST['remove_user_from_chat']);

            $this->model('ChatGroupMember');

            $this->model->removeChatGroupMember($remove_from_chat->group_id, $remove_from_chat->user_id);

            $group_members = $this->model->getChatGroupMembers($remove_from_chat->group_id);

            $this->model('User');

            foreach($group_members as $key => $gm) {

                $group_members[$key]["username"] = $this->model->getUser($gm['user_id'])[0]['username'];
            }

            echo json_encode(["lobby-list" => $group_members]);

            return;

        }

        if(isset($_REQUEST['leave-chat-group'])) {

            $this->model('ChatGroup');

            if($userid == $this->model->getChatGroup($_REQUEST['leave-chat-group'])[0]['creator_id']) {

                $this->model('ChatGroupMember');

                $this->model->removeChatGroupMembers($_REQUEST['leave-chat-group']);

                $this->model('ChatGroup');

                $this->model->deleteChatGroup($_REQUEST['leave-chat-group']);

                $this->model('Chat');

                $this->model->deleteChat($_REQUEST['leave-chat-group']);

            }
            else {

                $this->model('ChatGroupMember');

                $this->model->removeChatGroupMember($_REQUEST['leave-chat-group'], $userid);
            }

            echo json_encode(["requests" => $this->requests, "friends" => $this->friends]);

            return;
        }

        if(isset($_REQUEST['chat-groups-list'])) {

            $this->model('ChatGroupMember');

            $chat_groups = $this->model->getChatGroupByMemberId($userid);

            $this->model('ChatGroup');

            foreach($chat_groups as $key => $cg) {

                $chat_groups[$key]["group_name"] = $this->model->getChatGroup($cg['group_id'])[0]['chat_name'];
            }

            echo json_encode($chat_groups);

            return;
        }

        if(isset($_REQUEST['friends-list'])) {

            $this->model('ChatGroupMember');

            $group_member = $this->model->getChatGroupMember($userid, $_REQUEST['friends-list']);

            $group_members = $this->model->getChatGroupMembers($_REQUEST['friends-list']);

            $this->model('Friend');

            $friends = $this->model->getAllFriends($userid);

            if($friends != null) {

                foreach($this->friends as $key => $friend) {

                    foreach($group_members as $val => $gm) {

                        if($friend['user_id'] == $gm['user_id']) {

                            $this->friends[$key]["joined"] = true;
                            break;
                        }

                        else {

                            $this->friends[$key]["joined"] = false;
                        }
                    }
                }

                echo json_encode(["requests" => $this->requests, "friends" => $this->friends, "group" => $group_members, "group_member" => $group_member]);

                return;
            }
            else {

                echo json_encode(["requests" => $this->requests, "friends" => null, "group" => $group_members, "group_member" => $group_member]);

                return;
            }
        }

        if(isset($_REQUEST['lobby-list'])) {

            $this->model('ChatGroupMember');

            $group_member = $this->model->getChatGroupMember($userid, $_REQUEST['lobby-list']);

            $creator = false;

            $this->model('ChatGroup');

            if($this->model->getChatGroup($_REQUEST['lobby-list']) != false) {

                $group_chat = $this->model->getChatGroup($_REQUEST['lobby-list']);

                $this->model('ChatGroupMember');

                $group_members = $this->model->getChatGroupMembers($_REQUEST['lobby-list']);

                $this->model('User');

                foreach($group_members as $key => $gm) {

                    $group_members[$key]['username'] = $this->model->getUser($gm['user_id'])[0]['username'];

                    if($gm['user_id'] == $group_chat[0]['creator_id'])
                    {
                        $group_members[$key]['creator'] = true;
                    }
                    else 
                    {
                        $group_members[$key]['creator'] = false;
                    }
                }

                if($userid == $group_chat[0]['creator_id'])
                    $creator = true;
                else 
                    $creator = false;

                echo json_encode(["lobby_members" => $group_members, "lobby_creator" => $creator, "group_member" => $group_member]);

                return;
            }
            else {
                echo json_encode(["lobby_members" => null, "lobby_creator" => null,  "group_member" => $group_member]);

                return;
            }
        }

        if(isset($_REQUEST['messages'])) {

            $this->model('ChatGroupMember');

            $group_member = $this->model->getChatGroupMember($userid, $_REQUEST['messages']);

            $this->model('ChatGroup');

            if($this->model->getChatGroup($_REQUEST['messages']) != false) {

                $this->model('Chat');

                if($this->model->getChat($_REQUEST['messages']) != false) {

                    $this->selected_chat = $this->model->getChat($_REQUEST['messages']);

                    $this->model('User');

                    foreach($this->selected_chat as $key => $sc) {
                    
                        $this->selected_chat[$key]['username'] = $this->model->getUser($sc['user_id'])[0]['username'];
                    }

                    echo json_encode(["chat" => $this->selected_chat , "group_chat" => 'valid', "chat_group_member" => $group_member]);

                    return;
                }
                else {

                    echo json_encode(["chat" => null, "group_chat" => 'valid', "chat_group_member" => $group_member]);

                    return;
                }
            }
            else {
                echo json_encode(["chat" => null, "group_chat" => null, "chat_group_member" => $group_member]);

                return;
            }
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