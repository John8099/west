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
                include("../components/user-list-table.php");
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
      $("#student_list").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
      });
    });

    function handleOnclickUpdateInstructorAndPanel(groupId, action) {
      if (groupId) {
        swal.fire({
          title: 'Are you sure',
          icon: 'question',
          html: `you want to update ${action == "updateGroupPanel" ? "panel" : "instructor"} of this group?`,
          showDenyButton: true,
          confirmButtonText: 'Yes',
          denyButtonText: 'No',
        }).then((res) => {
          if (res.isConfirmed) {
            $.get(
              `../../backend/nodes?action=${action === "updateGroupPanel" ? "getAllPanel" : "getAllInstructor"}`,
              (data, status) => {
                const resp = JSON.parse(data)
                let options = null;
                if (action === "updateGroupPanel") {
                  options = resp.panels.map((data) => {
                    return `<option value="${data.id}" >
                    ${data.first_name} ${data.last_name}
                  </option>`
                  });
                } else {
                  options = resp.instructors.map((data) => {
                    return `<option value="${data.id}" >
                    ${data.first_name} ${data.last_name}
                  </option>`
                  });
                }

                swal.fire({
                  title: `Select ${action === "updateGroupPanel" ? "Panel" : "Instructor"}`,
                  icon: 'question',
                  html: `<select id="id" class="form-control" style="text-transform: capitalize">
                    ${options}
                  </select>`,
                  showDenyButton: true,
                  confirmButtonText: 'Submit',
                  denyButtonText: 'Cancel',
                }).then((res) => {
                  if (res.isConfirmed) {
                    updateGroupAdmin(groupId, $("#id option:selected").val(), action)
                  }
                })
              }).fail(function(e) {
              swal.fire({
                title: 'Error!',
                text: e.statusText,
                icon: 'error',
              })
            });
          }
        })
      } else {
        swal.fire({
          title: "Error!",
          text: "This group was not yet submitted there list to the instructor.",
          icon: "error"
        })
      }
    }

    function updateGroupAdmin(groupId, adminId, action) {
      $.post(
        `../../backend/nodes?action=updateGroupAdmin`, {
          group_id: groupId,
          admin_id: adminId,
          action: action
        },
        (data, status) => {
          const resp = JSON.parse(data)
          swal.fire({
            title: resp.success ? 'Success!' : 'Error!',
            text: resp.message,
            icon: resp.success ? 'success' : 'error',
          }).then(() => {
            location.reload();
          })
        }).fail(function(e) {
        swal.fire({
          title: 'Error!',
          text: e.statusText,
          icon: 'error',
        })
      });
    }
  </script>
</body>

</html>