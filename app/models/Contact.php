<?php

class Contact extends Model {

    function createMessage($message, $email) {

        $this->query('INSERT INTO contact VALUES(\'\', :message, :email)',
         array(':message' => $message, ':email' => $email));
    }
}

?>