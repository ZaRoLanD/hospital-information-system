<?php include "../../includes/header.php"; ?>
<link href="../../public/css/login.css" rel="stylesheet">

<!--Mise en page principale-->
<!--Section d'introduction-->
<section class="view intro-2">
    <div class="mask h-100 justify-content-center align-items-center">
        <div class="container">
            <div class="row">
                <div class="col-xl-5 col-lg-6 col-md-10 col-sm-12 mx-auto mt-lg-5">

                    <!--Formulaire avec en-tête-->
                    <div class="mt-5 card wow fadeIn" data-wow-delay="0.4s">
                        <div class="card-body">
                            <form action="#" class="login" id="login">
                                <input type="hidden" name="type" value="login">
                                <!--En-tête-->
                                <div class="form-header blue-gradient">
                                    <h3><i class="fa fa-user mt-2 mb-2"></i></h3>
                                    <h3 class="mt-2">Connexion</h3>
                                </div>

                                <!--Corps-->
                                <div class="md-form">
                                    <i class="fa fa-user prefix"></i>
                                    <input name="email" type="text" id="orangeForm-name" class="form-control">
                                    <label for="orangeForm-name">Adresse e-mail</label>
                                </div>

                                <div class="md-form">
                                    <i class="fa fa-lock prefix"></i>
                                    <input name="password" type="password" id="orangeForm-pass" class="form-control">
                                    <label for="orangeForm-pass">Votre mot de passe</label>
                                </div>

                                <div class="text-center">
                                    <button onclick="submitForm()" class="btn btn-primary btn-lg">Se connecter</button>
                                    <hr>
                                    <p class="text-white">&copy; <?= date('Y') ?> — Système d'Information Hospitalier</p>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!--/Formulaire avec en-tête-->

                </div>
            </div>
        </div>
    </div>
</section>
<!--Mise en page principale-->

<?php include "../../includes/footer.php"; ?>


<script>
    function submitForm(e) {
        event.preventDefault();
        var loginInfo = $('.login').serialize();
        $.ajax({
            type: 'POST',
            url: '../../includes/users/UsersController.php',
            data: loginInfo,
            beforeSend: function() {
                Swal.fire({
                    title: "Connexion en cours",
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
                    window.location.href = "../admin/admin.php";
                } else if (resp == "manager") {
                    window.location.href = "user.php";
                } else {
                    Swal.fire({
                        title: resp,
                        type: "error",
                    });
                }
            },

        })
    }
</script>
