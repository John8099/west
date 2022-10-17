<?php
include("../../backend/nodes.php");
if (!isset($_SESSION["username"])) {
  header("location: $SERVER_NAME/west/");
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
  <link rel="icon" href="<?= $systemInfo->logo ?>" />

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../assets/plugins/fontawesome-free/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../../assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../assets/dist/css/adminlte.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../../assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../../assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
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
      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">

              <?php
              if (isset($_GET["p"])) {
                if ($_GET["p"] == "add") {
                  include("../components/add-admin.php");
                } else if ($_GET["p"] == "edit") {
                  include("../components/edit-admin.php");
                } else {
                  include("../components/admin-list-table.php");
                }
              } else {
                include("../components/admin-list-table.php");
              }
              ?>

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
  <!-- Alert -->
  <script src="../../assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>

  <script src="../../assets/plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="../../assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="../../assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
  <script src="../../assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

  <script>
    $(function() {
      $("#admin_list").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
      });
    });

    function handleAddAdmin() {
      const current = `${window.location.origin}${window.location.pathname}?p=add`
      window.location.href = current
    }

    function handleOnclickEditAdmin(adminUname) {
      const current = `${window.location.origin}${window.location.pathname}?p=edit&&u=${adminUname}`
      window.location.href = current
    }

    function handleOnclickDeleteAdmin(adminId) {
      swal.fire({
        title: 'Delete admin',
        icon: 'warning',
        text: "Are you sure you want to delete this Admin?",
        showDenyButton: true,
        confirmButtonText: 'Yes',
        denyButtonText: 'No',
      }).then((res) => {
        if (res.isConfirmed) {
          $.post(
            "../../backend/nodes?action=deleteUser", {
              id: adminId
            },
            (data, status) => {
              swal.close()
              const resp = JSON.parse(data)
              if (resp.success) {
                swal.fire({
                  title: 'Success!',
                  text: resp.message,
                  icon: 'success',
                }).then(() => {
                  window.location.href = './admin-lists'
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
        }
      })
    }

    function displayImg(input, _this) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
          $('#cimg').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
      } else {
        $('#cimg').attr('src', "../../assets/dist/img/no-image-available.png");
      }
    }

    $("#add-admin").on("submit", function(e) {
      swal.showLoading()
      $.ajax({
        url: "../../backend/nodes?action=addAdmin",
        type: "POST",
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        success: function(data) {
          swal.close();
          const resp = JSON.parse(data);
          if (resp.success) {
            swal.fire({
              title: 'Success!',
              icon: 'success',
              html: resp.message,
              showDenyButton: true,
              confirmButtonText: 'Yes',
              denyButtonText: 'No',
            }).then((res) => {
              if (res.isConfirmed) {
                $("#add-admin")[0].reset()
                $('#cimg').attr('src', "../../assets/dist/img/no-image-available.png")
              } else if (res.isDenied) {
                window.location.href = "./admin-lists"
              }
            })

          } else {
            swal.fire({
              title: 'Error!',
              text: resp.message,
              icon: 'error',
            })
          }
        },
        error: function(data) {
          swal.fire({
            title: 'Oops...',
            text: 'Something went wrong.',
            icon: 'error',
          })
        }
      });
      e.preventDefault();
    })

    $("#edit-admin").on("submit", function(e) {
      swal.showLoading()
      $.ajax({
        url: "../../backend/nodes?action=editAdmin",
        type: "POST",
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        success: function(data) {
          swal.close();
          const resp = JSON.parse(data);
          if (resp.success) {
            swal.fire({
              title: 'Success!',
              text: resp.message,
              icon: 'success',
            }).then(() => {
              window.location.href = "./admin-lists"
            })

          } else {
            swal.fire({
              title: 'Error!',
              text: resp.message,
              icon: 'error',
            })
          }
        },
        error: function(data) {
          swal.fire({
            title: 'Oops...',
            text: 'Something went wrong.',
            icon: 'error',
          })
        }
      });
      e.preventDefault();
    })
  </script>
</body>

</html>