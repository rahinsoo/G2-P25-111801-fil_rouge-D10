<?php

namespace Model;

use Core\Database;

class TaskModel
{
    // tâches utilisateur
    public static function findByUser($userId)
    {
        $db = Database::getPDO();

        $stmt = $db->prepare("
            SELECT t.*
            FROM tache t
            JOIN produit p ON p.id_tache = t.id_tache
            WHERE p.id_user = :user_id
        ");

        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll();
    }

    //  tache d'un utilisateur
    public static function findOneByUser($taskId, $userId)
    {
        $db = Database::getPDO();

        $stmt = $db->prepare("
            SELECT t.*
            FROM tache t
            JOIN produit p ON p.id_tache = t.id_tache
            WHERE t.id_tache = :task_id
              AND p.id_user = :user_id
        ");

        $stmt->execute([
            'task_id' => $taskId,
            'user_id' => $userId
        ]);

        return $stmt->fetch();
    }

    // Créer une tâche
    public static function create($title, $description, $userId)
    {
        $db = Database::getPDO();

        // créer la tâche
        $stmt = $db->prepare("
            INSERT INTO tache (title, description)
            VALUES (:title, :description)
        ");

        $stmt->execute([
            'title' => $title,
            'description' => $description
        ]);

        $taskId = $db->lastInsertId();

        // lier la tâche à l'utilisateur
        $stmt = $db->prepare("
            INSERT INTO produit (id_user, id_tache)
            VALUES (:user_id, :task_id)
        ");

        $stmt->execute([
            'user_id' => $userId,
            'task_id' => $taskId
        ]);
    }

    // Mettre à jour une tâche
    public static function update($taskId, $title, $description)
    {
        $db = Database::getPDO();

        $stmt = $db->prepare("
            UPDATE tache
            SET title = :title,
                description = :description
            WHERE id_tache = :task_id
        ");

        $stmt->execute([
            'task_id' => $taskId,
            'title' => $title,
            'description' => $description
        ]);
    }

    // Supprimer une tâche
    public static function delete($taskId)
    {
        $db = Database::getPDO();

        $stmt = $db->prepare("
            DELETE FROM produit WHERE id_tache = :task_id
        ");
        $stmt->execute(['task_id' => $taskId]);

        // supprimer la tâche
        $stmt = $db->prepare("
            DELETE FROM tache WHERE id_tache = :task_id
        ");
        $stmt->execute(['task_id' => $taskId]);
    }
}
?>