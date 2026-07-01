<?php

$tituloPagina = 'Pessoas';

require_once __DIR__ . '/../layouts/header.php';

?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h1 class="h3 mb-1">
            Pessoas atendidas
        </h1>

        <p class="text-secondary mb-0">
            Cadastro, edição e inativação sem excluir o histórico.
        </p>

    </div>

    <button
        class="btn btn-success"
        type="button"
        onclick="abrirFormulario()">

        Nova pessoa

    </button>

</div>

<div id="alerta"></div>

<div
    id="cardFormulario"
    class="card shadow-sm border-0 mb-4 d-none">

    <div class="card-header">

        <strong>

            Cadastro de Pessoa

        </strong>

    </div>

    <div class="card-body">

        <form id="formPessoa">

            <input
                type="hidden"
                id="id"
                name="id">

            <div class="row g-3">

                <div class="col-md-6">

                    <label class="form-label">

                        Nome

                    </label>

                    <input
                        type="text"
                        name="nome"
                        class="form-control"
                        required>

                </div>

                <div class="col-md-3">

                    <label class="form-label">

                        Documento

                    </label>

                    <input
                        type="text"
                        name="documento"
                        class="form-control"
                        required>

                </div>

                <div class="col-md-3">

                    <label class="form-label">

                        Telefone

                    </label>

                    <input
                        type="text"
                        name="telefone"
                        class="form-control">

                </div>

                <div class="col-md-6">

                    <label class="form-label">

                        Curso

                    </label>

                    <input
                        type="text"
                        name="curso"
                        class="form-control">

                </div>

                <div class="col-md-3">

                    <label class="form-label">

                        Período

                    </label>

                    <input
                        type="text"
                        name="periodo"
                        class="form-control">

                </div>

                <div class="col-md-3">

                    <label class="form-label">

                        Status

                    </label>

                    <select
                        name="status"
                        class="form-select">

                        <option value="ativo">

                            Ativo

                        </option>

                        <option value="inativo">

                            Inativo

                        </option>

                    </select>

                </div>

            </div>

            <div class="mt-4 d-flex gap-2">

                <button
                    type="submit"
                    class="btn btn-success">

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

<div class="card shadow-sm border-0">

    <div class="card-header">

        <strong>

            Pessoas cadastradas

        </strong>

    </div>

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

                    <th width="180">

                        Ações

                    </th>

                </tr>

            </thead>

            <tbody id="tabelaPessoas">

                <tr>

                    <td
                        colspan="7"
                        class="text-center py-5">

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

function abrirFormulario() {

    formPessoa.reset();

    document.getElementById('id').value = '';

    cardFormulario.classList.remove('d-none');

    window.scrollTo({

        top: 0,

        behavior: 'smooth'

    });

}

function fecharFormulario() {

    formPessoa.reset();

    document.getElementById('id').value = '';

    cardFormulario.classList.add('d-none');

}
document.addEventListener('DOMContentLoaded', () => {

    carregarPessoas();

});

async function carregarPessoas() {

    try {

        const resposta = await AtendeLabApi.get(
            'pessoas',
            'listar'
        );

        const pessoas =
            AtendeLabApi.toList(resposta);

        const tbody =
            document.getElementById('tabelaPessoas');

        if (!pessoas.length) {

            tbody.innerHTML = `

                <tr>

                    <td
                        colspan="7"
                        class="text-center py-5">

                        Nenhuma pessoa cadastrada.

                    </td>

                </tr>

            `;

            return;

        }

        tbody.innerHTML = pessoas.map(pessoa => `

            <tr>

                <td>

                    ${AtendeLabApi.escape(pessoa.nome)}

                </td>

                <td>

                    ${AtendeLabApi.escape(pessoa.documento)}

                </td>

                <td>

                    ${AtendeLabApi.escape(
                        pessoa.telefone ?? ''
                    )}

                </td>

                <td>

                    ${AtendeLabApi.escape(
                        pessoa.curso ?? ''
                    )}

                </td>

                <td>

                    ${AtendeLabApi.escape(
                        pessoa.periodo ?? ''
                    )}

                </td>

                <td>

                    <span class="badge ${pessoa.status === 'ativo'
                        ? 'text-bg-success'
                        : 'text-bg-secondary'}">

                        ${AtendeLabApi.escape(
                            pessoa.status
                        )}

                    </span>

                </td>

                <td>

                    <button
                        class="btn btn-outline-primary btn-sm"
                        onclick="editarPessoa(${pessoa.id})">

                        Editar

                    </button>

                    <button
                        class="btn btn-outline-danger btn-sm"
                        onclick="inativarPessoa(${pessoa.id})">

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

formPessoa.addEventListener(
    'submit',
    async event => {

        event.preventDefault();

        const id =
            document.getElementById('id').value;

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

    }
);
async function editarPessoa(id) {

    try {

        const resposta = await AtendeLabApi.get(
            'pessoas',
            'buscar',
            { id }
        );

        const pessoa =
            AtendeLabApi.toObject(resposta);

        abrirFormulario();

        document.getElementById('id').value =
            pessoa.id;

        formPessoa.nome.value =
            pessoa.nome ?? '';

        formPessoa.documento.value =
            pessoa.documento ?? '';

        formPessoa.telefone.value =
            pessoa.telefone ?? '';

        formPessoa.curso.value =
            pessoa.curso ?? '';

        formPessoa.periodo.value =
            pessoa.periodo ?? '';

        formPessoa.status.value =
            pessoa.status ?? 'ativo';

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

    if (!confirm(
        'Deseja realmente inativar esta pessoa?'
    )) {

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