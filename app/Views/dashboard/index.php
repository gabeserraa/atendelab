<?php

$tituloPagina = 'Dashboard';

require __DIR__ . '/../layouts/header.php';

?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h1 class="h3 mb-1">
            Dashboard
        </h1>

        <p class="text-secondary mb-0">
            Resumo simples para validar a integração com o backend.
        </p>

    </div>

</div>

<div class="row g-3 mb-4">

    <div class="col-md-4">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <small class="text-secondary">
                    Pessoas cadastradas
                </small>

                <h2
                    id="totalPessoas"
                    class="display-6 mb-0">
                    --
                </h2>

            </div>

        </div>

    </div>

    <div class="col-md-4">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <small class="text-secondary">
                    Tipos de atendimento
                </small>

                <h2
                    id="totalTipos"
                    class="display-6 mb-0">
                    --
                </h2>

            </div>

        </div>

    </div>

    <div class="col-md-4">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <small class="text-secondary">
                    Atendimentos registrados
                </small>

                <h2
                    id="totalAtendimentos"
                    class="display-6 mb-0">
                    --
                </h2>

            </div>

        </div>

    </div>

</div>

<div class="card shadow-sm border-0">

    <div class="card-body">

        <h2 class="h5">
            Acesso rápido
        </h2>

        <p class="text-secondary">
            Use os módulos abaixo para cadastrar e consultar dados reais do banco.
        </p>

        <a
            href="?controller=frontend&action=pessoas"
            class="btn btn-success btn-sm">

            Gerenciar pessoas

        </a>

        <a
            href="?controller=frontend&action=tipos"
            class="btn btn-success btn-sm">

            Gerenciar tipos

        </a>

        <a
            href="?controller=frontend&action=atendimentos"
            class="btn btn-success btn-sm">

            Registrar atendimentos

        </a>

    </div>

</div>

<script>

document.addEventListener('DOMContentLoaded', async () => {

    const targets = {

        pessoas: document.getElementById('totalPessoas'),
        tipos: document.getElementById('totalTipos'),
        atendimentos: document.getElementById('totalAtendimentos')

    };

    for (const [controller, element] of Object.entries(targets)) {

        try {

            const response = await AtendeLabApi.get(controller, 'listar');

            element.textContent = AtendeLabApi.toList(response).length;

        } catch (error) {

            element.textContent = '--';
            element.title = error.message;

        }

    }

});

</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>