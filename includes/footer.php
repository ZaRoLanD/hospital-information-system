<?php $APP_SHELL = !empty($APP_SHELL); ?>

<?php if ($APP_SHELL) : ?>
    </main>

    <footer class="app-footer">
      <span>&copy; <?= date('Y') ?> Système d'Information Hospitalier</span>
      <span>v2.0 · interface repensée</span>
    </footer>
  </div><!-- /.layout-content -->
</div><!-- /.layout -->
<?php endif; ?>

<!-- SCRIPTS -->
<script type="text/javascript" src="../../public/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="../../public/js/popper.min.js"></script>
<script type="text/javascript" src="../../public/js/bootstrap.min.js"></script>
<script type="text/javascript" src="../../public/js/mdb.min.js"></script>
<script type="text/javascript" src="../../public/js/tables.js"></script>
<script type="text/javascript" src="../../public/js/sweetalert2.all.min.js"></script>
<script src="../../includes/canvas/canvasjs.min.js"></script>
<script src="../../public/js/theme.js"></script>
<script>
  $(function () {
    $('[data-toggle="popover"]').popover({ html: true });
  });
</script>

</body>
</html>
