<?php

$tituloPagina = 'Pessoas';

require_once __DIR__ . '/../layouts/header.php';

?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

    <div>

        <h2 class="mb-1">
            Pessoas atendidas
        </h2>

        <p class="text-secondary mb-0">
            Cadastro, edição e inativação sem excluir o histórico.
        </p>

    </div>

    <button
        class="btn btn-success"
        onclick="novaPessoa()">

        Nova pessoa

    </button>

</div>

<div id="alerta"></div>

<div
    class="card border-0 shadow-sm mb-4 d-none"
    id="cardFormulario">

    <div class="card-body">

        <h4 class="mb-4">

            Cadastro de Pessoa

        </h4>

        <form id="formPessoa">

            <input
                type="hidden"
                id="pessoaId"
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

                <div class="col-md-3">

                    <label class="form-label">

                        Documento

                    </label>

                    <input
                        class="form-control"
                        name="documento"
                        required>

                </div>

                <div class="col-md-3">

                    <label class="form-label">

                        Telefone

                    </label>

                    <input
                        class="form-control"
                        name="telefone">

                </div>

                <div class="col-md-6">

                    <label class="form-label">

                        Curso

                    </label>

                    <input
                        class="form-control"
                        name="curso">

                </div>

                <div class="col-md-3">

                    <label class="form-label">

                        Período

                    </label>

                    <input
                        class="form-control"
                        name="periodo">

                </div>

                <div class="col-md-3">

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

            </div>

            <div class="mt-4">

                <button
                    class="btn btn-success"
                    type="submit">

                    Salvar

                </button>

                <button
                    type="button"
                    class="btn btn-outline-secondary"
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

                    <th>Documento</th>

                    <th>Telefone</th>

                    <th>Curso</th>

                    <th>Período</th>

                    <th>Status</th>

                    <th class="text-end">

                        Ações

                    </th>

                </tr>

            </thead>

            <tbody id="tabelaPessoas">

                <tr>

                    <td
                        colspan="7"
                        class="text-center py-4">

                        Carregando...

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>

<script>

const formPessoa =
    document.getElementById('formPessoa');

const cardFormulario =
    document.getElementById('cardFormulario');

function novaPessoa() {

    formPessoa.reset();

    document.getElementById('pessoaId').value = '';

    cardFormulario.classList.remove('d-none');

    window.scrollTo({

        top:0,

        behavior:'smooth'

    });

}

function fecharFormulario() {

    formPessoa.reset();

    document.getElementById('pessoaId').value='';

    cardFormulario.classList.add('d-none');

}
document.addEventListener('DOMContentLoaded', carregarPessoas);

async function carregarPessoas() {

    try {

        const dados = AtendeLabApi.toList(
            await AtendeLabApi.get('pessoas', 'listar')
        );

        const tbody = document.getElementById('tabelaPessoas');

        if (!dados.length) {

            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-4">
                        Nenhuma pessoa cadastrada.
                    </td>
                </tr>
            `;

            return;

        }

        tbody.innerHTML = dados.map(p => `

            <tr>

                <td>${AtendeLabApi.escape(p.nome)}</td>

                <td>${AtendeLabApi.escape(p.documento)}</td>

                <td>${AtendeLabApi.escape(p.telefone ?? '')}</td>

                <td>${AtendeLabApi.escape(p.curso ?? '')}</td>

                <td>${AtendeLabApi.escape(p.periodo ?? '')}</td>

                <td>

                    <span class="badge ${p.status === 'ativo'
                        ? 'text-bg-success'
                        : 'text-bg-secondary'}">

                        ${AtendeLabApi.escape(p.status)}

                    </span>

                </td>

                <td class="text-end">

                    <button
                        class="btn btn-sm btn-outline-primary me-1"
                        onclick="editarPessoa(${Number(p.id)})">

                        Editar

                    </button>

                    <button
                        class="btn btn-sm btn-outline-danger"
                        onclick="inativarPessoa(${Number(p.id)})">

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

formPessoa.addEventListener('submit', async event => {

    event.preventDefault();

    const id = document.getElementById('pessoaId').value;

    try {

        await AtendeLabApi.post(

            'pessoas',

            id
                ? 'atualizar'
                : 'criar',

            new FormData(formPessoa)

        );

        AtendeLabApi.showAlert(

            'alerta',

            id
                ? 'Pessoa atualizada com sucesso.'
                : 'Pessoa cadastrada com sucesso.'

        );

        fecharFormulario();

        carregarPessoas();

    }

    catch (erro) {

        AtendeLabApi.showAlert(
            'alerta',
            erro.message,
            'danger'
        );

    }

});

async function editarPessoa(id) {

    try {

        const pessoa = AtendeLabApi.toObject(

            await AtendeLabApi.get(
                'pessoas',
                'buscar',
                { id }
            )

        );

        document.getElementById('pessoaId').value = pessoa.id;

        formPessoa.nome.value = pessoa.nome ?? '';
        formPessoa.documento.value = pessoa.documento ?? '';
        formPessoa.telefone.value = pessoa.telefone ?? '';
        formPessoa.curso.value = pessoa.curso ?? '';
        formPessoa.periodo.value = pessoa.periodo ?? '';
        formPessoa.status.value = pessoa.status ?? 'ativo';

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

async function inativarPessoa(id) {

    if (!confirm('Deseja realmente inativar esta pessoa?')) {

        return;

    }

    try {

        await AtendeLabApi.post(

            'pessoas',

            'inativar',

            { id }

        );

        AtendeLabApi.showAlert(

            'alerta',

            'Pessoa inativada com sucesso.'

        );

        carregarPessoas();

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