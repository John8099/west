<?php
include("../backend/nodes.php");
if (isset($_SESSION["username"])) {
  $user = get_user_by_username($_SESSION['username']);
}
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
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">
  <style>
    #searchNav::after {
      content: none
    }
  </style>
</head>

<body class="hold-transition layout-top-nav">
  <div class="wrapper">
    <?php
    if (!isset($_SESSION["username"])) {
      include_once("../components/navbar.php");
    } else {
      include_once("components/navbar.php");
    }
    ?>

    <!-- Content Wrapper.-->
    <div class="content-wrapper">

      <!-- Main content -->
      <div class="content" style="padding:9rem 0rem 0rem 0rem;">
        <?php
        if (isset($_GET['s'])) :
        ?>
          <div class="container">
            <div class="content py-2">
              <div class="col-12">
                <div class="card card-outline card-primary shadow rounded-0">
                  <div class="card-body rounded-0">
                    <h2>Archive List</h2>
                    <hr class="bg-navy">
                    <h3 class="text-center"><b>Search Result for <?= "\"{$_GET['s']}\"" ?> keyword</b></h3>
                    <div class="list-group"></div>
                  </div>
                  <div class="card-footer clearfix rounded-0">
                    <div class="col-12">
                      <div class="row">
                        <div class="col-md-6"><span class="text-muted">Display Items: 0</span></div>
                        <div class="col-md-6">
                          <ul class="pagination pagination-sm m-0 float-right">
                            <li class="page-item"><a class="page-link" href="./?page=projects&amp;q=awdawd&amp;p=0" disabled="">«</a></li>
                            <li class="page-item"><a class="page-link" href="./?page=projects&amp;q=awdawd&amp;p=2">»</a></li>
                          </ul>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php
        else :
        ?>
          <!-- Display All Archives -->
        <?php
        endif;
        ?>
      </div>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
  </div>
  <!-- ./wrapper -->

</body>

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="../assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../assets/dist/js/demo.js"></script>

<script>
  if (sessionStorage.getItem("searchInput")) {
    $("#searchInput").val(sessionStorage.getItem("searchInput"))
  }

  $("#searchInput").on("input", function(e) {
    sessionStorage.setItem("searchInput", e.target.value)
  })

  $(document).on('keypress', function(keyEvent) {
    if (keyEvent.which == 13 && $("#searchInput").val() !== "") {
      sessionStorage.setItem("searchInput", $("#searchInput").val())
      window.location.replace(`${window.location.origin}/west/pages/archives?s=${$("#searchInput").val()}`)
    }
  });
</script>

</html>