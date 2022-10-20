<?php
include("../../backend/nodes.php");
if (isset($_SESSION["username"])) {
  $user = get_user_by_username($_SESSION['username']);
  $middleName = $user->middle_name != null ? $user->middle_name[0] : "";
} else {
  header("location: ../../");
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
  <link rel="stylesheet" href="../../assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../assets/dist/css/adminlte.min.css">
  <!-- summernote -->
  <link rel="stylesheet" href="../../assets/plugins/summernote/summernote-bs4.min.css">
  <style>
    #searchNav::after {
      content: none
    }

    .banner-img {
      object-fit: scale-down;
      object-position: center center;
      height: 30vh;
      width: calc(100%);
    }
  </style>
</head>

<body class="hold-transition layout-top-nav">
  <div class="wrapper">
    <?php include_once("../components/navbar.php"); ?>

    <!-- Content Wrapper.-->
    <div class="content-wrapper">
      <!-- Main content -->
      <div class="container" style="padding-top: 9rem">
        <div class="row justify-content-center">
          <div class="col-md-8 col-sm-12">
            <div class="content">
              <div class="card card-outline card-primary shadow rounded-0">
                <div class="card-header rounded-0">
                  <h5 class="card-title"><?= isset($id) ? "Update Archive-{$archive_code} Details" : "Submit Documents" ?></h5>
                </div>
                <div class="card-body rounded-0">
                  <div class="container-fluid">
                    <form action="" id="archive-form">
                      <div class="form-group">
                        <label class="control-label text-navy">Document Title</label>
                        <input type="text" name="title" placeholder="Project Title" class="form-control form-control-border" required>
                      </div>

                      <div class="form-group">
                        <label class="control-label text-navy">Year</label>
                        <select name="year" class="form-control form-control-border" required>
                          <?php
                          for ($i = 0; $i < 51; $i++) :
                          ?>
                            <option <?= isset($year) && $year == date("Y", strtotime(date("Y") . " -{$i} years")) ? "selected" : "" ?>><?= date("Y", strtotime(date("Y") . " -{$i} years")) ?></option>
                          <?php endfor; ?>
                        </select>
                      </div>

                      <div class="form-group">
                        <label class="control-label text-navy">Description</label>
                        <textarea rows="3" name="description" placeholder="abstract" class="form-control form-control-border summernote" required></textarea>
                      </div>

                      <div class="form-group">
                        <label class="control-label text-navy">Project Members</label>

                      </div>

                      <div class="form-group">
                        <label class="control-label text-muted">Project Image/Banner Image</label>
                        <input type="file" name="banner" class="form-control form-control-border" accept="image/png,image/jpeg" onchange="displayImg(this,$(this))">
                      </div>

                      <div class="form-group text-center">
                        <img src="<?= "$SERVER_NAME/assets/dist/img/no-image-available.png" ?>" alt="My Avatar" id="cimg" class="img-fluid banner-img bg-gradient-dark border">
                      </div>

                      <div class="form-group">
                        <label class="control-label text-muted">Project Document (PDF File Only)</label>
                        <input type="file" name="pdfFile" class="form-control form-control-border" accept="application/pdf">
                      </div>

                      <div class="form-group d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary m-1"> Update</button>
                        <button type="button" class="btn btn-danger m-1" onclick="return window.history.back()"> Cancel</button>
                      </div>

                    </form>
                  </div>
                </div>
              </div>
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
<script src="../../assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../assets/dist/js/demo.js"></script>
<!-- Alert -->
<script src="../../assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>
<!-- Summernote -->
<script src="../../assets/plugins/summernote/summernote-bs4.min.js"></script>

<script>
  $('.summernote').summernote({
    height: 200,
    toolbar: [
      ['style', ['style']],
      ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
      ['fontname', ['fontname']],
      ['fontsize', ['fontsize']],
      ['color', ['color']],
      ['para', ['ol', 'ul', 'paragraph', 'height']],
      ['table', ['table']],
      ['insert', ['link', 'picture']],
      ['view', ['undo', 'redo', 'help']]
    ]
  })
</script>

</html>