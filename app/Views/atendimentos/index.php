<?php

$tituloPagina = 'Atendimentos';

require_once __DIR__ . '/../layouts/header.php';

?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

    <div>

        <h2 class="mb-1">
            Atendimentos
        </h2>

        <p class="text-secondary mb-0">
            Registro e acompanhamento dos atendimentos realizados.
        </p>

    </div>

    <button
        class="btn btn-success"
        onclick="novoAtendimento()">

        Novo atendimento

    </button>

</div>

<div id="alerta"></div>

<div
    class="card border-0 shadow-sm mb-4 d-none"
    id="cardFormulario">

    <div class="card-body">

        <h4 class="mb-4">

            Cadastro de Atendimento

        </h4>

        <form id="formAtendimento">

            <input
                type="hidden"
                id="atendimentoId"
                name="id">

            <div class="row g-3">

                <div class="col-md-6">

                    <label class="form-label">

                        Pessoa

                    </label>

                    <select
                        class="form-select"
                        id="pessoa_id"
                        name="pessoa_id"
                        required>

                        <option value="">

                            Carregando...

                        </option>

                    </select>

                </div>

                <div class="col-md-6">

                    <label class="form-label">

                        Tipo de atendimento

                    </label>

                    <select
                        class="form-select"
                        name="tipo_atendimento_id"
                        id="tipo_atendimento_id"
                        required>

                        <option value="">

                            Carregando...

                        </option>

                    </select>

                </div>

                <div class="col-md-12">

                    <label class="form-label">

                        Descrição

                    </label>

                    <textarea
                        class="form-control"
                        rows="4"
                        name="descricao"
                        required></textarea>

                </div>

                <div class="col-md-12">

                    <label class="form-label">

                        Observação

                    </label>

                    <textarea
                        class="form-control"
                        rows="3"
                        name="observacao"></textarea>

                </div>

                <div class="col-md-4">

                    <label class="form-label">

                        Data

                    </label>

                    <input
                        type="date"
                        class="form-control"
                        name="data_atendimento"
                        required>

                </div>

                <div class="col-md-4">

                    <label class="form-label">

                        Hora

                    </label>

                    <input
                        type="time"
                        class="form-control"
                        name="hora_atendimento"
                        required>

                </div>

                <div class="col-md-4">

                    <label class="form-label">

                        Status

                    </label>

                    <select
                        class="form-select"
                        name="status">

                        <option value="aberto">

                            Aberto

                        </option>

                        <option value="em_andamento">

                            Em andamento

                        </option>

                        <option value="concluido">

                            Concluído

                        </option>

                        <option value="cancelado">

                            Cancelado

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

                    <th>Pessoa</th>

                    <th>Tipo</th>

                    <th>Data</th>

                    <th>Hora</th>

                    <th>Status</th>

                    <th class="text-end">

                        Ações

                    </th>

                </tr>

            </thead>

            <tbody id="tabelaAtendimentos">

                <tr>

                    <td
                        colspan="6"
                        class="text-center py-4">

                        Carregando...

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>

<script>

const formAtendimento =
    document.getElementById('formAtendimento');

const cardFormulario =
    document.getElementById('cardFormulario');

function novoAtendimento() {

    formAtendimento.reset();

    document.getElementById('atendimentoId').value='';

    cardFormulario.classList.remove('d-none');

    carregarOpcoesFormulario();

    window.scrollTo({

        top:0,

        behavior:'smooth'

    });

}

function fecharFormulario() {

    formAtendimento.reset();

    document.getElementById('atendimentoId').value='';

    cardFormulario.classList.add('d-none');

}
document.addEventListener('DOMContentLoaded', async () => {

    await carregarOpcoesFormulario();

    await carregarAtendimentos();

});

