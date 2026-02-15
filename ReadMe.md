# 📝 MyBlog - Mini Blog Symfony 7.4

Un mini blog communautaire avec gestion des articles, utilisateurs et commentaires, développé avec **Symfony 7.4** dans le cadre de l'examen IPSSI.

## 🎯 Objectif

Créer une plateforme de blog collaborative où :
- **Administrateurs** gèrent tous les contenus (articles, utilisateurs, commentaires)
- **Modérateurs** peuvent créer et gérer leurs articles
- **Utilisateurs** peuvent lire, commenter et gérer leur profil
- **Visiteurs** ont un accès en lecture seule

## 🚀 Démarrage Rapide

### Prérequis
- PHP 8.2+
- Composer
- MySQL 8.0+
- Git

### Installation

```bash
# Cloner le projet
git clone https://github.com/MellGitson/two_forms.git
cd two_forms/app

# Installer les dépendances
composer install

# Créer la base de données
php bin/console doctrine:database:create

# Exécuter les migrations
php bin/console doctrine:migrations:migrate

# Charger les données de test
php bin/console doctrine:fixtures:load

# Démarrer le serveur
symfony serve
```

Accédez au site à : **http://127.0.0.1:8000**

## 🔑 Comptes de Test

| Rôle | Username | Mot de passe |
|------|----------|------------|
| 🔐 Admin | `admin` | `admin123` |
| 📝 Modérateur | `mell` | `canac` |
| 📖 Visiteur | Pas d'accès | - |

## 📋 Fonctionnalités Implémentées

### ✅ Gestion des Articles
- ✔️ Créer/Modifier/Supprimer des articles (Admin & Modérateurs)
- ✔️ Affichage de tous les articles approuvés
- ✔️ Système de catégories
- ✔️ Upload d'images (PNG, JPEG, JPG - 5MB max)
- ✔️ Approbation/Rejet par admin

### ✅ Gestion des Utilisateurs
- ✔️ Authentification sécurisée (Session + JWT ready)
- ✔️ CRUD complet des utilisateurs (Admin only)
- ✔️ Gestion des rôles (ROLE_USER, ROLE_MODERATOR, ROLE_ADMIN)
- ✔️ Profils utilisateurs avec photos
- ✔️ Modification du profil personnel

### ✅ Gestion des Commentaires
- ✔️ Ajouter des commentaires sur les articles (Users only)
- ✔️ Système d'approbation (Admin only)
- ✔️ Affichage des commentaires approuvés uniquement
- ✔️ Timestamps automatiques

### ✅ Design & Interface
- ✔️ Thème sombre avec néon cyan ✨
- ✔️ Responsive design (Mobile, Tablet, Desktop)
- ✔️ Animations fluides
- ✔️ Navigation intuitive
- ✔️ Système d'alertes/Flash messages

## 🏗️ Architecture

```
app/
├── src/
│   ├── Entity/          # Modèles de données
│   │   ├── User.php
│   │   ├── Post.php
│   │   ├── Comment.php
│   │   └── Category.php
│   ├── Controller/      # Logique métier
│   ├── Form/           # Types de formulaires
│   ├── Service/        # Services (Upload, etc.)
│   └── DataFixtures/   # Données de test
├── templates/          # Vues Twig
│   ├── base.html.twig
│   ├── home/
│   ├── post/
│   ├── user/
│   ├── profile/
│   └── login.html.twig
├── public/            # Fichiers publics
│   ├── uploads/       # Images uploadées
│   └── styles/        # CSS (optionnel)
└── config/           # Configuration Symfony
```

## 🔐 Système de Rôles

| Rôle | Routes accessibles | Actions possibles |
|------|------------------|------------------|
| **ROLE_ADMIN** | Tous | ✅ Gestion complète |
| **ROLE_MODERATOR** | Articles, Profil | ✅ Créer/éditer articles |
| **ROLE_USER** | Profil, Commentaires | ✅ Commenter, modifier profil |
| **Visiteur** | Accueil, Articles (lecture) | ⭕ Lecture seule |

## 📁 Routes Principales

