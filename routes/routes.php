<?php

use app\Controller\AnnonceListController;
use app\Controller\ItemController;
use app\Controller\AddItemController;
use app\Controller\SearchController;
use app\Controller\AnnonceurController;
use app\Controller\CategorieController;
use app\Controller\DepartementController;
use app\Service\ApiKeyService;
use app\Model\Annonce;
use app\Model\Categorie;
use app\Model\Annonceur;
use app\Model\Departement;

// Page d'accueil
$app->get('/', function () use ($twig, $menu, $chemin, $cat) {
    $annonceController = new AnnonceListController();
    $annonceController->displayAllAnnonce($twig, $menu, $chemin, $cat->getCategories());
});

// Affichage d'un item
$app->get('/item/{n}', function ($request, $response, $arg) use ($twig, $menu, $chemin, $cat, $errorHandler) {
    $n     = $arg['n'];
    $item = new ItemController($errorHandler);
    $item->afficherItem($twig, $menu, $chemin, $n, $cat->getCategories());
});

// Formulaire d'ajout d'annonce
$app->get('/add', function () use ($twig, $app, $menu, $chemin, $cat, $dpt, $errorHandler) {
    $ajout = new AddItemController($errorHandler);
    $ajout->addItemView($twig, $menu, $chemin, $cat->getCategories(), $dpt->getAllDepartments());
});

// Traitement du formulaire d'ajout
$app->post('/add', function ($request) use ($twig, $app, $menu, $chemin, $errorHandler) {
    $allPostVars = $request->getParsedBody();
    $ajout       = new AddItemController($errorHandler);
    $ajout->addNewItem($twig, $menu, $chemin, $allPostVars);
});

// Formulaire de modification d'un item
$app->get('/item/{id}/edit', function ($request, $response, $arg) use ($twig, $menu, $chemin, $errorHandler) {
    $id   = $arg['id'];
    $item = new ItemController($errorHandler);
    $item->modifyGet($twig, $menu, $chemin, $id);
});

// Traitement de la modification d'un item
$app->post('/item/{id}/edit', function ($request, $response, $arg) use ($twig, $app, $menu, $chemin, $cat, $dpt, $errorHandler) {
    $id          = $arg['id'];
    $allPostVars = $request->getParsedBody();
    $item        = new ItemController($errorHandler);
    $item->modifyPost($twig, $menu, $chemin, $id, $allPostVars, $cat->getCategories(), $dpt->getAllDepartments());
});

// Confirmation de modification
$app->map(['GET', 'POST'], '/item/{id}/confirm', function ($request, $response, $arg) use ($twig, $app, $menu, $chemin, $errorHandler) {
    $id   = $arg['id'];
    $allPostVars = $request->getParsedBody();
    $item        = new ItemController($errorHandler);
    $item->edit($twig, $menu, $chemin, $id, $allPostVars);
});

// Page de recherche
$app->get('/search', function () use ($twig, $menu, $chemin, $cat) {
    $s = new SearchController();
    $s->show($twig, $menu, $chemin, $cat->getCategories());
});

// Traitement de la recherche
$app->post('/search', function ($request, $response) use ($app, $twig, $menu, $chemin, $cat) {
    $array = $request->getParsedBody();
    $s     = new SearchController();
    $s->research($array, $twig, $menu, $chemin, $cat->getCategories());
});

// Affichage d'un annonceur
$app->get('/annonceur/{n}', function ($request, $response, $arg) use ($twig, $menu, $chemin, $cat) {
    $n         = $arg['n'];
    $annonceur = new AnnonceurController();
    $annonceur->afficherAnnonceur($twig, $menu, $chemin, $n, $cat->getCategories());
});

// Suppression d'un item (GET)
$app->get('/del/{n}', function ($request, $response, $arg) use ($twig, $menu, $chemin, $errorHandler) {
    $n    = $arg['n'];
    $item = new ItemController($errorHandler);
    $item->supprimerItemGet($twig, $menu, $chemin, $n);
});

// Suppression d'un item (POST)
$app->post('/del/{n}', function ($request, $response, $arg) use ($twig, $menu, $chemin, $cat, $errorHandler) {
    $n    = $arg['n'];
    $item = new ItemController($errorHandler);
    $item->supprimerItemPost($twig, $menu, $chemin, $n, $cat->getCategories());
});

