<?php
$clients = $listClient ?? [];
?>
<h1>Bienvenue dans ton espace de client</h1>

<?php foreach ($clients as $client): ?>
    <div class="row">
        <article class="cardsquare col">
            <h2 class="card__title"><?= $client['nom'] ?></h2>

            <div class="meta">
                <span class="badge"><?= (int) $client['numero_SIREN'] ?></span>
                <!--            <span class="badge">--><?php //= $client['type'] ?><!--</span>-->
                <!--            <span class="badge">--><?php //= $client['information'] ?><!--</span>-->
                <!--            <span class="badge">--><?php //= $client['adresse'] ?><!--</span>-->
                <span class="">Suppr</span>
                <span class="">Edit</span>
            </div>
        </article>
    </div>

<?php endforeach; ?>
<div class="row">
<!--<article class="card">-->
<h2 class="card__title cardsquare">
    <button type="button" id="openModalBtn" class="btn-create">Création entreprise</button>
</h2>
</div>
<!--</article>-->

<div class="meta">
    <span class="card">Total clients: <?= count($clients) ?></span>
</div>

<!-- Modale de création -->
<div id="createCustomerModal" class="modal">
    <div class="modal__content card">
        <span class="modal__close">&times;</span>
        <h2>Créer un nouveau client</h2>
        <form id="createCustomerForm" action="/customer/createCustomer" method="POST">
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" required>
            </div>
            <div class="form-group">
                <label for="numero_SIRET">Numéro SIRET</label>
                <input type="text" id="numero_SIRET" name="numero_SIRET" required>
            </div>
            <div class="form-group">
                <label for="type">Type</label>
                <input type="text" id="type" name="type" required>
            </div>
            <div class="form-group">
                <label for="information">Information</label>
                <textarea id="information" name="information"></textarea>
            </div>
            <div class="form-group">
                <label for="adresse">Adresse</label>
                <textarea id="adresse" name="adresse" required></textarea>
            </div>
            <button type="submit" class="btn-submit">Créer</button>
        </form>
    </div>
</div>
<script src="/js/modal.js"></script>