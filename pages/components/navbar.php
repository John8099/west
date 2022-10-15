<!-- Navbar -->
<nav class="main-header navbar navbar-expand-md bg-navy  position-fixed top-0" style="width: 100%;">
  <div>
    <span class="mr-2 text-white"><i class="fa fa-phone mr-1"></i> 09854698789 / 78945632</span>
  </div>

  <!-- Right navbar links -->
  <div class="ml-auto" style="padding: .5rem 1rem;">
    <span class="mx-2"><?= $user->email ?></span>
    <span class="mx-1"><a href="http://<?= $_SERVER['SERVER_NAME'] ?>/west/backend/nodes.php?action=logout"><i class="fa fa-power-off"></i></a></span>
  </div>
</nav>
<!-- /.navbar -->

<?php include_once("sub-navbar.php") ?>