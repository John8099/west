<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thesis Progress Monitoring and Archive Management System</title>
  <link rel="icon" href="<?= "http://{$_SERVER['SERVER_NAME']}/west" ?>/public/logo-1657357283.png" />

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
        <div class="row" style="width: 99vw;">
          <div class="col-md-5 col-sm-12 d-flex justify-content-center align-items-center bg-navy" style="height: 100%;">
            <div class="card card-outline card-primary rounded-0 shadow col-lg-10 col-sm-12 mt-2">
              <div class="card-header">
                <h5 class="card-title text-center text-dark"><b>Registration</b></h5>
              </div>
              <div class="card-body text-dark">
                <form id="registration-form" method="POST">
                  <div class="form-group">
                    <label class="col-form-label">
                      Roll
                    </label>
                    <input type="text" name="roll" class="form-control form-control-sm form-control-border" placeholder="Your roll ..." required>

                  </div>
                  <div class="form-group">
                    <label class="col-form-label">
                      Leader's name
                    </label>
                    <input type="text" class="form-control form-control-sm form-control-border" name="fname" placeholder="First name" required>
                    <br>
                    <input type="text" class="form-control form-control-sm form-control-border" name="mname" placeholder="Middle name (optional)">
                    <br>
                    <input type="text" class="form-control form-control-sm form-control-border" name="lname" placeholder="Last name" required>
                  </div>
                  <div class="form-group">
                    <label class="col-form-label">
                      Group number
                    </label>
                    <input type="number" name="group_number" class="form-control form-control-sm form-control-border" placeholder="Your group number ..." required>
                  </div>
                  <div class="form-group">
                    <label class="col-form-label">
                      Year & Section
                    </label>
                    <div class="input-group">
                      <input type="number" name="year" class="form-control form-control-sm form-control-border mr-3" placeholder="Year" required>

                      <input type="text" name="section" class="form-control form-control-sm form-control-border ml-3" placeholder="Section" required>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-form-label">
                      Email
                    </label>
                    <input type="email" name="email" id="inputEmail" class="form-control form-control-sm form-control-border" placeholder="Your email ..." required>

                    <span id="emailErrorField" class="error invalid-feedback"></span>
                  </div>

                  <div class="form-group">
                    <label class="col-form-label">
                      Password
                    </label>
                    <input type="password" name="password" class="form-control form-control-sm form-control-border" placeholder="Your password ..." required>
                  </div>

                  <div class="form-group d-flex justify-content-end">
                    <button type="submit" class="btn bg-navy">Register</button>
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
  const emailInput = $("#inputEmail")
  const emailFieldError = $("#emailErrorField")

  emailFieldError.hide();

  $("#registration-form").on("submit", function(e) {
    swal.showLoading();
    $.post(
      "../backend/nodes?action=student_registration",
      $(this).serialize(),
      (data, status) => {
        swal.close()
        const resp = JSON.parse(data)
        if (resp.success) {
          swal.fire({
            title: 'Success!',
            text: resp.message,
            icon: 'success',
          }).finally(() => {
            let location = `${window.location.origin}/west/pages/`

            const locations = {
              student: `${window.location.origin}/west/pages/student/`,
              instructor: `${window.location.origin}/west/pages/instructor/`,
              coordinator: `${window.location.origin}/west/pages/coordinator/`,
              panel: `${window.location.origin}/west/pages/panel/`,
              adviser: `${window.location.origin}/west/pages/adviser/`,
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
          })
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