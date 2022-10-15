<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thesis Progress Monitoring and Archive Management System</title>
  <link rel="icon" href="http://<?= $_SERVER['SERVER_NAME'] ?>/west/public/logo-1657357283.png" />

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">
  <style>
    .content-wrapper {
      background: url("<?= "http://{$_SERVER['SERVER_NAME']}/west" ?>/public/cover-1638840281.jpg");
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center center;
    }

    .title {
      font-size: 5em !important;
      color: white !important;
      text-shadow: 4px 5px 3px #414447 !important;
    }

    @media only screen and (max-width: 980px) {
      #right {
        display: none;
      }
    }
  </style>
</head>

<body class="hold-transition layout-top-nav">
  <div class="wrapper">

    <!-- Content Wrapper.-->
    <div class="content-wrapper">

      <!-- Main content -->
      <div class="content" style="padding: 0;">
        <div class="row" style="height: 100vh; width: 100vw;">
          <div class="col-md-5 col-sm-12 d-flex justify-content-center align-items-center bg-navy" style="height: 100%;">
            <div class="card card-outline card-primary rounded-0 shadow col-lg-10 col-sm-12">
              <div class="card-header">
                <h5 class="card-title text-center text-dark"><b>Login</b></h5>
              </div>
              <div class="card-body text-dark">
                <form id="login-form" method="POST">
                  <div class="form-group">
                    <label class="col-form-label">
                      Email
                    </label>
                    <input type="email" name="email" class="form-control form-control-sm form-control-border" placeholder="Your email ..." required>

                  </div>

                  <div class="form-group">
                    <label class="col-form-label">
                      Password
                    </label>
                    <input type="password" name="password" class="form-control form-control-sm form-control-border" placeholder="Your password ..." required>
                  </div>

                  <div class="form-group d-flex justify-content-end">
                    <button type="submit" class="btn bg-navy">Login</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="col-md-7 d-flex justify-content-center align-items-center" id="right">
            <div class="w-100">
              <center>
                <img src="<?= "http://{$_SERVER['SERVER_NAME']}/west" ?>/public/logo-1657357283.png" style="width: 150px; object-fit:scale-down; object-position:center center; border-radius:100%;">
              </center>
              <h1 class="text-center py-5 title">
                <b>
                  Thesis Progress Monitoring and Archive Management System
                </b>
              </h1>
            </div>
          </div>
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
<!-- Alert -->
<script src="../assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>

<script>
  $("#login-form").on("submit", function(e) {
    swal.showLoading();
    $.post(
      "../backend/nodes?action=login",
      $(this).serialize(),
      (data, status) => {
        swal.close()
        const resp = JSON.parse(data)
        if (resp.success) {
          let location = `${window.location.origin}/west/pages/`

          const locations = {
            student: `${window.location.origin}/west/pages/student/index`,
            instructor: `${window.location.origin}/west/pages/instructor/index`,
            coordinator: `${window.location.origin}/west/pages/coordinator/index`,
            panel: `${window.location.origin}/west/pages/panel/index`,
            adviser: `${window.location.origin}/west/pages/adviser/index`,
          }

          if (resp.role === "student") {
            location = locations.student
          } else if (resp.role === "instructor") {
            location = locations.instructor
          } else if (resp.role === "coordinator") {
            location = locations.coordinator
          } else if (resp.role === "panel") {
            location = locations.panel
          } else if (resp.role === "adviser") {
            location = locations.adviser
          }

          window.location.href = location
        } else {
          swal.fire({
            title: 'Error!',
            text: resp.message,
            icon: 'error',
          })
        }
      }).fail(function(e) {
      swal.fire({
        title: 'Error!',
        text: e.statusText,
        icon: 'error',
      })
    });

    e.preventDefault();
  })
</script>

</html>