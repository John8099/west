<?php
include("../../backend/nodes.php");
if (!isset($_SESSION["username"])) {
  header("location: $SERVER_NAME/");
}
$user = get_user_by_username($_SESSION['username']);
$systemInfo = systemInfo();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $systemInfo->system_name ?></title>
  <link rel="icon" href="<?= $SERVER_NAME . $systemInfo->logo ?>" />

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../assets/plugins/fontawesome-free/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../../assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../assets/dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <!-- Site wrapper -->
  <div class="wrapper">
    <!-- Navbar -->
    <?php
    include("../components/admin-nav.php");
    include("../components/admin-side-bar.php");
    ?>

    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->


      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">

        </div>
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

  </div>
  <!-- ./wrapper -->

  <!-- jQuery -->
  <script src="../../assets/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="../../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- overlayScrollbars -->
  <script src="../../assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../../assets/dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="../../assets/dist/js/demo.js"></script>
</body>

</html>