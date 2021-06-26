<?php

namespace Project\Controller;

class AbstractController
{
    public function render(string $viewName, array $data = []): void
    {
        // Separa as informações do array em variaveis com o mesmo nome
        extract($data);

        include "./views/{$viewName}.phtml";
    }

    public static function menu(): void
    {
        if (session()->has('login')) {
            $user = session()->all();

            include "./views/menu.phtml";
        }
    }
}