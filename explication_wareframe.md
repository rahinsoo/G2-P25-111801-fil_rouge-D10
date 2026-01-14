## 📚 Explication du Mini-Framework (version Game)

Ce framework suit une **architecture MVC (Model-View-Controller)** avec un système de routing personnalisé.  Voici comment il fonctionne :

### 🔄 Flux de requête

1. **Point d'entrée** (`public/index.php`)
    - Toutes les requêtes HTTP arrivent ici
    - Démarre la session PHP
    - Charge l'autoloader pour les classes
    - Initialise les dépendances (Database, Router, Controllers, etc.)

2. **Autoloading** (`autoload.php`)
    - Convertit automatiquement les namespaces en chemins de fichiers
    - Ex: `Core\Router` → `src/Core/Router.php`

3. **Routing** (`Core\Router`)
    - Analyse l'URL et la méthode HTTP
    - Associe la requête à une action du contrôleur
    - Support des routes simples (`/games`) et regex (`/games/(\d+)`)

4. **Contrôleur** (`Controller\AppController`)
    - Reçoit la requête
    - Appelle le Repository pour les données
    - Choisit la vue à afficher

5. **Repository** (`Repository\GamesRepository`)
    - Gère les requêtes SQL
    - Retourne les données brutes (tableaux associatifs)

