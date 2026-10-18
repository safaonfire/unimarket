# 🛍️ UniMarket — Marketplace Universitaire

UniMarket est une plateforme de commerce en ligne dédiée aux étudiants universitaires. Elle permet aux étudiants de vendre leurs créations artisanales, accessoires et produits personnalisés directement à leurs camarades, créant ainsi une communauté marchande au sein du campus.

---
## 🎬 Website Demo

Watch our video demonstration to discover UniMarket's main features and user experience.

[▶️ Watch the UniMarket Website Demo](https://youtu.be/S9sVKNOCdvU)

## 🚀 Stack technique

| Couche | Technologie |
|---|---|
| Backend | PHP 8+ natif, architecture MVC |
| Base de données | MySQL 8 via PDO (prepared statements) |
| Frontend | Bootstrap 5.3, Bootstrap Icons, JavaScript vanilla |
| Charts | Chart.js 4.4 |
| Sécurité | bcrypt (`password_hash` / `password_verify`), XSS escaping, actions POST uniquement |
| Serveur local | Laragon (Apache + MySQL) |

---

## ⚙️ Installation

### Prérequis
- [Laragon](https://laragon.org/) installé sur Windows
- PHP 8+
- MySQL 8

### Étapes

**1. Cloner le projet**
```bash
git clone https://github.com/ton-user/unimarket-main.git C:/laragon/www/unimarket-main
```

**2. Créer la base de données**

- Ouvrir [phpMyAdmin](http://localhost/phpmyadmin)
- Créer une base de données nommée `ppi`
- Importer le fichier `ppi.sql`

**3. Démarrer Laragon**

- Ouvrir Laragon → cliquer **Start All**
- Le virtual host `unimarket-main.test` est créé automatiquement

**4. Ouvrir l'application**

```
http://unimarket-main.test
```

> ⚠️ Ne pas utiliser `http://localhost/unimarket-main` — les liens internes ne fonctionneront pas.

---

## 🔐 Comptes de test

| Rôle | Email | Mot de passe |
|---|---|---|
| Super Admin | malkebenhmida@gmail.com | malek_bh_2005 |
| Vendeur | safeballoumi@gmail.com | safsoufa1 |
| Vendeur | imedtrabelsi@gmail.com | imed_imed23 |
| Client | safeballoumi263@gmail.com | safa123 |
| Client | jihengharbi@gmail.com | jihen_jihen123 |

---

## 📁 Structure du projet

```
unimarket-main/
├── assets/              # CSS, JS, images statiques
├── controller/          # Logique métier (MVC Controllers)
│   ├── admin/           # Super-administrateur
│   ├── vendeur/         # Vendeur
│   ├── user/            # Authentification & profil
│   ├── produit/         # Détail produit
│   ├── panier/          # Panier
│   ├── commande/        # Commandes
│   ├── commentaire/     # Commentaires
│   ├── favoris/         # Favoris
│   ├── message/         # Messagerie
│   ├── notification/    # Notifications
│   └── categorie/       # Catégories
├── model/               # Accès base de données (MVC Models)
│   ├── CRUD_user.php
│   ├── CRUD_produit.php
│   ├── CRUD_commande.php
│   └── user.php
├── view/                # Templates HTML (MVC Views)
│   ├── admin/
│   ├── vendeur/
│   ├── produit/
│   ├── commande/
│   ├── panier/
│   └── partials/
├── uploads/             # Images uploadées par les vendeurs
├── connexion.php        # Connexion PDO à la base de données
├── index.php            # Point d'entrée
└── ppi.sql              # Schéma + données de la base de données
```

---

## ✨ Fonctionnalités

### 👤 Authentification
- Inscription avec validation complète (nom, email unique, téléphone 8 chiffres, mot de passe sécurisé)
- Connexion sécurisée avec hachage **bcrypt**
- 3 rôles : `client`, `vendeur` (`admin`), `super-admin` (`supadm`)
- Modification du profil avec photo de profil

### 🛒 Catalogue & Produits
- Tri par popularité, prix, date
- Filtrage par catégories hiérarchiques (3 niveaux)
- Recherche en temps réel avec autocomplétion
- Badge "Promo" et "Handmade"

### 🛍️ Panier & Commandes
- Panier disponible sans connexion (session invité)
- Fusion automatique panier invité ↔ panier DB à la connexion
- Checkout avec lieu de rendez-vous sur le campus
- Validation du stock avant confirmation
- Historique des commandes avec statuts

### 💬 Messagerie & Notifications
- Chat intégré par commande (client ↔ vendeur)
- Polling automatique des nouveaux messages
- Notifications temps réel (likes, commandes, messages)
- Toasts non-intrusifs

### ❤️ Favoris
- Ajout/retrait en temps réel via AJAX
- Synchronisation DB pour les utilisateurs connectés
- Notification au vendeur lors d'un like

### 🏪 Espace Vendeur
- Dashboard avec gestion des produits (ajout, modification, suppression)
- Gestion des commandes reçues (confirmer / refuser)
- Demande pour devenir vendeur depuis l'espace client

### 🔧 Espace Super Admin
- Dashboard avec **statistiques** et **charts** (Chart.js)
  - 8 cartes KPI (produits, vendeurs, clients, CA, commandes...)
  - Line chart — commandes 7 derniers jours
  - Donut chart — statuts des commandes
  - Pie chart — produits par catégorie
  - Bar chart — top 5 produits commandés
- Gestion des utilisateurs (vendeurs & clients)
- Traitement des demandes vendeur (accepter / refuser)
- Gestion des catégories et produits

---

## 🗄️ Base de données

Le schéma principal est dans `ppi.sql`. Les migrations suivantes peuvent être appliquées si nécessaire :

| Fichier | Description |
|---|---|
| `migration_v2.sql` | Améliorations structure v2 |
| `migration_categories.sql` | Arborescence catégories |
| `migration_panier.sql` | Table panier persistant |
| `migration_commentaires.sql` | Table commentaires |
| `migration_demande_vendeur.sql` | Table demandes vendeur |
| `migration_notifications.sql` | Table notifications |
| `migration_photo_profil.sql` | Colonne photo profil |
| `migration_fix_adresse.sql` | Fix colonne adresse nullable |
| `migration_user_created_at.sql` | Colonne created_at sur user |

---

## 🔒 Sécurité

- Mots de passe hashés avec **bcrypt** (`PASSWORD_BCRYPT`)
- Toutes les requêtes SQL utilisent des **prepared statements** PDO
- Sorties HTML échappées avec `htmlspecialchars()` (protection XSS)
- Actions destructrices protégées par **POST uniquement**
- Validation du paramètre `redirect` contre les **open redirects**
- Contrôle d'accès par rôle sur chaque contrôleur

---

## 👨‍💻 Auteurs

Projet réalisé dans le cadre d'un **Projet de Fin d'Études (PPI)** — Institut Supérieur d'Informatique.

---

## 📄 Licence

Ce projet est à usage académique.
