<!doctype html>
<html lang="pt-br">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1">

    <title>Login — AtendeLab</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        href="/atendelab/public/assets/css/style.css"
        rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container">

        <div class="row justify-content-center align-items-center vh-100">

            <div class="col-auto">

                <div class="login-card card shadow-sm">

                    <div class="card-body p-4">

                        <h1 class="h4 text-center mb-2">
                            AtendeLab
                        </h1>

                        <p class="text-secondary text-center mb-4">
                            Controle de atendimentos acadêmicos
                        </p>

                        <?php if (!empty($erro)) : ?>

                            <div class="alert alert-danger">

                                <?= htmlspecialchars(
                                    $erro,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </div>

                        <?php endif; ?>

                        <?php if (!empty($mensagem)) : ?>

                            <div class="alert alert-success">

                                <?= htmlspecialchars(
                                    $mensagem,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </div>

                        <?php endif; ?>

                        <form
                            method="POST"
                            action="?controller=auth&action=entrar">

                            <div class="mb-3">

                                <label
                                    for="email"
                                    class="form-label">

                                    E-mail

                                </label>

                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    class="form-control"
                                    required>

                            </div>

                            <div class="mb-4">

                                <label
                                    for="senha"
                                    class="form-label">

                                    Senha

                                </label>

                                <input
                                    type="password"
                                    id="senha"
                                    name="senha"
                                    class="form-control"
                                    required>

                            </div>

                            <button
                                type="submit"
                                class="btn btn-success w-100">

                                Entrar

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>