# Exercice refactoring - Nicka Ratovobodo
## 1 - Mise en place de test
- J'ai installé PhpUnit dans mon projet : 
```bash 
composer require --dev phpunit/phpunit
composer require illuminate/events
```
- j'ai crée le fichier phpunit.xml à la racine du projet
- j'ai ajouté le dossier `tests/` dans lequel j'ai mis à la racine `bootstrap.php` puis j'ai créé le dossier Model qui regroupe l'ensemble des fichiers de tests sur les modèles

- pour lancer les tests, j'ai lancé la commande `vendor/bin/phpunit`

## 2 - Architecture

Pour améliorer la clarté et la maintenabilité de l’application, j’ai proposé les changements suivants :
- J'ai organisé les fichiers danas des dossiers Controller, Services, Middleware et Model

- Amélioration de la gestion des erreurs et de leur affichage

- J'ai créé un fichier de route pour séparer les responsabilités du fichier principal

- Renommage des fichiers en PascalCase pour suivre les conventions PSR-4 (ex : `addItem.php` → `AddItemController.php`). 

- J'ai uniformisé l’autoloading et des namespaces dans tout le projet
