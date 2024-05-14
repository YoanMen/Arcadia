# ECF Zoo Arcadia

Application conçue dans le cadre de ma formation Studi

Ce projet a été réalisé en utilisant PHP, HTML, CSS et Javascript en suivant le design Pattern MVC, j'ai voulu faire ce projet sans librairie externe ou de framework ayant commencé la formation en octobre je voulais perfectionner ma maîtrise de ces langages avant de passer à un framework.

## Tester en ligne

Vous pouvez tester l'application en ligne [ici](https://yoanmen.alwaysdata.net).

## Tester en local

Toutes les informations présente sont pour un systeme sous Ubuntu, à adapté selon votre système.

Pour faire tourner localement ce projet il va vous falloir

- un serveur PHP local, moi j'ai utiliser LAMP
- mySQL
- CouchDB
- serveur SMTP, j'ai utilisé sendMail pour les test en local.

### LAMP

Creation d'un dossier `localhost`

    sudo mkdir /var/www/localhost

Donner les droit:

    sudo chown -R $USER:users /var/www/localhost

Création du fichier de configuration:

    sudo nano /etc/apache2/sites-available/localhost.conf

Copiez-y :

      <VirtualHost *:80>
            ServerName localhost
            ServerAlias www.localhost
            DocumentRoot "/var/www/localhost"
            <Directory "/var/www/localhost/">
                     Options Indexes FollowSymLinks MultiViews
                     AllowOverride All
                     Require all granted
            </Directory>
            ErrorLog /var/log/apache2/error.localhost.log
            CustomLog /var/log/apache2/access.localhost.log combined
      </VirtualHost>

Activer le rewrite pour le .htaccess:

    sudo a2enmod rewrite

Rechargez apache2:

    sudo systemctl reload apache2

Placer vous dans le dossier ` /var/www/localhost` et cloner ou télécharger le projet de la branche `local`.

    git clone  https://github.com/YoanMen/Arcadia/tree/local

Déplacer le contenu du dossier `Arcadia` pour le placer à la racine de `localhost`, attention aux fichiers caché.

Donner les droit pour l'upload et la suppréssion des fichiers du dossier `upload` :

    sudo chmod -R 777 /var/www/localhost/public/uploads

### MySQL

Connectez-vous à votre MySQL.

Il va falloir créer une base de données:

    CREATE DATABASE arcadia

Importez ou exécutez le contenu du fichier `arcadia.sql`.

### CouchDB

Voir la page d'
[installation](https://docs.couchdb.org/en/stable/install/index.html) et suivez les étapes pour votre système.

Il va falloir créer une base de données, pour cela nous pouvons utiliser
[Postman](https://www.postman.com/downloads/) ou le terminal avec curl.

Remplacez `USER` et `PASSWORD` par votre compte créé à l'installation.

#### avec Postman:

    PUT http://USER:PASSWORD@localhost:5984/arcadia

Il nous faut aussi créer l'index qui servira à rechercher les animaux les plus consultés :

    PUT http://USER:PASSWORD@localhost:5984/_index

Body:

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

#### avec le terminal :

Création de la base de données :

```
curl -X PUT http://USER:PASSWORD@localhost:5984/arcadia
```

Création de l'index pour la recherche des animaux les plus consultés :

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

### CONFIGURATION

Changez les informations pour connecter les bases de données au projet. Le fichier se trouve dans `App/Core/config.php`, dans la partie localhost renseignez vos informations.

Si vous avez crée votre serveur sous un autre nom, remplacer `localhost` par votre nom afin qu'il se lance localement.

### LANCEMENT

Vous pouvez maintenant utiliser le site à l'adresse http://localhost/.

Consultez le manuel d'utilisation pour avoir un aperçu de la partie administrateur du site.
