<?php
class LoginToken extends Model {

    function generateLoginToken($email) {

        $crypto_strong = true;

        $token = bin2hex(openssl_random_pseudo_bytes(64, $crypto_strong));

        $userid = $this->query('SELECT id FROM users WHERE email=:email',
        array(':email'=>$email))[0]['id'];

        $this->query('INSERT INTO login_tokens VALUES (\'\', :token, :user_id)',
        array(':token'=>sha1($token), ':user_id'=>$userid));

        return $token;
    }

    function verifyLoginToken() {

        if(isset($_COOKIE['JAR'])) {

            if($this->query('SELECT user_id FROM login_tokens WHERE token=:token',
            array(':token'=>sha1($_COOKIE['JAR'])))) {

                $userid = $this->query('SELECT user_id FROM login_tokens WHERE token=:token',
                array(':token'=>sha1($_COOKIE['JAR'])))[0]['user_id'];

                if(isset($_COOKIE['JAR_'])) {

                    return $userid;
                } 
                else {

                    $crypto_strong = true;

                    $token = bin2hex(openssl_random_pseudo_bytes(64, $crypto_strong));

                    $this->query('INSERT INTO login_tokens VALUES (\'\', :token, :user_id)',
                    array(':token'=>sha1($token), ':user_id'=>$userid));

                    $this->query('DELETE from login_tokens WHERE token=:token',
                    array(':token'=>sha1($_COOKIE['JAR'])));

                    setcookie("JAR", $token, time() + 60 * 60 * 24 * 7, '/', null, null, false);

                    setcookie("JAR_", '1', time() + 60 * 60 * 24 * 2, '/', null, null, false);

                    return $userid;
                }
            }
            else {

                setcookie("JAR", '1', time()-3600);
                setcookie("JAR_", '1', time()-3600);
            }
        }

        return false;
    }
}
?>