$(document).ready(function () {
    firstAccess();
})

$('#loginForm').submit(function () {
    $.ajax({
        url: "/system/auth.php",
        type: 'POST',
        data: $(this).serialize(),
        beforeSend: function () {
            $('#loginForm button[type=submit]').html('<i class="fas fa-spinner fa-pulse"></i> Aguarde...')
            $('#msg').html('');
        },
        success: function (data) {
            window.location.href = '/users.php';
        },
        error: function (xhr) {
            let msg = JSON.parse(xhr.responseText);
            $('#msg').html(msg.message);
        },
        complete: function () {
            $('#loginForm button[type=submit]').html('Entrar')
        }
    });
    return false;
});

function firstAccess() {
    $.ajax({
        url: "/system/users.php",
        type: 'GET',
        success: function (data) {
            Swal.fire({
                title: 'Identifiquei que esse é o primeiro acesso do sistema',
                html: 'você será redirecionado para cadastrar o primeiro usuário.',
                showConfirmButton: false,
                timerProgressBar: true,
                timer: 10000,
                didOpen: () => {
                    Swal.showLoading();
                }
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.timer) {
                    window.location.href = "/users.php"
                }
            });
        },
        error: function (xhr) {
            if (xhr.status != 401) {
                let msg = JSON.parse(xhr.responseText);
                Swal.fire({ 'html': msg.message });
            }
        }
    });
}