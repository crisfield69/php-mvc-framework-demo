# PHP MVC Framework Demo

Projet personnel présentant un **framework MVC en PHP** entièrement développé à la main :  
front-office, back-office, routing, système d’autoload, formulaires, gestionnaires d’images, scripts d’import, cron jobs…  

Ce dépôt propose une **version nettoyée** du projet original (développé pour un client), expurgée de tout contenu sensible ou propriétaire :  
- aucune donnée réelle  
- aucune image réelle  
- aucune police sous licence propriétaire  
- pas de configuration de production  
- uniquement le code source de l’architecture

---

## 🚀 Objectifs du projet

- Montrer un exemple complet de développement **PHP orienté objet** sans framework externe.  
- Illustrer une **architecture MVC propre** avec un découpage clair : Front / Back / Core.  
- Démontrer la capacité à construire :
  - un back-office complet (CRUD, uploads, galerie photo, gestion de contenus)
  - un site front avec templates réutilisables
  - un système de routing maison
  - un système d’autoload
  - un module de formulaires configurable
  - un système de cron pour automatiser des imports de données
  - des gestionnaires JS maison pour le front et le back

---

## 🧱 Architecture générale

php-mvc-framework-demo/
├── Backend/
│ ├── Controller/
│ ├── Model/
│ └── View/
│
├── Frontend/
│ ├── Controller/
│ ├── Model/
│ ├── View/
│ └── HtmlWrapper.php
│
├── Core/
│ ├── Autoloader.php
│ ├── Controller/
│ ├── Routing/
│ ├── Form/
│ ├── View/
│ ├── Database.php
│ ├── HttpRequest.php
│ └── Util.php
│
├── public/
│ ├── styles/
│ ├── scripts/
│ └── images/ (placeholders)
│
├── uploads/ (vide, structure conservée)
│
├── cron/
│ ├── createTables
│ ├── imports
│ └── images processing
│
├── config/
│ └── config.example.php
│
├── index.php
└── README.md

markdown
Copier le code

Points clés :

- **Back-office complet** (CRUD, galeries, pages, conférences, catégories…)  
- **Front multi-vues** avec templates structurés  
- **Routing dynamique** via `Core/Routing/Router.php`  
- **Autoload maison** via `Core/Autoloader.php`  
- **Système de formulaires orienté objet**  
- **Gestion d’images** et redimensionnement (PhotosManager)  
- **Cron jobs** pour automatiser les imports et traitements  
- **Séparation stricte “Core / Backend / Frontend”**

---

## 🧭 Routing

Le Router analyse l’URL et appelle automatiquement :

- le **contrôleur**,  
- la **méthode/action**,  
- avec les **paramètres dynamiques**.

Exemple :

/exposants → Frontend/Controller/ExposantsController::index()
/admin/pages/edit/12 → Backend/Controller/PagesController::edit(12)

yaml
Copier le code

Le système gère également les 404 personnalisées via `NotFoundController`.

---

## 🛠 Système de formulaires

Le répertoire `Core/Form/` contient un **mini-framework de formulaires** :

- InputText  
- InputEmail  
- InputPassword  
- Textarea  
- Select  
- Button  
- Form + FormWidget

Chaque formulaire est construit en PHP orienté objet, puis rendu automatiquement dans les vues.

Idéal pour centraliser la logique et éviter le code dupliqué en HTML.

---

## 🗄 Base de données & Modèles

Le fichier `Core/Model.php` contient la classe abstraite parent des modèles.  
Chaque modèle :

- se connecte via `Core/Database.php`  
- expose des méthodes CRUD  
- renvoie des objets ou tableaux selon les besoins  
 
Les modèles se trouvent dans :

- `Backend/Model/` pour le back-office  
- `Frontend/Model/` pour le front-office  

---

## 📷 Gestion des images

Le projet contient :

- un gestionnaire d’upload  
- un resize automatique côté cron  
- un gestionnaire de galeries  
- un dossier `uploads/` (non versionné)

Les images réelles ont été supprimées, seules des structures vides persistent dans le dépôt.

---

## 🕒 Cron et automatisation

Le répertoire `cron/` contient :

- scripts d’import API  
- scripts de mise à jour  
- scripts de création de tables  
- scripts de redimensionnement d’images

Exécution manuelle :

```bash
php cron/resizePhotos.php

Exemple de tâche CRON :
*/15 * * * * php /path/to/project/cron/resizePhotos.php >> /var/log/project/cron.log 2>&1

🔧 Installation locale

1️⃣ Cloner le dépôt
bash
Copier le code
git clone https://github.com/crisfield69/php-mvc-framework-demo.git
cd php-mvc-framework-demo

2️⃣ Copier la configuration
bash
Copier le code
cp config/config.example.php config/config.php
Éditer config.php avec vos informations :

php
Copier le code
return [
    'db_host' => '127.0.0.1',
    'db_name' => 'demo_db',
    'db_user' => 'root',
    'db_pass' => '',
    'base_url' => 'http://localhost/php-mvc-framework-demo'
];

3️⃣ Configurer Apache
Créer un VirtualHost :

apache
Copier le code
<VirtualHost *:80>
  ServerName mvc-demo.local
  DocumentRoot "/chemin/vers/php-mvc-framework-demo/public"
  <Directory "/chemin/vers/php-mvc-framework-demo/public">
    AllowOverride All
    Require all granted
  </Directory>
</VirtualHost>

4️⃣ Importer la base SQL
Les tables peuvent être créées via les scripts :

bash
Copier le code
php cron/createCategoriesTable.php
php cron/createExposantsTable.php
🎨 Polices & licences
Open Sans (inclus) → SIL Open Font License (OFL)

Line Awesome (inclus) → MIT License

Formata (supprimée) → police propriétaire, non redistribuée
→ fallback automatique vers Open Sans



📌 Roadmap d’amélioration

Migration éventuelle vers Composer
Ajout d’un système de cache
Migration progressive vers un moteur de templates (Twig)

👤 Auteur
Christophe Grandchamp
Développeur front-end, webdesigner, architecte PHP MVC.