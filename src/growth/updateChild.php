<?php session_start(); ?>
<?php include "../../includes/dao.php" ?>
<?php include "../../includes/patients/PatientsController.php";
$patients = new PatientsController();
$GetPatient = $patients->viewChildHeight($_GET['id']);
$patient = $GetPatient->fetch(PDO::FETCH_ASSOC);
?>
<div class="container">
    <?= $jumbo->getJumbo("Mensurations de " . $patient['firstname'] . " " . $patient['lastname'], "") ?>

    <form method='POST' class='editChild' action='#' onsubmit="editPatient()">
        <input type="hidden" name="type" value="editChild" id="">
        <input type="hidden" name="id" value="<?= $_GET['id'] ?>" id="">
        <div class='form-group'>
            <label for='in_Dob'>Sexe</label>
            <select class="browser-default" name="in_Gender">
                <option value="<?= $patient['gender'] ?>"><?= $patient['gender'] ?>
                </option>
                <option value="male">Garçon
                </option>
                <option value="female">Fille</option>
            </select>
        </div>
        <div class='form-group'>
            <label for='in_Dob'>Date de naissance</label>
            <input name='in_Dob' value="<?= $patient['dob'] ?>" id='in_Dob' class='form-control' type='date' placeholder='Date de naissance' required>
        </div>
        <div class='form-group'>
            <label for='in_Height'>Taille (CM)</label>
            <input name='in_Height' value="<?= $patient['height'] ?>" id='in_Height' class='form-control' type='number' placeholder='Taille' required>
        </div>
        <div class='form-group'>
            <label for='in_Weight'>Poids (KG)</label>
            <input name='in_Weight' value="<?= $patient['weight'] ?>" id='in_Weight' class='form-control' type='number' placeholder='Poids' required>
        </div>
        <div class='form-group'>
            <label for='in_HeadC'>Périmètre crânien (CM)</label>
            <input name='in_HeadC' value="<?= $patient['headc'] ?>" id='in_HeadC' class='form-control' type='number' placeholder='Périmètre crânien' required>
        </div>
        <div class='form-group'>
            <label for='in_Bmi'>IMC</label>
            <input name='in_Bmi' value="<?= $patient['bmi'] ?>" id='in_Bmi' class='form-control' type='number' placeholder='BMI' required>
        </div>
        <div class='form-group'>
            <input type="submit" value="Enregistrer les mensurations" class="btn btn-success">
    </form>
    <button type="button" class="btn btn-primary" data-toggle="popover" data-html='true' data-placement="top"    title="Type de graphique" data-content="
    <a class='btn btn-sm btn-default' href='wfa.php?id=<?= $_GET['id'] ?>'>Percentiles (poids/âge)</a><br>
    <a class='btn btn-sm btn-default' href='hfa.php?id=<?= $_GET['id'] ?>'>Taille pour l'âge</a><br>
    <a class='btn btn-sm btn-default' href='wfh.php?id=<?= $_GET['id'] ?>'>Poids pour la taille</a><br>
    <a class='btn btn-sm btn-default' href='bfa.php?id=<?= $_GET['id'] ?>'>IMC pour l'âge</a><br>
    <a class='btn btn-sm btn-default' href='hcfa.php?id=<?= $_GET['id'] ?>'>Périmètre crânien pour l'âge</a><br>
    ">Graphiques</button>
</div>

</div>


<?php include "../../includes/footer.php"; ?>


<script>
    function editPatient(e) {
        event.preventDefault();
        var form = $('.editChild').serialize();
        $.ajax({
            type: 'POST',
            url: '../../includes/patients/PatientsController.php',
            data: form,
            beforeSend: function() {
                Swal.fire({
                    title: "Traitement de la demande",
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
                        title: "Mensurations mises à jour avec succès",
                        type: "success",
                    }).then(result => {
                        window.history.back();
                    });
                } else {
                    Swal.fire({
                        title: "Erreur : " + resp,
                        type: "error",
                    }).then(result => {
                        window.history.back();
                    });
                }
            },

        })
    }

    $(function() {
        $('[data-toggle="popover"]').popover()
    })
</script>