<?php

require __DIR__ . '/../autoload.php';

use Repository\ActiviteRepository;
use Core\Database;

$config = require __DIR__ . '/../config/db.php';
$pdo = Database::makePdo($config['db']);

$activiteRepository = new ActiviteRepository($pdo);

$activites = [
    ['DataPunch', 'création d\'une appli pointeuse', new DateTimeImmutable('2025/11/26'), new DateTimeImmutable('2026/04/23'), 'en cours', 1]
];

$pdo->beginTransaction();

foreach ($activites as [$nom, $description, $date_creation, $date_fin, $statut, $id_client]) {
    $activiteRepository->createActivite($nom, $description, $date_creation, $date_fin, $statut, $id_client);
}

$pdo->commit();
echo "Seed activites OK\n";