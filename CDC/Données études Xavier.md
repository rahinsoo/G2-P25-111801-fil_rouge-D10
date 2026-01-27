# Dossier d'étude du projet de création d'un suivit des taches et facturations.

## Réception et étude du CDC -> préparation d'un format merise, d'une maquette et d'un nouveau cdc

## Base de travail : Wareframe basique type Symfony (Game Catalog) et le merise étudié

--> Récupération de la base du Wareframe de Game Catalogue  
--> Réflexion sur la base de donnée créé --> évolution durant la préparation des CRUD  
|--> allez voir le fichier Bakend/config/CREATE_BDD.sql.

## Répartition des tâches

-> Travaux sur les CRUD

--> Laetitia sur les utilisateurs
--> Mattéo sur les Taches
--> Xavier sur les entreprises

### ETUDE CRUD UTILISATEUR

-> lors du choix de faire une page en js pour faire un modal en JS :  
-> read créé  
-> erreur lors de la création avec le modal.  

#### PLAN D'ORGANISATION DES FICHIERS

Structure actuelle de votre projet :
```
rahinsoo/G2-P25-111801-fil_rouge-D10/  
├── config/  
│   ├── db.php  
│   ├── db.local.php  
│   ├── routes.php  
│   └── CREATE_BDD.sql  
├── public/  
│   ├── assets/  
│   │   └── modal.css  
│   │   └── styles.css  
│   ├── img/  
│   │   └── DATAPUNCH.png  
│   └── index.php  
│   └── JS  
│       └── modal.js  
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
```

### PULL REQUEST CRUD UTILISATEUR puis avec CRUD ENTREPRISE

De base, la connection est en dehors du front initial :  
-> le but :  
--> faire fonctionner la base client sur un autre PC  
--> Lié la partie connection avec la partie Home initial si oui ou non connecté.  
--> validation des CRUD

Choix de la fusion des 2 CRUD et intégration au views :  
#### Schéma du flux de connexion

```
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
```

#### FLUX DE NAVIGATION FINAL

```
┌─────────────────────┐  
│   Utilisateur       │  
│   visite "/"        │  
└──────────┬──────────┘  
           │  
     ┌─────▼─────┐  
     │ Connecté ?│  
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
```


### Finalisation CRUD ENTREPRISE et Une partie du design

Design par "cases" : 

![Image du design pour les clients](IMG_xavier/CUSTOMER_read1.png)
Modification du design sur certaines parties.
![Image du design pour les clients](IMG_xavier/Mod_design_2.png)
![Image du design pour les clients](IMG_xavier/Mod_design_3.png)

Création de l'espace de travail du crud
- read -> lecture dans "home" et dans "client"
  ![Image de la liste des clients](IMG_xavier/CUSTOMER_CRUD1.png)
- delete -> bouton de suppression dans chaque carte client.
  ![Message lors de la suppression](IMG_xavier/CUSTOMER_DELETE1.png)
#### Important -> utilisation de JS pour utiliser une modale pour la création et la modification.

- create -> bouton pour la création -> affichage des données à créer
  ![Image de l'édition d'un client](IMG_xavier/CUSTOMER_CREATE1.png)
- update -> bouton dans chaque carte client -> insertion de l'affichage de la BDD possibilité de la modifier en totalité.
  ![Image de l'édition d'un client](IMG_xavier/CUSTOMER_EDIT1.png)


#### Préparation de l'utilisation de l'api SIERN

Création du compte à mon nom pour cette application Data Punch
Connection API - SIREN  
curl --header "X-INSEE-Api-Key-Integration: f03b71b1-35dc-4291-bb71-b135dcd2911a" \
https://api.insee.fr/api-sirene/3.11

Test Postman :
https://api.insee.fr/api-sirene/3.11/siren/{siren}

Image avec le siren de Diginamic (GET) :
![Image de test avec siren de Diginamic](IMG_xavier/Test_API_SIREN_Postman.png)

