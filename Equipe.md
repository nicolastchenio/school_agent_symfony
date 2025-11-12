# 👥 Organisation et Répartition des Tâches 

> Site d'agents IA pour l'éducation - Symfony 7.1 (Full Stack)

## 📋 Vue d'ensemble du projet

**Objectif** : Développer un MVP (Minimum Viable Product) d'une plateforme d'agents IA pour l'éducation avec Symfony (full stack : backend + frontend Twig).

**Stack technique** :
- **Backend** : Symfony 7.1 + Doctrine ORM
- **Frontend** : Twig + Tailwind CSS (ou Bootstrap)
- **Interactivité** : Symfony UX (Turbo + Stimulus.js)
- **Base de données** : MySQL 8.0
- **API IA** : OpenAI / Azure OpenAI

**Durée** : 1 journée (7 heures de travail effectif)
- Matin : 8h00 - 11h45 (3h45)
- Après-midi : 12h45 - 16h00 (3h15)

**Équipe** :
- 👩‍💻 **Flavie** - Frontend & Design
- 👨‍💻 **Nicolas** - Base de données & Backend
- 👨‍💻 **Gaël** - Backend & Logique Métier

**Méthodologie** : Agile en mode sprint court avec stand-ups réguliers

---

## 🎯 Planning de la journée (7h)

### ⏰ Matin (8h00-11h45) - 3h45

**🔄 Stand-up initial (8h00-8h15) - Tous ensemble**

#### 🎨 Flavie - Interface & Design (3h30)

**Tâches prioritaires :**
- [ ] Installer et configurer Tailwind CSS (ou Bootstrap 5)
- [ ] Créer le layout de base Twig :
  - `base.html.twig` (structure principale)
  - `_header.html.twig` (navigation)
  - `_footer.html.twig`
- [ ] Créer les templates Twig essentiels :
  - `agent/index.html.twig` (liste des agents)
  - `agent/show.html.twig` (détail agent)
  - `conversation/chat.html.twig` (interface de chat)
- [ ] Définir la charte graphique (couleurs thème éducation, typographie)
- [ ] Rendre l'interface responsive
- [ ] Créer les composants Twig réutilisables (cards, badges, buttons)

**Livrables** : Templates Twig et design de base

---

#### 🔧 Nicolas - Base de Données & CRUD (3h30)

**Tâches prioritaires :**
- [ ] Création des entités principales :
  - Entité `Agent` (id, nom, type, description, spécialité, status, prompt_system)
  - Entité `User` (id, nom, email, role, niveau_education)
  - Entité `Conversation` (id, user_id, agent_id, date_creation, statut)
  - Entité `Message` (id, conversation_id, role, contenu, timestamp)
- [ ] Générer et exécuter les migrations
- [ ] Créer des fixtures (5-8 agents IA, 3-5 utilisateurs)
- [ ] Commencer le CRUD des agents (routes liste et détail)

**Livrables** : Base de données prête + début CRUD

---

#### 🏗️ Gaël - Controllers & Système de Chat IA (3h30)

**Tâches prioritaires :**
- [ ] Controller `AgentController` (routes de base)
- [ ] Repository `AgentRepository` avec méthodes de recherche
- [ ] Templates Twig pour les agents
- [ ] Controller `ConversationController` (début)
- [ ] Service de base pour intégration API IA (structure)

**Livrables** : Backend structuré + début conversations

---

### 🍽️ Pause déjeuner (11h45-12h45) - 1h

---

### ⏰ Après-midi (12h45-16h00) - 3h15

**🔄 Stand-up de reprise (12h45-13h00)**

#### 🎯 Flavie - Templates Chat & Pages (3h)

**Tâches prioritaires :**
- [ ] Améliorer `agent/index.html.twig` (liste complète)
  - Affichage en grille avec catégories
  - Badges de spécialité (Maths, Sciences, Langues, etc.)
  - Boutons "Discuter avec l'agent"
