# 📸 SnapBook

> **Plateforme web de réservation de services photographiques**

SnapBook est une plateforme web qui permet aux clients de rechercher des photographes, consulter leurs profils et portfolios, voir leurs services et disponibilités, puis effectuer des réservations.

La plateforme permet également aux photographes de gérer leurs services, leurs portfolios, leurs disponibilités et leurs réservations.

Un espace administrateur permet de gérer les utilisateurs, les profils des photographes, les catégories, les réservations et les avis.

---

## 🎓 Projet Fil Rouge

**Formation :** Développement web Laravel augmenté par l’IA  
**Organisme :** Simplon Maghreb  
**Projet :** SnapBook  
**Année :** 2026  
**Développeur :** Taha Mouaddine  
**Formateur :** Salma Harda

---

## 📋 Sommaire

- [Présentation](#-présentation)
- [Contexte](#-contexte)
- [Objectifs](#-objectifs)
- [Utilisateurs et rôles](#-utilisateurs-et-rôles)
- [Fonctionnalités](#-fonctionnalités)
- [Workflow de réservation](#-workflow-de-réservation)
- [Technologies utilisées](#-technologies-utilisées)
- [Architecture](#-architecture)
- [Sécurité](#-sécurité)
- [Base de données](#-base-de-données)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Lancement du projet](#-lancement-du-projet)
- [Tests](#-tests)
- [Déploiement](#-déploiement)
- [Structure du projet](#-structure-du-projet)
- [Auteur](#-auteur)

---

# 📌 Présentation

SnapBook est une application web développée avec Laravel permettant de mettre en relation des clients avec des photographes professionnels.

Le client peut découvrir les photographes disponibles, consulter leurs informations, leurs services, leurs portfolios et leurs disponibilités.

Il peut ensuite sélectionner un service et une disponibilité afin de créer une réservation.

Le photographe peut gérer son activité depuis son espace personnel et traiter les demandes de réservation.

L'administrateur assure la gestion globale de la plateforme.

---

# 🎯 Contexte

Les clients peuvent avoir des difficultés à trouver rapidement un photographe correspondant à leurs besoins, à consulter ses réalisations et à connaître ses disponibilités.

Les photographes ont également besoin d'une plateforme permettant de présenter leurs services et de gérer leurs réservations.

SnapBook propose donc une solution centralisée pour faciliter la recherche, la présentation des services et la réservation de prestations photographiques.

---

# 🚀 Objectifs

Les principaux objectifs de SnapBook sont :

- Faciliter la recherche de photographes.
- Permettre aux photographes de présenter leurs services.
- Permettre l'ajout et la gestion d'un portfolio.
- Gérer les disponibilités des photographes.
- Permettre aux clients d'effectuer des réservations.
- Gérer le cycle de vie des réservations.
- Permettre aux clients de laisser un avis après une prestation terminée.
- Fournir un espace d'administration.
- Sécuriser l'accès aux différentes fonctionnalités selon le rôle de l'utilisateur.

---

# 👥 Utilisateurs et rôles

SnapBook possède trois rôles principaux :

## 👤 Client

Le client peut :

- Créer un compte.
- Se connecter et se déconnecter.
- Modifier son profil.
- Rechercher des photographes.
- Filtrer les photographes par nom, ville et catégorie.
- Consulter les profils des photographes.
- Consulter les portfolios.
- Consulter les services.
- Consulter les disponibilités.
- Créer une réservation.
- Consulter ses réservations.
- Annuler une réservation selon son statut.
- Consulter ses avis.
- Donner une note et un commentaire après une réservation terminée.

---

## 📷 Photographe

Le photographe peut :

- Créer un compte.
- Créer et gérer son profil professionnel.
- Ajouter et modifier ses services.
- Ajouter et supprimer des photos de son portfolio.
- Gérer ses disponibilités.
- Consulter les réservations concernant ses services.
- Accepter une réservation.
- Refuser une réservation.
- Marquer une réservation comme terminée.
- Consulter les avis reçus.

Un profil de photographe doit être **approuvé par l'administrateur** avant que ses services puissent être réservés.

---

## 🛡️ Administrateur

L'administrateur peut :

- Consulter les statistiques principales.
- Consulter les utilisateurs.
- Modifier le rôle d'un utilisateur selon les règles de l'application.
- Consulter les profils des photographes.
- Approuver ou rejeter un profil photographe.
- Gérer les catégories.
- Consulter les réservations.
- Consulter les avis.

---

# ✨ Fonctionnalités

## 🔎 Recherche de photographes

La plateforme permet de rechercher des photographes selon :

- Nom et prénom.
- Ville.
- Catégorie de service.

La liste affiche notamment :

- Nom du photographe.
- Ville.
- Note moyenne.
- Prix minimum.
- Première image du portfolio.

---

## 📷 Profil photographe

Chaque photographe possède une page présentant :

- Ses informations professionnelles.
- Sa ville.
- Son expérience.
- Sa biographie.
- Ses services.
- Son portfolio.
- Ses disponibilités.
- Ses avis.

---

## 🛎️ Services

Un photographe peut gérer ses services :

- Création.
- Modification.
- Suppression.
- Consultation.

Chaque service possède notamment :

- Un titre.
- Une description.
- Un prix.
- Une durée.
- Une catégorie.

---

## 🖼️ Portfolio

Les photographes peuvent présenter leurs réalisations grâce au portfolio.

Chaque photo peut contenir :

- Une image.
- Une description.

Les fichiers sont stockés dans le stockage public de Laravel.

---

## 📅 Disponibilités

Le photographe peut créer des créneaux disponibles avec :

- Une date.
- Une heure de début.
- Une heure de fin.

Ces disponibilités peuvent ensuite être sélectionnées par le client lors de la réservation.

---

# 📅 Workflow de réservation

Le processus principal de SnapBook est la réservation.

```text
Client
   ↓
Recherche un photographe
   ↓
Consulte son profil
   ↓
Choisit un service
   ↓
Choisit une disponibilité
   ↓
Indique l'adresse de l'événement
   ↓
Création de la réservation
   ↓
Statut : pending
   ↓
Photographe accepte ou rejette
   ↓
accepted / rejected
   ↓
Après la prestation
   ↓
completed
   ↓
Client peut laisser un avis
