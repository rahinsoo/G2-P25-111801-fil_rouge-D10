<?php


namespace Repository;

use PDO;

readonly final class CustomerRepository {
    public function __construct(private readonly PDO $pdo) {}

    public function findAllClients() : array {
        $sql = $this->pdo->query("SELECT * FROM ENTREPRISE");
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll() : int {
        $sql = $this->pdo->query("SELECT COUNT(*) FROM ENTREPRISE");
        return $sql->fetch(PDO::FETCH_COLUMN);
    }
/// CREATE ///
    public function createClient(
        string $nom,
        string $numero_siren,
        string $type,
        string $information,
        bool $is_facturable,
        string $adresse,

    ): bool
    {
        $sql = "INSERT INTO ENTREPRISE (nom, numero_SIREN, type, information, is_facturable,adresse) 
                VALUES (:nom, :prenom, :identifiant, :password)";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'nom' => $nom,
            'numero_SIREN' => $numero_siren,
            'type' => $type,
            'information' => $information,
            'is_facturable' => $is_facturable,
            'adresse' => $adresse,
        ]);
    }

    public function addClient() : array
    {
        $sql = $this->pdo->query("INSERT INTO ENTREPRISE (nom, ) VALUES ('Test Corp', '123 Test St', '10001', 'Testville', 'Technology', 'TVA123456')");
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    /// UPDATE ///
    public function updateUser(
        User $user
    ): bool {
        $sql = "
            UPDATE utilisateur
            SET nom = :nom,
                prenom = :prenom,
                identifiant = :identifiant,
                id_user_role = :role
            WHERE id_user = :id_user
        ";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'id_user' => $user->getId(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'identifiant' => $user->getIdentifiant(),
            'role' => $user->getRoleId()
        ]);
    }


}