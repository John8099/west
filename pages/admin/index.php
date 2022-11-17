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
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Dashboard</h1>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-gradient-secondary elevation-1"><i class="fas fa-th-list"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Total Categories</span>
                  <span class="info-box-number text-right">
                    4 default
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-gradient-navy elevation-1"><i class="fas fa-calendar-day"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Today's Scheduled Tasks</span>
                  <span class="info-box-number text-right">
                    0 default
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-gradient-warning elevation-1"><i class="fas fa-calendar"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Upcoming Scheduled Tasks</span>
                  <span class="info-box-number text-right">
                    0 default
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
          </div>

          <div class="row">
            <div class="col-sm-12">
              <canvas id="bubbleChart" style="min-height: 400px; height: 400px; max-height: 400px; max-width: 100%;"></canvas>
            </div>
          </div>
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
  <script src="../../assets/plugins/chart.js/Chart.min.js"></script>
</body>

<script>
  var bubbleChart = $('#bubbleChart').get(0).getContext('2d')
  const data = {
    datasets: [{
      label: 'First Dataset',
      data: [{
        x: "4-A",
        y: 10,
        r: (10 / 12) * 100 
      }, {
        x: "2012",
        y: 20,
        r: 10
      }],
      backgroundColor: "#007bff7d",
    }]
  };

  const config = {
    type: 'bubble',
    data: data,
    options: {
      maintainAspectRatio: false,
      responsive: true,
      scaleOverride: true,
      scaleSteps: 10,
      scaleStepWidth: 2020,
      scaleStartValue: 2012,
      pointStyle : "dash"
    }
  };
  new Chart(bubbleChart, config)
</script>

</html>