$(document).ready(function () {
    loadUsers();
});

var userModal = new bootstrap.Modal(document.getElementById('userModal'), {
    keyboard: false
});

$('#userForm').submit(function () {
    let btnContent = $('#userForm button[type=submit]').html();
    $.ajax({
        url: "/system/users.php",
        type: ($(this).attr('data-target') == '') ? 'POST' : 'PUT',
        data: $(this).serialize() + (($(this).attr('data-target') != '') ? ('&id=' + $(this).attr('data-target')) : ''),
        beforeSend: function () {
            $('#userForm button[type=submit]').html('<i class="fas fa-spinner fa-pulse"></i> Aguarde...');
        },
        success: function (data) {
            loadUsers();
            userModal.hide();
        },
        error: function (xhr) {
            let msg = JSON.parse(xhr.responseText);
            $('#msg').html(msg.message);
        },
        complete: function () {
            $('#userForm button[type=submit]').html(btnContent);
        }
    });
    return false;
});

$('#logout').click(function () {
    window.location.href = "system/auth.php";
});

$(document).on('click', '.del', function () {
    let btn = $(this);
    $.ajax({
        url: "/system/users.php",
        type: 'DELETE',
        data: { id: $(this).attr('data-target') },
        beforeSend: function () {
            btn.html('<i class="fas fa-spinner fa-pulse"></i>');
        },
        success: function (data) {
            loadUsers();
        },
        error: function (xhr) {
            Swal.fire({ 'html': 'Ocorreu um erro inesperado. Tente novamente.' });
            btn.html('<i class="far fa-trash-alt"></i>');
        }
    });
});

$(document).on('click', '.edit', function () {
    let line = $($(this).parent().parent().parent().children());
    $('#userForm').attr('data-target', line.eq(0).html());
    $('#name').val(line.eq(1).html());
    $('#username').val(line.eq(2).html());
    $('#password').removeAttr('required');
    $('#userForm button[type=submit]').html('Alterar');
    userModal.show();
});

$("#userModal").on("hidden.bs.modal", function () {
    $('#userForm').attr('data-target', '');
    $('#userForm input').val('');
    $('#msg').html('');
    $('#userForm button[type=submit]').html('Cadastrar');
});

function loadUsers() {
    $.ajax({
        url: "/system/users.php",
        type: 'GET',
        dataType: 'json',
        beforeSend: function () {
            $('#users tbody').html('<tr><td colspan="4" class="text-center"><i class="fas fa-spinner fa-pulse"></i> Aguarde...</td></tr>');
        },
        success: function (data) {
            let lines = "";
            $.each(data.data, function (i, item) {
                lines += `<tr>
                            <td>${item.id}</td>
                            <td>${item.name}</td>
                            <td>${item.username}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-primary edit"><i class="far fa-edit"></i></button>
                                <button type="button" data-target="${item.id}" class="btn btn-sm btn-danger del"><i class="far fa-trash-alt"></i></button>
                            </td>
                          </tr>`;
            });
            $('#users tbody').html(lines);
        },
        error: function (xhr) {
            if (xhr.status == 401) {
                window.location.href = 'index.html';
            } else {
                $('#users tbody').html('<tr><td colspan="4" class="text-center text-danger">Ocorreu um erro inesperado. Tente novamente.</td></tr>');
            }
        }
    });
}