- [ ] Améliorer `agent/show.html.twig` (page détail)
  - Description complète de l'agent
  - Spécialités et capacités
  - Bouton "Démarrer une conversation"
- [ ] Créer `conversation/chat.html.twig` (interface de chat)
  - Zone de conversation avec historique des messages
  - Formulaire d'envoi de message
  - Loader "agent en train d'écrire..."
  - Auto-scroll vers le bas
- [ ] Ajouter Stimulus controller pour :
  - Recherche d'agents en temps réel
  - Envoi de message en AJAX
  - Mise à jour dynamique du chat
- [ ] Ajouter des animations/transitions CSS

**Livrables** : Interface utilisateur complète responsive

---

#### 📖 Nicolas - Gestion des Agents CRUD (3h)

**Tâches prioritaires :**
- [ ] Ajouter routes création/édition dans `AgentController`
  - Route ajout agent (new/create)
  - Route modification agent (edit/update)
  - Route suppression agent (delete)
- [ ] Créer les formulaires Symfony
  - `AgentType` (formulaire agent avec champs spécifiques)
  - `MessageType` (formulaire envoi de message)
  - Validation des champs
- [ ] Intégrer les formulaires dans les templates
- [ ] Tester le CRUD complet

**Livrables** : CRUD complet des agents fonctionnel

---

#### 🔐 Gaël - Système de Conversation & API IA (3h)

**Tâches prioritaires :**
- [ ] Controller `ConversationController`
  - Route créer une conversation (create)
  - Route envoyer un message (send)
  - Route récupérer l'historique (history)
  - Route liste des conversations (index)
- [ ] Service `AIService` pour intégration API :
  - Méthode `sendToAI()` pour envoyer prompt à l'API
  - Méthode `formatResponse()` pour formater la réponse
  - Gestion des erreurs API
  - Système de retry basique
- [ ] Logique métier :
  - Créer conversation (user + agent)
  - Sauvegarder messages (user et assistant)
  - Intégrer le prompt système de l'agent
- [ ] Templates pour les conversations

**Livrables** : Système de conversation avec IA fonctionnel

---

### 🎤 Fin de journée (15h45-16h00) - 15 min

**🚀 Préparation démo finale (Tous ensemble)**

- [ ] Vérifier que toutes les fonctionnalités marchent
- [ ] Préparer le parcours de démonstration
- [ ] Lister les fonctionnalités réalisées
- [ ] Lister les difficultés rencontrées
- [ ] Commit et push final

---

## 📋 Fonctionnalités MVP (1 journée)

### ✅ Fonctionnalités OBLIGATOIRES

- [ ] **🐳 Configuration Docker** (PHP, MySQL, nginx)
- [ ] **🎯 Base Symfony Full Stack** (Twig + Stimulus UX)
- [ ] **🤖 Gestion des Agents IA**
  - Liste des agents disponibles (Maths, Sciences, Langues, Histoire, etc.)
  - Détail d'un agent (description, spécialités, prompt système)
  - Ajout/Modification/Suppression d'agents (CRUD)
  - Recherche d'agents par spécialité/nom
- [ ] **� Système de Conversation**
  - Démarrer une conversation avec un agent
  - Envoyer des messages et recevoir des réponses
  - Historique des conversations
  - Sauvegarde des messages en base de données
- [ ] **🔌 Intégration API IA**
  - Service pour communiquer avec l'API (OpenAI, Azure OpenAI, etc.)
  - Gestion des prompts système par agent
  - Formatage des réponses

### 🌟 Fonctionnalités BONUS (si temps disponible)

- [ ] Dashboard avec statistiques (nb conversations, agents populaires)
- [ ] Gestion des utilisateurs (profil étudiant/enseignant, niveau)
- [ ] Export d'une conversation (PDF/TXT)
- [ ] Système de favoris pour les agents
- [ ] Mode sombre/clair
- [ ] Notifications visuelles temps réel

---

## 🎯 Répartition des responsabilités

