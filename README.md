# ShopifyTest

## 🛠️ Environnement technique

- **Backend** : Symfony (PHP 8.2)
- **Base de données** : MySQL
- **Frontend** : HTML + JavaScript
- **Stub API Shopify** : `json-server` (Node.js)

---

## Installation et exécution

### 1. Cloner le dépôt
git clone https://github.com/JonathandryRandrianaly/shopifyTest.git

cd shopifyTest

### 2. Installer les dépendances Symfony (dans backend/):
composer install

### 3. Créer la base de données:
php bin/console doctrine:database:create

### 4. Générer les tables
php bin/console doctrine:schema:create

### 5. Lancer le serveur Symfony
php -S localhost:8000 -t public

### 6. Lancer le stub d'API Shopify (dans apiShopify/)
npx json-server --watch db.json --port 3000

### 7. Tester l'application
Ouvre frontend/index.html dans un navigateur.
Ce fichier utilise JavaScript pour interagir avec le backend.

Importer le fichier importAchat.xlsx

## Exemple de cron job
0 0 * * * /usr/bin/curl -X PUT http://localhost:8000/api/product/updateShopifyPrice > /dev/null 2>&1

## note:
shopify_api_url et toute autre configuration Symfony ont été implémentées dans le fichier .env.
