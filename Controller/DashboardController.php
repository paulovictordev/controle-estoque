<?php

namespace Project\Controller;

class DashboardController extends AbstractController
{
    public function dashboardAction(): void
    {
        $this->render('dashboard');
    }
}