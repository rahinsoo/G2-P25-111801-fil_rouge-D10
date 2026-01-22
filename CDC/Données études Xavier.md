# Dossier d'étude du projet de création d'un suivit des taches et facturations.

## Réception et étude du CDC -> préparation d'un format merise, d'une maquette et d'un nouveau cdc

## Base de travail : Wareframe basique type Symfony (Game Catalog) et le merise étudié

## Répartition des tâches

-> Travaux sur les CRUD

--> Laetitia sur les utilisateurs
--> Mattéo sur les Taches
--> Xavier sur les entreprises

### ETUDE CRUD UTILISATEUR

-> lors du choix de faire une page en js pour faire un modal en JS : 

#### PLAN D'ORGANISATION DES FICHIERS

Structure actuelle de votre projet :

rahinsoo/G2-P25-111801-fil_rouge-D10/  
├── config/  
│   ├── db.php  
│   ├── db.local.php  
│   ├── routes.php  
│   └── CREATE_BDD.sql  
├── public/  
│   ├── assets/  
│   │   └── styles. css  
│   ├── img/  
│   └── index.php  
├── src/  
│   ├── Controller/  
│   │   ├── AppController.php  
│   │   └── UserController.php  
│   ├── Core/  
│   │   ├── Cors.php  
│   │   ├── Database.php  
│   │   ├── Request.php  
│   │   ├── Response.php  
│   │   ├── Router.php  
│   │   └── Session.php  
│   ├── Helper/  
│   │   └── Debug.php  
│   ├── js/  
│   │   └── modal.js  ← À DÉPLACER  
│   ├── Model/  
│   │   ├── Home.php  
│   │   └── User.php  
│   └── Repository/  
│       ├── CustomerRepository.php  
│       ├── HomeRepository.php  
│       ├── RoleRepository.php  
│       └── UserRepository.php  
├── views/  
│   ├── pages/  
│   │   ├── customer/  
│   │   │   └── listCustomer.php  
│   │   ├── home. php  
│   │   ├── games. php  
│   │   ├── add. php  
│   │   ├── detail.php    
│   │   └── not-found.php  
│   └── partials/  
│       ├── header.php  
│       └── footer.php  
├── scripts/  
├── autoload.php  
└── docker-compose.yml  


### PULL REQUEST CRUD UTILISATEUR puis avec CRUD ENTREPRISE

De base, la connection est en dehors du front initial :  
-> le but :  
--> faire fonctionner la base client sur un autre PC  
--> Lié la partie connection avec la partie Home initial si oui ou non connecté.  
--> validation des CRUD

Choix de la fusion des 2 CRUD et intégration au views :  
#### Schéma du flux de connexion

┌─────────────────┐  
│   / ou /login   │  ← Page d'entrée  
└────────┬────────┘  
         │  
         ▼  
┌─────────────────────────┐  
│  AuthController:: login │  Affiche views/pages/auth/login.php  
└────────┬────────────────┘  
         │ POST /login  
         ▼  
┌──────────────────────────────┐  
│ AuthController::authenticate │  Vérifie les identifiants  
└────────┬─────────────────────┘  
         │  
         ├─── ❌ Échec → Redirect /login (avec message d'erreur)  
         │  
         └─── ✅ Succès → Redirect /dashboard  
         │  
         ▼  
┌──────────────────────────┐  
│ DashboardController      │  
│ :: index()               │  
│                          │  
│ Vérifie isLogged()       │  
│                          │  
│ Affiche:                 │  
│ views/pages/dashboard/   │  
│ index.php                │  
└──────────────────────────┘  

#### FLUX DE NAVIGATION FINAL

┌─────────────────────┐  
│   Utilisateur       │  
│   visite "/"        │  
└──────────┬──────────┘  
           │  
     ┌─────▼─────┐  
     │ Connecté ? │  
     └─────┬─────┘  
           │  
    ┌──────┴──────┐
    │             │  
   NON           OUI  
    │             │  
    ▼             ▼  
┌────────────┐  ┌──────────┐  
│ /connection│  │  /home   │  
│            │  │          │  
│ Formulaire │  │ +header  │  
│   login    │  │  + liens │  
└────┬───────┘  └────┬─────┘  
     │               │  
     │ POST /login   │  
     └───────►┌──────▼──────┐  
              │ Authentifié │  
              └──────┬──────┘  
                     │  
                     ▼  
                ┌──────────┐  
                │  /home   │  
                └──────────┘  

