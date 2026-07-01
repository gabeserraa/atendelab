<?php

class AtendimentosController
{
    private PDO $pdo;

    public function __construct()
    {
        require __DIR__ . '/../../config/database.php';

        $this->pdo = $pdo;
    }

    public function listar(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $sql = "
            SELECT

                a.id,

                a.pessoa_id,
                a.tipo_atendimento_id,
                a.usuario_id,

                p.nome AS pessoa_nome,

                t.nome AS tipo_nome,

                u.nome AS usuario_nome,

                a.data_atendimento,
                a.hora_atendimento,

                a.descricao,
                a.observacao,
                a.observacao_final,

                a.status

            FROM atendimentos a

            INNER JOIN pessoas p
                ON p.id = a.pessoa_id

            INNER JOIN tipos_atendimentos t
                ON t.id = a.tipo_atendimento_id

            INNER JOIN usuarios u
                ON u.id = a.usuario_id

            ORDER BY a.id DESC
        ";

        $stmt = $this->pdo->query($sql);

        echo json_encode(

            $stmt->fetchAll(PDO::FETCH_ASSOC),

            JSON_PRETTY_PRINT
            | JSON_UNESCAPED_UNICODE

        );
    }

    public function buscarPorId(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $id = filter_input(
            INPUT_GET,
            'id',
            FILTER_VALIDATE_INT
        );

        if (!$id) {

            http_response_code(400);

            echo json_encode([
                'erro' => 'ID inválido.'
            ]);

            return;
        }

        $sql = "

            SELECT *

            FROM atendimentos

            WHERE id = :id

            LIMIT 1

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(
            ':id',
            $id,
            PDO::PARAM_INT
        );

        $stmt->execute();

        $registro =
            $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$registro) {

            http_response_code(404);

            echo json_encode([
                'erro' => 'Atendimento não encontrado.'
            ]);

            return;
        }

        echo json_encode(

            $registro,

            JSON_PRETTY_PRINT
            | JSON_UNESCAPED_UNICODE

        );
    }

    public function opcoesFormulario(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $pessoas = $this->pdo
            ->query("

                SELECT
                    id,
                    nome

                FROM pessoas

                WHERE status='ativo'

                ORDER BY nome

            ")
            ->fetchAll(PDO::FETCH_ASSOC);

        $tipos = $this->pdo
            ->query("

                SELECT
                    id,
                    nome

                FROM tipos_atendimentos

                WHERE status='ativo'

                ORDER BY nome

            ")
            ->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([

            'pessoas' => $pessoas,

            'tipos' => $tipos

        ]);
    }
        public function criar(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $pessoa_id = filter_input(INPUT_POST, 'pessoa_id', FILTER_VALIDATE_INT);
        $tipo_atendimento_id = filter_input(INPUT_POST, 'tipo_atendimento_id', FILTER_VALIDATE_INT);

        $usuario_id = $this->usuarioResponsavel();

        $data_atendimento = trim($_POST['data_atendimento'] ?? '');
        $hora_atendimento = trim($_POST['hora_atendimento'] ?? '');

        $descricao = trim($_POST['descricao'] ?? '');
        $observacao = trim($_POST['observacao'] ?? '');

        $status = $_POST['status'] ?? 'aberto';

        if (
            !$pessoa_id ||
            !$tipo_atendimento_id ||
            !$usuario_id ||
            $data_atendimento === '' ||
            $hora_atendimento === ''
        ) {

            http_response_code(400);

            echo json_encode([
                'erro' => 'Campos obrigatórios não informados.'
            ]);

            return;
        }

        $sql = "

            INSERT INTO atendimentos
            (
                pessoa_id,
                tipo_atendimento_id,
                usuario_id,
                data_atendimento,
                hora_atendimento,
                descricao,
                observacao,
                status
            )

            VALUES
            (
                :pessoa_id,
                :tipo,
                :usuario,
                :data,
                :hora,
                :descricao,
                :observacao,
                :status
            )

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([

            ':pessoa_id' => $pessoa_id,
            ':tipo' => $tipo_atendimento_id,
            ':usuario' => $usuario_id,
            ':data' => $data_atendimento,
            ':hora' => $hora_atendimento,
            ':descricao' => $descricao,
            ':observacao' => $observacao,
            ':status' => $status

        ]);

        http_response_code(201);

        echo json_encode([
            'mensagem' => 'Atendimento cadastrado com sucesso.'
        ]);
    }

    public function atualizar(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if (!$id) {

            http_response_code(400);

            echo json_encode([
                'erro' => 'ID inválido.'
            ]);

            return;
        }

        $sql = "

            UPDATE atendimentos

            SET

                pessoa_id = :pessoa_id,

                tipo_atendimento_id = :tipo,

                data_atendimento = :data,

                hora_atendimento = :hora,

                descricao = :descricao,

                observacao = :observacao,

                status = :status

            WHERE id = :id

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([

            ':id' => $id,

            ':pessoa_id' => filter_input(INPUT_POST, 'pessoa_id', FILTER_VALIDATE_INT),

            ':tipo' => filter_input(INPUT_POST, 'tipo_atendimento_id', FILTER_VALIDATE_INT),

            ':data' => trim($_POST['data_atendimento'] ?? ''),

            ':hora' => trim($_POST['hora_atendimento'] ?? ''),

            ':descricao' => trim($_POST['descricao'] ?? ''),

            ':observacao' => trim($_POST['observacao'] ?? ''),

            ':status' => $_POST['status'] ?? 'aberto'

        ]);

        echo json_encode([
            'mensagem' => 'Atendimento atualizado com sucesso.'
        ]);
    }

    public function atualizarStatus(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        $status = trim($_POST['status'] ?? '');

        $observacao_final = trim($_POST['observacao_final'] ?? '');

        $statusPermitidos = [

            'aberto',

            'em_andamento',

            'finalizado',

            'cancelado'

        ];

        if (!$id || !in_array($status, $statusPermitidos, true)) {

            http_response_code(400);

            echo json_encode([
                'erro' => 'Dados inválidos.'
            ]);

            return;
        }

        if ($status === 'finalizado' && $observacao_final === '') {

            http_response_code(400);

            echo json_encode([
                'erro' => 'Informe a observação final.'
            ]);

            return;
        }

        $stmt = $this->pdo->prepare("

            UPDATE atendimentos

            SET

                status = :status,

                observacao_final = :observacao

            WHERE id = :id

        ");

        $stmt->execute([

            ':id' => $id,

            ':status' => $status,

            ':observacao' => $observacao_final

        ]);

        echo json_encode([
            'mensagem' => 'Status atualizado com sucesso.'
        ]);
    }

    private function usuarioResponsavel(): int
    {
        if (isset($_SESSION['usuario']['id'])) {

            return (int) $_SESSION['usuario']['id'];

        }

        return (int) ($_POST['usuario_id'] ?? 0);
    }
}