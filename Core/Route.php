<?php

use Project\Controller\AuthenticationController;
use Project\Controller\DashboardController;
use Project\Controller\ProductController;

function createRoute(string $controllerName, ?string $actionName = null): array
{
    return [
        'controller' => $controllerName,
        'action' => $actionName
    ];
}

return [
    '/' => createRoute(AuthenticationController::class, 'loginAction'),
    '/login' => createRoute(AuthenticationController::class, 'loginAction'),
    '/logout' => createRoute(AuthenticationController::class, 'logoutAction'),
    '/dashboard' => createRoute(DashboardController::class, 'dashboardAction'),
    '/produto/listar' => createRoute(ProductController::class, 'listAction'),
    '/produto/adicionar' => createRoute(ProductController::class, 'addAction'),
    '/produto/editar' => createRoute(ProductController::class, 'editAction'),
    '/produto/remover' => createRoute(ProductController::class, 'removeAction'),
];