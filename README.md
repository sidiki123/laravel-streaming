# Laravel streaming app

Cette application vous permet de gérer et diffuser des fichiers audio en streaming.

## Installation

Configurations requises 

1. PHP (version 8.1 ou supérieure)
2. Composer (version 2.0.1 ou supérieure)
3. MySQL

## Instructions d'installation

Suivez les étapes ci-dessous pour installer et exécuter l'application :

1. Télécharger ou clonez le dépôt à l'aide de la commande suivante :
git clone https://github.com/

2. Accédez au répertoire de l'application :

cd nom-du-depot

3. Installez les dépendances PHP à l'aide de Composer :

composer install

4. Copiez le fichier `.env.example` et renommez-le en `.env`

cp .env.example .env

5. Générez une nouvelle clé d'application :

php artisan key:generate

6. Exécutez les migrations :

php artisan migrate


De plus, vous devez installer FFMpeg et FFProbe :

1. Téléchargez les binaires FFMpeg et FFProbe depuis le site officiel (https://ffmpeg.org/download.html) suivant votre système d'exploitation

2. Après avoir téléchargé le fichier, décompresser le.

3. Vous pouvez également configurer les chemins vers les binaires directement dans le fichier `.env` de votre application en ajoutant les lignes suivantes :
Dans cet exemple, le dossier décompréssé se trouve à la racinde de votre disque.

FFMPEG_BINARIES=C:/chemin/vers/ffmpeg.exe
FFPROBE_BINARIES=C:/chemin/vers/ffprobe.exe



## Liens utiles

- [Documentation de la bibliothèque FFMpeg pour Laravel](https://github.com/protonemedia/laravel-ffmpeg#installation)
- [Tutoriel d'installation de FFMpeg pour Laravel](https://protone.media/en/blog/how-to-use-ffmpeg-in-your-laravel-projects)
- [Manuel d'installation FFMpeg](https://bbc.github.io/bbcat-orchestration-docs/installation-mac-manual)