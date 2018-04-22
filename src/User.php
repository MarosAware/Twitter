<?php

class User
{
    private
    $id,
    $username,
    $hashPass,
    $email;

    public function __construct()
    {
        $this->id = -1;
        $this->username = "";
        $this->email = "";
        $this->hashPass = "";
    }

    public function __destruct()
    {
        // TODO: Implement __destruct() method.
        echo "Zniszczony";
    }

    //Login Method

    public function login(PDO $conn, $email, $password)
    {
        if(self::isValidEmail($email) && self::isValidPassword($password)) {
            $dbPassword = $this->getDBPasswordByEmail($conn, $email);

            if($dbPassword !== false && password_verify($password, $dbPassword)) {
                return self::loadUserById($conn, $this->getUserIdByEmail($conn, $email));
            } else {
                return false;
            }
        }
    }

    //Validation Method Below:

    public static function isValidPassword($password)
    {
        if(!empty($password) && strlen($password) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function isValidUserName($username)
    {
        if(!empty($username) && strlen($username) > 0 && is_string($username)) {
            return true;
        } else {
            return false;
        }
    }

    public static function isValidEmail($email)
    {
        if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) !== false) {
            return true;
        } else {
            return false;
        }
    }

    //Validation Method End

    public function setPassword($newPass)
    {
        if($this->isValidPassword($newPass)) {
            $options = ['cost' => 10];

            $newHashedPass = password_hash($newPass, PASSWORD_BCRYPT, $options);
            $this->hashPass = $newHashedPass;
            return true;
        } else {
            return false;
        }

    }

    public function saveToDB(PDO $conn)
    {
        if ($this->id == -1) { /* Saving new user to DB */
            $stmt = $conn->prepare(
                'INSERT INTO Users(username, email, hash_pass) VALUES (:username, :email, :pass)' );
            $result = $stmt->execute(
                [ 'username' => $this->username,
                'email' => $this->email,
                'pass' => $this->hashPass]);
            if ($result !== false) {
                $this->id = $conn->lastInsertId();
                return true;
            } else { // User exists so we update User
                $stmt = $conn->prepare(
                    'UPDATE Users SET username=:username, email=:email, hash_pass=:hash_pass WHERE id=:id');
                $result = $stmt->execute(
                    [ 'username' => $this->username,
                        'email' => $this->email,
                        'hash_pass' => $this->hashPass,
                        'id' => $this->id,
                    ]);
                if ($result === true) { return true; }
            }
        }
        return false;
    }

    public static function loadUserById(PDO $conn, $id)
    {
        $stmt = $conn->prepare('SELECT * FROM Users WHERE id=:id');
        $result = $stmt->execute(['id' => $id]);
        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->username = $row['username'];
            $loadedUser->hashPass = $row['hash_pass'];
            $loadedUser->email = $row['email'];
            return $loadedUser;
        }
        return null;
    }

    public static function loadAllUsers(PDO $conn)
    {
        $ret = []; $sql = "SELECT * FROM Users";
        $result = $conn->query($sql);
        if ($result !== false && $result->rowCount() != 0) {
            foreach ($result as $row) {
                $loadedUser = new User();
                $loadedUser->id = $row['id'];
                $loadedUser->username = $row['username'];
                $loadedUser->hashPass = $row['hash_pass'];
                $loadedUser->email = $row['email'];
                $ret[] = $loadedUser;
            }
        }
        return $ret;
    }

    public function delete(PDO $conn)
    {
        if ($this->id != -1) {
            $stmt = $conn->prepare('DELETE FROM Users WHERE id=:id');
            $result = $stmt->execute(['id' => $this->id]);
            if ($result === true) {
                $this->id = -1;
                return true;
            }
            return false;
        }
        return true;
    }

    public static function checkEmailExists(PDO $conn, $email)
    {
        $stmt = $conn->prepare('SELECT email FROM Users WHERE email=:email');
        $stmt->bindValue(1, $email, PDO::PARAM_STR);
        $result = $stmt->execute(['email' => $email]);

        if($result === true && $stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function setUserName($username)
    {
        if($this->isValidUserName($username)) {
            $this->username = $username;
            return true;
        } else {
            return false;
        }

    }

    public function setEmail($email)
    {
        if($this->isValidEmail($email)) {
            $this->email = $email;
            return true;
        } else {
            return false;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserName()
    {
        return $this->username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    private function getDBPasswordByEmail(PDO $conn, $email)
    {
        $stmt = $conn->prepare('SELECT hash_pass FROM Users WHERE email=:email');
        $result = $stmt->execute(['email' => $email]);

        if($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['hash_pass'];
        } else {
            return false;
        }
    }

    private function getUserIdByEmail(PDO $conn, $email)
    {
        $stmt = $conn->prepare('SELECT id FROM Users WHERE email=:email');
        $result = $stmt->execute(['email' => $email]);

        if($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['id'];
        } else {
            return false;
        }
    }



}

//$user = new User();
//$user->setUserName('Wiesiek');
//$user->setPassword('wiesiek123');
//$user->setEmail('wiesiek@gmail.com');
//var_dump($user->saveToDB(Database::connect()));
//
//var_dump($user->saveToDB(Database::connect()));