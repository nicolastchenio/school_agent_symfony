# 🛠️ Stack Technique - Agents IA Éducation

## 📚 Technologies utilisées

### Backend
- **Framework** : Symfony 7.1
- **ORM** : Doctrine
- **Base de données** : MySQL 8.0
- **PHP** : 8.2+

### Frontend
- **Template Engine** : Twig
- **CSS Framework** : Tailwind CSS (ou Bootstrap 5)
- **JavaScript** : Symfony UX (Turbo + Stimulus.js)
- **Interactivité** : AJAX natif ou Fetch API

### DevOps
- **Containerisation** : Docker + Docker Compose
- **Services** : PHP-FPM, MySQL, phpMyAdmin

### API IA
- **Provider** : OpenAI API (ou Azure OpenAI)
- **Modèle suggéré** : GPT-4 ou GPT-3.5-turbo

---

## 📦 Packages Symfony à installer

```bash
# Essentiels
composer require symfony/orm-pack
composer require symfony/twig-bundle
composer require symfony/form
composer require symfony/validator
composer require symfony/security-bundle

# Fixtures & Data
composer require --dev doctrine/doctrine-fixtures-bundle
composer require fakerphp/faker

# Symfony UX (Stimulus)
composer require symfony/ux-turbo
composer require symfony/stimulus-bundle

# HTTP Client pour l'API IA
composer require symfony/http-client

# Assets
composer require symfony/webpack-encore-bundle
```

---

## 🎨 Setup Frontend

### Option 1 : Tailwind CSS (Recommandé)
```bash
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init
```

**tailwind.config.js** :
```js
module.exports = {
  content: [
    "./templates/**/*.html.twig",
    "./assets/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        'edu-blue': '#3b82f6',
        'edu-green': '#10b981',
        'edu-purple': '#8b5cf6',
      }
    },
  },
  plugins: [],
}
```

### Option 2 : Bootstrap 5
```bash
npm install bootstrap @popperjs/core
```

---

## 📁 Structure des dossiers

```
app/
├── config/
├── migrations/
├── public/
│   └── index.php
├── src/
│   ├── Controller/
│   │   ├── AgentController.php
│   │   ├── ConversationController.php
│   │   └── HomeController.php
│   ├── Entity/
│   │   ├── Agent.php
│   │   ├── User.php
│   │   ├── Conversation.php
│   │   └── Message.php
│   ├── Repository/
│   │   ├── AgentRepository.php
│   │   ├── ConversationRepository.php
│   │   └── MessageRepository.php
│   ├── Service/
│   │   └── AIService.php
│   ├── Form/
│   │   ├── AgentType.php
│   │   └── MessageType.php
│   └── DataFixtures/
│       └── AppFixtures.php
├── templates/
│   ├── base.html.twig
│   ├── home/
│   │   └── index.html.twig
│   ├── agent/
│   │   ├── index.html.twig
│   │   ├── show.html.twig
│   │   ├── new.html.twig
│   │   └── edit.html.twig
│   ├── conversation/
│   │   ├── index.html.twig
│   │   └── chat.html.twig
│   └── _partials/
│       ├── _header.html.twig
│       ├── _footer.html.twig
│       └── _agent_card.html.twig
└── assets/
    ├── app.js
    ├── styles/
    │   └── app.css
    └── controllers/
        ├── chat_controller.js
        └── search_controller.js
```

---

## 🔌 Configuration API OpenAI

**.env** :
```env
###> OpenAI ###
OPENAI_API_KEY=sk-your-api-key-here
OPENAI_API_URL=https://api.openai.com/v1/chat/completions
OPENAI_MODEL=gpt-3.5-turbo
###< OpenAI ###
```

**config/services.yaml** :
```yaml
parameters:
    openai_api_key: '%env(OPENAI_API_KEY)%'
    openai_api_url: '%env(OPENAI_API_URL)%'
    openai_model: '%env(OPENAI_MODEL)%'
```

---

## 🎯 Exemples de code

### Service AIService.php
```php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class AIService
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private string $apiKey,
        private string $apiUrl,
        private string $model
    ) {}

    public function sendMessage(string $systemPrompt, string $userMessage): string
    {
        $response = $this->httpClient->request('POST', $this->apiUrl, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => $this->model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userMessage],
                ],
            ],
        ]);

        $data = $response->toArray();
        return $data['choices'][0]['message']['content'] ?? 'Erreur de réponse';
    }
}
```

### Stimulus Controller (chat_controller.js)
```js
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['messages', 'input', 'loader']

    async send(event) {
        event.preventDefault();
        
        const message = this.inputTarget.value;
        this.addMessage('user', message);
        this.inputTarget.value = '';
        this.loaderTarget.classList.remove('hidden');

        const response = await fetch('/conversation/send', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message })
        });

        const data = await response.json();
        this.loaderTarget.classList.add('hidden');
        this.addMessage('assistant', data.response);
    }

    addMessage(role, content) {
        const div = document.createElement('div');
        div.className = role === 'user' ? 'message-user' : 'message-assistant';
        div.textContent = content;
        this.messagesTarget.appendChild(div);
        this.messagesTarget.scrollTop = this.messagesTarget.scrollHeight;
    }
}
```

---

## 🚀 Commandes utiles

```bash
# Créer une entité
php bin/console make:entity Agent

# Générer une migration
php bin/console make:migration

# Exécuter les migrations
php bin/console doctrine:migrations:migrate

# Charger les fixtures
php bin/console doctrine:fixtures:load

# Créer un controller
php bin/console make:controller AgentController

# Créer un formulaire
php bin/console make:form AgentType

# Compiler les assets
npm run watch
```

---

## ✅ Checklist Setup Initial

- [ ] Docker compose up
- [ ] Installer les dépendances PHP : `composer install`
- [ ] Installer les dépendances JS : `npm install`
- [ ] Configurer `.env` avec les credentials
- [ ] Créer la base de données : `php bin/console doctrine:database:create`
- [ ] Lancer les migrations : `php bin/console doctrine:migrations:migrate`
- [ ] Charger les fixtures : `php bin/console doctrine:fixtures:load`
- [ ] Compiler les assets : `npm run build`
- [ ] Tester l'app : http://localhost:8000

---

## 📖 Ressources

- [Symfony Docs](https://symfony.com/doc/current/index.html)
- [Twig Documentation](https://twig.symfony.com/doc/)
- [Symfony UX](https://ux.symfony.com/)
- [Stimulus.js](https://stimulus.hotwired.dev/)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [OpenAI API](https://platform.openai.com/docs/api-reference)
