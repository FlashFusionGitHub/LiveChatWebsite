<?php

class Chat extends Model {

    function createChat() {

        $this->query('INSERT INTO chat VALUES(\'\', :chat_id, :user_id, :message, :posted)',
        array());
    }

    function getChat($chat_group_id) {
        
        return $this->query('SELECT * FROM chat WHERE chat_id=:chat_id',
        array(':chat_id'=>$chat_group_id));
    }
}

?>