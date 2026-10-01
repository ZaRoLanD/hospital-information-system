<?php session_start(); ?>
<?php include "../../includes/dao.php" ?>
<?php (new Session())->requireRole('admin'); ?>
<div class="container">
    <?= $jumbo->getJumbo("Ajouter un médicament", "Enregistrer un nouveau médicament au stock") ?>

    <form class='presp' role='form' action='#' onsubmit="addPrep()">
        <input type="hidden" name="type" value="addPresp">
        <div class='form-group'>
            <label for='in_Name' class="fas fa-tablets prefix"> Nom du médicament</label>
            <input name='in_Name' id='in_Name' class='form-control' type='text' placeholder='Nom' required>
        </div>
        <div class='form-group'>
            <label for='in_Expiry' class="fas fa-calendar"> Date de péremption</label>
            <input name='in_Expiry' id='in_Expiry' class='form-control' type='date' placeholder='Péremption' required>
        </div>
        <div class='form-group'>
            <label for='in_Presp' class="fas fa-prescription-bottle-alt prefix"> Posologie</label>
            <input name='in_Presp' id='in_Presp' class='form-control' type='text' placeholder='Posologie'>
        </div>
        <div class='form-group'>
            <label for='in_Price' class="fas fa-money-bill prefix"> Price</label>
            <input name='in_Price' id='in_Price' class='form-control' type='text' placeholder='Prix (MWK)'>
        </div>
        <div class='form-group'>
            <label for='in_Qty' class="fas fa-sort-numeric-up prefix"> Quantité en stock</label>
            <input name='in_Qty' id='in_Qty' class='form-control' type='number' placeholder='Quantité'>
        </div>
        <div class='form-group'>
            <input class='btn btn-success' type='submit' value="Ajouter le médicament">
        </div>
    </form>
</div>
<?php include "../../includes/footer.php"; ?>

<script>
    function addPrep(e) {
        event.preventDefault();
        var presp = $('.presp').serialize();
        $.ajax({
            type: 'POST',
            url: '../../includes/presp/PrespController.php',
            data: presp,
            beforeSend: function() {
                Swal.fire({
                    title: "Ajout du médicament",
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
                        title: "Médicament ajouté avec succès",
                        type: "success",
                    }).then(result => {
                        window.location.reload();
                    });

                } else {
                    Swal.fire({
                        title: "Erreur : " + resp,
                        type: "error",
                    });
                }
            },

        })
    }
</script>