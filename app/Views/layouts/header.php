<?php

require_once __DIR__ . '/config-view.php';

$usuario = $_SESSION['usuario'] ?? null;

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>

        <?= htmlspecialchars(
            $tituloPagina ?? 'AtendeLab',
            ENT_QUOTES,
            'UTF-8'
        ) ?>

    </title>

    <link
        rel="stylesheet"
        href="<?= $baseUrl ?>assets/css/style.css">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <script
        src="<?= $baseUrl ?>assets/js/api.js">
    </script>

</head>

<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">

    <div class="container">

        <a
            class="navbar-brand fw-bold"
            href="?controller=auth&action=dashboard">

            AtendeLab

        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#menuPrincipal">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div
            class="collapse navbar-collapse"
            id="menuPrincipal">

            <ul class="navbar-nav me-auto">

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="?controller=auth&action=dashboard">

                        Dashboard

                    </a>

                </li>

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="?controller=frontend&action=pessoas">

                        Pessoas

                    </a>

                </li>

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="?controller=frontend&action=tipos">

                        Tipos

                    </a>

                </li>

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="?controller=frontend&action=atendimentos">

                        Atendimentos

                    </a>

                </li>

            </ul>

            <?php if ($usuario): ?>

                <span class="navbar-text text-white me-3">

                    <strong>

                        <?= htmlspecialchars(
                            $usuario['nome'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>

                    </strong>

                    <small>

                        <?= htmlspecialchars(
                            $usuario['perfil'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>

                    </small>

                </span>

                <a
                    href="?controller=auth&action=logout"
                    class="btn btn-outline-light btn-sm">

                    Sair

                </a>

            <?php endif; ?>

        </div>

    </div>

</nav>

<main class="container mt-4">