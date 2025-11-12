# Migration School Agent vers Symfony

Guide complet pour recréer le projet School Agent avec Symfony.

---

## 📋 Prérequis

- PHP 8.1 ou supérieur
- Composer
- MySQL (WAMP avec port 3308 OU Docker)
- Docker & Docker Compose (recommandé)
- Symfony CLI (optionnel mais recommandé)
- Node.js et npm (pour les assets)

---

## 🐳 Configuration Docker (En place)

Le projet utilise Docker avec une configuration simple dans `app/docker-compose.yml`.

### Structure Docker actuelle

```yaml
services:
  php:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: symfony_app
    working_dir: /var/www/html/app
    volumes:
      - .:/var/www/html/app
    ports:
      - "8000:8000"
    depends_on:
      - db
    environment:
      DATABASE_URL: mysql://root:root@db:3306/symfony_db?serverVersion=8.0&charset=utf8mb4

  db:
    image: mysql:8.0
    container_name: symfony_db
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: symfony_db
    ports:
      - "3307:3306"
    volumes:
      - db_data:/var/lib/mysql

  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    container_name: symfony_phpmyadmin
    restart: always
    ports:
      - "8080:80"
    environment:
      PMA_HOST: db
      MYSQL_ROOT_PASSWORD: root

volumes:
  db_data:
```

### Démarrer Docker

```powershell
# Depuis la racine du projet
cd C:\Users\flavi\OneDrive\Documents\Simplon\Projet\school_agent_symfony

# Option 1 : Lancer depuis la racine
docker-compose -f app\docker-compose.yml up -d

# Option 2 : Entrer dans app/ puis lancer
cd app
docker-compose up -d
```

### Accéder aux services

- **Application Symfony** : http://localhost:8000
- **PhpMyAdmin** : http://localhost:8080 (user: root, password: root)
- **MySQL** : localhost:3307 (depuis votre machine hôte)

> ✅ **Configuration actuelle** : Le port MySQL est **3307** pour éviter les conflits avec WAMP/XAMPP.

### Commandes Docker utiles

```powershell
# Depuis le dossier app/
cd app

# Entrer dans le conteneur PHP
docker exec -it symfony_app bash

# OU avec docker-compose
docker-compose exec php bash

# Exécuter des commandes Symfony dans le conteneur
docker exec -it symfony_app php bin/console doctrine:database:create
docker exec -it symfony_app php bin/console doctrine:migrations:migrate
docker exec -it symfony_app php bin/console doctrine:fixtures:load

# Arrêter les conteneurs
docker-compose stop

# Redémarrer les conteneurs
docker-compose restart

# Supprimer les conteneurs (garde les données)
docker-compose down

# Supprimer les conteneurs ET les volumes (supprime la BDD)
docker-compose down -v

# Voir les logs en temps réel
docker-compose logs -f php

# Reconstruire les conteneurs
docker-compose up -d --build

# Nettoyer les vieux conteneurs si conflit
docker rm -f symfony_app symfony_db symfony_phpmyadmin
```

### Workflow de développement avec Docker

```powershell
# 1. Démarrer Docker (depuis app/)
cd app
docker-compose up -d

# 2. Installer les dépendances (première fois)
docker exec -it symfony_app composer install
docker exec -it symfony_app npm install

# 3. Créer la base de données
docker exec -it symfony_app php bin/console doctrine:database:create

# 4. Exécuter les migrations
docker exec -it symfony_app php bin/console doctrine:migrations:migrate

# 5. Charger les fixtures
docker exec -it symfony_app php bin/console doctrine:fixtures:load

# 6. Compiler les assets (en mode watch)
docker exec -it symfony_app npm run watch

# 7. Accéder à l'application
# Ouvrir http://localhost:8000
```

---

## 🚀 Installation de Symfony

### 1. Installation de Symfony CLI (recommandé)

```bash
# Windows avec Scoop
scoop install symfony-cli

# Ou télécharger depuis https://symfony.com/download
```

### 2. Créer un nouveau projet Symfony

```bash
# Se placer dans le dossier parent
cd c:\Users\flavi\OneDrive\Documents\Simplon\Projet

# Créer le projet (webapp = version complète avec Twig, Doctrine, etc.)
symfony new school_agent_symfony --webapp

# OU avec Composer si pas de Symfony CLI
composer create-project symfony/skeleton:"7.1.*" school_agent_symfony
cd school_agent_symfony
composer require webapp
```

