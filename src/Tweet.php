<?php

class Tweet
{
    private $id;
    private $userId;
    private $text;
    private $creationDate;

    public function __construct()
    {
        $this->id = -1;
        $this->userId = '';
        $this->text = '';
        $this->creationDate = '';
    }


    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }

    static public function loadTweetById(PDO $conn, $id)
    {
        $stmt = $conn->prepare('SELECT * FROM Tweets WHERE id=:id');
        $result = $stmt->execute(['id' => $id]);
        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedTweet = new Tweet();
            $loadedTweet->id = $row['id'];
            $loadedTweet->userId = $row['user_id'];
            $loadedTweet->text = $row['text'];
            $loadedTweet->creationDate = $row['creation_date'];
            return $loadedTweet;
        }
        return null;
    }

    static public function loadAllTweetsByUserId(PDO $conn, $userId)
    {
        $stmt = $conn->prepare('SELECT * FROM Tweets WHERE user_id=:user_id');
        $result = $stmt->execute(['user_id' => $userId]);
        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedTweet = new Tweet();
            $loadedTweet->id = $row['id'];
            $loadedTweet->userId = $row['user_id'];
            $loadedTweet->text = $row['text'];
            $loadedTweet->creationDate = $row['creation_date'];
            return $loadedTweet;
        }
        return null;
    }

    static public function loadAllTweets(PDO $conn)
    {
        $ret = [];
        $sql = "SELECT * FROM Tweets ORDER BY creation_date DESC";
        $result = $conn->query($sql);
        if ($result !== false && $result->rowCount() > 0) {
            foreach ($result as $row) {
                $loadedTweet = new Tweet();
                $loadedTweet->id = $row['id'];
                $loadedTweet->userId = $row['user_id'];
                $loadedTweet->text = $row['text'];
                $loadedTweet->creationDate = $row['creation_date'];
                $ret[] = $loadedTweet;
            }
        }
        return $ret;
    }

    public function saveToDB(PDO $conn)
    {
        if ($this->id == -1) {

            $sql = 'INSERT INTO Tweets(user_id, text, creation_date) VALUES(:user_id, :text, :creation_date)';
            $stmt = $conn->prepare($sql);
            $result = $stmt->execute(['user_id' => $this->userId, 'text' => $this->text, 'creation_date' => $this->creationDate]);
            if ($result !== false) {
                $this->id = $conn->lastInsertId();
                return true;
            }
        }
        return false;
    }
}