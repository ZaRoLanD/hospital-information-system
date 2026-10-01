<?php session_start(); ?>
<?php include "../../includes/dao.php" ?>
<?php include "../../includes/tests/TestsController.php";
$tests = new Tests();
?>
<div class="container">
    <?= $jumbo->getJumbo("Examens du patient", "Consulter les examens et leurs résultats") ?>

    <?php $patientsList = $tests->viewTest($_GET['id']); ?>
    <a title="Orienter le patient" onclick="modal(<?= $_GET['id'] ?>)" class="btn btn-sm btn-default fa fa-ambulance"></a>

    <table class="table table-striped table-hover">
        <thead>
            <th>ID</th>
            <th>Prénom</th>
            <th>Nom</th>
            <th>Examen</th>
            <th>Résultat</th>
            <th>Actions</th>
        </thead>


        <tbody>
            <?php while ($row = $patientsList->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <tr>
                    <td><?= $row['user_id'] ?></td>
                    <td><?= $row['firstname'] ?></td>
                    <td><?= $row['lastname'] ?></td>
                    <td><?= $row['test_name'] ?></td>
                    <td><?= $row['result'] ?></td>
                    <td><a title="Ajouter une prescription" href="../patients/addDiagnosis.php?t_id=<?= $row['test_id'] ?>&id=<?= $row['user_id'] ?>" class="btn btn-sm btn-default fa fa-plus"></a>
                        <a title="Voir les prescriptions" href="../patients/viewDiag.php?t_id=<?= $row['test_id'] ?>&id=<?= $row['user_id'] ?>" class="btn btn-sm btn-default fa fa-eye"></a>
                </tr>
            <?php } ?>
        </tbody>

    </table>

</div>



<?php include "../../includes/footer.php"; ?>


<script>
    function Delete(id) {
        event.preventDefault();
        Swal.fire({
            title: "Supprimer le patient n° " + id + " ?",
            text: "Cette action est irréversible !",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            animation: true,
            cancelButtonColor: "#d33",
            confirmButtonText: "Oui, supprimer !",
            cancelButtonText: "Annuler"
        }).then(result => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: '../../includes/patients/PatientsController.php',
                    data: {
                        type: "deletePatient",
                        id: id
                    },
                    beforeSend: function() {
                        Swal.fire({
                            title: "Suppression en cours",
                            type: "info",
                            text: "Suppression du patient " + id,
                            icon: "info",
                        })
                    },
                    success: function(resp) {
                        if (resp == "success") {
                            Swal.fire({
                                text: "Patient supprimé",
                                type: "success",
                                showConfirmButton: true,
                                timer: 10000,
                                animation: true,
                            }).then(result => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: "Erreur",
                                type: "error",
                                text: "Une erreur est survenue : " + resp,
                                icon: "error",
                                button: "Réessayer",
                                showConfirmButton: true,
                            }).then(result => {
                                window.location.reload();
                            });
                        }
                    }
                });
            }
        });

    }

    function refer(e) {
        event.preventDefault();
        var form = $('.refer').serialize();
        $.ajax({
            type: 'POST',
            url: '../../includes/patients/PatientsController.php',
            data: form,
            beforeSend: function() {
                Swal.fire({
                    title: "Orientation du patient",
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
                        title: "Patient orienté avec succès",
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


<div id="publish" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Orienter le patient</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="refer" action="" onsubmit="refer()">
                    <input type="hidden" name="type" value="refer">
                    <input class="id" type="hidden" name="id" value="">
                    <input type="text" name="reason" class="form-control" placeholder="Motif d'orientation" required>
                    <br>
                    <input type="text" name="name" class="form-control" placeholder="Hôpital d'accueil" required>
                    <input type="submit" class="btn btn-success" value="Enregistrer">
                </form>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>
<script>
    function modal(id) {
        event.preventDefault();
        $('.modal').modal('show');
        $('.id').attr("value", id);
    }
</script>