// Affichage d'une catégorie
$app->get('/cat/{n}', function ($request, $response, $arg) use ($twig, $menu, $chemin, $cat) {
    $n = $arg['n'];
    $categorie = new CategorieController();
    $categorie->displayCategorie($twig, $menu, $chemin, $cat->getCategories(), $n);
});

// Page API
$app->get('/api(/)', function () use ($twig, $menu, $chemin, $cat) {
    $template = $twig->load('api.html.twig');
    $menu     = array(
        array(
            'href' => $chemin,
            'text' => 'Acceuil'
        ),
        array(
            'href' => $chemin . '/api',
            'text' => 'Api'
        )
    );
    echo $template->render(array('breadcrumb' => $menu, 'chemin' => $chemin));
});

// Groupes d'API REST
$app->group('/api', function () use ($app, $twig, $menu, $chemin, $cat) {

    $app->group('/annonce', function () use ($app) {

        $app->get('/{id}', function ($request, $response, $arg) use ($app) {
            $id          = $arg['id'];
            $annonceList = ['id_annonce', 'id_categorie as categorie', 'id_annonceur as annonceur', 'id_departement as departement', 'prix', 'date', 'titre', 'description', 'ville'];
            $return      = Annonce::select($annonceList)->find($id);

            if (isset($return)) {
                $response->headers->set('Content-Type', 'application/json');
                $return->categorie     = Categorie::find($return->categorie);
                $return->annonceur     = Annonceur::select('email', 'nom_annonceur', 'telephone')
                    ->find($return->annonceur);
                $return->departement   = Departement::select('id_departement', 'nom_departement')->find($return->departement);
                $links                 = [];
                $links['self']['href'] = '/api/annonce/' . $return->id_annonce;
                $return->links         = $links;
                echo $return->toJson();
            } else {
                $app->notFound();
            }
        });
    });

    $app->group('/annonces(/)', function () use ($app) {

        $app->get('/', function ($request, $response) use ($app) {
            $annonceList = ['id_annonce', 'prix', 'titre', 'ville'];
            $response->headers->set('Content-Type', 'application/json');
            $a     = Annonce::all($annonceList);
            $links = [];
            foreach ($a as $ann) {
                $links['self']['href'] = '/api/annonce/' . $ann->id_annonce;
                $ann->links            = $links;
            }
            $links['self']['href'] = '/api/annonces/';
            $a->links              = $links;
            echo $a->toJson();
        });
    });

    $app->group('/categorie', function () use ($app) {

        $app->get('/{id}', function ($request, $response, $arg) use ($app) {
            $id = $arg['id'];
            $response->headers->set('Content-Type', 'application/json');
            $a     = Annonce::select('id_annonce', 'prix', 'titre', 'ville')
                ->where('id_categorie', '=', $id)
                ->get();
            $links = [];

            foreach ($a as $ann) {
                $links['self']['href'] = '/api/annonce/' . $ann->id_annonce;
                $ann->links            = $links;
            }

            $c                     = Categorie::find($id);
            $links['self']['href'] = '/api/categorie/' . $id;
            $c->links              = $links;
            $c->annonces           = $a;
            echo $c->toJson();
        });
    });

    $app->group('/categories(/)', function () use ($app) {
        $app->get('/', function ($request, $response, $arg) use ($app) {
            $response->headers->set('Content-Type', 'application/json');
            $c     = Categorie::get();
            $links = [];
            foreach ($c as $cat) {
                $links['self']['href'] = '/api/categorie/' . $cat->id_categorie;
                $cat->links            = $links;
            }
            $links['self']['href'] = '/api/categories/';
            $c->links              = $links;
            echo $c->toJson();
        });
    });

    $app->get('/key', function () use ($app, $twig, $menu, $chemin, $cat) {
        $kg = new ApiKeyService();
        $kg->show($twig, $menu, $chemin, $cat->getCategories());
    });

    $app->post('/key', function () use ($app, $twig, $menu, $chemin, $cat) {
        $nom = $_POST['nom'];

        $kg = new ApiKeyService();
        $kg->generateKey($twig, $menu, $chemin, $cat->getCategories(), $nom);
    });
});