<?php

$tituloPagina = 'Tipos de Atendimento';

require_once __DIR__ . '/../layouts/header.php';

?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

    <div>

        <h2 class="mb-1">
            Tipos de atendimento
        </h2>

        <p class="text-secondary mb-0">
            Categorias utilizadas nos registros de atendimento.
        </p>

    </div>

    <button
        class="btn btn-success"
        onclick="novoTipo()">

        Novo tipo

    </button>

</div>

<div id="alerta"></div>

<div
    class="card border-0 shadow-sm mb-4 d-none"
    id="cardFormulario">

    <div class="card-body">

        <h4 class="mb-4">

            Cadastro de Tipo

        </h4>

        <form id="formTipo">

            <input
                type="hidden"
                id="tipoId"
                name="id">

            <div class="row g-3">

                <div class="col-md-6">

                    <label class="form-label">

                        Nome

                    </label>

                    <input
                        class="form-control"
                        name="nome"
                        required>

                </div>

                <div class="col-md-6">

                    <label class="form-label">

                        Status

                    </label>

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

                <div class="col-12">

                    <label class="form-label">

                        Descrição

                    </label>

                    <textarea
                        class="form-control"
                        rows="4"
                        name="descricao"></textarea>

                </div>

            </div>

            <div class="mt-4">

                <button
                    class="btn btn-success"
                    type="submit">

                    Salvar

                </button>

                <button
                    class="btn btn-outline-secondary"
                    type="button"
                    onclick="fecharFormulario()">

                    Cancelar

                </button>

            </div>

        </form>

    </div>

</div>

<div class="card border-0 shadow-sm">

    <div class="table-responsive">

        <table class="table table-hover align-middle mb-0">

            <thead class="table-light">

                <tr>

                    <th>Nome</th>

                    <th>Descrição</th>

                    <th>Status</th>

                    <th class="text-end">

                        Ações

                    </th>

                </tr>

            </thead>

            <tbody id="tabelaTipos">

                <tr>

                    <td
                        colspan="4"
                        class="text-center py-4">

                        Carregando...

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>

<script>

const formTipo =
    document.getElementById('formTipo');

const cardFormulario =
    document.getElementById('cardFormulario');

function novoTipo() {

    formTipo.reset();

    document.getElementById('tipoId').value='';

    cardFormulario.classList.remove('d-none');

    window.scrollTo({

        top:0,

        behavior:'smooth'

    });

}

function fecharFormulario() {

    formTipo.reset();

    document.getElementById('tipoId').value='';

    cardFormulario.classList.add('d-none');

}
document.addEventListener('DOMContentLoaded', carregarTipos);

async function carregarTipos() {

    try {

        const dados = AtendeLabApi.toList(
            await AtendeLabApi.get('tipos', 'listar')
        );

        const tbody = document.getElementById('tabelaTipos');

        if (!dados.length) {

            tbody.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center py-4">
                        Nenhum tipo cadastrado.
                    </td>
                </tr>
            `;

            return;

        }

        tbody.innerHTML = dados.map(tipo => `

            <tr>

                <td>

                    ${AtendeLabApi.escape(tipo.nome)}

                </td>

                <td>

                    ${AtendeLabApi.escape(tipo.descricao ?? '')}

                </td>

                <td>

                    <span class="badge ${tipo.status === 'ativo'
                        ? 'text-bg-success'
                        : 'text-bg-secondary'}">

                        ${AtendeLabApi.escape(tipo.status)}

                    </span>

                </td>

                <td class="text-end">

                    <button
                        class="btn btn-sm btn-outline-primary me-1"
                        onclick="editarTipo(${Number(tipo.id)})">

                        Editar

                    </button>

                    <button
                        class="btn btn-sm btn-outline-danger"
                        onclick="inativarTipo(${Number(tipo.id)})">

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

        fecharFormulario();

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

        formTipo.nome.value = tipo.nome ?? '';
        formTipo.descricao.value = tipo.descricao ?? '';
        formTipo.status.value = tipo.status ?? 'ativo';

        cardFormulario.classList.remove('d-none');

        window.scrollTo({

            top: 0,

            behavior: 'smooth'

        });

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

        AtendeLabApi.showAlert(

            'alerta',

            'Tipo inativado com sucesso.'

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