---

## 📦 Installation des dépendances nécessaires

```bash
cd school_agent_symfony

# Base de données (Doctrine ORM)
composer require doctrine

# Formulaires et validation
composer require form validator

# Authentification et sécurité
composer require security

# Twig (templates) - déjà inclus avec webapp
# composer require twig

# HTTP Client (pour API Grok)
composer require symfony/http-client

# Maker (pour générer du code)
composer require --dev symfony/maker-bundle

# Fixtures (données de test)
composer require --dev doctrine/doctrine-fixtures-bundle
composer require fakerphp/faker

# Profiler (debug) - déjà inclus avec webapp en dev
# composer require --dev symfony/profiler-pack
```

---

## ⚙️ Configuration de la base de données

### 1. Modifier `.env` ou créer `.env.local`

```bash
# Créer .env.local (ignoré par Git)
cp .env .env.local
```

Éditer `.env.local` :
```env
# Configuration MySQL Docker (dans le conteneur)
DATABASE_URL="mysql://root:root@db:3306/symfony_db?serverVersion=8.0&charset=utf8mb4"

# Clé API Grok (xAI)
GROK_API_KEY="votre_clé_api_ici"
GROK_API_URL="https://api.x.ai/v1/chat/completions"
GROK_MODEL="grok-beta"
```

### 2. Créer la base de données

```powershell
# AVEC DOCKER (recommandé) :
cd app
docker exec -it symfony_app php bin/console doctrine:database:create

# Ou si la BDD existe déjà, supprimer et recréer
docker exec -it symfony_app php bin/console doctrine:database:drop --force
docker exec -it symfony_app php bin/console doctrine:database:create
```

---

## 🗄️ Création des entités (Models) - MVP 1 journée

### 1. Créer les entités avec Maker

```bash
# Entité User
php bin/console make:entity User

# Propriétés à ajouter :
# - email: string, 180, unique, not null
# - password: string, 255, not null
# - nom: string, 100, not null
# - prenom: string, 100, nullable
# - role: string, 50, not null, default 'ROLE_USER'
# - niveauEducation: string, 100, nullable (ex: "Collège", "Lycée", "Université")

# Entité Agent
php bin/console make:entity Agent

# Propriétés :
# - nom: string, 100, not null (ex: "Prof de Maths")
# - type: string, 100, not null (ex: "education", "tuteur")
# - description: text, not null
# - specialite: string, 100, not null (ex: "Mathématiques", "Français")
# - status: string, 50, not null, default 'active'
# - promptSystem: text, not null (Prompt système pour l'IA)

# Entité Conversation
php bin/console make:entity Conversation

# Propriétés :
# - dateCreation: datetime, not null
# - statut: string, 50, not null, default 'active' (active, archivée)
# - user: relation ManyToOne vers User
# - agent: relation ManyToOne vers Agent

# Entité Message
php bin/console make:entity Message

# Propriétés :
# - role: string, 50, not null ('user' ou 'assistant')
# - contenu: text, not null
# - timestamp: datetime, not null
# - conversation: relation ManyToOne vers Conversation
```

### 2. Créer la migration et exécuter

```bash
# Générer la migration
php bin/console make:migration

# Vérifier le fichier de migration dans migrations/

# Exécuter la migration
php bin/console doctrine:migrations:migrate
```

---

## 🔐 Configuration de la sécurité

### 1. Configurer l'authentification User

```bash
# Transformer User en entité d'authentification
php bin/console make:user

# Choisir User comme entité
# Choisir email comme identifiant unique
```

### 2. Créer le système de connexion

```bash
# Générer le formulaire de login
php bin/console make:auth

# Choisir :
# - Login form authenticator
# - SecurityController comme nom
# - Oui pour logout
```

### 3. Générer le formulaire d'inscription

```bash
php bin/console make:registration-form
```

---

## 🎨 Création des contrôleurs

```bash
# Contrôleur Home (page d'accueil)
php bin/console make:controller HomeController

# Contrôleur Agent (gestion des agents IA)
php bin/console make:controller AgentController

# Contrôleur Conversation (gestion des conversations)
php bin/console make:controller ConversationController
```

---

## 🎭 Templates Twig - MVP

Structure des templates dans `templates/` :

