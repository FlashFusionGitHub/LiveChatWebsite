<?php

class FriendRequest extends Model {

    // get all sent requests
    function requestSender($user_id) {

        return $this->query('SELECT * FROM friend_requests WHERE (sender_id=:sender_id)',
        array(':sender_id'=>$user_id));
    }

    // get all received requests
    function requestReceiver($user_id) {

        return $this->query('SELECT * FROM friend_requests WHERE (receiver_id=:receiver_id)',
        array(':receiver_id'=>$user_id));
    }

    // check if the request has already been sent
    function requestSent($user_id, $friend_id) {

        if($this->query('SELECT * FROM friend_requests WHERE (sender_id=:sender_id AND receiver_id=:receiver_id) OR (sender_id=:receiver_id AND receiver_id=:sender_id)',
        array(':sender_id'=>$user_id, ':receiver_id'=>$friend_id))) {

            return true;
        }

        return false;
    }

    // send request
    function sendRequest($user_id, $friend_id) {

        $this->query('INSERT INTO friend_requests VALUES (\'\', :sender_id, :receiver_id)',
        array(':sender_id'=>$user_id, ':receiver_id'=>$friend_id));
    }

    // cancel/deny friend request
    function cancelRequest($user_id, $friend_id) {

        if($this->requestSent($user_id, $friend_id)) {

            $this->query('DELETE FROM friend_requests WHERE (sender_id=:sender_id AND receiver_id=:receiver_id) OR (sender_id=:receiver_id AND receiver_id=:sender_id)',
            array(':sender_id'=>$user_id, ':receiver_id'=>$friend_id));
        }
    }
}

?>