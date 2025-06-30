<?php
require 'vendor/autoload.php';

use app\Controller\CategorieController;
use app\Controller\DepartementController;
use app\Controller\AnnonceListController;
use app\Controller\ItemController;
use app\Controller\AddItemController;
use app\Controller\SearchController;
use app\Controller\AnnonceurController;
use app\Service\ApiKeyService;
use app\Service\Database\Connection;
use app\Middleware\TrailingSlashMiddleware;

use app\Model\Annonce;
use app\Model\Categorie;
use app\Model\Annonceur;
use app\Model\Departement;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;


connection::createConn();

// Initialisation de Slim
$app = new App([
    'settings' => [
        'displayErrorDetails' => true,
    ],
]);

// Initialisation de Twig
$loader = new FilesystemLoader(__DIR__ . '/template');
$twig   = new Environment($loader);

// Ajout d'un middleware pour le trailing slash
$app->add(new TrailingSlashMiddleware());


if (!isset($_SESSION)) {
    session_start();
    $_SESSION['formStarted'] = true;
}

if (!isset($_SESSION['token'])) {
    $token                  = md5(uniqid(rand(), TRUE));
    $_SESSION['token']      = $token;
    $_SESSION['token_time'] = time();
} else {
    $token = $_SESSION['token'];
}

$menu = [
    [
        'href' => './index.php',
        'text' => 'Accueil'
    ]
];

$chemin = dirname($_SERVER['SCRIPT_NAME']);

$cat = new CategorieController();
$dpt = new DepartementController();

use app\Service\ErrorHandler;
$errorHandler = new ErrorHandler($twig);
// Inclusion des routes
require __DIR__ . '/routes/routes.php';

$app->run();
