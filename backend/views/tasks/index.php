<h1>Mes tâches</h1>

<a href="/tasks/create"> Nouvelle tâche</a>
<hr>

<?php if (empty($tasks)): ?>
    <p>Aucune tâche</p>
<?php endif; ?>

<?php foreach ($tasks as $task): ?>
    <div style="margin-bottom:10px;">
        <strong><?= htmlspecialchars($task['title']) ?></strong><br>
        <?= htmlspecialchars($task['description']) ?><br>

        <a href="/tasks/edit/<?= $task['id_tache'] ?>"> Modifier</a>

        <form method="POST" action="/tasks/delete/<?= $task['id_tache'] ?>" style="display:inline">
            <button type="submit"> Supprimer</button>
        </form>
    </div>
<?php endforeach; ?>
