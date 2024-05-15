# ECF Zoo Arcadia

Application conçue dans le cadre de ma formation Studi

Ce projet a été réalisé en utilisant PHP, HTML, CSS et Javascript en suivant le design Pattern MVC. J'ai voulu faire ce projet sans librairie externe ou framework, ayant commencé la formation en octobre, je voulais perfectionner ma maîtrise de ces langages avant de passer à un framework.

## Tester en ligne

Vous pouvez tester l'application en ligne [ici](https://yoanmen.alwaysdata.net).

## Tester en local

Toutes les informations présentes sont pour un système sous Ubuntu, à adapter selon votre système.

Pour faire tourner localement ce projet, il vous faudra :

- LAMP
- mySQL
- CouchDB
- SendMail.

### LAMP

Création d'un dossier `arcadia` :

    sudo mkdir /var/www/arcadia

Donnez les droits :

    sudo chown -R $USER:users /var/www/arcadia

Création du fichier de configuration :

    sudo nano /etc/apache2/sites-available/arcadia.conf

Copiez-y :

    <VirtualHost *:80>
            ServerName arcadia
            ServerAlias arcadia
            DocumentRoot "/var/www/arcadia"
            <Directory "/var/www/arcadia/">
                    Options FollowSymLinks
                    AllowOverride All
                    Require all granted
            </Directory>
            ErrorLog /var/log/apache2/error.arcadia.log
            CustomLog /var/log/apache2/access.arcadia.log combined
    </VirtualHost>

Modifiez le hosts :

    sudo nano  /etc/hosts

Ajoutez la ligne :

    127.0.0.1       arcadia

Activez le module rewrite pour le .htaccess :

    sudo a2enmod rewrite

Activez le site arcadia :

    sudo a2ensite arcadia

Rechargez Apache2 :

    sudo systemctl reload apache2

Placez-vous dans le dossier `/var/www/arcadia` et clonez ou téléchargez le projet de la branche `local` :

    git clone  https://github.com/YoanMen/Arcadia/tree/local

Déplacez le contenu du dossier `Arcadia` pour le placer à la racine de `arcadia`, attention aux fichiers cachés.

Donnez les droits pour l'upload et la suppression des fichiers du dossier uploads :

    sudo chmod -R 777 /var/www/arcadia/public/uploads

### MySQL

Connectez-vous à votre MySQL.

    mysql --host=localhost --user=root --password

Création de la base de données:

    CREATE DATABASE arcadia;

Sélectionnez la base de données:

    USE arcadia;

Collez-y le contenu du fichier `arcadia.sql`.

Vous pouvez aussi utiliser `phpmyadmin` pour crée la base de données.

### CouchDB

Voir la page d'
[installation](https://docs.couchdb.org/en/stable/install/index.html) et suivez les étapes pour votre système.

Il va falloir créer une base de données, pour cela nous pouvons utiliser
[Postman](https://www.postman.com/downloads/) ou le terminal avec `curl`.

Remplacez `USER` et `PASSWORD` par votre compte créé à l'installation.

#### avec Postman:

Création de la base de données:

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

### SENDMAIL

Pour installer sendMail je vous invite à suivre ce [tutoriel](https://kenfavors.com/code/how-to-install-and-configure-sendmail-on-ubuntu/).

J'utilise le service de mailinator afin de procédé à mes tests.

Récupérez votre nom d'hôte:

    hostname

Modifiez le hosts:

    sudo nano /etc/hosts

Modifiez la première ligne comme ceci:

    127.0.0.1  localhost.localdomain localhost HOSTNAME

Éditez le fichier de configuration de sendMail:

    sudo nano /etc/mail/sendmail.cf

Décommentez la ligne `#O HostsFile=/etc/hosts`

Redémarrez votre ordinateur.

Rendez-vous sur [mailinator](https://www.mailinator.com/v4/public/inboxes.jsp?to=arcadiacontact) pour voir la boite de réception.

### CONFIGURATION

Changez les informations pour connecter les bases de données au projet. Le fichier se trouve dans `App/Core/config.php`, dans la partie localhost renseignez vos informations.

### LANCEMENT

Vous pouvez maintenant utiliser le site à l'adresse http://arcadia/.

Consultez le manuel d'utilisation pour avoir un aperçu de la partie administration du site.
