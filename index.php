<?php

require __DIR__ . "/autoload.php";

ob_start(); // Bug que não sei o que é, pedi muito tempo e isso resolve.

session();

$route = getRouteFromURL();

function getRouteFromURL(): array
{
    // Capturando a url que o usuário está acessando
    $url = explode('?', $_SERVER['REQUEST_URI'])[0];

    //importa as rotas da aplicação
    $routes = include_once 'Core/Route.php';

    // Verifica se existe rota definida
    if (!isset($routes[$url])) {
        echo "<h1>Não tem rota criar a pagina 404</h1>";
        exit;
    }

    return $routes[$url];
}

function main(): void
{
    global $route;

    // Pega o nome do controller
    $controller = $route['controller'];
    // Pega o nome da ação que é o método
    $method = $route['action'];

    (new $controller())->$method();
}

include './views/template.phtml';