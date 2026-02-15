# 🚀 QUICK START GUIDE - MyBlog

## ⚡ Démarrage en 5 Minutes

### Étape 1: Entrer dans le dossier app
```bash
cd two_forms/app
```

### Étape 2: Démarrer le serveur
```bash
symfony serve
```

### Étape 3: Ouvrir dans le navigateur
```
http://127.0.0.1:8000
```

### Étape 4: Se connecter (test)
**Admin**:
- Username: `admin`
- Password: [À configurer lors du démarrage initial]

**Modérateur**:
- Username: `mell`
- Password: `canac`

### Étape 5: Explorer le site
- Accueil: `/` - Articles publics
- Articles: `/post/new` - Créer (Mod/Admin)
- Profil: `/profile/` - Mon profil (Connecté)
- Admin: `/user/` - Gestion users (Admin)

---

## 🔧 Installation Complète (Si première fois)

### 1. Installer les dépendances
```bash
cd two_forms/app
composer install
```

### 2. Créer la base de données
```bash
php bin/console doctrine:database:create
```

### 3. Exécuter les migrations
```bash
php bin/console doctrine:migrations:migrate
```

### 4. Charger les données de test
```bash
php bin/console doctrine:fixtures:load
```

### 5. Vider le cache
```bash
php bin/console cache:clear
```

### 6. Démarrer le serveur
```bash
symfony serve
```

---

## 📱 Accès aux Pages Clés

### Pour Tous
| Page | URL | Description |
|------|-----|-------------|
| Accueil | `/` | Articles publics |
| Voir article | `/post/{id}` | Détails complets |
| Login | `/login` | Connexion |
| Logout | `/logout` | Déconnexion |

### Utilisateurs Connectés (ROLE_USER+)
| Page | URL | Description |
|------|-----|-------------|
| Mon profil | `/profile/` | Voir mon profil |
| Éditer profil | `/profile/edit` | Modifier infos |
| Ajouter commentaire | `/post/{id}#comment-form` | Sur la page article |

### Modérateurs (ROLE_MODERATOR+)
| Page | URL | Description |
|------|-----|-------------|
| Créer article | `/post/new` | Nouvel article |
| Éditer article | `/post/{id}/edit` | Modifier sien |
| Supprimer article | `/post/{id}` DELETE | Supprimer sien |

### Administrateurs (ROLE_ADMIN)
| Page | URL | Description |
|------|-----|-------------|
| Liste users | `/user/` | Tous les users |
| Créer user | `/user/new` | Nouveau user |
| Éditer user | `/user/{id}/edit` | Modifier user |
| Supprimer user | `/user/{id}` DELETE | Supprimer user |
| Créer article | `/post/new` | Nouvel article |
| Valider article | `/post/{id}` (Update) | Approuver |

---

## 🛠️ Commandes Utiles

### Cache & Compilation
```bash
# Vider le cache
php bin/console cache:clear

# Vérifier syntax Twig
php bin/console lint:twig templates/
```

### Base de Données
```bash
# Voir migrations appliquées
php bin/console doctrine:migrations:list

# Créer nouvelle migration après changement Entity
php bin/console make:migration
php bin/console doctrine:migrations:migrate

# Réinitialiser BDD complètement
php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

### Développement
```bash
# Démarrer serveur (port 8000)
symfony serve

# Démarrer avec port custom
symfony serve --port=8080

# Lancer en debug mode
php bin/console debug:router  # Voir toutes routes
php bin/console debug:service # Voir tous services
```

---

## 🧪 Tester les Différents Rôles

### Admin Access
✅ Tout faire (créer, éditer, supprimer articles ET users)
```
Username: admin
Password: [À configurer lors du démarrage initial]
Routes: /, /post/*, /user/*, /profile/*
```

### Modérateur Access
✅ Créer et gérer SES articles, ajouter commentaires
```
Username: mell
Password: canac
Routes: /, /post/new, /post/{own}/*, /profile/*, /comment/*
```

### User Regular (Non créé dans fixtures, créer un)
✅ Lire articles, ajouter commentaires, gérér profil
```
Routes: /, /post/{id}, /profile/*, /comment/*
```

### Visiteur (Non connecté)
✅ Lire articles publiquement
```
Routes: /, /post/{id}
Blocked: /profile/*, /post/new, /user/*, /comment/*
```

---

## 🎨 Personnaliser le Design

### Couleurs (CSS Variables)
Éditer `templates/base.html.twig` ligne ~30:
```css
:root {
  --color-primary: #00d9ff;      /* Cyan Neon */
  --color-primary-light: #00f5ff;
  --bg-dark: #0a0e27;            /* Navy Dark */
  --bg-secondary: #1a1f3a;
  --text-primary: #e4e6eb;
  --text-secondary: #a0a6b8;
}
```

### Changer couleur primaire
Remplacer `#00d9ff` par votre couleur favorite dans le fichier base.html.twig

### Ajouter/Modifier animations
Voir section `@keyframes` dans base.html.twig

---

## 📸 Upload Images

### Post Image
1. Aller à `/post/new`
2. Remplir formulaire
3. Cliquer bouton "Choose File"
4. Sélectionner image PNG/JPEG/JPG (max 5MB)
5. Submit

