<?php session_start(); ?>
<?php include "../../includes/dao.php" ?>
<?php include "../../includes/patients/PatientsController.php";
$patients = new PatientsController();
?>
<div class="container">
    <?= $jumbo->getJumbo("Suivi des enfants", "Consulter et mettre à jour les mensurations") ?>

    <?php if (isset($_GET['years'])) : ?>
        <?php $patientsList = $patients->viewAllChildrenByDate($_GET['years']); ?>
    <?php else : ?>
        <?php $patientsList = $patients->viewAllChildren(); ?>
    <?php endif; ?>

    <form method="get" class="form-inline mb-2">
        <input class="form-control mr-2" placeholder="Filtrer par âge (années)" type="text" name="years" id="years">
        <button type="submit" class="btn btn-primary btn-sm">Filtrer</button>
    </form>
    <button onclick="refresh()" class="btn btn-default btn-sm text-left mb-3">Réinitialiser le filtre</button>

    <table class="table table-responsive table-striped table-hover">
        <thead>
            <th>ID</th>
            <th>Prénom</th>
            <th>Nom</th>
            <th>Date de naissance</th>
            <th>Adresse</th>
            <th>Statut matrimonial</th>
            <th>Date d'enregistrement</th>
            <th>Contact</th>
            <th>Actions</th>
        </thead>


        <tbody>
            <?php while ($row = $patientsList->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <tr>
                    <td><?= $row['user_id'] ?></td>
                    <td><?= $row['firstname'] ?></td>
                    <td><?= $row['lastname'] ?></td>
                    <td><?= $row['dob'] ?></td>
                    <td><?= $row['address'] ?></td>
                    <td><?= $row['married'] ?></td>
                    <td><?= $row['dor'] ?></td>
                    <td><?= $row['contact'] ?></td>
                    <td>

                        <button type="button" class="btn btn-primary" data-toggle="popover" data-html='true' data-placement="top" title="Type de graphique" data-content="
                                                <a class='btn btn-sm btn-default' href='wfa.php?id=<?= $row['user_id'] ?>'>Percentiles (poids/âge)</a><br>
                                                <a class='btn btn-sm btn-default' href='hfa.php?id=<?= $row['user_id'] ?>'>Taille pour l'âge</a><br>
                                                <a class='btn btn-sm btn-default' href='wfh.php?id=<?= $row['user_id'] ?>'>Poids pour la taille</a><br>
                                                <a class='btn btn-sm btn-default' href='bfa.php?id=<?= $row['user_id'] ?>'>IMC pour l'âge</a><br>
                                                <a class='btn btn-sm btn-default' href='hcfa.php?id=<?= $row['user_id'] ?>'>Périmètre crânien pour l'âge</a><br>
                                        ">Graphiques</button>
                    </td>
                    <td><a title="Mettre à jour les mensurations" href="updateChild.php?id=<?= $row['user_id'] ?>" class="btn btn-sm btn-default fa fa-pencil"></a> </td>
                </tr>
            <?php } ?>
        </tbody>

    </table>

</div>



<?php include "../../includes/footer.php"; ?>

<script>
    function refresh() {
        window.location.href = 'viewChildren.php';
    }

    $(function() {
        $('[data-toggle="popover"]').popover()
    })
</script>
