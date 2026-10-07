# COMELEON

Site web de prise de rendez-vous pour un institut d'esthétique.

<img width="1919" height="945" alt="Capture d&#39;écran 2026-10-07 163622" src="https://github.com/user-attachments/assets/3853be44-904d-4fc3-a8a3-adbda38dc0b9" />

## Contexte
Mr. X, désire augmenter sa visibilité sur internet pour booster son business. Cela devient impossible pour un entrepreneur de ne pas avoir de site en ligne.
Il désire avoir un site moderne avec un style qui correspond à son activité.


Forme de l'objet
Il désire un site, accessible et ergonomique pour présenter son activité, que les clients puissent laisser des avis, consulter ses différentes prestations

[Comeleon-Web_Symfony-Fiche Descriptive.docx](https://github.com/user-attachments/files/33162224/Comeleon-Web_Symfony-Fiche.Descriptive.docx)


## Prérequis

- PHP >= 8.4
- Composer
- MySQL
- [Symfony CLI](https://symfony.com/download)

Sous Windows, après avoir installé [Scoop](https://scoop.sh/) : installez Symfony CLI avec PowerShell :

```powershell
scoop install symfony-cli
```

Sous Linux, avec Homebrew : installer Symfony CLI :

```bash
brew install symfony-cli
```
## Fonctionnalités

### Côté client
- Consultation des prestations disponibles
- Prise de rendez-vous en ligne
- Espace client
- Avis clients

### Côté administrateur
- Tableau de bord des rendez-vous
- Gestion des utilisateurs (passage Client / Admin)

## Stack technique

### Back-end
- **PHP** >= 8.4
- **Framework** : Symfony 8.1

### Front-end
- **HTML** : Twig
- **CSS**
- **JavaScript**

## Installation

1. Placez-vous dans votre espace de travail, puis clonez le dépôt :

```bash
   cd chemin/vers/votre/workspace

   git clone https://github.com/AlexisAlagille/COMELEON.git

   cd COMELEON
```

2. Installez les dépendances :

```bash
   composer install
```

3. Configurez l'environnement : Créer `.env.local` et renseignez y votre `DATABASE_URL`.

4. Créez la base de données et lancez les migrations :

```bash
   php bin/console doctrine:database:create

   php bin/console doctrine:migrations:migrate
```

5. Lancez le serveur local :

```bash
   symfony server:start
```

Le site est alors accessible sur `https://127.0.0.1:8000`.

## Architecture

```text
COMELEON/
├── bin/                 Commandes Symfony
├── config/              Configuration de Symfony et des bundles
├── migrations/          Migrations de base de données
├── public/              Point d’entrée du site et ressources publiques
│   └── assets/          CSS, JavaScript et images
├── src/                 Code PHP de l’application
│   ├── Controller/      Contrôleurs et routes
│   ├── Dto/             Objets de transfert de données
│   ├── Entity/          Entités Doctrine
│   ├── Form/            Formulaires Symfony
│   ├── Repository/      Accès aux données
│   └── Service/         Logique métier
├── templates/           Pages et composants Twig
├── composer.json        Dépendances et configuration PHP
└── symfony.lock         Versions des recettes Symfony
```

## Source des Images (libres de droit)

**https://www.pexels.com/fr-fr/**

## Auteur

- [Alexis Alagille](https://github.com/AlexisAlagille)
- [Romain Caniaux](https://github.com/romaincaniaux-code)
