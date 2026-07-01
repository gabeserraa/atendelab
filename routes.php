<?php

require_once __DIR__ . '/app/Middleware/auth.php';

require_once __DIR__ . '/app/Controllers/AuthController.php';
require_once __DIR__ . '/app/Controllers/FrontendController.php';
require_once __DIR__ . '/app/Controllers/UsuariosController.php';
require_once __DIR__ . '/app/Controllers/PessoasController.php';
require_once __DIR__ . '/app/Controllers/TiposAtendimentosController.php';
require_once __DIR__ . '/app/Controllers/AtendimentosController.php';
require_once __DIR__ . '/app/Controllers/DashboardController.php';

$controller = $_GET['controller'] ?? 'auth';
$action = $_GET['action'] ?? 'login';

switch ($controller) {


    case 'auth':

        $authController = new AuthController();

        switch ($action) {

            case 'login':
                $authController->exibirLogin();
                break;

            case 'entrar':
                $authController->entrar();
                break;

            case 'dashboard':
                exigirAutenticacao();
                $authController->dashboard();
                break;

            case 'logout':
                $authController->logout();
                break;

            default:
                echo 'Ação de autenticação não encontrada.';
                break;
        }

        break;

    case 'frontend':

        exigirAutenticacao();

        $frontendController = new FrontendController();

        switch ($action) {

            case 'dashboard':
                $frontendController->dashboard();
                break;

            case 'pessoas':
                $frontendController->pessoas();
                break;

            case 'tipos':
                $frontendController->tipos();
                break;

            case 'atendimentos':
                $frontendController->atendimentos();
                break;

            default:
                echo 'Página não encontrada.';
                break;
        }

        break;

    case 'dashboard':

        exigirAutenticacao();

        $dashboardController = new DashboardController();

        switch ($action) {

            case 'resumo':
                $dashboardController->resumo();
                break;

            default:
                echo 'Ação de dashboard não encontrada.';
                break;
        }

        break;


    case 'usuarios':

        exigirAutenticacao();

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

        break;


    case 'pessoas':

        exigirAutenticacao();

        $pessoasController = new PessoasController();

        switch ($action) {

            case 'listar':
                $pessoasController->listar();
                break;

            case 'buscar':
            case 'buscarPorId':
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

        break;


    case 'tipos':

        exigirAutenticacao();

        $tiposController = new TiposAtendimentosController();

        switch ($action) {

            case 'listar':
                $tiposController->listar();
                break;

            case 'buscar':
            case 'buscarPorId':
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

        break;

    case 'atendimentos':

        exigirAutenticacao();

        $atendimentosController = new AtendimentosController();

        switch ($action) {

            case 'listar':
                $atendimentosController->listar();
                break;

            case 'visualizar':
            case 'buscar':
                $atendimentosController->buscarPorId();
                break;

            case 'criar':
                $atendimentosController->criar();
                break;

            case 'atualizar':
                $atendimentosController->atualizar();
                break;

            case 'atualizarStatus':
                $atendimentosController->atualizarStatus();
                break;

            case 'opcoesFormulario':
                $atendimentosController->opcoesFormulario();
                break;

            default:
                echo 'Ação de atendimentos não encontrada.';
                break;
        }

        break;

    default:

        header('Location: ?controller=auth&action=login');
        exit;
}