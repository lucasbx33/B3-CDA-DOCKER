# REYNAUD LUCAS

# GESTION PRODUITS

## Prérequis
Cette application est compatible `PHP5` et a été testée avec une base de données `MySQL 5.7`.

## Installation
- Copier les fichiers du dossier `www` dans un dossier accessible par le serveur Web.
- Assurez vous que le dossier `uploads` est accessible en lecture et écriture par le serveur Web : `chmod 777 uploads`
- Importez la base de données test à partir du dump SQL `database/gestion_produits.sql`.
- Connectez vous à l'application avec l'url adaptée avec les informations suivantes :
    - Login : `admin`
    - Mot de passe : `password`

## Fonctionnalités
L'application permet de :
- Lister les produits
- Afficher la fiche produit en lecture seule
- Ajouter des produits
- Modifier les produits
- Supprimer les produits
- Pour chaque produit, il est possible d'ajouter autant de photos que nécessaire

# Exercice 1
- docker network create exo_network

- docker build -t exo_docker_mysql -f dockerfile .
- docker build -t exo_docker_php -f dockerfile .

- docker run -d --name app_php_docker --network exo_network -p 80:80 exo_docker_php
- docker run -d --name mysql_exo_docker --network exo_network -p 3306:3306 exo_docker_mysql

# Exercice 2

- docker-compose up --build -d

# Exercice 3

DEV:
- docker-compose -f docker-compose.dev.yml up --build -d

PROD:
- docker-compose up --build -d

# Exercice 4

branche "postgreSQL"

docker-compose up --build -d
