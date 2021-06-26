<?php

namespace Project\Controller;

class AuthenticationController extends AbstractController
{
    public function loginAction(): void
    {
        if (!$_POST) {
            $this->render('login');
            return;
        }

        $email = $_POST['email'];

        if (!is_email($email)) {
            die('Não é um email válido');
        }

        $usuario = user()->findByEmail($email);

        if (!$usuario) {
            die("Usuário ou Senha incorretos.");
        }

        if(!passwd_verify($_POST['password'], $usuario->senha)) {
            die("Usuário ou Senha incorretos.");
        }

        if (!session()->has("login")) {
            session()->set('login', $usuario);
        }

        header('location: /dashboard');
    }

    public function logoutAction(): void
    {
        session()->destroy();

        header('location: /');
    }
}