6. **Response** (`Core\Response`)
    - Gère le rendu des vues (HTML)
    - Gère les redirections
    - Gère les réponses JSON (pour l'API)

### 🏗️ Architecture en couches

| Couche | Responsabilité | Connait HTTP | Fait du SQL |
|--------|----------------|--------------|-------------|
| **View** | Affichage | Non | Non |
| **Controller** | Coordination HTTP | Oui | Non |
| **Repository** | Accès aux données | Non | Oui |
| **Database** | Connexion PDO | Non | Non |

### 📁 Structure des dossiers

```
├── public/          # Point d'entrée web
├── src/             # Code source
│   ├── Core/        # Composants du framework
│   ├── Controller/  # Contrôleurs de l'application
│   └── Repository/  # Accès aux données
├── views/           # Templates PHP
├── config/          # Configuration (routes, BDD)
└── autoload.php     # Chargement automatique des classes
```

---

## 💬 Ajout de commentaires

Je peux ajouter des commentaires détaillés en français dans tous les fichiers du framework pour mieux expliquer leur fonctionnement.

**Voulez-vous que je crée une pull request avec les commentaires ajoutés dans les fichiers suivants ? **

- `public/index.php` - Point d'entrée
- `autoload.php` - Autoloader
- `src/Core/Router.php` - Système de routing
- `src/Core/Request.php` - Gestion des requêtes
- `src/Core/Response.php` - Gestion des réponses
- `src/Core/Session.php` - Gestion des sessions
- `src/Core/Database.php` - Connexion BDD
- `src/Repository/GamesRepository.php` - Repository
- `src/Controller/AppController.php` - Contrôleur principal
- `config/routes.php` - Définition des routes

Confirmez-vous que vous souhaitez que j'**ouvre une pull request** pour ajouter ces commentaires ? 

## 🎯 Pourquoi ajouter des Models ?

Actuellement, votre framework utilise des **tableaux associatifs** pour représenter les données :

```php
// Ce que vous avez maintenant
$game = ['id' => 1, 'title' => 'Zelda', 'rating' => 9.5];
echo $game['title']; // Pas de typage, risque d'erreurs
```

Avec des **Models** (aussi appelés **Entités**), vous aurez :

```php
// Ce que vous aurez avec des Models
$game = new Game(id: 1, title: 'Zelda', rating: 9.5);
echo $game->getTitle(); // Typage fort, autocomplétion IDE
```

### ✅ Avantages des Models

- **Typage fort** : PHP sait exactement quel type de données vous manipulez
- **Autocomplétion** :  Votre IDE vous suggère les propriétés disponibles
- **Validation** : Vous pouvez valider les données dans le constructeur
- **Méthodes métier** : Vous pouvez ajouter des méthodes comme `isNewRelease()`, `getFormattedRating()`, etc.
- **Sécurité** : Plus difficile d'accéder à une propriété qui n'existe pas

---

## 🏗️ Comment créer des Models

### Étape 1️⃣ :  Créer le dossier `src/Model`

```bash
mkdir src/Model
```

### Étape 2️⃣ : Créer votre premier Model `Game.php`

```php name=src/Model/Game.php
<?php

namespace Model;

/**
 * Représente un jeu vidéo dans le catalogue
 * Cette classe encapsule toutes les données d'un jeu
 */
final readonly class Game {
    
    /**
     * @param int $id Identifiant unique du jeu
     * @param string $title Titre du jeu
     * @param string $platform Plateforme (PS5, Xbox, PC, etc.)
     * @param string $genre Genre du jeu (Action, RPG, etc.)
     * @param int $releaseYear Année de sortie
     * @param float $rating Note du jeu (0 à 10)
     * @param string|null $description Description du jeu
     * @param string|null $notes Notes personnelles
     */
    public function __construct(
        private int $id,
        private string $title,
        private string $platform,
        private string $genre,
        private int $releaseYear,
        private float $rating,
        private ?string $description = null,
        private ?string $notes = null,
    ) {
        // Validation des données
        if ($this->rating < 0 || $this->rating > 10) {
            throw new \InvalidArgumentException('Rating must be between 0 and 10');
        }
        
        if ($this->releaseYear < 1970 || $this->releaseYear > date('Y') + 2) {
            throw new \InvalidArgumentException('Invalid release year');
        }
    }
    
    // Getters
    public function getId(): int {
        return $this->id;
    }
    
    public function getTitle(): string {
        return $this->title;
    }
    
    public function getPlatform(): string {
        return $this->platform;
    }
    
    public function getGenre(): string {
        return $this->genre;
    }
    
    public function getReleaseYear(): int {
        return $this->releaseYear;
    }
    
    public function getRating(): float {
        return $this->rating;
    }
    
    public function getDescription(): ?string {
        return $this->description;
    }
    
    public function getNotes(): ?string {
        return $this->notes;
    }
    
    // Méthodes métier (business logic)
    
    /**
     * Vérifie si le jeu est récent (sorti il y a moins de 2 ans)
     */
    public function isNewRelease(): bool {
        return $this->releaseYear >= (int)date('Y') - 2;
    }
    
    /**
     * Retourne la note formatée avec des étoiles
     */
    public function getFormattedRating(): string {
        $fullStars = floor($this->rating / 2);
        $halfStar = ($this->rating % 2) >= 1 ? 1 : 0;
        $emptyStars = 5 - $fullStars - $halfStar;
        
        return str_repeat('⭐', (int)$fullStars) 
             . str_repeat('✨', $halfStar) 
             . str_repeat('☆', (int)$emptyStars);
    }
    
    /**
     * Retourne la qualité du jeu selon sa note
     */
    public function getQualityLabel(): string {
        return match(true) {
            $this->rating >= 9 => 'Masterpiece',
            $this->rating >= 8 => 'Excellent',
            $this->rating >= 7 => 'Good',
            $this->rating >= 6 => 'Decent',
            default => 'Poor'
        };
    }
    
    /**
     * Convertit le Model en tableau associatif (utile pour les vues)
     */
    public function toArray(): array {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'platform' => $this->platform,
            'genre' => $this->genre,
            'releaseYear' => $this->releaseYear,
            'rating' => $this->rating,
            'description' => $this->description,
            'notes' => $this->notes,
        ];
    }
}
```

---

### Étape 3️⃣ : Modifier le Repository pour retourner des Models

```php name=src/Repository/GamesRepository.php
<? php

namespace Repository;

use Model\Game;
use PDO;

/**
 * Repository pour gérer l'accès aux données des jeux
 * Responsabilité :  uniquement les requêtes SQL
 */
readonly final class GamesRepository {
    
    public function __construct(private PDO $pdo) {}
    
    /**
     * Convertit une ligne de BDD en objet Game
     */
    private function hydrateGame(array $data): Game {
        return new Game(
            id: (int)$data['id'],
            title: $data['title'],
            platform: $data['platform'],
            genre: $data['genre'],
            releaseYear:  (int)$data['releaseYear'],
            rating: (float)$data['rating'],
            description: $data['description'] ?? null,
            notes: $data['notes'] ?? null,
        );
    }
    
    /**
     * Convertit plusieurs lignes en tableau de Game
     * @return Game[]
     */
    private function hydrateGames(array $rows): array {
        return array_map(fn($row) => $this->hydrateGame($row), $rows);
    }
    
    /**
     * Récupère tous les jeux triés par note
     * @return Game[]
     */
    public function findAllSortedByRating(): array {
        $sql = $this->pdo->query("SELECT * FROM games ORDER BY rating DESC");
        $rows = $sql->fetchAll(PDO::FETCH_ASSOC);
        return $this->hydrateGames($rows);
    }
    
    /**
     * Récupère tous les jeux
     * @return Game[]
     */
    public function findAll(): array {
        $sql = $this->pdo->query("SELECT * FROM games");
        $rows = $sql->fetchAll(PDO::FETCH_ASSOC);
        return $this->hydrateGames($rows);
    }
    
    /**
     * Récupère les N meilleurs jeux
     * @return Game[]
     */
    public function findTop(int $limit): array {
        $sql = $this->pdo->prepare("SELECT * FROM games ORDER BY rating DESC LIMIT : limit");
        $sql->bindValue('limit', $limit, PDO:: PARAM_INT);
        $sql->execute();
        $rows = $sql->fetchAll(PDO::FETCH_ASSOC);
        return $this->hydrateGames($rows);
    }
    
    /**
     * Récupère un jeu par son ID
     */
    public function findById(int $id): ?Game {
        $sql = $this->pdo->prepare("SELECT * FROM games WHERE id = :id");
        $sql->bindValue('id', $id, PDO::PARAM_INT);
        $sql->execute();
        $row = $sql->fetch(PDO::FETCH_ASSOC);
        
        return $row ? $this->hydrateGame($row) : null;
    }
    
    /**
     * Compte le nombre total de jeux
     */
    public function countAll(): int {
        $sql = $this->pdo->query("SELECT COUNT(*) FROM games");
        return (int)$sql->fetch(PDO::FETCH_COLUMN);
    }
    
    /**
     * Récupère un jeu aléatoire
     */
    public function findRandom(): ?Game {
        $sql = $this->pdo->query("SELECT * FROM games ORDER BY RAND() LIMIT 1");
        $row = $sql->fetch(PDO::FETCH_ASSOC);
        
        return $row ? $this->hydrateGame($row) : null;
    }
    
    /**
     * Crée un nouveau jeu dans la base de données
     * @return int L'ID du jeu créé
     */
    public function createGame(Game $game): int {
        $sql = $this->pdo->prepare(
            "INSERT INTO games (title, platform, genre, releaseYear, rating, description, notes) 
             VALUES (:title, :platform, : genre, :releaseYear, : rating, :description, :notes)"
        );
        
        $sql->execute([
            'title' => $game->getTitle(),
            'platform' => $game->getPlatform(),
            'genre' => $game->getGenre(),
            'releaseYear' => $game->getReleaseYear(),
            'rating' => $game->getRating(),
            'description' => $game->getDescription(),
            'notes' => $game->getNotes()
        ]);
        
        return (int)$this->pdo->lastInsertId();
    }
}
```

---

### Étape 4️⃣ : Adapter les Controllers

```php name=src/Controller/AppController.php
<?php

namespace Controller;

use Core\Request;
use Core\Response;
use Core\Session;
use Model\Game;
use Repository\GamesRepository;

final readonly class AppController {
    
    public function __construct(
        private Response $response,
        private GamesRepository $gamesRepository,
        private Session $session,
        private Request $request,
    ) {}
    
    /**
     * Page d'accueil - affiche les 3 meilleurs jeux
     */
    public function home(): void {
        $games = $this->gamesRepository->findTop(3);
        
        $this->response->render('home', [
            'featuredGames' => $games, // Array de Game objects
            'total' => $this->gamesRepository->countAll()
        ]);
    }
    
    /**
     * Page listant tous les jeux
     */
    public function games(): void {
        $games = $this->gamesRepository->findAllSortedByRating();
        
        $this->response->render('games', [
            'games' => $games // Array de Game objects
        ]);
    }
    
    /**
     * Page de détail d'un jeu
     */
    public function gameById(int $id): void {
        $game = $this->gamesRepository->findById($id);
        
        // Si le jeu n'existe pas, afficher 404
        if ($game === null) {
            $this->response->render('not-found', [], 404);
            return;
        }
        
        $success = $this->session->pullFlash('success');
        
        $this->response->render('detail', [
            'id' => $id,
            'game' => $game, // Game object
            'success' => $success
        ]);
    }
    
    /**
     * Redirection vers un jeu aléatoire
     */
    public function random(): void {
        $lastId = $this->session->get('last_random_id');
        $game = null;
        
        // Essayer de trouver un jeu différent du dernier
        for ($i = 0; $i < 5; $i++) {
            $candidate = $this->gamesRepository->findRandom();
            
            if ($candidate && $candidate->getId() !== $lastId) {
                $game = $candidate;
                break;
            }
        }
        
        // Si aucun jeu trouvé, prendre le candidat
        if ($game === null) {
            $game = $this->gamesRepository->findRandom();
        }
        
        if ($game) {
            $this->session->set('last_random_id', $game->getId());
            $this->response->redirect('/games/' . $game->getId());
        } else {
            $this->response->redirect('/games');
        }
    }
}
```

---

### Étape 5️⃣ : Utiliser les Models dans les vues

```php name=views/pages/detail.php
<?php 
/** @var \Model\Game $game */
?>

<section class="game-detail">
    <div class="card">
        <h1><?= htmlspecialchars($game->getTitle()) ?></h1>
        
        <div class="game-meta">
            <span class="badge"><?= htmlspecialchars($game->getPlatform()) ?></span>
            <span class="badge"><?= htmlspecialchars($game->getGenre()) ?></span>
            <span class="badge"><?= $game->getReleaseYear() ?></span>
            
            <?php if ($game->isNewRelease()): ?>
                <span class="badge badge--new">🆕 New Release</span>
            <?php endif; ?>
        </div>
        
        <div class="rating">
            <span class="rating__stars"><?= $game->getFormattedRating() ?></span>
            <span class="rating__number"><?= $game->getRating() ?>/10</span>
            <span class="rating__label"><?= $game->getQualityLabel() ?></span>
        </div>
        
        <? php if ($game->getDescription()): ?>
            <p><? = nl2br(htmlspecialchars($game->getDescription())) ?></p>
        <?php endif; ?>
        
        <?php if ($game->getNotes()): ?>
            <div class="notes">
                <h3>Personal Notes</h3>
                <p><? = nl2br(htmlspecialchars($game->getNotes())) ?></p>
            </div>
        <?php endif; ?>
    </div>
</section>
```

---

## 📊 Comparaison :  Avant / Après

### ❌ Avant (tableaux)

```php
$game = $repository->findById(1);
echo $game['title']; // Pas de typage
echo $game['titl']; // Erreur silencieuse, affiche null
$game['rating'] = 15; // Aucune validation
```

### ✅ Après (Models)

```php
$game = $repository->findById(1);
echo $game->getTitle(); // Typage fort, autocomplétion
echo $game->getTitl(); // Erreur PHP immédiate
$game = new Game(... , rating: 15); // Exception levée
```

---

## 🎯 Architecture finale

```
HTTP Request
    ↓
Router (routes.php)
    ↓
Controller (AppController)
    ↓
Repository (GamesRepository)
    ↓
Database (MySQL/PDO)
    ↓
Repository → hydrate() → Model (Game)
    ↓
Controller → passe le Model
    ↓
View (utilise les getters du Model)
    ↓
HTTP Response
```

---