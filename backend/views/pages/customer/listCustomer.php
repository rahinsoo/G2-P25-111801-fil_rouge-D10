<?php
$clients = $featuredClient ?? [];
?>
<h1>Bienvenue dans ton espace de client</h1>

<?php foreach ($clients as $client): ?>
    <article class="card">
        <h2 class="card__title"><?= $client['nom'] ?></h2>

        <div class="meta">
            <span class="badge"><?= (int)$client['numero_SIRET'] ?></span>
            <span class="badge"><?= $client['type'] ?></span>
            <span class="badge"><?= $client['information'] ?></span>
            <span class="badge"><?= $client['adresse'] ?></span>
            <span class="">Suppr</span>
            <span class="">Edit</span>
            <span class="">Creation activité</span>
        </div>
        <!--            <a href="/games/--><?php //= $client['id'] ?><!--">Naviguer vers le détail</a>-->
    </article>
<?php endforeach; ?>