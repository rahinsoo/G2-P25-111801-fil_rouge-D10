<h1>Mes tâches</h1>

<ul>
<?php foreach ($tasks as $task): ?>
    <li>
        <?= htmlspecialchars($task['nom']) ?>
        - <?= $task['date_debut'] ?>
        → <?= $task['date_fin'] ?>
    </li>
<?php endforeach; ?>
</ul>
