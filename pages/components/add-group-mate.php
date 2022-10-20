<div class="card card-outline card-primary shadow rounded-0">
  <div class="card-header rounded-0">
    <h5 class="card-title">Add Group mate</h5>
  </div>
  <div class="card-body rounded-0">
    <div class="container-fluid">
      <form method="POST" id="add-group-mate" enctype="multipart/form-data">
        <input type="number" name="group_number" value="<?= $user->group_number ?>" hidden readonly>
        <div class="row">
          <div class="col-lg-4">
            <div class="form-group">
              <label class="control-label text-navy">First name</label>
              <input type="text" name="fname" class="form-control form-control-border" required>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="form-group">
              <label class="control-label text-navy">MiddleName</label>
              <input type="text" name="mname" class="form-control form-control-border">
            </div>
          </div>
          <div class="col-lg-4">
            <div class="form-group">
              <label class="control-label text-navy">LastName</label>
              <input type="text" name="lname" class="form-control form-control-border" required>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-6">
            <div class="form-group">
              <label class="control-label text-navy">Roll</label>
              <input type="text" name="roll" class="form-control form-control-border" required>
            </div>

            <div class="form-group">
              <label class="control-label text-navy">Email</label>
              <input type="email" name="email" class="form-control form-control-border" required>
            </div>

            <div class="form-group">
              <label class="control-label text-navy">Year and Section</label>
              <div class="row">

                <div class="col-md-6">
                  <input type="number" name="year" class="form-control form-control-border" required>
                </div>
                <div class="col-md-6">
                  <input type="text" name="section" class="form-control form-control-border" required>
                </div>
              </div>
            </div>

          </div>

          <div class="col-lg-6">
            <div class="form-group">
              <label for="img" class="control-label text-muted">Choose Image</label>
              <input type="file" name="avatar" class="form-control border-0" accept="image/png,image/jpeg" onchange="displayImg(this,$(this))">
            </div>
            <div class="form-group text-center">
              <img src="<?= $SERVER_NAME ?>/assets/dist/img/no-image-available.png" alt="My Avatar" id="cimg" class="img-fluid student-img bg-gradient-dark border"  style="width: 217px; height: 217px;">
            </div>
          </div>
        </div>
        <hr>
        <div class="row">

        </div>
        <div class="row">
          <div class="col-lg-12">
            <div class="form-group text-center">
              <button type="submit" class="btn btn-default bg-navy"> Add</button>
              <button type="button" onclick="return window.history.back()" class="btn btn-danger"> Cancel</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>