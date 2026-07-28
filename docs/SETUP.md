# Installation locale

## Prérequis

- PHP 8.x avec PDO MySQL
- extension PHP MongoDB
- Composer 2
- MySQL 8.x ou MariaDB compatible
- MongoDB 6.x ou version compatible

Vérifiez les extensions avec :

```bash
php -m
composer --version
```

## 1. Récupérer les sources

L'application complète se trouve actuellement sur la branche `master`.

```bash
git clone --branch master https://github.com/EngGhada/Studi-E-Commerce-V1.0.git
cd Studi-E-Commerce-V1.0
composer install
```

## 2. Configurer l'environnement

Copiez l'exemple, puis remplacez les valeurs avec vos paramètres locaux :

```bash
cp .env.example .env
```

Sous PowerShell :

```powershell
Copy-Item .env.example .env
```

Ne versionnez jamais `.env`.

## 3. Préparer MySQL

Créez une base et un compte dédiés. Exemple à adapter :

```sql
CREATE DATABASE e_commerce CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'ecommerce_user'@'localhost' IDENTIFIED BY 'mot_de_passe_local';
GRANT ALL PRIVILEGES ON e_commerce.* TO 'ecommerce_user'@'localhost';
FLUSH PRIVILEGES;
```

Importez ensuite le schéma :

```bash
mysql -u ecommerce_user -p e_commerce < database/e-commercedb.sql
```

Le dump contient des données pédagogiques. N'utilisez pas ces comptes ni leurs
valeurs dans un environnement exposé.

## 4. Préparer MongoDB

Créez ou utilisez une instance locale, puis configurez `MONGO_URI` et
`MONGO_DATABASE`. L'import du journal fourni est facultatif :

```bash
mongoimport \
  --uri "mongodb://127.0.0.1:27017" \
  --db e_commerce_logs \
  --collection Activities_USER_Log \
  --file database/e-commercedb-mongo.Activities_USER_Log.json \
  --jsonArray
```

## 5. Lancer l'application

```bash
php -S localhost:8000
```

- Front-office : `http://localhost:8000`
- Administration : `http://localhost:8000/admin`

Créez un compte depuis l'interface. Pour tester le back-office dans une base
strictement locale, attribuez explicitement `GroupID = 1` à votre propre compte.
Ne publiez pas d'identifiants administrateur dans le dépôt.

## Dépannage

### Les dépendances ne sont pas installées

Exécutez `composer install` et vérifiez que `vendor/autoload.php` existe.

### La connexion MySQL échoue

Vérifiez les cinq variables `DB_*`, l'existence de la base et les droits du
compte MySQL.

### La connexion MongoDB échoue

Vérifiez que le service MongoDB fonctionne, que l'extension PHP `mongodb` est
active et que `MONGO_URI` est accessible.

### Les accents sont incorrects

Le dump historique utilise principalement `utf8`. Une migration vers `utf8mb4`
est recommandée avant de nouveaux imports.
