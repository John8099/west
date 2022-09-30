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
  <link rel="stylesheet" href="./assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="./assets/dist/css/adminlte.min.css">
  <style>
    #searchNav::after {
      content: none
    }

    #header {
      height: 70vh;
      width: calc(100%);
      position: relative;
      top: -2rem;
    }

    #header:before {
      content: "";
      position: absolute;
      height: calc(100%);
      width: calc(100%);
      background: url("<?= "http://{$_SERVER['SERVER_NAME']}/west" ?>/public/cover-1638840281.jpg");
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center center;
      filter: brightness(50%);
    }

    #header>div {
      position: absolute;
      height: calc(100%);
      width: calc(100%);
      z-index: 2;
    }

    .site-title {
      font-size: 5em !important;
      color: white !important;
      text-shadow: 4px 5px 3px #414447 !important;
    }
  </style>
</head>

<body class="hold-transition layout-top-nav">
  <div class="wrapper">
    <?php include_once("./components/navbar.php"); ?>

    <!-- Content Wrapper.-->
    <div class="content-wrapper">

      <!-- Main content -->
      <div class="content" style="padding:9rem 0rem 0rem 0rem;">
        <div id="header" class="shadow mb-4">
          <div class="d-flex justify-content-center h-100 w-100 align-items-center flex-column">
            <h1 class="w-100 text-center site-title">Thesis Progress Monitoring and Archive Management System</h1>
          </div>
        </div>
      </div>
      <!-- /.content -->

      <section class="content ">
        <div class="container">
          <div class="col-lg-12 py-5">
            <div class="card card-outline card-navy shadow rounded-0">
              <div class="card-body rounded-0">
                <div class="container-fluid">
                  <h3 class="text-center">Welcome</h3>
                  <hr>
                  <div class="welcome-content">
                    <p style="margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec ut aliquam ligula. Cras consequat id orci eget imperdiet. Nulla eu libero purus. Donec dolor ipsum, dictum sit amet convallis quis, blandit ut nibh. Sed gravida molestie augue, et rutrum ipsum gravida at. Sed pulvinar ante ut justo molestie ullamcorper. Etiam lectus mi, maximus a suscipit vitae, sagittis vitae enim. Donec ullamcorper laoreet purus at mattis.</p>
                    <p style="margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px;">In eu nulla neque. Integer et posuere lorem. Ut cursus lorem sit amet magna consequat auctor. Morbi justo ipsum, semper rhoncus leo non, facilisis mollis lorem. Aliquam erat volutpat. Sed convallis, metus eu auctor porta, metus felis tincidunt neque, nec molestie sapien ante ac purus. Ut bibendum odio in scelerisque molestie.</p>
                    <p style="margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px;">Etiam convallis vitae nisi scelerisque gravida. Morbi commodo aliquam tellus, ut iaculis velit volutpat eget. Vestibulum bibendum diam nec sapien accumsan, quis convallis tellus sodales. Praesent ex diam, gravida pellentesque dolor id, sagittis rutrum sapien. Mauris pretium enim quis est bibendum auctor. Aliquam bibendum aliquet nisi, nec iaculis tortor commodo et. Nulla facilisi. Proin ultrices, nisi ac lacinia pellentesque, lectus magna sodales ante, vitae porttitor est nisl bibendum neque. Integer at quam sed augue dictum accumsan id et turpis. Donec dignissim erat vitae purus tincidunt, viverra euismod leo luctus. Duis vulputate, nunc a iaculis hendrerit, libero nibh dignissim elit, a pharetra orci ex vehicula arcu.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
    <!-- /.content-wrapper -->
  </div>
  <!-- ./wrapper -->

</body>

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="./assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="./assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="./assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="./assets/dist/js/demo.js"></script>

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
      window.location.href = `${window.location.origin}/west/pages/archives?s=${$("#searchInput").val()}`
    }
  });
</script>

</html>