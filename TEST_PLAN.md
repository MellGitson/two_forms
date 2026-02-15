# 🧪 PLAN DE TEST - MyBlog

## 📋 Introduction

Ce document guide les utilisateurs pour tester complètement le MyBlog et s'assurer que toutes les fonctionnalités fonctionnent correctement.

---

## 🎯 Test 1: Accueil & Navigation

### Étapes
1. Ouvrir http://127.0.0.1:8000/
2. Vérifier éléments visibles:
   - [ ] Logo "📝 MyBlog" avec glow cyan
   - [ ] Barre de navigation sticky
   - [ ] Hero section avec titre "Bienvenue sur MyBlog"
   - [ ] Grille d'articles (3 colonnes)
   - [ ] Footer avec liens

3. Tester responsive:
   - [ ] Ouvrir DevTools (F12)
   - [ ] Mobile (375px): Navigation collapse, 1 colonne
   - [ ] Tablet (768px): 2 colonnes articles
   - [ ] Desktop (1200px): 3 colonnes

### Résultat attendu
✅ Page charge correctement, responsive work, éléments stylisés dark/cyan

---

## 🔐 Test 2: Authentification & Login

### Test 2.1: Visiteur
**But**: Vérifier accès visiteur

1. Rester non-connecté
2. Cliquer sur "Lire la suite" article
3. [ ] Article visible complètement
4. Voir section commentaires
5. [ ] Formulaire commentaire NOT visible (avec message: "Connectez-vous pour commenter")

### Test 2.2: Login Admin
**Identifiants**: admin / admin123

1. Cliquer "Connexion" en haut
2. Remplir formulaire:
   - [ ] Username: admin
   - [ ] Password: admin123
3. Click "Connexion"
4. [ ] Redirection vers `/`
5. [ ] Flash message verte "Successfully logged in"
6. Vérifier navbar:
   - [ ] "Connecté en tant que: admin"
   - [ ] Link "/user/" visible
   - [ ] Link "/post/new" visible
   - [ ] Link "/profile/" visible

### Test 2.3: Login Modérateur
**Identifiants**: mell / canac

1. Logout en haut
2. Login avec mell / canac
3. [ ] Redirection `/`
4. Vérifier navbar:
   - [ ] "Connecté en tant que: mell"
   - [ ] Link "/user/" NOT visible (Admin only)
   - [ ] Link "/post/new" visible (Modérateur)
   - [ ] Link "/profile/" visible

### Test 2.4: Logout
1. Click "Déconnexion"
2. [ ] Redirection `/login`
3. [ ] Session terminée (navbar montre "Connexion")

### Résultat attendu
✅ Login/logout fonctionne, permissions ajustées par rôle

---

## 📝 Test 3: Gestion Articles (Admin/Modérateur)

### Test 3.1: Créer un Article (Admin)
**Login**: admin / admin123

1. Cliquer `/post/new`
2. Form visible avec champs:
   - [ ] Titre (TextInput)
   - [ ] Contenu (Textarea)
   - [ ] Image (FileInput)
   - [ ] Catégorie (Select dropdown)
   - [ ] Approuvé (Checkbox - admin see)

3. Remplir:
   - Titre: "Mon premier article"
   - Contenu: "Ceci est un test..."
   - Catégorie: Sélectionner une
   - Image: Upload image PNG/JPG
   - Approuvé: Vérifier
4. Click "Créer"
5. [ ] Redirection article `/post/{id}`
6. [ ] Article visible immédiatement

### Test 3.2: Créer un Article (Modérateur)
**Login**: mell / canac

1. Cliquer `/post/new`
2. Remplir form (pas checkbox "Approuvé")
3. Click "Créer"
4. [ ] Article créé en attente d'approbation (approved=false)
5. Switch à admin et approuver

### Test 3.3: Éditer Article
1. Aller article personnel (`/post/{id}`)
2. Click bouton "Éditer"
3. Form préremplit avec data
4. Modifier titre: "Mon article édité"
5. Click "Éditer"
6. [ ] Description actualisée
7. [ ] Timestamp updatedAt changé

### Test 3.4: Supprimer Article
1. Aller sur article personnel
2. Click bouton "Supprimer"
3. [ ] Redirection accueil
4. [ ] Article disparu de liste

### Test 3.5: Limitation
**Login**: mell (modérateur)

1. Tenter éditer article d'un autre
2. [ ] Access Denied (403) ou option désactivée

### Résultat attendu
✅ Admin: CRUD complet | Modérateur: CRUD sien | Validation images

---

## 💬 Test 4: Commentaires

### Test 4.1: Visiteur
1. Non connecté
2. Ouvrir article
3. [ ] Form commentaires absent, message de login

### Test 4.2: Ajouter Commentaire (User)
**Login**: admin (ou créer user)

1. Ouvrir article
2. Scroll bas, voir form commentaires
3. Remplir:
   - [ ] Contenu: "Bel article!"
4. Click "Ajouter commentaire"
5. [ ] Redirection article
6. [ ] Message: "Commentaire créé, en attente approbation"

