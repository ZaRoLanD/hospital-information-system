<?php session_start(); ?>
<?php include "../../includes/dao.php"; ?>

<div class="container">

<div class="hero mb-4">
  <h1 class="display-4 font-weight-bold">Système de Gestion Hospitalière</h1>
  <h5 class="text-uppercase mt-3">Commencer</h5>
  <div class="mt-4">
    <a href="../patients/viewPatients.php" class="btn btn-primary btn-lg mr-2 mb-2">
      <i class="fas fa-user-injured mr-1"></i> Patients
    </a>
    <?php if ($_SESSION['role'] == "admin") : ?>
      <a href="../presp/viewPresp.php" class="btn btn-light btn-lg mr-2 mb-2">
        <i class="fas fa-capsules mr-1"></i> Pharmacie
      </a>
    <?php endif; ?>
    <a href="../req/addReq.php" class="btn btn-light btn-lg mb-2">
      <i class="fas fa-shopping-cart mr-1"></i> Réquisitions
    </a>
  </div>
</div>

<div class="row">
  <div class="col-md-4 mb-3">
    <div class="card stat-card">
      <span class="stat-icon" style="background: rgba(13,148,136,.15); color: #0d9488;"><i class="fas fa-user-injured"></i></span>
      <div>
        <div class="stat-value"><?= htmlspecialchars($_SESSION['email'] ?? '') ?></div>
        <div class="stat-label">Connecté en tant que <?= ($_SESSION['role'] ?? '') === 'admin' ? 'administrateur' : 'utilisateur' ?></div>
      </div>
    </div>
  </div>
  <div class="col-md-4 mb-3">
    <div class="card stat-card">
      <span class="stat-icon" style="background: rgba(217,119,6,.15); color: #d97706;"><i class="fas fa-clipboard-list"></i></span>
      <div>
        <div class="stat-value"><a href="../req/viewReq.php" class="text-reset">Réquisitions</a></div>
        <div class="stat-label">Examiner les demandes de médicaments</div>
      </div>
    </div>
  </div>
  <div class="col-md-4 mb-3">
    <div class="card stat-card">
      <span class="stat-icon" style="background: rgba(2,132,199,.15); color: #0284c7;"><i class="fas fa-child"></i></span>
      <div>
        <div class="stat-value"><a href="../growth/viewChildren.php" class="text-reset">Enfants</a></div>
        <div class="stat-label">Mettre à jour les mensurations</div>
      </div>
    </div>
  </div>
</div>

</div><!-- /.container -->

<?php include "../../includes/footer.php"; ?>
