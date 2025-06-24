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

