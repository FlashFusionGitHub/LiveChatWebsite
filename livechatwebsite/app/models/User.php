<?php

class User extends Model {

    function getUser($userid) {

        if($this->query('SELECT * FROM users WHERE id=:id', array(':id'=>$userid))) {

            return $this->query('SELECT * FROM users WHERE id=:id', array(':id'=>$userid));
        }

        return false;
    }

    function getUserByUsername($username) {

        if($this->query('SELECT * FROM users WHERE username=:username', array(':username'=>$username))) {

            return $this->query('SELECT * FROM users WHERE username=:username', array(':username'=>$username));
        }

        return false;
    }

    function getUserByEmail($email) {

        if($this->query('SELECT * FROM users WHERE email=:email', array(':email'=>$email))) {

            return $this->query('SELECT * FROM users WHERE email=:email', array(':email'=>$email));
        }

        return false;
    }

    function setProfileImage($profileImageURL, $userid) {
        
        $this->query('UPDATE users SET profile_image=:profile_image WHERE id=:userid',
        array(':profile_image'=>$profileImageURL, ':userid'=>$userid));
    }

    function createAccount($firstname, $lastname, $username, $email, $password) {

        $this->query('INSERT INTO users VALUES (\'\', :firstname, :lastname, :email, :username, :password, \'false\', \'https://i.imgur.com/iKyeoO6.jpg\')',
        array(':firstname'=>$firstname, ':lastname'=>$lastname, ':email'=>$email, ':username'=>$username,
        ':password'=>password_hash($password, PASSWORD_BCRYPT)));
    }

    function changeFirstname($userid, $firstname) {

        $this->query('UPDATE users SET firstname=:firstname WHERE id=:userid',
        array(':firstname'=>$firstname, ':userid'=>$userid));
    }

    function changeLastName($userid, $lastname) {

        $this->query('UPDATE users SET lastname=:lastname WHERE id=:userid',
        array(':lastname'=>$lastname, ':userid'=>$userid));
    }

    function changeUsername($userid, $username) {

        $this->query('UPDATE users SET username=:username WHERE id=:userid',
        array(':username'=>$username, ':userid'=>$userid));
    }

    function changePassword($userid, $password) {

        $this->query('UPDATE users SET password=:password WHERE id=:userid',
        array(':password'=>password_hash($password, PASSWORD_BCRYPT), ':userid'=>$userid));
    }

    function changeEmail($userid, $email) {

        $this->query('UPDATE users SET email=:email WHERE id=:userid',
        array(':email'=>$email, ':userid'=>$userid));
    }

    function isUsernameTaken($username) {

        if($this->query('SELECT username from users WHERE username=:username',
        array(':username'=>$username))) {

            return true;
        }

        return false;
    }

    function isEmailTaken($email) {

        if($this->query('SELECT email from users WHERE email=:email',
        array(':email'=>$email))) {

            return true;
        }

        return false;
    }

    function loginCheckEmail($email) {

        if($this->query('SELECT email FROM users WHERE email=:email',
        array(':email'=>$email))) {

            return true;
        }

        return false;
    }

    function loginCheckPassword($password, $email) {

        static $hashedPassword = 0;
        
        $hashedPassword = $this->query('SELECT password FROM users WHERE email=:email',
        array(':email'=>$email))[0]['password'];

        if(password_verify($password, $hashedPassword)) {

            return true;
        }

        return false;
    }

    public function logoutUser() {
        if(isset($_COOKIE['JAR'])) {

            $this->query('DELETE FROM login_tokens WHERE token=:token',
            array(':token'=>sha1($_COOKIE['JAR'])));
        }

        setcookie("JAR", '1', time()-3600);
        setcookie("JAR_", '1', time()-3600);
    }

    function logoutAllDevices() {

        if(isset($_COOKIE['JAR'])) {

            $userid = $this->query('SELECT user_id FROM login_tokens WHERE token=:token',
            array(':token'=>sha1($_COOKIE['JAR'])))[0]['user_id'];

            $this->query('DELETE FROM login_tokens WHERE user_id=:user_id',
            array(':user_id'=>$userid));
        }

        setcookie('JAR', '1', time()-3600);
        setcookie('JAR_', '1', time()-3600);
    }
}
?>