<?php session_start(); ?>
<?php include "../../includes/dao.php" ?>
<?php include "../../includes/patients/PatientsController.php";
$patients = new PatientsController();
$GetPatient = $patients->getPatient($_GET['id']);
$patient = $GetPatient->fetch(PDO::FETCH_ASSOC);
?>
<div class="container">
    <?= $jumbo->getJumbo("Diagnostic de " . $patient['firstname'] . " " . $patient['lastname'], "<a href='viewDiag.php?id=" . $patient['user_id'] . "'>Voir les diagnostics précédents</a>") ?>

    <form role='form' class="addDiag" onsubmit="addDiag()">
        <input name='type' type="hidden" value="addDiag">
        <input name='id' type="hidden" value=<?= $patient['user_id'] ?>>
        <?php if (isset($_GET['t_id'])) : ?>
            <input name='test_id' type="hidden" value=<?= $_GET['t_id'] ?>>
        <?php endif ?>
        <div class='form-group'>
            <label for='in_Temp' class="fas fa-temperature-high"> Température</label>
            <input name='in_Temp' id='in_Temp' class='form-control' type='text' placeholder='Température' required>
        </div>
        <div class='form-group'>
            <label for='in_Bp' class="fas fa-digital-tachograph"> Tension artérielle</label>
            <input name='in_Bp' id='in_Bp' class='form-control' type='text' placeholder='Tension artérielle' required>
        </div>

        <div class='form-group'>
            <label for='in_Presp1' class="fas fa-prescription-bottle-alt"> Prescription</label>
            <input name='in_Presp1' id='in_Presp1' class='form-control' type='text' placeholder='Prescription'>
        </div>
        <div class='form-group'>
            <label for='in_Remarks' class="fas fa-pencil-alt"> Observations</label>
            <input name='in_Remarks' id='in_Remarks' class='form-control' type='text' placeholder='Observations' required>
        </div>
        <div class='form-group'>
            <input type="submit" value="Enregistrer le diagnostic" class="btn btn-success">
        </div>
    </form>
</div>


<?php include "../../includes/footer.php"; ?>

<script>
    function addDiag(e) {
        event.preventDefault();
        var form = $('.addDiag').serialize();
        $.ajax({
            type: 'POST',
            url: '../../includes/patients/PatientsController.php',
            data: form,
            beforeSend: function() {
                Swal.fire({
                    title: "Enregistrement du diagnostic",
                    type: "info",
                    timer: 10000,
                    onBeforeOpen: () => {
                        Swal.showLoading()
                        timerInterval = setInterval(() => {}, 100);
                    }
                });
            },
            success: function(resp) {                    if (resp == "success") {
                        Swal.fire({
                            title: "Diagnostic enregistré avec succès",
                            type: "success",
                    }).then(result => {
                        window.location.reload();
                    });                    } else {
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