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

        $sql = "SELECT
                    a.*,
                    p.nome AS pessoa,
                    u.nome AS usuario
                FROM atendimentos a
                LEFT JOIN pessoas p ON p.id = a.pessoa_id
                LEFT JOIN usuarios u ON u.id = a.usuario_id
                ORDER BY a.id DESC";

        $stmt = $this->pdo->query($sql);

        echo json_encode(
            $stmt->fetchAll(PDO::FETCH_ASSOC),
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
    }

    public function buscarPorId(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            http_response_code(400);

            echo json_encode([
                'erro' => 'ID inválido.'
            ]);

            return;
        }

        $stmt = $this->pdo->prepare(
            "SELECT *
             FROM atendimentos
             WHERE id = :id"
        );

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $atendimento = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$atendimento) {

            http_response_code(404);

            echo json_encode([
                'erro' => 'Atendimento não encontrado.'
            ]);

            return;
        }

        echo json_encode(
            $atendimento,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
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

        if (
            !in_array(
                $status,
                ['aberto', 'em_andamento', 'finalizado', 'cancelado'],
                true
            )
        ) {

            http_response_code(400);

            echo json_encode([
                'erro' => 'Status inválido.'
            ]);

            return;
        }

        try {

            $stmt = $this->pdo->prepare(
                "INSERT INTO atendimentos
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
                    :tipo_atendimento_id,
                    :usuario_id,
                    :data_atendimento,
                    :hora_atendimento,
                    :descricao,
                    :observacao,
                    :status
                )"
            );

            $stmt->bindValue(':pessoa_id', $pessoa_id);
            $stmt->bindValue(':tipo_atendimento_id', $tipo_atendimento_id);
            $stmt->bindValue(':usuario_id', $usuario_id);
            $stmt->bindValue(':data_atendimento', $data_atendimento);
            $stmt->bindValue(':hora_atendimento', $hora_atendimento);
            $stmt->bindValue(':descricao', $descricao);
            $stmt->bindValue(':observacao', $observacao);
            $stmt->bindValue(':status', $status);

            $stmt->execute();

            http_response_code(201);

            echo json_encode([
                'mensagem' => 'Atendimento cadastrado com sucesso.',
                'id' => $this->pdo->lastInsertId()
            ]);

        } catch (PDOException $e) {

            http_response_code(500);

            echo json_encode([
                'erro' => 'Erro ao cadastrar atendimento.',
                'detalhes' => $e->getMessage()
            ]);
        }
    }

    public function atualizar(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        $pessoa_id = filter_input(INPUT_POST, 'pessoa_id', FILTER_VALIDATE_INT);
        $tipo_atendimento_id = filter_input(INPUT_POST, 'tipo_atendimento_id', FILTER_VALIDATE_INT);
        $usuario_id = filter_input(INPUT_POST, 'usuario_id', FILTER_VALIDATE_INT);

        $data_atendimento = trim($_POST['data_atendimento'] ?? '');
        $hora_atendimento = trim($_POST['hora_atendimento'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $observacao = trim($_POST['observacao'] ?? '');
        $status = $_POST['status'] ?? 'aberto';

        if (!$id) {

            http_response_code(400);

            echo json_encode([
                'erro' => 'ID inválido.'
            ]);

            return;
        }

        try {

            $stmt = $this->pdo->prepare(
                "UPDATE atendimentos
                 SET
                    pessoa_id = :pessoa_id,
                    tipo_atendimento_id = :tipo_atendimento_id,
                    usuario_id = :usuario_id,
                    data_atendimento = :data_atendimento,
                    hora_atendimento = :hora_atendimento,
                    descricao = :descricao,
                    observacao = :observacao,
                    status = :status
                 WHERE id = :id"
            );

            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':pessoa_id', $pessoa_id);
            $stmt->bindValue(':tipo_atendimento_id', $tipo_atendimento_id);
            $stmt->bindValue(':usuario_id', $usuario_id);
            $stmt->bindValue(':data_atendimento', $data_atendimento);
            $stmt->bindValue(':hora_atendimento', $hora_atendimento);
            $stmt->bindValue(':descricao', $descricao);
            $stmt->bindValue(':observacao', $observacao);
            $stmt->bindValue(':status', $status);

            $stmt->execute();

            echo json_encode([
                'mensagem' => 'Atendimento atualizado com sucesso.'
            ]);

        } catch (PDOException $e) {

            http_response_code(500);

            echo json_encode([
                'erro' => 'Erro ao atualizar atendimento.'
            ]);
        }
    }

    public function finalizar(): void
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

        $stmt = $this->pdo->prepare(
            "UPDATE atendimentos
             SET status = 'finalizado'
             WHERE id = :id"
        );

        $stmt->bindValue(':id', $id);
        $stmt->execute();

        echo json_encode([
            'mensagem' => 'Atendimento finalizado com sucesso.'
        ]);
    }

    public function cancelar(): void
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

        $stmt = $this->pdo->prepare(
            "UPDATE atendimentos
             SET status = 'cancelado'
             WHERE id = :id"
        );

        $stmt->bindValue(':id', $id);
        $stmt->execute();

        echo json_encode([
            'mensagem' => 'Atendimento cancelado com sucesso.'
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