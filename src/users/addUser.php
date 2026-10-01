<?php session_start(); ?>
<?php include "../../includes/dao.php" ?>
<?php (new Session())->requireRole('admin'); ?>
<?php include "../../includes/users/UsersController.php";
?>
<div class="container">

    <?= $jumbo->getJumbo("Ajouter un utilisateur", "Créer un nouveau compte d'accès") ?>
    <form method='POST' class='addUser' action='#' onsubmit="adduser()">
        <input type="hidden" name="type" value="addUser" id="">
        <div class='form-group'>
            <label for='in_Email'>Adresse e-mail</label>
            <input name='in_Email' id='in_Email' class='form-control' type='text' placeholder='Adresse e-mail' required>
        </div>
        <div class='form-group'>
            <label for='in_Password'>Mot de passe</label>
            <input name='in_Password' id='in_Password' class='form-control' type='password' placeholder='Mot de passe'>
        </div>
        <div class='form-group'>
            <label for='in_Role'>Rôle</label>
            <select class='form-control browser-default' id='in_Role' name='in_Role' required>
                <option value="user">Utilisateur</option>
                <option value="admin">Administrateur</option>
            </select>
        </div>
        <div class='form-group'>
            <input type="submit" value="Ajouter l'utilisateur" class="btn btn-success">
        </div>
    </form>

    <?php include "../../includes/footer.php"; ?>

    <script>
        function adduser(e) {
            event.preventDefault();
            var form = $('.addUser').serialize();
            $.ajax({
                type: 'POST',
                url: '../../includes/users/UsersController.php',
                data: form,
                beforeSend: function() {
                    Swal.fire({
                        title: "Ajout de l'utilisateur",
                        type: "info",
                        onBeforeOpen: () => {
                            Swal.showLoading()
                            timerInterval = setInterval(() => {}, 100)
                        }
                    });
                },
                success: function(resp) {
                    if (resp == "success") {
                        Swal.fire({
                            title: "Utilisateur ajouté avec succès",
                            type: "success",
                        }).then(result => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: "Erreur",
                            text: resp,
                            type: "error",
                        }).then(result => {
                            window.location.reload();
                        });
                    }
                },

            })
        }
    </script>
