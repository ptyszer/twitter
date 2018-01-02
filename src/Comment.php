<?php

class Comment
{
    private $id;
    private $userId;
    private $postId;
    private $creationDate;
    private $text;

    public function __construct()
    {
        $this->id = -1;
        $this->userId = 0;
        $this->postId = 0;
        $this->creationDate = '';
        $this->text = '';
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId)
    {
        $this->userId = $userId;
    }

    public function getPostId(): int
    {
        return $this->postId;
    }

    public function setPostId(int $postId)
    {
        $this->postId = $postId;
    }

    public function getCreationDate(): string
    {
        return $this->creationDate;
    }

    public function setCreationDate(string $creationDate)
    {
        $this->creationDate = $creationDate;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text)
    {
        $this->text = $text;
    }

    static public function loadCommentById(PDO $conn, $id)
    {
        $stmt = $conn->prepare('SELECT * FROM Comments WHERE id=:id');
        $result = $stmt->execute(['id' => $id]);
        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedComment = new Comment();
            $loadedComment->id = $row['id'];
            $loadedComment->userId = $row['user_id'];
            $loadedComment->postId = $row['post_id'];
            $loadedComment->text = $row['text'];
            $loadedComment->creationDate = $row['creation_date'];
            return $loadedComment;
        }
        return null;
    }

    static public function loadAllCommentsByPostId(PDO $conn, $postId)
    {
        $ret = [];
        $stmt = $conn->prepare('SELECT * FROM Comments WHERE post_id=:post_id ORDER BY creation_date DESC');
        $result = $stmt->execute(['post_id' => $postId]);
        if ($result !== false && $stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $loadedComment = new Comment();
                $loadedComment->id = $row['id'];
                $loadedComment->userId = $row['user_id'];
                $loadedComment->postId = $row['post_id'];
                $loadedComment->text = $row['text'];
                $loadedComment->creationDate = $row['creation_date'];
                $ret[] = $loadedComment;
            }
            return $ret;
        }
        return null;
    }

    public function saveToDB(PDO $conn)
    {
        if ($this->id == -1) {

            $sql = 'INSERT INTO Comments(user_id, post_id, text, creation_date) VALUES(:user_id, :post_id, :text, :creation_date)';
            $stmt = $conn->prepare($sql);
            $result = $stmt->execute(['user_id' => $this->userId, 'post_id' => $this->postId, 'text' => $this->text, 'creation_date' => $this->creationDate]);
            if ($result !== false) {
                $this->id = $conn->lastInsertId();
                return true;
            }
        }
        return false;
    }
}