### Test 4.3: Approbation (Admin)
**Login**: admin

1. Si admin, créer commentaire sur même article
2. [ ] Son commentaire visible immédiatement (approved=true par défaut)
3. Si modérateur a créé, aller `/` et checker article
4. [ ] Commentaire mod pas visible (approved=false)

### Test 4.4: Modifier Approbation
(Si interface existe)
1. Admin panel commentaires
2. Finder commentaire non-approuvé
3. Click "Approuver"
4. [ ] Commentaire maintenant visible

### Résultat attendu
✅ Utilisateurs créent, admin approuve, workflow fonctionne

---

## 👥 Test 5: Gestion Utilisateurs (Admin Only)

### Test 5.1: Liste utilisateurs
**Login**: admin

1. Click "/user/" en navbar
2. [ ] Tableau avec colonnes:
   - ID
   - Username
   - Email
   - Rôles
   - Photo (si uploadée)
   - Actions (Edit, Delete)

### Test 5.2: Créer utilisateur
1. Click "Nouveau utilisateur"
2. Form visible:
   - [ ] Username (TextInput)
   - [ ] Email (EmailInput)
   - [ ] Prénom/Nom (TextInputs)
   - [ ] Password (PasswordInput)
   - [ ] Roles (MultiSelect)
   - [ ] Photo profil (FileInput)

3. Remplir:
   - Username: testuser
   - Email: test@example.com
   - Prénom: Test
   - Nom: User
   - Password: TestPass123
   - Rôles: Sélectionner ROLE_USER
   - Photo: Upload image
4. Click "Créer"
5. [ ] Redirection `/user/{new_id}`
6. [ ] Utilisateur visible dans liste

### Test 5.3: Éditer utilisateur
1. Click sur user → `/user/{id}/edit`
2. Form preremplit
3. Modifier email: newemail@test.com
4. Click "Éditer"
5. [ ] Email actualisé dans liste

### Test 5.4: Supprimer utilisateur
1. Aller list `/user/`
2. Click "Supprimer" sur un user
3. [ ] Redirection `/user/`
4. [ ] User disparu de liste

### Test 5.5: Pas accès modérateur
**Login**: mell

1. Tenter accéder `/user/`
2. [ ] Access Denied (403)

### Résultat attendu
✅ Admin CRUD users, modérateur bloqué

---

## 👤 Test 6: Profil Personnel

### Test 6.1: Voir profil
**Login**: admin

1. Click "/profile/" navbar
2. Page visible:
   - [ ] Photo de profil (si existe)
   - [ ] Username: admin
   - [ ] Email: admin@example.com
   - [ ] Prénom: Admin
   - [ ] Nom: Gitson
   - [ ] Rôles: ROLE_ADMIN
   - [ ] Bouton "Éditer"

### Test 6.2: Éditer profil
1. Click bouton "Éditer"
2. Form preremplit:
   - [ ] Prénom
   - [ ] Nom
   - [ ] Email
   - [ ] Photo de profil (FileInput)

3. Modifier:
   - Prénom: "AdminFoo"
   - Click "Éditer"
4. [ ] Redirection `/profile/`
5. [ ] Prénom actualisé "AdminFoo"

### Test 6.3: Upload photo
1. `/profile/edit`
2. Section "Photo de profil"
3. Click "Choisir fichier"
4. Sélectionner image PNG/JPG
5. Click "Éditer"
6. [ ] Photo actualise
7. [ ] Fichier dans `public/uploads/profiles/`

### Résultat attendu
✅ Profil visible et éditable, photo upload fonctionne

---

## 🖼️ Test 7: Upload Images

### Test 7.1: Article Image
1. `/post/new` (modérateur/admin)
2. Choisir image test.jpg
3. Remplir autres champs
4. Submit
5. [ ] Image visible sur article
6. [ ] Fichier dans `public/uploads/posts/`
7. [ ] Nom sécurisé (UUID-name.jpg)

### Test 7.2: Validations
1. Tenter upload PNG > 5MB
2. [ ] Erreur: "File too large"
3. Tenter upload .txt
4. [ ] Erreur: "Invalid MIME type"
5. Tenter upload .gif
6. [ ] Erreur: "Only PNG/JPEG allowed"

### Test 7.3: Delete Article cleanups
1. Créer article avec image
2. Supprimer article
3. [ ] Image file aussi supprimé du dossier uploads/
4. [ ] Pas de fichier orphelin

### Résultat attendu
✅ Upload fonctionne, validations strictes, cleanup automatique

---

## 🎨 Test 8: Design & Responsive

