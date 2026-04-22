# 🎓 Mini LMS Pédagogique - Prototype Laravel

## 📝 Présentation du projet
Ce projet est un prototype de plateforme LMS conçu pour gérer des formations, des chapitres, des quiz et le suivi des notes. Il répond à un exercice de développement Laravel sur 4 jours.

L'application utilise l'intelligence artificielle pour assister la création de contenus pédagogiques et de quiz.

---

## 🚀 Fonctionnalités
* **Gestion complète** : Formations, chapitres et sous-chapitres.
* **Quiz dynamiques** : Questions à choix multiples avec calcul automatique du score.
* **IA Intégrée** : Génération assistée de leçons et de questions de quiz.
* **Suivi des notes** : Enregistrement des résultats sur 20 pour chaque apprenant.

---

## 🛠️ Stack Technique
* **Framework** : Laravel 11
* **Base de données** : SQLite ou MySQL
* **IA API** : Intégration via Groq (Llama 3.3)
* **Authentification** : Laravel Breeze
* **Déploiement** : Railway (HTTPS activé)

---

## ⚙️ Installation et Lancement

### 1. Installation des dépendances
```bash
git clone [URL_DU_DEPOT]
cd mini-lms
composer install
npm install && npm run build
```

### 2. Configuration et Environnement
```bash
cp .env.example .env
php artisan key:generate
```
> **Note :** Ajoutez votre `GROQ_API_KEY` dans le fichier `.env` pour l'IA.

### 3. Initialisation de la Base de Données
```bash
touch database/database.sqlite
php artisan migrate:fresh --seed
```

### 4. Lancement du Serveur
```bash
php artisan serve
```

---

## 🔑 Identifiants de Test

| Rôle | Email | Mot de passe |
| :--- | :--- | :--- |
| **Administrateur** | `admin@lms.com` | `password` |
| **Apprenant** | `marc@eleve.com` | `password` |

---

## 📈 Exemple de contenu inclus
Un module complet sur les **"Verbes irréguliers en anglais"** est inclus (chapitres, sous-chapitres et quiz) pour illustrer l'importation de contenu assistée par IA.