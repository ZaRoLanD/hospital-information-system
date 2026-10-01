<?php session_start(); ?>
<?php include "../../includes/dao.php" ?>
<?php (new Session())->requireRole('admin'); ?>
<?php include "../../includes/users/UsersController.php";
$patients = new User();
?>
<div class="container">

    <?= $jumbo->getJumbo("Liste des utilisateurs", "Comptes ayant accès au système") ?>

    <?php $patientsList = $patients->viewAllUsers(); ?>

    <table class="table table-striped table-hover">
        <thead>
            <th>ID</th>
            <th>Adresse e-mail</th>
            <th>Rôle</th>
        </thead>
        <tbody>
            <?php while ($row = $patientsList->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <tr>
                    <td><?= $row['user_id'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['role'] === 'admin' ? 'Administrateur' : 'Utilisateur' ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include "../../includes/footer.php"; ?>
