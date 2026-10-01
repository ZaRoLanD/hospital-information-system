<?php session_start(); ?>
<?php include "../../includes/dao.php" ?>
<?php include "../../includes/patients/PatientsController.php";
?>
<div class="container">
    <?= $jumbo->getJumbo("Ajouter un patient", "Enregistrer un nouveau dossier patient") ?>
    <form method='POST' class='addP' action='#' onsubmit="addPatient()">
        <input type="hidden" name="type" value="addPatient" id="">
        <div class='form-group'>
            <label for='in_Firstname'>Prénom</label>
            <input name='in_Firstname' id='in_Firstname' class='form-control' type='text' placeholder='Prénom' required>
        </div>
        <div class='form-group'>
            <label for='in_Lastname'>Nom</label>
            <input name='in_Lastname' id='in_Lastname' class='form-control' type='text' placeholder='Nom' required>
        </div>
        <div class='form-group'>
            <label for='in_Dob'>Date de naissance</label>
            <input name='in_Dob' id='in_Dob' class='form-control' type='date' placeholder='Date de naissance' required>
        </div>
        <div class='form-group'>
            <label for='in_Address'>Adresse</label>
            <textarea name='in_Address' id='in_Address' class=' form-control' rows='5' placeholder='Adresse' required></textarea>
        </div>
        <label for='in_Married'>Statut matrimonial</label>
        <div class='form-group'>
            <select name="in_Married" id="in_Married" class="form-control browser-default">
                <option value="married">Marié(e)</option>
                <option value="single">Célibataire</option>
                <option value="divorced">Divorcé(e)</option>
            </select>
        </div>
        <div class='form-group'>
            <label for='in_Contact'>Contact</label>
            <input name='in_Contact' id='in_Contact' class='form-control' type='text' placeholder='Contact' required>
        </div>
        <div class='form-group'>
            <input type="submit" value="Ajouter le patient" class="btn btn-success">
        </div>
    </form>

    <?php include "../../includes/footer.php"; ?>

    <script>
        function addPatient(e) {
            event.preventDefault();
            var form = $('.addP').serialize();
            $.ajax({
                type: 'POST',
                url: '../../includes/patients/PatientsController.php',
                data: form,
                beforeSend: function() {
                    Swal.fire({
                        title: "Ajout du patient",
                        type: "info",
                        timer: 10000,
                        onBeforeOpen: () => {
                            Swal.showLoading()
                            timerInterval = setInterval(() => {}, 100)
                        }
                    });
                },
                success: function(resp) {
                    if (resp == "success") {
                        Swal.fire({
                            title: "Patient ajouté avec succès",
                            type: "success",
                        }).then(result => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: "Erreur : " + resp,
                            type: "error",
                        }).then(result => {
                            window.location.reload();
                        });
                    }
                },

            })
        }
    </script>
