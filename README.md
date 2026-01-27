# Suivi du projet Data punch pour la société Data Time



# Explication du Framework Custom PHP (Architecture MVC)

Ton application utilise un **mini-framework PHP personnalisé** basé sur une **architecture MVC** (Model-View-Controller) avec un système de **routing centralisé**.

---

## **1. Architecture Globale (Flux de requête)**

Voici comment une requête HTTP traverse ton application :

```
┌────────────────────────────────────────────────────────────┐
│  1. Requête HTTP (ex: GET /home)                           │
└────────────────────────┬───────────────────────────────────┘
                         │
                         ▼
┌────────────────────────────────────────────────────────────┐
│  2. Point d'entrée : public/index.php                      │
│     - Démarre la session PHP                               │
│     - Charge l'autoloader                                  │
│     - Initialise les dépendances (Router, Controllers...)  │
└────────────────────────┬───────────────────────────────────┘
                         │
                         ▼
┌────────────────────────────────────────────────────────────┐
│  3. Configuration des routes : config/routes.php           │
│     - Associe /home → $controller->home()                  │
└────────────────────────┬───────────────────────────────────┘
                         │
                         ▼
┌────────────────────────────────────────────────────────────┐
│  4. Router : Core\Router                                   │
│     - Analyse l'URL et la méthode HTTP                     │
│     - Trouve la méthode du controller correspondante       │
└────────────────────────┬───────────────────────────────────┘
                         │
                         ▼
┌────────────────────────────────────────────────────────────┐
│  5. Controller : Controller\AppController                  │
│     - Vérifie l'authentification                           │
│     - Appelle le Repository pour récupérer les données     │
│     - Appelle Response->render() pour afficher la vue      │
└────────────────────────┬───────────────────────────────────┘
                         │
                         ▼
┌────────────────────────────────────────────────────────────┐
│  6. Repository : Repository\HomeRepository                 │
│     - Exécute les requêtes SQL                             │
│     - Retourne les données (tableau ou objets)             │
└────────────────────────┬───────────────────────────────────┘
                         │
                         ▼
┌────────────────────────────────────────────────────────────┐
│  7. Response : Core\Response->render()                     │
│     - Charge views/partials/header.php                     │
│     - Charge views/pages/{nom_vue}.php                     │
│     - Charge views/partials/footer.php                     │
└────────────────────────────────────────────────────────────┘
```

---

## **2. Structure des Dossiers**

```
backend/
├── public/
│   └── index.php          ← Point d'entrée (toutes les requêtes passent ici)
├── config/
│   ├── db.php             ← Configuration base de données
│   └── routes.php         ← Définition de toutes les routes
├── src/
│   ├── Core/              ← Composants du framework
│   │   ├── Router.php     ← Gestion du routing
│   │   ├── Response.php   ← Rendu des vues et redirections
│   │   ├── Request.php    ← Gestion des requêtes HTTP
│   │   ├── Session.php    ← Gestion des sessions
│   │   └── Database.php   ← Connexion PDO
│   ├── Controller/        ← Contrôleurs (logique métier)
│   │   ├── AppController.php
│   │   ├── UserController.php
│   │   └── AuthController.php
│   └── Repository/        ← Accès aux données (SQL)
│       ├── HomeRepository.php
│       └── CustomerRepository.php
├── views/
│   ├── partials/          ← Composants réutilisables
│   │   ├── header.php     ← Header (menu, navigation)
│   │   └── footer.php     ← Footer
│   └── pages/             ← Pages complètes
│       ├── home.php
│       ├── customer/
│       │   └── listCustomer.php
│       └── user/
│           ├── list.php
│           └── create.php
└── autoload.php           ← Chargement automatique des classes
```

---

## **3. Composants Clés du Framework**

### **A. Router (`Core\Router`)**

Le router associe une **URL** à une **méthode de controller**.

