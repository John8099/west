<!-- Navbar -->
<nav class="main-header navbar navbar-expand-md bg-navy  position-fixed top-0" style="width: 100%;">
  <div>
    <span class="mr-2 text-white"><i class="fa fa-phone mr-1"></i> 09854698789 / 78945632</span>
  </div>

  <!-- Right navbar links -->
  <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto ">
    <?php
    include_once("links.php");

    $navBarLinks = array_filter(
      $links,
      fn ($val) => $val["config"] == "navbar",
      ARRAY_FILTER_USE_BOTH
    );
    foreach ($navBarLinks as $key => $value) :
    ?>
      <li class="nav-item">
        <a class="nav-link text-white" href="<?= $value["url"] ?>"><?= $value["title"] ?></a>
      </li>
    <?php
    endforeach;
    ?>
  </ul>
</nav>
<!-- /.navbar -->

<?php include_once("sub-navbar.php") ?>