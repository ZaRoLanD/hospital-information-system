<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Système d'Information Hospitalier</title>

  <!-- Apply saved theme before first paint to avoid a flash -->
  <script>
    (function () {
      try {
        var params = new URLSearchParams(window.location.search);
        var t = params.get('theme') || localStorage.getItem('his-theme');
        if (!t && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
          t = 'dark';
        }
        if (t) document.documentElement.setAttribute('data-theme', t);
      } catch (e) { /* private mode */ }
    })();
  </script>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Vendor CSS -->
  <link href="../../public/css/all.css" rel="stylesheet">
  <link href="../../public/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../public/css/sweetalert2.css" rel="stylesheet">
  <link href="../../public/css/mdb.min.css" rel="stylesheet">

  <!-- HIS design system (must stay last) -->
  <link href="../../public/css/style.css" rel="stylesheet">
  <link href="../../public/css/login.css" rel="stylesheet">
</head>