```php name=backend/config/routes.php url=https://github.com/rahinsoo/G2-P25-111801-fil_rouge-D10/blob/client_crud-V2/backend/config/routes.php
<?php

use Core\Router;
use Controller\AppController;

return function (Router $router, AppController $controller, ...) {
    
    // Route simple
    $router->get('/home', [$controller, 'home']);
    
    // Route avec paramètre dynamique (regex)
    $router->get('/users/edit/(\d+)', function($matches) use ($userController) {
        $userController->edit((int)$matches[1]); // $matches[1] = l'ID
    });
    
    // Route POST
    $router->post('/users/store', [$userController, 'store']);
};
```

**Types de routes :**
- `/home` → Route simple
- `/users/edit/(\d+)` → Route avec regex (capture l'ID)
- `$router->post()` → Pour les formulaires

---

### **B. Response (`Core\Response`)**

La classe `Response` gère le **rendu des vues**.

```php name=backend/src/Core/Response.php url=https://github.com/rahinsoo/G2-P25-111801-fil_rouge-D10/blob/client_crud-V2/backend/src/Core/Response.php
<?php

namespace Core;

final class Response {
    
    // Affiche une vue avec des données
    public function render(string $view, array $data = [], int $status = 200): void {
        http_response_code($status);
        extract($data); // Transforme ['users' => [...]] en $users
        
        require __DIR__ . '/../../views/partials/header.php';
        require __DIR__ . '/../../views/pages/' . $view . '.php'; // Ex: home.php
        require __DIR__ . '/../../views/partials/footer.php';
    }
    
    // Redirige vers une autre page
    public function redirect(string $to, int $status = 302): void {
        header('Location:' . $to, true, $status);
        exit;
    }
    
    // Retourne une réponse JSON (pour API)
    public function json(mixed $data, int $status = 200): void {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }
}
```

**Méthode `render()` :**
1. Charge le **header** (menu, navigation)
2. Charge la **page** (`views/pages/{nom}.php`)
3. Charge le **footer**

**Utilisation dans un controller :**
```php
$this->response->render('home', [
    'featuredClient' => $clients, // Devient $featuredClient dans la vue
    'total' => 42
]);
```

---

### **C. Controller (Exemple : `AppController`)**

Le controller orchestre la logique métier.

```php name=backend/src/Controller/AppController.php url=https://github.com/rahinsoo/G2-P25-111801-fil_rouge-D10/blob/client_crud-V2/backend/src/Controller/AppController.php
<?php

namespace Controller;

use Core\Response;
use Core\Session;
use Repository\HomeRepository;

final readonly class AppController {
    
    public function __construct(
        private Response $response,
        private HomeRepository $homeRepository,
        private Session $session,
    ) {}
    
    public function home(): void {
        // 1. Vérifier l'authentification
        if (!$this->session->isLogged()) {
            header('Location: /login');
            exit;
        }
        
        // 2. Récupérer les données
        $clients = $this->homeRepository->findAllClients();
        
        // 3. Afficher la vue
        $this->response->render('home', [
            'featuredClient' => $clients,
            'total' => $this->homeRepository->countAll()
        ]);
    }
}
```

---

### **D. Vue (`views/pages/home.php`)**

Les vues affichent les données reçues du controller.

```php name=backend/views/pages/home.php url=https://github.com/rahinsoo/G2-P25-111801-fil_rouge-D10/blob/client_crud-V2/backend/views/pages/home.php
<?php
$clients = $featuredClient ?? []; // Variable passée par le controller
$user = $_SESSION['user'] ?? null;
?>

<section class="welcome-section">
    <h1>Bienvenue <?= htmlspecialchars($user['prenom'] ?? '') ?> ! 👋</h1>
</section>

<section class="clients-list">
    <h2>Vos clients</h2>
    <?php foreach ($clients as $client): ?>
        <article class="card">
            <h3><?= htmlspecialchars($client['nom']) ?></h3>
        </article>
    <?php endforeach; ?>
</section>
```

---