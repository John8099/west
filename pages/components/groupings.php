<div class="card card-outline card-primary rounded-0 shadow">
  <div class="card-header">
    <h3 class="card-title">My Group list</h3>
    <div class="card-tools">

      <button type="button" id="btnSubmitToInstructor" class="btn btn-success btn-sm">
        <i class="fa fa-check"></i>
        Submit list to instructor
      </button>
      <a href="<?= $SERVER_NAME ?>/west/pages/student/my-groupings?page=add-group-mate" class="btn btn-sm btn-primary">
        <i class="fas fa-plus"></i>
        Add New group mate
      </a>
    </div>
  </div>
  <div class="card-body">
    <div class="container-fluid">
      <div class="container-fluid">
        <table class="table table-bordered table-hover table-striped">
          <thead>
            <tr class="bg-gradient-dark text-light">
              <th>#</th>
              <th>Date Created</th>
              <th>Student roll</th>
              <th>Role</th>
              <th>Name</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="text-center">1</td>
              <td><?= date("Y-m-d H:i", strtotime($user->date_added)) ?></td>
              <td>
                <?= $user->roll ?>
              </td>
              <td>Leader</td>
              <td>
                <p class="m-0 truncate-1"><?= ucwords("$user->last_name, $user->first_name $middleName") ?></p>
              </td>
              <td align="center">
                <a href="<?= "$SERVER_NAME/west/pages/profile?page=manage_profile" ?>" class="btn btn-flat btn-default btn-sm border"><i class="fa fa-eye"></i> View</a>
              </td>
            </tr>
            <?php
            $query = mysqli_query($conn, "SELECT * FROM users WHERE group_number = '$user->group_number' and id != '$user->id'");
            $count = 2;
            while ($member = mysqli_fetch_object($query)) :
              $memberMiddleName = $member->middle_name != null ? $member->middle_name[0] : "";
            ?>
              <tr>
                <td class="text-center"><?= $count ?></td>
                <td><?= date("Y-m-d H:i", strtotime($member->date_added)) ?></td>
                <td>
                  <?= $member->roll ?>
                </td>
                <td>Member</td>
                <td>
                  <p class="m-0 truncate-1"><?= ucwords("$member->last_name, $member->first_name $memberMiddleName") ?></p>
                </td>
                <td align="center">
                  <a href="<?= "$SERVER_NAME/west/pages/student/my-groupings?page=member_profile&&u=$member->username" ?>" class="btn btn-flat btn-default btn-sm border"><i class="fa fa-eye"></i> View</a>
                </td>
              </tr>
            <?php
              $count++;
            endwhile;
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>