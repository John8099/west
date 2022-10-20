<div class="card mt-2">
  <div class="card-header">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h4>Admin List</h4>
      </div>
      <div class="col-sm-6 d-flex justify-content-end">
        <button type="button" class="btn btn-primary" style="height: 38px;" onclick="handleAddAdmin()">Add Admin</button>
      </div>
    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body">
    <table id="admin_list" class="table table-bordered table-hover">
      <thead>
        <tr class="bg-gradient-dark text-light">
          <th>Date Added</th>
          <th>Date Updated</th>
          <th>Avatar</th>
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $query = mysqli_query(
          $conn,
          "SELECT * FROM users WHERE `role` != 'student' and id != '$user->id' ORDER BY id DESC"
        );
        while ($admin = mysqli_fetch_object($query)) :
          $adminName = ucwords("$admin->first_name " . $admin->middle_name[0] . ". $admin->last_name");
        ?>
          <tr>
            <td><?= date("Y-m-d H:i", strtotime($admin->date_added)) ?></td>
            <td><?= date("Y-m-d H:i", strtotime($admin->date_updated)) ?></td>
            <td>
              <img src="<?= $SERVER_NAME . $admin->avatar ?>" class="img-circle" style="width: 3rem; height: 3rem" alt="User Image">
            </td>
            <td><?= $adminName ?></td>
            <td><?= $admin->email ?></td>
            <td><?= ucwords($admin->role) ?></td>
            <td class="text-center">
              <button type="button" class="btn btn-warning m-1" onclick="handleOnclickEditAdmin('<?= $admin->username ?>')">
                Edit
              </button>
              <button type="button" class="btn btn-danger m-1" onclick="handleOnclickDeleteAdmin('<?= $admin->id ?>')">
                Delete
              </button>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>

    </table>
  </div>
  <!-- /.card-body -->
</div>