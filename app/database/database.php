<?php
class Database {

    public static $host = "localhost";
    public static $databaseName = "live_chat_db";
    public static $username = "root";
    public static $password = "";

    private static function connect() {
        try {
            $connection = new PDO("mysql:host=".self::$host.";dbname=".self::$databaseName.";charset=utf8", self::$username, self::$password);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $connection;
        }
        catch(PDOexception $e) {
            die("Connection failed, We'll get'em next time!... " . $e->getMessage());
        }
    }

    public static function query($query, $params = array()) {

        $statement = self::connect()->prepare($query);

        try {
            if($statement != null) {
                $statement->execute($params);

                if(explode(' ', $query)[0] == 'SELECT') {
                    $data = $statement->fetchAll();
                    return $data;
                }
            }
        }
        catch(PDOexception $e) {
            die("Oops, something went wrong... " . $e->getMessage());
        }
    }
}
?>