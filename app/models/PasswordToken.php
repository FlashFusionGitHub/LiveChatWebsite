<?php
class PasswordToken extends Model {

    function generatePasswordToken($email) {

        if($this->query('SELECT id FROM users WHERE email=:email', array(':email'=>$email))) {

            $crypto_strong = true;

            $token = bin2hex(openssl_random_pseudo_bytes(64, $crypto_strong));

            $userid = $this->query('SELECT id FROM users WHERE email=:email',
            array(':email'=>$email))[0]['id'];

            $this->query('INSERT INTO password_tokens VALUES (\'\', :token, :user_id)',
            array(':token'=>sha1($token), ':user_id'=>$userid));

            return $token;
        }

        return false;
    }

    function verifyPasswordResetToken($token) {

        if($this->query('SELECT user_id FROM password_tokens WHERE token=:token', array(':token'=>sha1($token)))) {

            $userid = $this->query('SELECT user_id FROM password_tokens WHERE token=:token',
                array(':token'=>sha1($token)))[0]['user_id'];

            return $userid;
        }

        return false;
    }

    function deleteToken($userid) {

        $this->query('DELETE FROM password_tokens WHERE user_id=:userid',
            array(':userid'=>$userid));
    }
}
?>