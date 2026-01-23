<?php
$clients = $listClient ?? [];
?>
<h1>Bienvenue dans ton espace de client</h1>

<!-- ✅ Affichage des messages flash -->
<?php if (isset($_SESSION['flash'])): ?>
    <?php foreach ($_SESSION['flash'] as $type => $message): ?>
        <div class="alert alert-<?= $type ?>">
            <?= htmlspecialchars($message) ?>
        </div>
        <?php endforeach; ?>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<?php foreach ($clients as $client): ?>
    <article class="card">
        <h2 class="card__title"><?= htmlspecialchars($client['nom']) ?></h2>

        <div class="meta">
            <span class="badge"><?= htmlspecialchars((string)$client['numero_SIREN']) ?></span>
            <span class="badge"><?= htmlspecialchars($client['type']) ?></span>
            <span class="badge"><?= htmlspecialchars($client['information']) ?></span>
            <span class="badge"><?= htmlspecialchars($client['adresse']) ?></span>
            <span class="">Suppr</span>
            <span class="">Edit</span>
            <span class="">Creation activité</span>
        </div>
    </article>
<?php endforeach; ?>

<h2 class="card__title card">
    <button type="button" id="openModalBtn" class="btn-create">Création entreprise</button>
</h2>

<div class="meta">
    <span class="card">Total clients:  <?= count($clients) ?></span>
</div>

<!-- Modale de création -->
<div id="createCustomerModal" class="modal">
    <div class="modal__content card">
        <span class="modal__close">&times;</span>
        <h2>Créer un nouveau client</h2>
        <!-- ✅ CORRECTION : action pointe vers /customer/listCustomer -->
        <form id="createCustomerForm" action="/customer/createCustomer" method="POST">
            <div class="form-group">
                <label for="nom">Nom *</label>
                <input type="text" id="nom" name="nom" required>
            </div>
            <div class="form-group">
                <label for="numero_SIRET">Numéro SIRET *</label>
                <input type="text" id="numero_SIREN" name="numero_SIREN" required
                       pattern="[0-9]{14}"
                       title="Le SIRET doit contenir 14 chiffres">
            </div>
            <div class="form-group">
                <label for="type">Type *</label>
                <select id="type" name="type" required>
                    <option value="">-- Choisir un type --</option>
                    <option value="SARL">SARL</option>
                    <option value="SAS">SAS</option>
                    <option value="SA">SA</option>
                    <option value="Auto-entrepreneur">Auto-entrepreneur</option>
                    <option value="Association">Association</option>
                    <option value="Autre">Autre</option>
                </select>
            </div>
            <div class="form-group">
                <label for="information">Information</label>
                <textarea id="information" name="information"
                          placeholder="Informations complémentaires... "></textarea>
            </div>
            <div class="form-group">
                <label for="adresse">Adresse *</label>
                <textarea id="adresse" name="adresse" required
                          placeholder="123 Rue Exemple, 75001 Paris"></textarea>
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" id="is_facturable" name="is_facturable" checked>
                    Client facturable
                </label>
            </div>
            <button type="submit" class="btn-submit">Créer</button>
        </form>
    </div>
</div>