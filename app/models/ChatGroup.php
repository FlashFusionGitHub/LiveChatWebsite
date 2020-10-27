<?php

class ChatGroup extends Model {

    function createChatGroup($user_id, $chat_name) {
        $this->query('INSERT INTO chat_group VALUES (\'\', :creator_id, :chat_name)',
        array(':creator_id'=>$user_id, ':chat_name'=>$chat_name));
    }


    function getChatGroup($user_id) {
        return $this->query('SELECT * FROM chat_group WHERE creator_id=:creator_id',
        array(':creator_id'=>$user_id));
    }

    function deleteChatGroup() {

    }
}

?>