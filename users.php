<?php
session_start();
if (empty($_SESSION['logged']) && empty($_SESSION['first_access'])) {
    header("Location: index.html");
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - Fictícia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" integrity="sha512-q3eWabyZPc1XTCmF+8/LuE1ozpg5xxn7iO89yfSOd5/oKvyqLngoNGsx8jq92Y8eXJ/IRxQbEC+FGSYxtk2oiw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <header class="container" style="margin-top: 10rem">
        <div class="row">
            <div class="col text-white">
                <p>Olá, <strong><?php echo !empty($_SESSION['logged']) ? ($_SESSION['logged']['name'] . ' ') : ''; ?></strong>seja bem vindo(a).</p>
            </div>
        </div>
    </header>

    <main class="container content-wrapper pt-3 mt-0">
        <div class="row">
            <div class="col d-flex justify-content-between">
                <h4 class="mb-0">Usuários</h4>
                <div class="header-buttons">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#userModal" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Novo
                        Usuário</button>
                    <button type="button" id="logout" class="btn btn-sm btn-danger"><i class="fas fa-sign-out-alt"></i>
                        Sair</button>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col">
                <table id="users" class="table table-striped">
                    <thead>
                        <th>#</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th></th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </main>

    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="userModalLabel">Cadastro</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="userForm" data-target="">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome:</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">E-mail:</label>
                            <input type="email" name="username" id="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Senha:</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <p id="msg" class="text-danger"></p>
                        <button type="submit" class="btn btn-success w-100">Cadastrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/user.js"></script>

</body>

</html>