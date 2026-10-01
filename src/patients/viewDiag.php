<?php session_start(); ?>
<?php include "../../includes/dao.php" ?>
<?php include "../../includes/patients/PatientsController.php";
$patients = new PatientsController();
$GetPatient = $patients->viewDiag($_GET['id']);
$patient = $GetPatient->fetch(PDO::FETCH_ASSOC);
?>
<div class="container">
    <?= $jumbo->getJumbo("Diagnostic de " . $patient['firstname'] . " " . $patient['lastname'], "<a href='addDiagnosis.php?id=" . $patient['user_id'] . "'>Ajouter un diagnostic</a>") ?>

    <table class="table table-striped table-hover">
        <thead>
            <th>Nom</th>
            <th>Date</th>
            <th>Prescription</th>
            <th>Observations</th>
        </thead>
        <tbody>
            <td><?= $patient['firstname'] ?> <?= $patient['lastname'] ?></td>
            <td><?= $patient['created'] ?></td>
            <td>
                <?= $patient['presp1'] ?>
            </td>
            <td><?= $patient['remarks'] ?></td>
        </tbody>
    </table>
</div>

<?php include "../../includes/footer.php"; ?>