async function carregarOpcoesFormulario() {

    try {

        const resposta = await AtendeLabApi.get(

            'atendimentos',

            'opcoesFormulario'

        );

        document.getElementById('pessoa_id').innerHTML =

            '<option value="">Selecione...</option>' +

            resposta.pessoas.map(pessoa => `

                <option value="${pessoa.id}">

                    ${AtendeLabApi.escape(pessoa.nome)}

                </option>

            `).join('');

        document.getElementById('tipo_atendimento_id').innerHTML =

            '<option value="">Selecione...</option>' +

            resposta.tipos.map(tipo => `

                <option value="${tipo.id}">

                    ${AtendeLabApi.escape(tipo.nome)}

                </option>

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


async function carregarAtendimentos() {

    try {

        const dados = AtendeLabApi.toList(

            await AtendeLabApi.get(
                'atendimentos',
                'listar'
            )

        );

        const tbody =
            document.getElementById('tabelaAtendimentos');

        if (!dados.length) {

            tbody.innerHTML = `

                <tr>

                    <td colspan="6"
                        class="text-center py-4">

                        Nenhum atendimento encontrado.

                    </td>

                </tr>

            `;

            return;

        }

        tbody.innerHTML = dados.map(atendimento => `

            <tr>

                <td>

                    ${AtendeLabApi.escape(
                        atendimento.pessoa_nome ??
                        atendimento.pessoa ??
                        ''
                    )}

                </td>

                <td>

                    ${AtendeLabApi.escape(
                        atendimento.tipo_nome ??
                        atendimento.tipo ??
                        ''
                    )}

                </td>

                <td>

                    ${AtendeLabApi.escape(
                        atendimento.data
                    )}

                </td>

                <td>

                    ${AtendeLabApi.escape(
                        atendimento.hora
                    )}

                </td>

                <td>

                    <span class="badge text-bg-primary">

                        ${AtendeLabApi.escape(
                            atendimento.status
                        )}

                    </span>

                </td>

                <td class="text-end">

                    <button
                        class="btn btn-sm btn-outline-primary me-1"
                        onclick="editarAtendimento(${Number(atendimento.id)})">

                        Editar

                    </button>

                    <button
                        class="btn btn-sm btn-outline-success"
                        onclick="alterarStatus(${Number(atendimento.id)})">

                        Status

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

formAtendimento.addEventListener('submit', async event => {

    event.preventDefault();

    const id =
        document.getElementById('atendimentoId').value;

    try {

        await AtendeLabApi.post(

            'atendimentos',

            id
                ? 'atualizar'
                : 'criar',

            new FormData(formAtendimento)

        );

        AtendeLabApi.showAlert(

            'alerta',

            id
                ? 'Atendimento atualizado com sucesso.'
                : 'Atendimento cadastrado com sucesso.'

        );

        fecharFormulario();

        carregarAtendimentos();

    }

    catch (erro) {

        AtendeLabApi.showAlert(
            'alerta',
            erro.message,
            'danger'
        );

    }

});

async function editarAtendimento(id) {

    try {

        const atendimento =
            AtendeLabApi.toObject(

                await AtendeLabApi.get(
                    'atendimentos',
                    'buscar',
                    { id }
                )

            );

        document.getElementById('atendimentoId').value =
            atendimento.id;

        formAtendimento.pessoa_id.value =
            atendimento.pessoa_id;

        formAtendimento.tipo_atendimento_id.value =
            atendimento.tipo_atendimento_id;

        formAtendimento.descricao.value =
            atendimento.descricao ?? '';

        formAtendimento.data_atendimento.value =
            atendimento.data;

        formAtendimento.hora_atendimento.value =
            atendimento.hora;

        formAtendimento.status.value =
            atendimento.status;

        formAtendimento.observacao.value =
            atendimento.observacao ?? '';  

        cardFormulario.classList.remove('d-none');

        window.scrollTo({

            top:0,

            behavior:'smooth'

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

async function finalizar(id) {

    if (!confirm('Finalizar este atendimento?')) {
        return;
    }

    await AtendeLabApi.post(
        'atendimentos',
        'finalizar',
        { id }
    );

    carregarAtendimentos();

}

async function cancelar(id) {

    if (!confirm('Cancelar este atendimento?')) {
        return;
    }

    await AtendeLabApi.post(
        'atendimentos',
        'cancelar',
        { id }
    );

    carregarAtendimentos();

}

</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>