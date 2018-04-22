<?php

class Message
{
    private $id, $userIdSend, $userIdGet, $text, $creation_date, $isRead;


    public function __construct()
    {
        $this->id = -1;
        $this->userIdSend = "";
        $this->userIdGet = "";
        $this->creation_date = "";
        $this->text = "";
        $this->isRead = 0;
    }

    private function isValidText($text)
    {
        if (!empty($text) && mb_strlen($text) > 0 && mb_strlen($text) <= 252) {
            return true;
        } else {
            return false;
        }
    }

    public static function loadAllMessagesByUserIdSend(PDO $conn, $userIdSend)
    {
        $allMessages = [];
        $stmt = $conn->prepare('SELECT * FROM Messages WHERE userIdSend=:userIdSend ORDER BY id DESC');
        $result = $stmt->execute(['userIdSend' => $userIdSend]);

        if ($result === true && $stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $loadedMessage = new Message();
                $loadedMessage->id = $row['id'];
                $loadedMessage->userIdSend = $row['userIdSend'];
                $loadedMessage->userIdGet = $row['userIdGet'];
                $loadedMessage->text = $row['text'];
                $loadedMessage->creation_date = $row['creation_date'];
                $loadedMessage->isRead = $row['isRead'];
                $allMessages[] = $loadedMessage;
            }
        }
        return $allMessages;
    }

    public static function loadMessageById(PDO $conn, $id)
    {
        $stmt = $conn->prepare('SELECT * FROM Messages WHERE id=:id');
        $result = $stmt->execute(['id' => $id]);

        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedMessage = new Message();
            $loadedMessage->id = $row['id'];
            $loadedMessage->userIdSend = $row['userIdSend'];
            $loadedMessage->userIdGet = $row['userIdGet'];
            $loadedMessage->text = $row['text'];
            $loadedMessage->creation_date = $row['creation_date'];
            $loadedMessage->isRead = $row['isRead'];
            return $loadedMessage;
        }
        return null;
    }

    public static function loadAllMessagesByUserIdGet(PDO $conn, $userIdGet)
    {
        $allMessages = [];
        $stmt = $conn->prepare('SELECT * FROM Messages WHERE userIdGet=:userIdGet ORDER BY id DESC');
        $result = $stmt->execute(['userIdGet' => $userIdGet]);

        if ($result === true && $stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $loadedMessage = new Message();
                $loadedMessage->id = $row['id'];
                $loadedMessage->userIdSend = $row['userIdSend'];
                $loadedMessage->userIdGet = $row['userIdGet'];
                $loadedMessage->text = $row['text'];
                $loadedMessage->creation_date = $row['creation_date'];
                $loadedMessage->isRead = $row['isRead'];
                $allMessages[] = $loadedMessage;
            }
            return $allMessages;
        }
        return null;
    }

    public static function loadAllMessages(PDO $conn)
    {
        $allMessages = [];
        $query = "SELECT * FROM Messages ORDER BY id DESC";
        $result = $conn->query($query);


        if ($result !== false && $result->rowCount() != 0) {
            foreach ($result as $row) {
                $loadedMessage = new Message();
                $loadedMessage->id = $row['id'];
                $loadedMessage->userIdSend = $row['userIdSend'];
                $loadedMessage->userIdGet = $row['userIdGet'];
                $loadedMessage->text = $row['text'];
                $loadedMessage->creation_date = $row['creation_date'];
                $loadedMessage->isRead = $row['isRead'];
                $allMessages[] = $loadedMessage;
            }
        }
        return $allMessages;
    }

    public function saveToDB(PDO $conn)
    {
        if ($this->id === -1) { //Save Message to DB

            $query = "INSERT INTO Messages (userIdSend, userIdGet, text, creation_date, isRead) 
                      VALUES(:userIdSend, :userIdGet, :text, :creation_date, :isRead)";

            $stmt = $conn->prepare($query);
            $result = $stmt->execute(
                [
                    'userIdSend' => $this->userIdSend,
                    'userIdGet' => $this->userIdGet,
                    'text' => $this->text,
                    'creation_date' => $this->creation_date,
                    'isRead' => $this->isRead
                ]
            );

            if ($result !== false) {
                $this->id = $conn->lastInsertId();
                return true;
            }

        } else { //Message Exist so we update
            $stmt = $conn->prepare(
                'UPDATE Messages SET userIdSend=:userIdSend, userIdGet=:userIdGet, text=:text,
                              creation_date=:creation_date, isRead=:isRead WHERE id=:id');

            $result = $stmt->execute(
                [
                    'id' => $this->id,
                    'userIdSend' => $this->userIdSend,
                    'userIdGet' => $this->userIdGet,
                    'text' => $this->text,
                    'creation_date' => $this->creation_date,
                    'isRead' => $this->isRead
                ]
            );
            if ($result === true) {
                return true;
            }
        }
        return false;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserIdSend()
    {
        return $this->userIdSend;
    }

    public function getUserIdGet()
    {
        return $this->userIdGet;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getCreation_date()
    {
        return $this->creation_date;
    }

    public function getIsRead()
    {
        return $this->isRead;
    }

    public function setUserIdSend($userIdSend)
    {
        $this->userIdSend = $userIdSend;
    }

    public function setUserIdGet($userIdGet)
    {
        $this->userIdGet = $userIdGet;
    }

    public function setText($text)
    {
        if($this->isValidText($text)) {
            $this->text = $text;
            return true;
        }else {
            return false;
        }

    }

    public function setIsRead($boolean)
    {
        $this->isRead = $boolean;
    }

    public function setCreation_date($creation_date = 'now')
    {
        $date = new DateTime($creation_date);
        $date = $date->format('Y-m-d H:i:s');
        $this->creation_date = $date;
    }
}