```
templates/
├── base.html.twig              # Template de base
├── home/
│   └── index.html.twig         # Page d'accueil
├── agent/
│   ├── index.html.twig         # Liste des agents IA
│   ├── show.html.twig          # Détail d'un agent
│   ├── new.html.twig           # Créer un agent (admin)
│   └── edit.html.twig          # Modifier un agent (admin)
├── conversation/
│   ├── index.html.twig         # Liste des conversations
│   └── chat.html.twig          # Interface de chat
├── _partials/
│   ├── _header.html.twig       # En-tête
│   ├── _footer.html.twig       # Pied de page
│   └── _agent_card.html.twig   # Carte agent réutilisable
└── security/
    ├── login.html.twig         # Page de connexion
    └── register.html.twig      # Page d'inscription (optionnel)
```

---

## 🎨 Assets (CSS/JS)

### 1. Installer Webpack Encore

```bash
# Installer Node.js dependencies
npm install

# Ou avec Yarn
yarn install
```

### 2. Organiser les assets

```
assets/
├── app.js                      # Point d'entrée JS principal
├── styles/
│   ├── app.css                 # Styles principaux
│   ├── home.css
│   ├── ia.css
│   ├── chat.css
│   └── conversations.css
└── js/
    ├── home.js
    ├── chat.js
    └── conversations.js
```

### 3. Compiler les assets

```bash
# Mode développement (watch)
npm run watch

# Mode production
npm run build
```

---

## 🔌 Service pour l'API Grok

Créer un service pour communiquer avec l'API Grok (xAI) :

```bash
# Créer le service
php bin/console make:service GrokApiService
```

Fichier `src/Service/GrokApiService.php` :

```php
<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class GrokApiService
{
    private HttpClientInterface $client;
    private string $apiKey;
    private string $apiUrl;
    private string $model;

    public function __construct(
        HttpClientInterface $client,
        string $grokApiKey,
        string $grokApiUrl,
        string $grokModel
    ) {
        $this->client = $client;
        $this->apiKey = $grokApiKey;
        $this->apiUrl = $grokApiUrl;
        $this->model = $grokModel;
    }

    public function sendMessage(string $systemPrompt, string $userMessage): array
    {
        try {
            $response = $this->client->request('POST', $this->apiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => $this->model,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userMessage]
                    ],
                    'temperature' => 1.0,
                    'max_tokens' => 1024,
                ],
            ]);

            $data = $response->toArray();
            
            return [
                'success' => true,
                'content' => $data['choices'][0]['message']['content'] ?? '',
                'usage' => $data['usage'] ?? null
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
```

Configurer dans `config/services.yaml` :

```yaml
services:
    App\Service\GrokApiService:
        arguments:
            $grokApiKey: '%env(GROK_API_KEY)%'
            $grokApiUrl: '%env(GROK_API_URL)%'
            $grokModel: '%env(GROK_MODEL)%'
```

---

## 🗺️ Configuration des routes - MVP

Fichier `config/routes.yaml` :

```yaml
# Page d'accueil
home:
    path: /
    controller: App\Controller\HomeController::index

# Routes Agent
agent_index:
    path: /agents
    controller: App\Controller\AgentController::index

agent_show:
    path: /agents/{id}
    controller: App\Controller\AgentController::show

# Routes Conversation/Chat
conversation_index:
    path: /conversations
    controller: App\Controller\ConversationController::index

conversation_new:
    path: /conversations/new/{agentId}
    controller: App\Controller\ConversationController::new

conversation_chat:
    path: /conversations/{id}/chat
    controller: App\Controller\ConversationController::chat

conversation_send_message:
    path: /conversations/{id}/send
    controller: App\Controller\ConversationController::sendMessage
    methods: [POST]
```

---

## 🛡️ Sécurité - Configuration

Fichier `config/packages/security.yaml` :

```yaml
security:
    password_hashers:
        App\Entity\User:
            algorithm: auto

    providers:
        app_user_provider:
            entity:
                class: App\Entity\User
                property: email

    firewalls:
        dev:
            pattern: ^/(_(profiler|wdt)|css|images|js)/
            security: false
        main:
            lazy: true
            provider: app_user_provider
            form_login:
                login_path: app_login
                check_path: app_login
                default_target_path: home
            logout:
                path: app_logout
                target: home

    access_control:
        - { path: ^/admin, roles: ROLE_ADMIN }
        - { path: ^/ia, roles: ROLE_USER }
        - { path: ^/login, roles: PUBLIC_ACCESS }
```

