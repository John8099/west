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
  <!-- Full calendar -->
  <link rel="stylesheet" href="../../assets/plugins/fullcalendar/main.css">
  <style>
    .cursor-pointer {
      cursor: pointer !important;
    }

    .fc-toolbar-chunk button {
      text-transform: capitalize !important;
    }
  </style>
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
              <?php include("../components/schedule-list.php"); ?>
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
  <!-- Full calendar -->
  <script src="../../assets/plugins/fullcalendar/main.js"></script>

  <script>
    const scheds = $.parseJSON('<?= getAllSchedules() ? json_encode(getAllSchedules()) : '{}' ?>')
    let events = []
    $(document).ready(function() {

      if (Object.keys(scheds).length > 0) {
        Object.keys(scheds).map(k => {
          var data = scheds[k]
          var event_item = {
            id: data.id,
            title: data.title,
            start: data.schedule_from,
            end: data.schedule_to,
            backgroundColor: '#3788d8',
            borderColor: '#3788d8',
            allDay: data.is_whole == 1,
            className: 'cursor-pointer'
          }
          events.push(event_item)
        })
      }

      const date = new Date()
      const d = date.getDate(),
        m = date.getMonth(),
        y = date.getFullYear()
      const Calendar = FullCalendar.Calendar;
      const calendar = new Calendar(document.querySelector('#calendar'), {
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        themeSystem: 'bootstrap',
        events: events,
        editable: false,
        droppable: false,
        drop: false,
        eventClick: function(info) {
          handleOpenModal(info.event.id, 'preview')
        }
      });

      calendar.render();
    })

    function handleOpenModal(modalId, action) {
      if (action === "preview") {
        $(`#preview${modalId}`).modal({
          show: true,
          backdrop: 'static',
          keyboard: false,
          focus: true
        })
      } else {
        $(`#editScheduleModal${modalId}`).modal({
          show: true,
          backdrop: 'static',
          keyboard: false,
          focus: true
        })
      }
    }

    function handleOnClickEdit(modalId, action) {
      if (action == "openEdit") {
        $(`#editScheduleModal${modalId}`).modal({
          show: true,
          backdrop: 'static',
          keyboard: false,
          focus: true
        })
      } else {
        $(`#preview${modalId}`).modal({
          show: true,
          backdrop: 'static',
          keyboard: false,
          focus: true
        })
      }
    }

    function handleSaveEditForm(el) {
      const formValue = $(el[0].form).serialize()
      handleSave(formValue)
    }
    $("#schedule-form").on("submit", function(e) {
      handleSave($(this).serialize())
      e.preventDefault()
    })

    function handleSave(formValues) {
      swal.showLoading();
      $.post(
        "../../backend/nodes?action=saveSchedule",
        formValues,
        (data, status) => {
          const resp = JSON.parse(data)
          if (resp.success) {
            swal.fire({
              title: 'Success!',
              text: resp.message,
              icon: 'success',
            }).then(() => window.location.reload())
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

    function handleDeleteSchedule(scheduleId) {
      swal.fire({
        title: 'Are you sure',
        icon: 'question',
        html: `you want to delete this schedule?`,
        showDenyButton: true,
        confirmButtonText: 'Yes',
        denyButtonText: 'No',
      }).then((res) => {
        if (res.isConfirmed) {
          swal.showLoading();
          $.post(
            "../../backend/nodes?action=deleteSchedule", {
              id: scheduleId
            },
            (data, status) => {
              const resp = JSON.parse(data)
              if (resp.success) {
                swal.fire({
                  title: 'Success!',
                  text: resp.message,
                  icon: 'success',
                }).then(() => window.location.reload())
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
  </script>
</body>

</html>