### Profile Picture
1. Aller à `/profile/edit`
2. Section "Photo de profil"
3. Cliquer "Choose File"
4. Sélectionner image PNG/JPEG/JPG
5. Submit

### Dossiers upload
```
public/uploads/
├── posts/    # Images articles
└── profiles/ # Photos de profil
```

---

## 🐛 Dépannage Rapide

### Le site ne démarre pas?
```bash
# Vider cache
php bin/console cache:clear

# Vérifier PHP version
php --version  # Doit être 8.2+

# Vérifier composer
composer install
```

### 500 Error?
```bash
# Vérifier logs
tail -f var/log/dev.log

# Vérifier permissions
chmod -R 755 public/uploads/
chmod -R 755 var/
```

### Images ne s'affichent pas?
```bash
# Vérifier dossier existe
ls -la public/uploads/posts/
ls -la public/uploads/profiles/

# Créer si manquant
mkdir -p public/uploads/{posts,profiles}
chmod -R 755 public/uploads/
```

### Base de données introuvable?
```bash
# Créer
php bin/console doctrine:database:create

# Migrer
php bin/console doctrine:migrations:migrate

# Charger fixtures
php bin/console doctrine:fixtures:load
```

---

## 📝 Ajouter un Nouvel Article

### Via Interface (Admin/Modérateur)
1. Aller à `/post/new`
2. Remplir:
   - **Titre**: "Mon article cool"
   - **Contenu**: Long texte
   - **Catégorie**: Sélectionner
   - **Image**: Upload PNG/JPG
3. Click "Créer l'article"

### Voir l'article
- Admin: Visible tout de suite
- Modérateur: Visible après approbation admin

---

## 👤 Ajouter un Nouvel Utilisateur

### Via Interface (Admin only)
1. Aller à `/user/new`
2. Remplir:
   - **Username**: Unique
   - **Email**: Unique et valide
   - **Prénom/Nom**: Texte libre
   - **Password**: Sécurisé (8+ chars)
   - **Rôles**: Sélectionner ROLE_USER, ROLE_MODERATOR, ou ROLE_ADMIN
   - **Photo**: Optionnel (PNG/JPG)
3. Click "Créer l'utilisateur"

### Modification
1. Aller à `/user/{id}/edit`
2. Modifier champs voulus
3. Submit

### Suppression
1. Aller à `/user/{id}`
2. Click bouton "Supprimer"
3. Confirmer

---

## 📊 Structure Données Rapide

### User
```
id, username, email, password, firstName, lastName, 
profilePicture, roles (JSON), createdAt, updatedAt
```

### Article (Post)
```
id, title, content, imagePath, category_id, author_id,
approved (boolean), createdAt, updatedAt
```

### Comment
```
id, content, author_id, post_id,
approved (boolean), createdAt, updatedAt
```

### Catégory
```
id, name
```

---

## 🔐 Sécurité Basique

### Génération Password Hash
Ne pas utiliser plaintext! Symfony hash automatiquement:
```php
// Lors création user, plainPassword devient password hashé
$user->setPassword($passwordHasher->hashPassword($user, $plainPassword));
```

### Permissions
Toutes les routes sensibles ont `#[IsGranted('ROLE_XXX')]`

### Upload Protection
- Validation MIME type (PNG/JPEG/JPG)
- Limite 5MB
- Noms générés aléatoirement

---

## 🌐 Déploiement (Optionnel)

### Sur Heroku
```bash
# Créer compte + installer CLI
heroku create my-blog

# Configurer DATABASE_URL
heroku config:set DATABASE_URL=mysql://...

# Déployer
git push heroku main

# Migrer BDD
heroku run php bin/console doctrine:migrations:migrate
```

### Sur DigitalOcean
1. Créer droplet (PHP 8.2 + MySQL)
2. Clone repo GitHub
3. Installer composer dependencies
4. Setup .env avec vraie BDD
5. Faire migrations
6. Point domain vers IP

---

## 📚 Documentation Complète

Pour plus de détails:
- **Features**: Lire [CAHIER_DES_CHARGES.md](./CAHIER_DES_CHARGES.md)
- **Intro**: Lire [ReadMe.md](./ReadMe.md)
- **History**: Lire [IMPLEMENTATION_LOG.md](./IMPLEMENTATION_LOG.md)
- **Checklist**: Lire [CHECKLIST.md](./CHECKLIST.md)

---

## ✅ Quick Checklist Avant Présentation

- [ ] Serveur démarre: `symfony serve`
- [ ] Accueil charge: http://127.0.0.1:8000
- [ ] Login fonctionne avec identifiants configurés
- [ ] Article crée et visible
- [ ] Photo de profil uploadée
- [ ] Commentaire approuvé visible
- [ ] Responsive sur phone (F12 → Mobile)
- [ ] Tout est committé Git

---

## 🎓 Status
✅ **PRÊT À L'EMPLOI**

Simplement lancer `symfony serve` et accéder à http://127.0.0.1:8000!

**Bon développement! 🚀**

*Last updated: Février 15, 2026*