---

## 📊 Fixtures (données de test)

### 1. Installer DoctrineFixturesBundle

```bash
composer require --dev doctrine/doctrine-fixtures-bundle
```

### 2. Créer les fixtures

```bash
php bin/console make:fixtures AppFixtures
```

### 3. Charger les fixtures

```bash
php bin/console doctrine:fixtures:load
```

---

## 🚦 Démarrer le serveur

### Avec Docker (configuration actuelle)

```powershell
# Démarrer les conteneurs (depuis app/)
cd app
docker-compose up -d

# L'application sera accessible sur :
# - Symfony : http://localhost:8000
# - PhpMyAdmin : http://localhost:8080
```

### Sans Docker (local - non recommandé)

```powershell
# Avec Symfony CLI
symfony serve

# Ou avec le serveur PHP intégré
php -S localhost:8000 -t public/
```

---

## 📝 Commandes utiles

```bash
# Vider le cache
php bin/console cache:clear

# Lister les routes
php bin/console debug:router

# Lister les services
php bin/console debug:container

# Vérifier la configuration
php bin/console debug:config

# Créer un utilisateur admin manuellement
php bin/console security:hash-password

# Mettre à jour le schéma de BDD
php bin/console doctrine:schema:update --force
```

---

## 📂 Structure finale du projet

```
school_agent_symfony/
├── config/              # Configuration Symfony
├── migrations/          # Migrations de base de données
├── public/              # Point d'entrée web
│   └── index.php
├── src/
│   ├── Controller/     # Contrôleurs (Home, Agent, Conversation)
│   ├── Entity/         # Entités (User, Agent, Conversation, Message)
│   ├── Form/           # Formulaires
│   ├── Repository/     # Repositories Doctrine
│   └── Service/        # Services (GrokApiService)
├── templates/          # Templates Twig
├── var/                # Cache et logs
├── vendor/             # Dépendances Composer
├── .env                # Configuration environnement
├── .env.local          # Configuration locale (ignoré par Git)
└── composer.json       # Dépendances PHP
```

---

## ✅ Checklist migration

### Configuration initiale
- [ ] Installer Docker Desktop
- [ ] Créer docker-compose.yml et Dockerfile
- [ ] Démarrer les conteneurs Docker (`docker-compose up -d`)
- [ ] Vérifier PhpMyAdmin (http://localhost:8080)

### Symfony et dépendances
- [ ] Créer le projet Symfony
- [ ] Installer toutes les dépendances Composer
- [ ] Installer les dépendances npm

### Base de données
- [ ] Configurer .env.local avec DATABASE_URL Docker
- [ ] Créer la base de données
- [ ] Créer toutes les entités (User, Agent, Conversation, Message)
- [ ] Générer et exécuter les migrations

### Sécurité et authentification
- [ ] Configurer la sécurité (User, Login)
- [ ] Créer le système de login
- [ ] Tester l'authentification

### Contrôleurs et vues
- [ ] Créer les contrôleurs (Home, Agent, Conversation)
- [ ] Migrer les templates vers Twig
- [ ] Configurer les routes

### Assets et frontend
- [ ] Migrer les CSS/JS vers assets/
- [ ] Compiler les assets avec Webpack Encore
- [ ] Tester le design responsive

### Service IA
- [ ] Créer le service GroqApiService
- [ ] Configurer la clé API dans .env.local
- [ ] Tester les fonctionnalités IA

### Données de test
- [ ] Créer des fixtures de test
- [ ] Charger les fixtures

### Tests et validation
- [ ] Tester toutes les fonctionnalités
- [ ] Vérifier les logs d'erreur
- [ ] Documenter l'API si nécessaire

---

## 🔗 Ressources utiles

- Documentation Symfony : https://symfony.com/doc/current/index.html
- Doctrine ORM : https://www.doctrine-project.org/
- Twig : https://twig.symfony.com/
- Webpack Encore : https://symfony.com/doc/current/frontend.html

---

## 🆘 Aide et support

Si vous rencontrez des problèmes :
1. Vérifier les logs dans `var/log/`
2. Utiliser le profiler Symfony (barre de debug en bas)
3. Consulter la documentation officielle
4. Demander de l'aide !
