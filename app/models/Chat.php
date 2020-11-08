<?php

class Chat extends Model {

    function createChat($chat_id, $user_id, $message) {

        $datetime_formatted = date_format(new DateTime(), 'Y-m-d H:i:s');

        $this->query('INSERT INTO chat VALUES(\'\', :chat_id, :user_id, :message, :posted)',
        array(':chat_id'=>$chat_id, ':user_id'=>$user_id, ':message'=>$message, ':posted'=>$datetime_formatted));
    }

    function getChat($chat_group_id) {

        if($this->query('SELECT * FROM chat WHERE chat_id=:chat_id', array(':chat_id'=>$chat_group_id))) {

            return $this->query('SELECT * FROM chat WHERE chat_id=:chat_id', array(':chat_id'=>$chat_group_id));
        }
        else {

            return false;
        }
    }

    function getChatByUserId($user_id) {
        
        return $this->query('SELECT * FROM chat WHERE user_id=:user_id',
        array(':user_id'=>$user_id));
    }

    function deleteChat($chat_group_id) {
        
        return $this->query('DELETE FROM chat WHERE chat_id=:chat_id',
        array(':chat_id'=>$chat_group_id));
    }
}

?>