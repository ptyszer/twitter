<?php

class Message
{
    private $id;
    private $senderId;
    private $receiverId;
    private $postDate;
    private $text;
    private $readStatus;

    public function __construct()
    {
        $this->id = -1;
        $this->senderId = 0;
        $this->receiverId = 0;
        $this->postDate = '';
        $this->text = '';
        $this->readStatus = 0;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSenderId(): int
    {
        return $this->senderId;
    }

    public function setSenderId(int $senderId)
    {
        $this->senderId = $senderId;
    }

    public function getReceiverId(): int
    {
        return $this->receiverId;
    }

    public function setReceiverId(int $receiverId)
    {
        $this->receiverId = $receiverId;
    }

    public function getPostDate(): string
    {
        return $this->postDate;
    }

    public function setPostDate(string $postDate)
    {
        $this->postDate = $postDate;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text)
    {
        $this->text = $text;
    }

    public function getReadStatus(): int
    {
        return $this->readStatus;
    }

    public function setRead()
    {
        $this->readStatus = 1;
    }

    static public function loadMessageById(PDO $conn, $id)
    {
        $stmt = $conn->prepare('SELECT * FROM Messages WHERE id=:id');
        $result = $stmt->execute(['id' => $id]);
        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedMessage = new Message();
            $loadedMessage->id = $row['id'];
            $loadedMessage->senderId = $row['sender_id'];
            $loadedMessage->receiverId = $row['receiver_id'];
            $loadedMessage->text = $row['text'];
            $loadedMessage->postDate = $row['post_date'];
            $loadedMessage->readStatus = $row['read_status'];
            return $loadedMessage;
        }
        return null;
    }

    static public function loadAllMessagesBySenderId(PDO $conn, $senderId)
    {
        $ret = [];
        $stmt = $conn->prepare('SELECT * FROM Messages WHERE sender_id=:sender_id ORDER BY post_date DESC');
        $result = $stmt->execute(['sender_id' => $senderId]);
        if ($result !== false && $stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $loadedMessage = new Message();
                $loadedMessage->id = $row['id'];
                $loadedMessage->senderId = $row['sender_id'];
                $loadedMessage->receiverId = $row['receiver_id'];
                $loadedMessage->text = $row['text'];
                $loadedMessage->postDate = $row['post_date'];
                $loadedMessage->readStatus = $row['read_status'];
                $ret[] = $loadedMessage;
            }
            return $ret;
        }
        return null;
    }

    static public function loadAllMessagesByReceiverId(PDO $conn, $receiverId)
    {
        $ret = [];
        $stmt = $conn->prepare('SELECT * FROM Messages WHERE receiver_id=:receiver_id ORDER BY post_date DESC');
        $result = $stmt->execute(['receiver_id' => $receiverId]);
        if ($result !== false && $stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $loadedMessage = new Message();
                $loadedMessage->id = $row['id'];
                $loadedMessage->senderId = $row['sender_id'];
                $loadedMessage->receiverId = $row['receiver_id'];
                $loadedMessage->text = $row['text'];
                $loadedMessage->postDate = $row['post_date'];
                $loadedMessage->readStatus = $row['read_status'];
                $ret[] = $loadedMessage;
            }
            return $ret;
        }
        return null;
    }

    public function saveToDB(PDO $conn)
    {
        if ($this->id == -1) {

            $sql = 'INSERT INTO Messages(sender_id, receiver_id, text, post_date, read_status) VALUES(:sender_id, :receiver_id, :text, :post_date, :read_status)';
            $stmt = $conn->prepare($sql);
            $result = $stmt->execute(['sender_id' => $this->senderId, 'receiver_id' => $this->receiverId, 'text' => $this->text, 'post_date' => $this->postDate, 'read_status' => $this->readStatus]);
            if ($result !== false) {
                $this->id = $conn->lastInsertId();
                return true;
            }
        } else {
            $stmt = $conn->prepare('UPDATE Messages SET read_status=:read_status WHERE  id=:id ');
            $result = $stmt->execute(['read_status' => $this->readStatus, 'id' => $this->id]);
            if ($result === true) {
                return true;
            }
            return false;
        }
    }
}