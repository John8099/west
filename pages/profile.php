<?php
include("../backend/nodes.php");
if (isset($_SESSION["username"])) {
  $user = get_user_by_username($_SESSION['username']);
  $middleName = $user->middle_name != null ? $user->middle_name[0] : "";
} else {
  header("location: ../");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thesis Progress Monitoring and Archive Management System</title>
  <link rel="icon" href="<?= "$SERVER_NAME/west" ?>/public/logo-1657357283.png" />

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
      <div class="container">

        <div class="content" style="padding-top: 9rem">
          <?php
          if (isset($_GET["page"])) :
            if ($_GET["page"] == "my_archive") {
              include("components/student-achieve.php");
            } elseif ($_GET["page"] == "manage_profile") {
              include("components/manage-profile.php");
            }
          ?>
          <?php
          else :
          ?>
            <div class="card card-outline card-primary shadow rounded-0">
              <div class="card-header rounded-0">
                <h5 class="card-title">Your Information:</h5>
                <div class="card-tools">
                  <a href="<?= "$self?page=my_archive" ?>" class="btn btn-primary"><i class="fa fa-archive"></i> My Archives</a>
                  <a href="<?= "$self?page=manage_profile" ?>" class="btn btn-default bg-navy "><i class="fa fa-edit"></i> Update Account</a>
                </div>
              </div>
              <div class="card-body rounded-0">
                <div class="container-fluid">
                  <div class="col-md-12">
                    <div class="row">
                      <div class="col-lg-4 col-sm-12">
                        <center>
                          <img src="http://localhost/wvsu4/otas/dist/img/no-image-available.png" alt="Student Image" class="img-fluid student-img bg-gradient-dark border">
                        </center>
                      </div>
                      <div class="col-lg-8 col-sm-12">
                        <dl>
                          <dt class="text-navy">Student Name:</dt>
                          <dd class="pl-4">
                            <?= ucwords("$user->last_name, $user->first_name $middleName") ?>
                          </dd>
                          <dt class="text-navy">Email:</dt>
                          <dd class="pl-4"><?= $user->email ?></dd>
                          <dt class="text-navy">Group number:</dt>
                          <dd class="pl-4"><?= $user->group_number ?></dd>
                          <dt class="text-navy">Year and Section:</dt>
                          <dd class="pl-4"><?= strtoupper($user->year_and_section) ?></dd>
                        </dl>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php
          endif;
          ?>
        </div>
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