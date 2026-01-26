
<h1>Dashboard</h1>

<p>
    Bienvenue <?= htmlspecialchars($user['prenom']) ?>
</p>

<ul>
    <li>
        <?php if ($isAdmin): ?>
        <a href="/users">Gestion des utilisateurs</a>
        <?php endif; ?>
    </li>
    <li><a href="/activites">Gestion des activités</a></li>
    <li><a href="/affectations">Gestion des affectations</a></li>
    <li><a href="/logout">Déconnexion</a></li>
</ul>
