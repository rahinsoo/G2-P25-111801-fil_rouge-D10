<?php $user = $this->session->get('user'); ?>

<h1>Dashboard</h1>

<p>
    Bienvenue <?= htmlspecialchars($this->session->get('user')['prenom']) ?>
</p>

<ul>
    <li>
        <?php if ($this->session->isAdmin()): ?>
        <a href="/users">Gestion des utilisateurs</a>
        <?php endif; ?>
    </li>
    <li><a href="/activites">Gestion des activités</a></li>
    <li><a href="/affectations">Gestion des affectations</a></li>
    <li><a href="/logout">Déconnexion</a></li>
</ul>
