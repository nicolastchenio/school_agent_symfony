# POUR UTILISER SYMFONY SUR LE DOCKER #
il faut demarrer le conteneur "school_agent_symfony_docker"

```
docker-compose up -d
```

puis dans le terminal executer les commande suivante

ce positionner dans le dossier "app"

```
docker exec -it symfony_app bash
symfony serve --listen-ip=0.0.0.0 --allow-http --port=8000
```

ne pas oublier de faire la commande suivante pour charger les librairie de Symfony que l on utilise

```
composer install
```

# fixture #
commande pour lancer les fixtures :
```
php bin/console doctrine:fixtures
```

# iaControler #
rajouter dans le fichier ".env" le code suivant :
```
# app/.env
GROQ_API_KEY="votre_cle_api_groq_ici"
```