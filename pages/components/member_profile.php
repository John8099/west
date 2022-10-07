<?php $member = get_user_by_username($_GET["u"]); ?>

<div class="card card-outline card-primary shadow rounded-0">
  <form method="POST" id="update-member" enctype="multipart/form-data">

    <div class="card-header">
      <h5 class="card-title"><?= ucwords("$member->last_name's") ?> Profile</h5>
      <div class="card-tools">
        <button type="submit" class="btn btn-sm btn-primary">
          <i class="fa fa-edit"></i>
          Edit
        </button>
        <button type="button" class="btn btn-sm btn-danger " onclick="handleDeleteMember('<?= $member->id ?>')">
          <i class="fa fa-trash"></i>
          Delete
        </button>
        <button type="button" onclick="return window.history.back()" class="btn btn-default border btn-sm">
          <i class="fa fa-angle-left"></i>
          Back to List
        </button>
      </div>
    </div>
    <div class="card-body rounded-0">
      <div class="container-fluid">
        <input type="number" name="userId" value="<?= $member->id ?>" hidden readonly>
        <input type="text" name="role" value="<?= $member->role ?>" hidden readonly>
        <input type="text" name="group_number" value="<?= $member->group_number ?>" hidden readonly>
        <div class="row">
          <div class="col-lg-4">
            <div class="form-group">
              <label class="control-label text-navy">First name</label>
              <input type="text" name="fname" class="form-control form-control-border" value="<?= $member->first_name ?>" required>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="form-group">
              <label class="control-label text-navy">MiddleName</label>
              <input type="text" name="mname" class="form-control form-control-border" value="<?= $member->middle_name ?>">
            </div>
          </div>
          <div class="col-lg-4">
            <div class="form-group">
              <label class="control-label text-navy">LastName</label>
              <input type="text" name="lname" class="form-control form-control-border" value="<?= $member->last_name ?>" required>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-6">
            <div class="form-group">
              <label class="control-label text-navy">Roll</label>
              <input type="text" name="roll" class="form-control form-control-border" value="<?= $member->roll ?>" required>
            </div>

            <div class="form-group">
              <label class="control-label text-navy">Email</label>
              <input type="email" name="email" class="form-control form-control-border" value="<?= $member->email ?>" required>
            </div>

            <div class="form-group">
              <label class="control-label text-navy">Year and Section</label>
              <div class="row">
                <?php
                $year_and_section = $member->year_and_section;
                $year = explode("-", $year_and_section)[0];
                $section = explode("-", $year_and_section)[1];

                ?>
                <div class="col-md-6">
                  <input type="number" name="year" class="form-control form-control-border" value="<?= $year ?>" required>
                </div>
                <div class="col-md-6">
                  <input type="text" name="section" class="form-control form-control-border" value="<?= $section ?>" required>
                </div>
              </div>
            </div>

          </div>

          <div class="col-lg-6">
            <div class="form-group">
              <label for="img" class="control-label text-muted">Choose Image</label>
              <input type="file" name="avatar" class="form-control form-control-border" accept="image/png,image/jpeg" onchange="displayImg(this,$(this))">
            </div>
            <div class="form-group text-center">
              <img src="<?= $member->avatar == null ? "$SERVER_NAME/west/assets/dist/img/no-image-available.png" : $member->avatar ?>" alt="My Avatar" id="cimg" class="img-fluid student-img bg-gradient-dark border">
            </div>
          </div>
        </div>
        <hr>
      </div>
    </div>
  </form>

</div>