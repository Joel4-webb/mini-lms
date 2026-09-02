# Mini LMS Pédagogique - Prototype Laravel

##  Présentation du projet

Ce projet est un prototype de plateforme **LMS (Learning Management System)** conçu pour gérer des formations, des chapitres, des sous-chapitres, des quiz et le suivi de progression des apprenants.

L'application intègre nativement **l'intelligence artificielle**, via l'API **Groq**, afin d'automatiser la création de contenus pédagogiques et de quiz.

Elle repose également sur un **système d'économie de points** et de **création collaborative**, permettant aux utilisateurs de générer eux-mêmes des formations :

-  **2 créations gratuites** par utilisateur.
-  Des créations supplémentaires peuvent être débloquées grâce aux **points gagnés en réussissant les quiz**.
-  L'IA permet de générer automatiquement les plans de cours, les leçons et les quiz.

---

##  Fonctionnalités

###  Gestion pédagogique

- Gestion complète des **formations**.
- Gestion des **chapitres** et **sous-chapitres**.
- Organisation structurée des contenus pédagogiques.
- Suivi de la progression des apprenants.

###  Création par les utilisateurs

Les apprenants peuvent eux-mêmes créer des formations :

- Jusqu'à **2 formations gratuitement**.
- Utilisation des **points gagnés lors des quiz** pour créer de nouvelles formations.
- Création collaborative de contenus pédagogiques.

###  Quiz dynamiques

- Génération automatique de **QCM**.
- Questions générées par l'intelligence artificielle.
- Calcul automatique du score.
- Enregistrement des résultats.
- Attribution de points en fonction des performances.

###  Intelligence artificielle

L'application utilise l'API **Groq** pour automatiser :

- La génération des plans de cours.
- La génération des leçons détaillées.
- La génération de contenu pédagogique au format HTML.
- La génération des questions de quiz.
- L'adaptation des quiz au contenu de chaque leçon.

###  Système d'économie

Un système de points permet de réguler l'utilisation de l'intelligence artificielle :

- Attribution de points lors de la réussite des quiz.
- Utilisation des points pour débloquer de nouvelles créations.
- Gestion du solde de points de chaque utilisateur.
- Les points constituent une ressource permettant de limiter les requêtes IA.

###  Interface dynamique

- Landing page moderne basée sur une approche **Soft UI**.
- Interface responsive.
- Statistiques réelles.
- Animations avec **Alpine.js**.
- Interface construite avec **Tailwind CSS**.

###  Suivi des notes

- Enregistrement des résultats de chaque quiz.
- Calcul des notes **sur 20**.
- Historique des performances des apprenants.
- Suivi de la progression pédagogique.

---

#  Tutoriel : Créer une leçon et un quiz avec l'IA

Suivez les étapes suivantes pour générer un module d'apprentissage complet directement depuis l'interface.

## 1.  Accéder à la création

1. Connectez-vous avec votre compte **Administrateur** ou **Apprenant**.
2. Profitez de vos **2 créations gratuites**.
3. Une fois les créations gratuites utilisées, utilisez les **points accumulés grâce à la réussite des quiz**.
4. Lancez la génération d'une nouvelle formation ou d'un sous-chapitre.

---

## 2.  Générer le contenu pédagogique

Saisissez un titre évocateur pour votre leçon.

### Exemple

> Les bases de la programmation Python

Cliquez ensuite sur le bouton :

**« Générer via IA »**

L'application interroge l'API **Groq** afin de générer automatiquement un cours structuré et formaté en HTML.

Une fois le contenu généré :

1. Vérifiez le contenu.
2. Modifiez-le si nécessaire.
3. Enregistrez la leçon.

---

## 3.  Générer le quiz

Une fois la leçon enregistrée :

1. Cliquez sur **« Générer un Quiz »**.
2. L'IA analyse automatiquement le contenu de la leçon.
3. Elle génère plusieurs questions à choix multiples (**QCM**).
4. L'apprenant répond aux questions.
5. Le score est calculé automatiquement.
6. La note est enregistrée sur **20**.
7. Les points correspondants peuvent être attribués à l'apprenant.

---

#  Stack Technique

| Technologie | Utilisation |
|---|---|
| **Laravel 11** | Framework backend |
| **PHP** | Langage principal |
| **SQLite / MySQL** | Base de données |
| **Blade** | Moteur de templates |
| **Tailwind CSS** | Framework CSS |
| **Alpine.js** | Interactions et animations frontend |
| **Groq API** | Intelligence artificielle |
| **Llama 3 / OpenAI OSS** | Modèles IA |
| **Laravel Breeze** | Authentification |

---

#  Installation et lancement

## 1.  Cloner le projet

```bash
git clone [URL_DU_DEPOT]
cd mini-lms


## 2.  Installer les dépendances PHP

```bash
composer install
```

## 3.  Installer les dépendances frontend

```bash
npm install
npm run build
```

---

## 4.  Configurer l'environnement

Copiez le fichier `.env.example` :

```bash
cp .env.example .env
```

Générez ensuite la clé de l'application :

```bash
php artisan key:generate
```

###  Configuration de l'API Groq

Ajoutez votre clé API Groq dans le fichier `.env` :

```env
GROQ_API_KEY=votre_cle_api_groq
```

---

## 5.  Initialiser la base de données

### Avec SQLite

Créez le fichier de base de données :

```bash
touch database/database.sqlite
```

Puis lancez les migrations et les seeders :

```bash
php artisan migrate:fresh --seed
```

> ** Note :** Le seeder interroge l'API IA afin de générer automatiquement **3 formations complètes**.
>
> L'exécution peut prendre environ **1 à 2 minutes** selon la disponibilité et la vitesse de l'API.

### Avec MySQL

Configurez les informations de connexion dans votre fichier `.env` :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mini_lms
DB_USERNAME=root
DB_PASSWORD=
```

Puis exécutez :

```bash
php artisan migrate:fresh --seed
```

---

## 6.  Lancer le serveur

Démarrez le serveur Laravel :

```bash
php artisan serve
```

L'application sera alors accessible à l'adresse :

```text
http://127.0.0.1:8000
```

---

#  Identifiants de test

| Rôle | Email | Mot de passe |
|---|---|---|
| 👨‍💼 **Administrateur** | `admin@lms.com` | `password` |
| 👨‍🎓 **Apprenant** | `marc@eleve.com` | `password` |


---

#  Structure générale du projet

```text
mini-lms/
├── app/
│   ├── Http/
│   ├── Models/
│   └── Services/
│
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── database.sqlite
│
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│
├── routes/
│   └── web.php
│
├── public/
│
├── .env.example
├── composer.json
├── package.json
└── README.md
```

---

#  Objectif du projet

L'objectif de ce prototype est de proposer une plateforme d'apprentissage moderne combinant :

**Formation + Intelligence Artificielle + Gamification + Création collaborative**

Le système permet ainsi aux apprenants de **consommer, créer et améliorer des contenus pédagogiques**, tout en utilisant un mécanisme de points pour encourager leur progression et leur participation.

---

##  Statut du projet

>  **Prototype / Projet en développement**

De nouvelles fonctionnalités pourront être ajoutées progressivement, notamment :

- Amélioration de la génération IA
- Système de badges et récompenses
- Classement des apprenants
- Statistiques avancées
- Gestion plus poussée des rôles et permissions
- Amélioration de l'expérience utilisateur
- Déploiement en production

---

##  Auteur

Projet réalisé dans le cadre d'un prototype de plateforme **LMS pédagogique basée sur Laravel et l'intelligence artificielle**.