<?php

require_once __DIR__ . '/app/Controllers/UsuariosController.php';
require_once __DIR__ . '/app/Controllers/PessoasController.php';

$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

/*
|--------------------------------------------------------------------------
| ROTAS DE USUÁRIOS
|--------------------------------------------------------------------------
*/
if ($controller === 'usuarios') {

    $usuariosController = new UsuariosController();

    switch ($action) {

        case 'listar':
            $usuariosController->listar();
            break;

        case 'buscar':
            $usuariosController->buscarPorId();
            break;

        case 'criar':
            $usuariosController->criar();
            break;

        case 'atualizar':
            $usuariosController->atualizar();
            break;

        case 'excluir':
            $usuariosController->excluir();
            break;

        default:
            echo 'Ação de usuários não encontrada.';
            break;
    }
}

/*
|--------------------------------------------------------------------------
| ROTAS DE PESSOAS
|--------------------------------------------------------------------------
*/
elseif ($controller === 'pessoas') {

    $pessoasController = new PessoasController();

    switch ($action) {

        case 'listar':
            $pessoasController->listar();
            break;

        case 'buscar':
            $pessoasController->buscarPorId();
            break;

        case 'criar':
            $pessoasController->criar();
            break;

        case 'atualizar':
            $pessoasController->atualizar();
            break;

        case 'inativar':
            $pessoasController->inativar();
            break;

        default:
            echo 'Ação de pessoas não encontrada.';
            break;
    }
}

/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/
else {

    echo '<h1>AtendeLab</h1>';
    echo '<p>Projeto em execução.</p>';

    echo '<h3>Rotas disponíveis:</h3>';

    echo '<p><strong>Usuários:</strong></p>';
    echo '<ul>
            <li>?controller=usuarios&action=listar</li>
            <li>?controller=usuarios&action=buscar&id=1</li>
            <li>?controller=usuarios&action=criar</li>
            <li>?controller=usuarios&action=atualizar</li>
            <li>?controller=usuarios&action=excluir</li>
          </ul>';

    echo '<p><strong>Pessoas:</strong></p>';
    echo '<ul>
            <li>?controller=pessoas&action=listar</li>
            <li>?controller=pessoas&action=buscar&id=1</li>
            <li>?controller=pessoas&action=criar</li>
            <li>?controller=pessoas&action=atualizar</li>
            <li>?controller=pessoas&action=inativar</li>
          </ul>';
}