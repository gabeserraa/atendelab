<?php

require_once __DIR__ . '/app/Controllers/AuthController.php';
require_once __DIR__ . '/app/Controllers/UsuariosController.php';
require_once __DIR__ . '/app/Controllers/PessoasController.php';
require_once __DIR__ . '/app/Controllers/AtendimentosController.php';
require_once __DIR__ . '/app/Controllers/TiposAtendimentosController.php';
require_once __DIR__ . '/app/Middleware/auth.php';

$controller = $_GET['controller'] ?? 'auth';
$action = $_GET['action'] ?? 'login';

if ($controller === 'auth') {

    $authController = new AuthController();

    switch ($action) {

        case 'login':
            $authController->exibirLogin();
            break;

        case 'entrar':
            $authController->entrar();
            break;

        case 'dashboard':
            $authController->dashboard();
            break;

        case 'logout':
            $authController->logout();
            break;

        default:
            http_response_code(404);
            echo 'Ação de autenticação não encontrada.';
            break;
    }
}

elseif ($controller === 'usuarios') {

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


elseif ($controller === 'atendimentos') {

    $atendimentosController = new AtendimentosController();

    switch ($action) {

        case 'listar':
            $atendimentosController->listar();
            break;

        case 'buscar':
            $atendimentosController->buscarPorId();
            break;

        case 'criar':
            $atendimentosController->criar();
            break;

        case 'atualizar':
            $atendimentosController->atualizar();
            break;

        case 'finalizar':
            $atendimentosController->finalizar();
            break;

        case 'cancelar':
            $atendimentosController->cancelar();
            break;

        default:
            echo 'Ação de atendimentos não encontrada.';
            break;
    }
}

elseif ($controller === 'tipos_atendimentos') {

    $tiposController = new TiposAtendimentosController();

    switch ($action) {

        case 'listar':
            $tiposController->listar();
            break;

        case 'buscar':
            $tiposController->buscarPorId();
            break;

        case 'criar':
            $tiposController->criar();
            break;

        case 'atualizar':
            $tiposController->atualizar();
            break;

        case 'inativar':
            $tiposController->inativar();
            break;

        default:
            echo 'Ação de tipos de atendimento não encontrada.';
            break;
    }
}


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

    echo '<p><strong>Atendimentos:</strong></p>';
    echo '<ul>
            <li>?controller=atendimentos&action=listar</li>
            <li>?controller=atendimentos&action=buscar&id=1</li>
            <li>?controller=atendimentos&action=criar</li>
            <li>?controller=atendimentos&action=atualizar</li>
            <li>?controller=atendimentos&action=finalizar</li>
            <li>?controller=atendimentos&action=cancelar</li>
          </ul>';

    echo '<p><strong>Tipos de Atendimento:</strong></p>';
    echo '<ul>
            <li>?controller=tipos_atendimentos&action=listar</li>
            <li>?controller=tipos_atendimentos&action=buscar&id=1</li>
            <li>?controller=tipos_atendimentos&action=criar</li>
            <li>?controller=tipos_atendimentos&action=atualizar</li>
            <li>?controller=tipos_atendimentos&action=inativar</li>
          </ul>';
}