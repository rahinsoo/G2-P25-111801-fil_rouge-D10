<?php

namespace Repository;

use Model\Activite;
use PDO;

readonly class ActiviteRepository
{
    public function __construct(private PDO $pdo)
    {}

    /// CREATE ///
    public function createActivite(
        string $nom,
        string $description,
        \DateTimeImmutable $date_creation,
        ?\DateTimeImmutable $date_fin,
        string $statut,
        int    $id_client
    ): bool
    {
        $sql = "INSERT INTO activite (nom, description, date_creation, date_fin, statut, id_client) 
                VALUES (:nom, :description, :date_creation, :date_fin, :statut, :id_client)";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'nom' => $nom,
            'description' => $description,
            'date_creation' => $date_creation->format('Y-m-d H:i:s'),
            'date_fin' => $date_fin?->format('Y-m-d H:i:s'),
            'statut' => $statut,
            'id_client' => $id_client
        ]);
    }

    /// READ ///
    public function readAll(): array
    {
        $sql = $this->pdo->query
        ("SELECT
        a.id_activite,
        a.nom,
        a.description,
        a.date_creation,
        a.date_fin,
        a.statut,
        a.id_client,
        c.nom AS nom_client
        FROM activite a
        LEFT JOIN client c ON a.id_client = c.id_client");
        $rows = $sql->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function($row) {
            return new Activite(
                $row['id_activite'],
                $row['nom'],
                $row['description'],
                new \DateTimeImmutable($row['date_creation']),
                $row['date_fin'] ? new \DateTimeImmutable($row['date_fin']) : null,
                $row['statut'],
                $row['id_client'],
                $row['nom_client']
            );
        },
            $rows);
    }

}