<?php

class Tweet
{
    private $id, $userId, $text, $creationDate;

    public function __construct()
    {
        $this->id = -1;
        $this->userId = "";
        $this->text = "";
        $this->creationDate = "";
    }

    private function isValidText($text)
    {
        if ((empty($text) === false) && mb_strlen($text) > 0 && mb_strlen($text) <= 140) {
            return true;
        } else {
            return false;
        }
    }

    public static function loadTweetById(PDO $conn, $id)
    {
        $stmt = $conn->prepare('SELECT * FROM Tweets WHERE id=:id');
        $result = $stmt->execute(['id' => $id]);

        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedTweet = new Tweet();
            $loadedTweet->id = $row['id'];
            $loadedTweet->userId = $row['userId'];
            $loadedTweet->text = $row['text'];
            $loadedTweet->creationDate = $row['creationDate'];
            return $loadedTweet;
        }
        return null;
    }

    public static function loadAllTweetsByUserId(PDO $conn, $userId)
    {
        $allTweets = [];
        $stmt = $conn->prepare('SELECT * FROM Tweets WHERE userId=:userId');
        $result = $stmt->execute(['userId' => $userId]);

        if ($result === true && $stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $loadedTweet = new Tweet();
                $loadedTweet->id = $row['id'];
                $loadedTweet->userId = $row['userId'];
                $loadedTweet->text = $row['text'];
                $loadedTweet->creationDate = $row['creationDate'];
                $allTweets[] = $loadedTweet;
            }
        }
        return $allTweets;
    }

    public static function loadAllTweets(PDO $conn)
    {
        $allTweets = [];
        $query = "SELECT * FROM Tweets ORDER BY id DESC";
        $result = $conn->query($query);


        if ($result !== false && $result->rowCount() != 0) {
            foreach ($result as $row) {
                $loadedTweet = new Tweet();
                $loadedTweet->id = $row['id'];
                $loadedTweet->userId = $row['userId'];
                $loadedTweet->text = $row['text'];
                $loadedTweet->creationDate = $row['creationDate'];
                $allTweets[] = $loadedTweet;
            }
        }
        return $allTweets;
    }

    public function saveToDB(PDO $conn)
    {
        if ($this->id === -1) { //Save Tweet to DB
            $query = "INSERT INTO Tweets (userId, text, creationDate) VALUES(:userId, :text, :creationDate)";

            $stmt = $conn->prepare($query);
            $result = $stmt->execute(
                [
                    'userId' => $this->userId,
                    'text' => $this->text,
                    'creationDate' => $this->creationDate
                ]
            );

            if ($result !== false) {
                $this->id = $conn->lastInsertId();
                return true;
            }

        } else { //Tweet Exist so we update
            $stmt = $conn->prepare(
                'UPDATE Tweet SET userId=:userId, text=:text, creationDate=:creationDate WHERE id=:id');

            $result = $stmt->execute(
                [
                    'userId' => $this->userId,
                    'text' => $this->text,
                    'creationDate' => $this->creationDate
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

    public function getUserId()
    {
        return $this->userId;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
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

    public function setCreationDate($creationDate = 'now')
    {
        $date = new DateTime($creationDate);
        $date = $date->format('Y-m-d H:i:s');
        $this->creationDate = $date;
    }

}