### Pages Publiques
| Route | Contrôleur | Description |
|-------|-----------|-------------|
| `/` | HomeController | Page d'accueil |
| `/post/{id}` | PostController | Voir un article |
| `/login` | LoginController | Connexion |

### Pages Utilisateur Connecté
| Route | Contrôleur | Rôle requis |
|-------|-----------|-----------|
| `/profile/` | ProfileController | ROLE_USER+ |
| `/profile/edit` | ProfileController | ROLE_USER+ |
| `/comment/new` | CommentController | ROLE_USER+ |

### Pages Modérateur/Admin
| Route | Contrôleur | Rôle requis |
|-------|-----------|-----------|
| `/post/new` | PostController | ROLE_MODERATOR+ |
| `/post/{id}/edit` | PostController | ROLE_MODERATOR+ |
| `/post/{id}` (DELETE) | PostController | ROLE_MODERATOR+ |
| `/user/` | UserController | ROLE_ADMIN |
| `/user/new` | UserController | ROLE_ADMIN |
| `/user/{id}/edit` | UserController | ROLE_ADMIN |

## 📦 Upload de Fichiers

### Formats acceptés
- PNG
- JPEG
- JPG

### Limites
- Taille maximale: **5 MB**
- Articles: `public/uploads/posts/`
- Profils: `public/uploads/profiles/`

### Fonctionnement
- Validation MIME type
- Génération de noms sécurisés
- Suppression automatique des anciens fichiers lors de la modification

## 🎨 Design System

### Palette de Couleurs
```css
--color-primary: #00d9ff;      /* Cyan Neon */
--color-primary-light: #00f5ff;/* Cyan Light */
--bg-dark: #0a0e27;            /* Navy Dark */
--bg-secondary: #1a1f3a;       /* Dark Blue */
--text-primary: #e4e6eb;       /* Light Gray */
```

### Éléments
- Navigation sticky avec logo et menu
- Système d'alertes stylisés
- Formulaires avec validation visuelle
- Cards d'articles avec hover effects
- Animations fluides (fadeInUp, slideUp)

## 🔧 Configuration

### Fichiers importants
- `.env` - Variables d'environnement
- `config/doctrine.yaml` - Configuration base de données
- `config/services.yaml` - Services (FileUploadService, etc.)
- `config/packages/security.yaml` - Authentification/Autorisation

### Connections Base de Données
```
Host: 127.0.0.1
Port: 8889
User: root
Password: root
Database: blog_db
```

## 🧪 Tests

Données de test automatiquement chargées :
- 1 Admin + 1 Modérateur + 1 Utilisateur
- 3 Catégories (Technologie, Lifestyle, Actualités)
- 3 Articles d'exemple
- 2 Commentaires d'exemple

## 🐛 Dépannage

### Le cache n'est pas à jour?
```bash
php bin/console cache:clear
```

### Les images ne s'affichent pas?
```bash
# Vérifier les permissions
chmod -R 755 public/uploads/
```

### Erreur de base de données?
```bash
# Réinitialiser
php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

## 📚 Cahier des Charges

Pour une description complète de tous les critères d'acceptation, consultez : [CAHIER_DES_CHARGES.md](./CAHIER_DES_CHARGES.md)

## 📖 Documentation Symfony

- [Symfony 7.4 Docs](https://symfony.com/doc/current/index.html)
- [Doctrine ORM](https://www.doctrine-project.org/)
- [Twig Template Engine](https://twig.symfony.com/)

## 🚀 Déploiement

Le projet est prêt pour le déploiement sur :
- Heroku
- Digital Ocean
- AWS
- Any shared hosting (PHP 8.2+, MySQL 8.0+)

## 👨‍💻 Auteur

**Mellissa Gitson**
- GitHub: [@MellGitson](https://github.com/MellGitson)
- Email: mellissa.gitson@example.com

## 📄 License

MIT License - Libre d'utilisation pour formation

## 🎓 Examen

Ce projet a été développé pour l'examen IPSSI au mois de **Février 2026**.

---

**Status**: ✅ Production Ready

**Last Updated**: Février 2026

**Version**: 1.0.0
