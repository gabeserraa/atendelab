<?php

class FrontendController
{
    public function dashboard(): void
    {
        require __DIR__ . '/../Views/dashboard/index.php';
    }

    public function pessoas(): void
    {
        require __DIR__ . '/../Views/pessoas/index.php';
    }

    public function tipos(): void
    {
        require __DIR__ . '/../Views/tipos/index.php';
    }

    public function atendimentos(): void
    {
        require __DIR__ . '/../Views/atendimentos/index.php';
    }
}