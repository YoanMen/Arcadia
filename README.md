# ECF Zoo Arcadia

Application conçue dans le cadre de ma formation Studi

Ce projet a été réalisé en utilisant PHP, HTML, CSS et Javascript, j'ai voulu faire ce projet sans librairie externe ou de framework ayant commencé la formation en octobre je voulais perfectionner ma maîtrise de ces langages avant de passer à un framework.

## Tester en local

Pour faire tourner localement ce projet il va vous falloir

- un serveur local, moi j'ai utiliser Xampp
- CouchDB
- mySQL
- serveur SMTP, j'ai utilisé sendMail pour les test en local.

Cloner le projet de la branche "local"

    git clone  https://github.com/YoanMen/Arcadia/tree/local

### XAMPP

Démarrer votre serveur local et le projet dans le htdocs de Xampp

### MySQL

Connecter vous a votre mysql

Il va falloir créer une base de données

    CREATE DATABASE arcadia

importé ou exécuté le contenu du fichier arcadia.sql

### CouchDB

Voir la page d'
[installation](https://docs.couchdb.org/en/stable/install/index.html) et suivez les étapes pour votre système.

Il va falloir crée une une base de données, pour cela nous pouvons utiliser
[Postman](https://www.postman.com/downloads/) ou le terminal avec curl

postman:

remplacer user et password par vos identifiant.

    PUT http://USER:PASSWORD@localhost:5984/arcadia

Il nous faut aussi créer l'index qui servira à rechercher les animaux les plus consultés

    PUT http://USER:PASSWORD@localhost:5984/_index

body:

```
{
   "index": {
      "fields": [
         "click"
      ]
   },
   "name": "click-index"
}
```

avec le terminal :

```
curl -X PUT http://USER:PASSWORD@localhost:5984/arcadia
```

création de l'index pour la recherche des animaux les plus consulté

```
curl -X POST http://USER:PASSWORD@localhost:5984/arcadia/_index \
-H "Content-Type: application/json" \
-d '{
   "index": {
      "fields": [
         "click"
      ]
   },
   "name": "click-index"
}'
```

### Paramétrage

Changer les informations pour connecter les base de données au projet, le fichier se trouve dans App/Core/config.php, dans la parti localhost renseigné vos informations.

Vous pouvez maintenant utiliser le site à l'adresse http://localhost/.
