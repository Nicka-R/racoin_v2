<?php

namespace app\Service;

use Twig\Environment;

class ErrorHandler
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function renderError(array $menu, string $chemin, array $errors, int $code = 400)
    {
        http_response_code($code);
        echo $this->twig->render('error.html.twig', [
            'breadcrumb' => $menu,
            'chemin' => $chemin,
            'errors' => $errors
        ]);
    }
}