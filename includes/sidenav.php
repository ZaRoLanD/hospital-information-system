<?php
// Indique que le shell applicatif (sidebar + topbar) est actif sur cette page.
// footer.php s'en sert pour savoir s'il doit fermer le balisage du shell.
$APP_SHELL = true;
?>
<body class="app-body">

<div class="layout">

  <!-- Barre latérale -->
  <aside class="app-sidebar" id="appSidebar">
    <div class="sidebar-brand">
      <span class="brand-mark">H</span>
      <span class="brand-text">
        SIH
        <small>Système d'Information Hospitalier</small>
      </span>
    </div>

    <nav class="app-nav">
      <div class="nav-section">Soins</div>

      <div class="nav-group">
        <a class="nav-link-item" href="../patients/viewPatients.php"><i class="fas fa-user-injured"></i> Patients</a>
      </div>

      <?php if ($_SESSION['role'] != "admin") : ?>
        <div class="nav-group">
          <a class="nav-link-item" href="../patients/addPatient.php"><i class="fas fa-user-plus"></i> Ajouter un patient</a>
        </div>
      <?php endif; ?>

      <div class="nav-group">
        <a class="nav-link-item" href="../diag/viewDiag.php"><i class="fas fa-stethoscope"></i> Diagnostics</a>
      </div>

      <div class="nav-group">
        <a class="nav-link-item" href="../growth/viewChildren.php"><i class="fas fa-child"></i> Enfants</a>
      </div>

      <div class="nav-section">Pharmacie</div>

      <?php if ($_SESSION['role'] == "admin") : ?>
        <div class="nav-group">
          <a class="nav-link-item" href="../presp/addPresp.php"><i class="fas fa-pills"></i> Ajouter un médicament</a>
        </div>
        <div class="nav-group">
          <a class="nav-link-item" href="../presp/viewPresp.php"><i class="fas fa-capsules"></i> Liste des médicaments</a>
        </div>
      <?php endif; ?>

      <div class="nav-group">
        <a class="nav-link-item" href="../req/addReq.php"><i class="fas fa-shopping-cart"></i> Nouvelle réquisition</a>
      </div>
      <div class="nav-group">
        <a class="nav-link-item" href="../req/viewReq.php"><i class="fas fa-clipboard-list"></i> Réquisitions</a>
      </div>
      <?php if ($_SESSION['role'] == "admin") : ?>
        <div class="nav-group">
          <a class="nav-link-item" href="../req/report.php"><i class="fas fa-chart-bar"></i> Rapports</a>
        </div>
      <?php endif; ?>

      <?php if ($_SESSION['role'] == "admin") : ?>
        <div class="nav-section">Administration</div>
        <div class="nav-group">
          <a class="nav-link-item" href="../users/addUser.php"><i class="fas fa-user-shield"></i> Ajouter un utilisateur</a>
        </div>
        <div class="nav-group">
          <a class="nav-link-item" href="../users/viewUsers.php"><i class="fas fa-users-cog"></i> Liste des utilisateurs</a>
        </div>
      <?php endif; ?>
    </nav>

    <div class="sidebar-footer">
      <div class="user-chip">
        <span class="avatar"><i class="fas fa-user"></i></span>
        <span class="user-meta">
          <span class="name"><?= htmlspecialchars($_SESSION['email'] ?? '') ?></span>
          <span class="role"><?= ($_SESSION['role'] ?? '') === 'admin' ? 'Administrateur' : 'Utilisateur' ?></span>
        </span>
      </div>
    </div>
  </aside>

  <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

  <!-- Zone de contenu -->
  <div class="layout-content">

    <!-- Barre supérieure -->
    <header class="app-topbar">
      <button class="topbar-btn" id="sidebarToggle" type="button" aria-label="Afficher/masquer le menu">
        <i class="fas fa-bars"></i>
      </button>

      <span class="topbar-title">Système d'Information Hospitalier</span>

      <div class="topbar-actions">
        <button class="topbar-btn" id="themeToggle" type="button" aria-label="Basculer le thème" title="Mode clair / sombre">
          <i class="fas fa-moon"></i><i class="fas fa-sun"></i>
        </button>

        <div class="dropdown">
          <button class="topbar-btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-label="Compte">
            <i class="fas fa-user-circle"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-right">
            <div class="dropdown-item-text small text-muted-2">
              <?= htmlspecialchars($_SESSION['email'] ?? '') ?>
              <span class="badge badge-primary ml-1"><?= htmlspecialchars($_SESSION['role'] ?? '') ?></span>
            </div>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="../index/logout.php"><i class="fas fa-sign-out-alt mr-2"></i>Déconnexion</a>
          </div>
        </div>
      </div>
    </header>

    <!-- Contenu de la page (chaque page fournit son propre .container) -->
    <main>
