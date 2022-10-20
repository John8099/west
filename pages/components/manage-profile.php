<div class="card card-outline card-primary shadow rounded-0">
  <div class="card-header rounded-0">
    <h5 class="card-title">Update Details</h5>
  </div>
  <div class="card-body rounded-0">
    <div class="container-fluid">
      <form method="POST" id="update-form" enctype="multipart/form-data">
        <input type="text" name="userId" value="<?= $user->id ?>" hidden readonly>
        <input type="text" name="role" value="<?= $user->role ?>" hidden readonly>
        <div class="row">
          <div class="col-lg-4">
            <div class="form-group">
              <label class="control-label text-navy">First name</label>
              <input type="text" name="fname" class="form-control form-control-border" value="<?= $user->first_name ?>" required>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="form-group">
              <label class="control-label text-navy">MiddleName</label>
              <input type="text" name="mname" class="form-control form-control-border" value="<?= $user->middle_name ?>">
            </div>
          </div>
          <div class="col-lg-4">
            <div class="form-group">
              <label class="control-label text-navy">LastName</label>
              <input type="text" name="lname" class="form-control form-control-border" value="<?= $user->last_name ?>" required>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-6">
            <div class="form-group">
              <label class="control-label text-navy">Roll</label>
              <input type="text" name="roll" class="form-control form-control-border" value="<?= $user->roll ?>" required>
            </div>
            <div class="form-group">
              <label class="control-label text-navy">Group number</label>
              <input type="number" name="group_number" class="form-control form-control-border" value="<?= $user->group_number ?>" required>
            </div>

            <div class="form-group">
              <label class="control-label text-navy">Year and Section</label>
              <div class="row">
                <?php
                $year_and_section = $user->year_and_section;
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

            <div class="form-group">
              <label for="email" class="control-label text-navy">Email</label>
              <input type="email" name="email" class="form-control form-control-border" required value="<?= $user->email ?>">
            </div>
            <div class="form-group">
              <label for="password" class="control-label text-navy">New Password</label>
              <input type="password" name="password" id="password" placeholder="Password" class="form-control form-control-border">
            </div>

            <div class="form-group">
              <label for="cpassword" class="control-label text-navy">Confirm New Password</label>
              <input type="password" name="cpassword" placeholder="Confirm Password" class="form-control form-control-border">
            </div>

            <small class="text-muted">Leave the New Password and Confirm New Password Blank if you don't wish to change your password.</small>

            <div class="form-group mt-4">
              <label for="oldpassword">Please Enter your Current Password</label>
              <input type="password" name="oldpassword" id="oldpassword" placeholder="Current Password" class="form-control form-control-border">
            </div>
          </div>

          <div class="col-lg-6">
            <div class="form-group">
              <label for="img" class="control-label text-muted">Choose Image</label>
              <input type="file" name="avatar" class="form-control border-0" accept="image/png,image/jpeg" onchange="displayImg(this,$(this))">
            </div>
            <div class="form-group text-center">
              <img src="<?= $user->avatar == null ? "$SERVER_NAME/assets/dist/img/no-image-available.png" : $SERVER_NAME . $user->avatar ?>" alt="My Avatar" id="cimg" class="img-fluid student-img bg-gradient-dark border" style="width: 217px; height: 217px;">
            </div>
          </div>
        </div>
        <hr>
        <div class="row">

        </div>
        <div class="row">
          <div class="col-lg-12">
            <div class="form-group text-center">
              <button type="submit" class="btn btn-default bg-navy"> Update</button>
              <button type="button" onclick="return window.history.back()" class="btn btn-danger"> Cancel</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>