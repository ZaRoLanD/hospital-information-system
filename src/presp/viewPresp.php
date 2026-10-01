<?php session_start(); ?>
<?php include "../../includes/dao.php" ?>
<?php (new Session())->requireRole('admin'); ?>
<?php include "../../includes/presp/PrespController.php";
$drugs = new Presp();
?>
<div class="container">

    <?= $jumbo->getJumbo("Liste des médicaments", "Gérer le stock de la pharmacie") ?>

    <?php $drugsList = $drugs->getAllPresp(); ?>

    <table class="table table-striped table-hover">
        <thead>
            <th>ID</th>
            <th>Nom</th>
            <th>Posologie</th>
            <th>Date de péremption</th>
            <th>Prix (MWK)</th>
            <th>Stock</th>
            <th>Actions</th>
        </thead>
        <tbody>
            <?php while ($row = $drugsList->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <tr>
                    <td><?= $row['drug_id'] ?></td>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['presp'] ?></td>
                    <td><?= $row['expiry'] ?></td>
                    <td><?= $row['price'] ?></td>
                    <td><?= $row['qty'] ?></td>
                    <td>
                        <a title="Modifier" href="updatePresp.php?id=<?= $row['drug_id'] ?>" class="btn btn-sm btn-default fa fa-pencil"></a>
                        <a title="Supprimer" class="btn btn-danger btn-sm fa fa-trash-o" onclick="Delete(<?= $row['drug_id'] ?>)"></a>
                    </td>
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
            title: "Supprimer le médicament n° " + id + " ?",
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
                    url: '../../includes/presp/PrespController.php',
                    data: {
                        type: "deleteDrug",
                        id: id
                    },
                    beforeSend: function() {
                        Swal.fire({
                            title: "Suppression en cours",
                            type: "info",
                            text: "Suppression du médicament " + id,
                            icon: "info",
                        })
                    },
                    success: function(resp) {
                        if (resp == "success") {
                            Swal.fire({
                                text: "Médicament supprimé",
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
</script>
