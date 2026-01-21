<?php

namespace Model;

use Core\Database;
use PDO;

class TaskModel
{
    public static function findByUser($userId)
    {
        $db = Database::getPDO();
        $stmt = $db->prepare("
            SELECT *
            FROM tache
            WHERE id_user = :id_user
            ORDER BY id_tache DESC
        ");
        $stmt->execute(['id_user' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findOneByUser($taskId, $userId)
    {
        $db = Database::getPDO();
        $stmt = $db->prepare("
            SELECT *
            FROM tache
            WHERE id_tache = :id AND id_user = :user
        ");
        $stmt->execute([
            'id' => $taskId,
            'user' => $userId
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($title, $description, $userId)
    {
        $db = Database::getPDO();
        $stmt = $db->prepare("
            INSERT INTO tache (title, description, id_user)
            VALUES (:title, :description, :user)
        ");
        $stmt->execute([
            'title' => $title,
            'description' => $description,
            'user' => $userId
        ]);
    }

    public static function update($taskId, $title, $description)
    {
        $db = Database::getPDO();
        $stmt = $db->prepare("
            UPDATE tache
            SET title = :title, description = :description
            WHERE id_tache = :id
        ");
        $stmt->execute([
            'title' => $title,
            'description' => $description,
            'id' => $taskId
        ]);
    }

    public static function delete($taskId)
    {
        $db = Database::getPDO();
        $stmt = $db->prepare("
            DELETE FROM tache WHERE id_tache = :id
        ");
        $stmt->execute(['id' => $taskId]);
    }
}
?>