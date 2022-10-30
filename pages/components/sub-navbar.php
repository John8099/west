<!-- Sub Navbar -->
<nav class="navbar navbar-expand-md nav-white bg-white" style="position:fixed; top: 3.5rem; width: 100%;z-index: 999;">
  <div class="container-fluid">
    <a href="./" class="navbar-brand d-flex align-items-center">
      <img src="<?= "http://{$_SERVER['SERVER_NAME']}/west" ?>/public/logo-1657357283.png" alt="Site Logo" class="brand-image img-circle elevation-3" style="opacity: .8;height: 33px;">
      <span class="ml-2" style="color: black">WVSU</span>
    </a>

    <ul class="navbar-nav d-flex justify-content-center">
      <?php
      include_once("links.php");

      $navBarLinks = array_filter(
        $links,
        fn ($val) => in_array($user->role, $val["allowedViews"]),
        ARRAY_FILTER_USE_BOTH
      );
      foreach ($navBarLinks as $key => $value) :
        $query = mysqli_query(
          $conn,
          "SELECT * FROM thesis_groups WHERE group_leader_id='$user->id' and group_number='$user->group_number'"
        );
        $thesisGroupData = null;

        if (mysqli_num_rows($query) > 0) {
          $thesisGroupData = mysqli_fetch_object($query);
        }

        $hasSubmittedDocuments = hasSubmittedDocuments($user);
        if ($value["title"] == "Document Status" && !$hasSubmittedDocuments) {
          continue;
        }
        
        if ($value["title"] == "Messages" && $thesisGroupData != null && $thesisGroupData->instructor_id == null) {
          continue;
        }
      ?>
        <li class="nav-item">
          <a class="nav-link <?= $value["url"] == str_replace(".php", "", $self) ? "active" : ""  ?>" style="color: black" href="<?= $value["url"] ?>"><?= $value["title"] ?></a>
        </li>
      <?php
      endforeach;
      ?>
    </ul>

    <ul class="navbar-nav">
      <li class="nav-item dropdown">
        <a id="searchNav" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle" style="color: black;">
          <i class="fa fa-search"></i>
        </a>

        <ul aria-labelledby="searchNav" class="dropdown-menu border-0 shadow p-1" style="left: 0px; right: inherit; width: 420px">
          <div class="search-field">
            <input type="search" id="searchInput" class="form-control rounded-0" placeholder="Search..." value="">
          </div>
        </ul>
      </li>
    </ul>
  </div>
</nav>
<!-- /.Sub Navbar -->