### Test 8.1: Colors & Theme
1. Ouvrir page quelconque
2. [ ] Fond sombre (#0a0e27)
3. [ ] Texte clair (#e4e6eb)
4. [ ] Accents cyan (#00d9ff)
5. [ ] Inputs dark avec focus cyan
6. [ ] Boutons gradient

### Test 8.2: Animations
1. Hover sur article card
2. [ ] Élévation (box-shadow increase)
3. [ ] Glow cyan border
4. [ ] Smooth transition
5. Hover sur button
6. [ ] Couleur change smooth
7. [ ] Cursor pointer

### Test 8.3: Responsive Mobile (320-375px)
1. F12 → Device Toggle → iPhone SE
2. [ ] Navigation collapse (hamburger?)
3. [ ] Hero text taille adapté
4. [ ] Articles 1 colonne
5. [ ] Forms 100% width
6. [ ] Tous cliquable sans zoom

### Test 8.4: Responsive Tablet (768px)
1. F12 → Device Toggle → iPad
2. [ ] Navigation normal
3. [ ] Articles 2 colonnes
4. [ ] Padding adapté

### Test 8.5: Responsive Desktop (1200px+)
1. F12 → Disable device toggle
2. [ ] Articles 3 colonnes
3. [ ] Max-width container
4. [ ] Full spacing

### Résultat attendu
✅ Dark cyan neon theme implémenté, responsive fonctionne

---

## ⚠️ Test 9: Gestion Erreurs

### Test 9.1: 404 Not Found
1. Ouvrir http://127.0.0.1:8000/invalid-page
2. [ ] Erreur 404 page

### Test 9.2: 403 Forbidden
**Login**: mell (modérateur)

1. Tenter `/user/` (admin only)
2. [ ] Erreur 403 ou redirection

### Test 9.3: 500 Server Error
1. Supprimer dossier `public/uploads/posts/`
2. Tenter créer article avec image
3. [ ] Erreur 500 ou graceful
4. [ ] Recréer dossier, retry

### Test 9.4: Validation Forms
1. `/post/new`
2. Submit formulaire vide
3. [ ] Erreurs affichées:
   - [ ] Titre required
   - [ ] Contenu required
   - [ ] Catégorie required

### Test 9.5: Unique Email
1. Admin → `/user/new`
2. Créer user avec email existing
3. [ ] Erreur: "Email already used"

### Résultat attendu
✅ Erreurs gérées proprement, messages clairs

---

## 🔄 Test 10: Workflow Complet

### Scénario: Modérateur crée et admin approuve

**Étape 1**: Logout admin, login modérateur

```
Login: mell / canac
```

**Étape 2**: Créer article

1. Click `/post/new`
2. Remplir:
   - Titre: "Modérateur Article"
   - Contenu: "Await admin approval..."
   - Catégorie: Tech
   - Image: Upload
   - **Ne pas cocher Approuvé**
3. Submit

**Étape 3**: Article créé, pas visible pour visiteurs

1. Logout
2. Accueil
3. [ ] Article NOT dans liste publique

**Étape 4**: Admin approuve

1. Login admin
2. Possible approuver via:
   - Admin panel (si existe)
   - Ou éditer article et vérifier "Approuvé"
3. Si éditer: `/post/{id}/edit` → Vérifier "Approuvé" → Submit
4. [ ] Article maintenant visible accueil

**Résultat**: ✅ Workflow approbation fonctionne

---

## 📊 Test 11: Données & Performance

### Test 11.1: Photo chargement
1. Accueil avec articles
2. [ ] Toutes images chargent < 3 secondes
3. F12 → Network → JPG files < 500KB

### Test 11.2: Navigation fluide
1. Click entre pages
2. [ ] Pas de lag/freeze
3. [ ] Animations smooth

### Test 11.3: Database intégrité
1. Terminal:
   ```bash
   php bin/console doctrine:query:sql "SELECT COUNT(*) FROM post"
   ```
2. [ ] Articles comptes correspondent affichage

### Résultat attendu
✅ Données cohérentes, performance acceptable

---

## ✅ CHECKLIST FINALE

Avant de valider complètement:

- [ ] Test 1: Accueil & Navigation ✅
- [ ] Test 2: Authentification ✅
- [ ] Test 3: Gestion Articles ✅
- [ ] Test 4: Commentaires ✅
- [ ] Test 5: Gestion Users ✅
- [ ] Test 6: Profil ✅
- [ ] Test 7: Upload ✅
- [ ] Test 8: Design ✅
- [ ] Test 9: Erreurs ✅
- [ ] Test 10: Workflow ✅
- [ ] Test 11: Performance ✅

**Si tous ✅**: Projet est **PRODUCTION READY** 🚀

---

## 🆘 Si Test Échoue

### Problem: Page blanche
```bash
php bin/console cache:clear
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

### Problem: Images manquent
```bash
mkdir -p public/uploads/{posts,profiles}
chmod -R 755 public/uploads/
```

### Problem: 500 Error
```bash
tail -f var/log/dev.log  # Voir erreur
php bin/console debug:router  # Vérifier routes
```

### Problem: Database error
```bash
php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

---

## 📝 Status Final

**Tous tests **PASSÉS** = LIVRABLE VALIDÉ** ✅

*Test execution date: _____________*  
*Tester name: _____________*  
*Result: PASSED / FAILED*

---

**Bonne chance pour les tests! 🧪**

*Last updated: Février 15, 2026*
