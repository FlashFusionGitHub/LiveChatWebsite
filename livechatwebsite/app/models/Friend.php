<?php

class Friend extends Model {

    function addFriend($friend_id, $user_id) {

        $friendRequest = new FriendRequest('FriendRequest', '');

        if($friendRequest->requestSent($user_id, $friend_id)) {

            if(!$this->alreadyFriends($user_id, $friend_id)) {

            $this->query('DELETE FROM friend_requests WHERE (sender_id=:sender_id AND receiver_id=:receiver_id) OR (sender_id=:receiver_id AND receiver_id=:sender_id)',
            array(':sender_id'=>$user_id, ':receiver_id'=>$friend_id));

            $this->query('INSERT INTO friends VALUES (\'\', :user1_id, :user2_id)',
            array(':user1_id'=>$user_id, ':user2_id'=>$friend_id));

            return true;
            }
        }

        return false;
    }

    function getAllFriends($user_id) {

        if($this->query('SELECT * FROM friends WHERE (user1_id=:user1_id) OR (user2_id=:user2_id)', array(':user1_id'=>$user_id, ':user2_id'=>$user_id))) {
            
            return ($this->query('SELECT * FROM friends WHERE (user1_id=:user1_id) OR (user2_id=:user2_id)',
            array(':user1_id'=>$user_id, ':user2_id'=>$user_id)));
        }

        return false;
    }

    function alreadyFriends($user_id, $friend_id) {

        if($this->query('SELECT * FROM friends WHERE (user1_id=:user1_id AND user2_id=:user2_id) OR (user1_id=:user2_id AND user2_id=:user1_id)',
         array(':user1_id'=>$user_id, ':user2_id'=>$friend_id))) {

            return true;
        }

        return false;
    }

    function getFriendId($username) {

        if($this->query('SELECT id FROM users WHERE username=:username', array(':username'=>$username))) {

            return $this->query('SELECT id FROM users WHERE username=:username', array(':username'=>$username))[0]['id'];
        }

        return false;
    }

    function removeFriend($friend_id, $user_id) {

        if($this->alreadyFriends($user_id, $friend_id)) {

            $this->query('DELETE FROM friends WHERE (user1_id=:user1_id AND user2_id=:user2_id) OR (user1_id=:user2_id AND user2_id=:user1_id)',
            array(':user1_id'=>$user_id, ':user2_id'=>$friend_id));

            return true;
        }

        return false;
    }
}

?>