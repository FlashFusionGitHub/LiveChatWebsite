<?php


class ChatGroupMember extends Model {

    function createChatGroupMember($group_id, $user_id) {

        $this->query('INSERT INTO chat_group_members VALUES(:group_id, :user_id)',
        array(':group_id'=>$group_id, ':user_id'=>$user_id));
    }

    function removeChatGroupMember($group_id, $user_id) {

        $this->query('DELETE FROM chat_group_members WHERE group_id=:group_id AND user_id=:user_id',
        array(':group_id'=>$group_id, ':user_id'=>$user_id));
    }

    function removeChatGroupMembers($group_id) {

        $this->query('DELETE FROM chat_group_members WHERE group_id=:group_id',
        array(':group_id'=>$group_id));
    }

    function getChatGroupMembers($group_id) {

        return $this->query('SELECT * FROM chat_group_members WHERE group_id=:group_id',
        array(':group_id'=>$group_id));
    }

    function getChatGroupByMemberId($user_id) {
        return $this->query('SELECT * FROM chat_group_members WHERE user_id=:user_id',
        array(':user_id'=>$user_id));
    }
}

?>