# Docker Symfony 7.4 + Angular 21 Cheat Sheet

## 1️⃣ Gestion générale du projet

- Lancer tous les conteneurs (build si nécessaire) :

```bash
docker compose up -d --build
```

- Arrêter tous les conteneurs :

```bash
docker compose down
```

- Arrêter et supprimer **volumes inclus** (⚠️ supprime la base de données) :

```bash
docker compose down -v
```

- Redémarrer un conteneur spécifique :

```bash
docker compose restart php
docker compose restart angular
docker compose restart nginx
docker compose restart mysql84
```

- Voir l’état des conteneurs :

```bash
docker compose ps
```

- Voir les logs en temps réel :

```bash
docker compose logs -f php
docker compose logs -f angular
docker compose logs -f nginx
docker compose logs -f mysql84
```

## 2️⃣ Symfony / PHP

### Exécuter n’importe quelle commande Symfony

```bash
docker compose exec php php bin/console <commande>
```

### Commandes fréquentes

- Vérifier la version Symfony :

```bash
docker compose exec php php bin/console about
```

- Créer une entité :

```bash
docker compose exec php php bin/console make:entity
```

- Créer une migration Doctrine :

```bash
docker compose exec php php bin/console make:migration
```

- Exécuter les migrations :

```bash
docker compose exec php php bin/console doctrine:migrations:migrate
```

- Clear le cache :

```bash
docker compose exec php php bin/console cache:clear
```

- Installer un package Composer :

```bash
docker compose exec php composer require <package>
```

- Installer un package Composer pour dev :

```bash
docker compose exec php composer require --dev <package>
```

## 3️⃣ Angular / Node

### Exécuter les scripts npm

```bash
docker compose exec angular npm run <script>
```

### Scripts courants

- Lancer le serveur dev (port 4200 exposé) :

```bash
docker compose exec angular npm start
```

- Build production :

```bash
docker compose exec angular npm run build
```

- Watch mode pour dev :

```bash
docker compose exec angular npm run watch
```

- Lancer les tests :

```bash
docker compose exec angular npm test
```

> ⚠️ Assure-toi que dans ton `package.json`, `start` contient bien `--host 0.0.0.0` pour que le port soit accessible depuis ton navigateur.

## 4️⃣ MySQL / PhpMyAdmin

- Accéder à MySQL depuis le conteneur PHP :

```bash
docker compose exec php mysql -u root -p
```

- Accéder à PhpMyAdmin depuis le navigateur :

```
http://localhost:8087
```

- Pour vérifier la base de données :

```bash
docker compose exec php php bin/console doctrine:database:show
```

## 5️⃣ Astuces utiles

- Tu **n’as jamais besoin d’entrer manuellement** dans le conteneur pour exécuter Symfony ou Angular, tout se fait via `docker compose exec`.
- Tes dossiers `backend` et `frontend` sont **montés en volumes**, donc tes fichiers locaux sont directement synchronisés.
- Redémarrage Angular si port 4200 bloqué :

```bash
docker compose restart angular
```

---

**Fin du cheat sheet**
