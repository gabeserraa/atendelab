<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">

    <h2 class="mb-4">Tipos de Atendimento</h2>

    <div id="alerta"></div>

    <div class="card mb-4">

        <div class="card-header">
            Novo Tipo
        </div>

        <div class="card-body">

            <form id="formTipo">

                <input type="hidden" id="tipoId" name="id">

                <div class="mb-3">

                    <label class="form-label">Nome</label>

                    <input
                        type="text"
                        class="form-control"
                        name="nome"
                        required>

                </div>

                <div class="mb-3">

                    <label class="form-label">Descrição</label>

                    <textarea
                        class="form-control"
                        name="descricao"
                        rows="3"></textarea>

                </div>

                <div class="mb-3">

                    <label class="form-label">Status</label>

                    <select
                        class="form-select"
                        name="status">

                        <option value="ativo">
                            Ativo
                        </option>

                        <option value="inativo">
                            Inativo
                        </option>

                    </select>

                </div>

                <button
                    class="btn btn-primary"
                    type="submit">

                    Salvar

                </button>

            </form>

        </div>

    </div>

    <div class="card">

        <div class="card-header">
            Tipos cadastrados
        </div>

        <div class="card-body">

            <table class="table table-bordered table-hover">

                <thead>

                    <tr>

                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Status</th>
                        <th width="160">Ações</th>

                    </tr>

                </thead>

                <tbody id="tabelaTipos"></tbody>

            </table>

        </div>

    </div>

</div>

<script>

const formTipo = document.getElementById('formTipo');

document.addEventListener('DOMContentLoaded', carregarTipos);

async function carregarTipos() {

    try {

        const dados = AtendeLabApi.toList(
            await AtendeLabApi.get('tipos', 'listar')
        );

        const tbody = document.getElementById('tabelaTipos');

        if (!dados.length) {

            tbody.innerHTML =
                '<tr><td colspan="4" class="text-center">Nenhum tipo cadastrado.</td></tr>';

            return;

        }

        tbody.innerHTML = dados.map(t => `

            <tr>

                <td>${AtendeLabApi.escape(t.nome)}</td>

                <td>${AtendeLabApi.escape(t.descricao ?? '')}</td>

                <td>

                    <span class="badge ${t.status === 'ativo'
                        ? 'text-bg-success'
                        : 'text-bg-secondary'}">

                        ${AtendeLabApi.escape(t.status)}

                    </span>

                </td>

                <td>

                    <button
                        class="btn btn-sm btn-outline-primary"
                        onclick="editarTipo(${Number(t.id)})">

                        Editar

                    </button>

                    <button
                        class="btn btn-sm btn-outline-danger"
                        onclick="inativarTipo(${Number(t.id)})">

                        Inativar

                    </button>

                </td>

            </tr>

        `).join('');

    }

    catch (erro) {

        AtendeLabApi.showAlert(
            'alerta',
            erro.message,
            'danger'
        );

    }

}

formTipo.addEventListener('submit', async event => {

    event.preventDefault();

    const id = document.getElementById('tipoId').value;

    try {

        await AtendeLabApi.post(

            'tipos',

            id
                ? 'atualizar'
                : 'criar',

            new FormData(formTipo)

        );

        AtendeLabApi.showAlert(

            'alerta',

            id
                ? 'Tipo atualizado com sucesso.'
                : 'Tipo cadastrado com sucesso.'

        );

        formTipo.reset();

        document.getElementById('tipoId').value = '';

        carregarTipos();

    }

    catch (erro) {

        AtendeLabApi.showAlert(
            'alerta',
            erro.message,
            'danger'
        );

    }

});

async function editarTipo(id) {

    try {

        const tipo = AtendeLabApi.toObject(
            await AtendeLabApi.get(
                'tipos',
                'buscar',
                { id }
            )
        );

        document.getElementById('tipoId').value = tipo.id;

        formTipo.nome.value = tipo.nome;
        formTipo.descricao.value = tipo.descricao;
        formTipo.status.value = tipo.status;

    }

    catch (erro) {

        AtendeLabApi.showAlert(
            'alerta',
            erro.message,
            'danger'
        );

    }

}

async function inativarTipo(id) {

    if (!confirm('Deseja realmente inativar este tipo?')) {

        return;

    }

    try {

        await AtendeLabApi.post(
            'tipos',
            'inativar',
            { id }
        );

        carregarTipos();

    }

    catch (erro) {

        AtendeLabApi.showAlert(
            'alerta',
            erro.message,
            'danger'
        );

    }

}

</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>