# COMELEON

Site web de prise de rendez-vous pour un institut d'esthétique.

## Prérequis

- PHP >= 8.4
- Composer
- MySQL
- [Symfony CLI](https://symfony.com/download)

Sous Windows, après avoir installé [Scoop](https://scoop.sh/) : installez Symfony CLI avec PowerShell :

```powershell
scoop install symfony-cli
```

Sous Linux, avec Homebrew : installer Symfony CLI avec PowerShell :

```powershell
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

## Auteur

- [Alexis Alagille](https://github.com/AlexisAlagille)
- [Romain Caniaux](https://github.com/romaincaniaux-code)