### 👩‍💻 Flavie - Frontend & Design
**Spécialité** : Twig, CSS (Tailwind/Bootstrap), UI/UX, Responsive
- Templates Twig (agents, chat, layouts)
- Design et charte graphique éducative
- Interface de chat responsive
- Stimulus controllers pour interactivité
- Animations et transitions CSS

### 👨‍💻 Nicolas - Base de données & Backend
**Spécialité** : Entités, Doctrine, CRUD
- Entités et migrations (Agent, Conversation, Message, User)
- CRUD des agents IA
- Fixtures avec agents éducatifs
- Tests et validation

### 👨‍💻 Gaël - Backend & Logique Métier
**Spécialité** : Controllers, Business Logic, API
- Routes et controllers (agents, conversations)
- Service d'intégration API IA
- Système de conversation et messagerie
- Gestion des prompts système
- Documentation

---

## 📊 Planning Visuel (1 journée - 7h)

```
8h00-8h15   : Stand-up initial
│
8h15-11h45  : Setup + Fondations (3h30)
│   ├── Flavie  : Setup Tailwind + Templates Twig de base
│   ├── Nicolas : Entités + Migrations + Fixtures + Début CRUD Agents
│   └── Gaël    : Controllers + Routes + Structure API Service
│
11h45-12h45 : Pause déjeuner
│
12h45-13h00 : Stand-up de reprise
│
13h00-16h00 : Fonctionnalités Principales (3h)
│   ├── Flavie  : Templates Chat + Pages Agents + Stimulus JS
│   ├── Nicolas : CRUD Agents complet + Formulaires
│   └── Gaël    : Système de conversation + Intégration API IA
│
15h45-16h00 : Démo finale & Commit
```

---

## 🔄 Communication & Coordination

### 📱 Canaux de communication
- **Chat groupe** : pour questions rapides
- **Stand-ups** : 2 fois (matin 8h, après-midi 12h45)
- **Pair programming** : si blocage > 20 min

### 🚨 En cas de blocage
1. Essayer de résoudre pendant 15 min
2. Demander de l'aide à l'équipe
3. Faire du pair programming si nécessaire
4. Adapter les priorités si besoin

### ✅ Commits réguliers
- Commiter toutes les 1-2h minimum
- Messages de commit clairs
- Pull avant de push pour éviter les conflits

---

## 🎁 Livrables finaux

À la fin de la journée, vous devez avoir :

1. **✅ Application fonctionnelle** accessible via http://localhost:8000
2. **✅ Docker** qui démarre sans erreur (PHP, MySQL, phpMyAdmin)
3. **✅ Base de données** avec agents IA de test (5-8 agents différents)
4. **✅ CRUD des agents** complet et fonctionnel (création, modification, suppression)
5. **✅ Système de conversation** avec un agent IA opérationnel
6. **✅ API IA intégrée** (OpenAI, Azure OpenAI, ou autre) avec gestion des prompts
7. **✅ Interface Twig** responsive et esthétique (liste agents + chat)
8. **✅ Historique des conversations** sauvegardé en base
9. **✅ Code** sur GitHub avec commits réguliers
10. **✅ Démo rapide** (3-5 min) : rechercher agent → démarrer conversation → poser questions


---

## 🤖 Exemples d'Agents IA à créer

Pour les **fixtures**, créer des agents spécialisés comme :

1. **📐 Prof de Maths** - Aide aux exercices, explications de concepts mathématiques
2. **🔬 Assistant Sciences** - Physique, chimie, biologie (explications simples)
3. **🇬🇧 Tuteur d'Anglais** - Grammaire, vocabulaire, conversation
4. **📚 Prof de Français** - Orthographe, conjugaison, analyse de texte
5. **🌍 Expert Histoire-Géo** - Aide aux cours d'histoire et géographie
6. **💻 Coach Programmation** - Aide pour apprendre à coder (Python, JavaScript, etc.)
7. **🎨 Mentor Créatif** - Aide pour projets artistiques et créatifs
8. **🧠 Coach Méthodologie** - Organisation, méthodes de travail, révisions

---


