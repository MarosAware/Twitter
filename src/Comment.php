<?php

class Comment
{
    private $id, $userId, $postId, $creation_date, $text;


    public function __construct()
    {
        $this->id = -1;
        $this->userId = "";
        $this->postId = "";
        $this->creation_date = "";
        $this->text = "";
    }

    private function isValidText($text)
    {
        if (!empty($text) && mb_strlen($text) > 0 && mb_strlen($text) <= 60) {
            return true;
        } else {
            return false;
        }
    }

    public static function loadAllCommentsByUserId(PDO $conn, $userId)
    {
        $stmt = $conn->prepare('SELECT * FROM Comments WHERE userId=:userId');
        $result = $stmt->execute(['userId' => $userId]);

        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedComment = new Comment();
            $loadedComment->id = $row['id'];
            $loadedComment->userId = $row['userId'];
            $loadedComment->postId = $row['postId'];
            $loadedComment->text = $row['text'];
            $loadedComment->creation_date = $row['creation_date'];
            return $loadedComment;
        }
        return null;
    }

    public static function loadCommentById(PDO $conn, $id)
    {
        $stmt = $conn->prepare('SELECT * FROM Comments WHERE id=:id');
        $result = $stmt->execute(['id' => $id]);

        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedComment = new Comment();
            $loadedComment->id = $row['id'];
            $loadedComment->userId = $row['userId'];
            $loadedComment->postId = $row['postId'];
            $loadedComment->text = $row['text'];
            $loadedComment->creation_date = $row['creation_date'];
            return $loadedComment;
        }
        return null;
    }

    public static function loadAllCommentsByPostId(PDO $conn, $postId)
    {
        $allComments = [];
        $stmt = $conn->prepare('SELECT * FROM Comments WHERE postId=:postId ORDER BY id DESC');
        $result = $stmt->execute(['postId' => $postId]);

        if ($result === true && $stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $loadedComment = new Comment();
                $loadedComment->id = $row['id'];
                $loadedComment->userId = $row['userId'];
                $loadedComment->postId = $row['postId'];
                $loadedComment->text = $row['text'];
                $loadedComment->creation_date = $row['creation_date'];
                $allComments[] = $loadedComment;
            }
        }
        return $allComments;
    }

    public static function loadAllComments(PDO $conn)
    {
        $allComments = [];
        $query = "SELECT * FROM Comments ORDER BY id DESC";
        $result = $conn->query($query);


        if ($result !== false && $result->rowCount() != 0) {
            foreach ($result as $row) {
                $loadedComment = new Comment();
                $loadedComment->id = $row['id'];
                $loadedComment->userId = $row['userId'];
                $loadedComment->postId = $row['postId'];
                $loadedComment->text = $row['text'];
                $loadedComment->creation_date = $row['creation_date'];
                $allComments[] = $loadedComment;
            }
        }
        return $allComments;
    }

    public function saveToDB(PDO $conn)
    {
        if ($this->id === -1) { //Save Comment to DB
            $query = "INSERT INTO Comments (userId, postId, text, creation_date) VALUES(:userId, :postId, :text, :creation_date)";

            $stmt = $conn->prepare($query);
            $result = $stmt->execute(
                [
                    'userId' => $this->userId,
                    'postId' => $this->postId,
                    'text' => $this->text,
                    'creation_date' => $this->creation_date
                ]
            );

            if ($result !== false) {
                $this->id = $conn->lastInsertId();
                return true;
            }

        } else { //Comment Exist so we update
            $stmt = $conn->prepare(
                'UPDATE Comments SET userId=:userId, postId=:postId, text=:text, creation_date=:creation_date WHERE id=:id');

            $result = $stmt->execute(
                [
                    'userId' => $this->userId,
                    'postId' => $this->postId,
                    'text' => $this->text,
                    'creation_date' => $this->creation_date
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

    public function getCreation_date()
    {
        return $this->creation_date;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function setPostId($postId)
    {
        $this->postId = $postId;
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

    public function setCreation_date($creation_date = 'now')
    {
        $date = new DateTime($creation_date);
        $date = $date->format('Y-m-d H:i:s');
        $this->creation_date = $date;
    }
    
}