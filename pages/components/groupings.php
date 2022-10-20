<div class="card card-outline card-primary rounded-0 shadow">
  <div class="card-header">
    <h3 class="card-title">
      My Group list
      <div class='mt-2'>
        <?php
        $query = mysqli_query(
          $conn,
          "SELECT * FROM thesis_groups WHERE group_leader_id='$user->id' and group_number='$user->group_number'"
        );

        $inviteQuery = mysqli_query(
          $conn,
          "SELECT * FROM invite WHERE leader_id='$user->id'"
        );

        $inviteData = null;
        $thesisGroupData = null;

        if (mysqli_num_rows($query) > 0) {

          $thesisGroupData = mysqli_fetch_object($query);

          if ($thesisGroupData->instructor_id != null) {
            $instructor = get_user_by_id($thesisGroupData->instructor_id);
            echo "<h6> Instructor: <strong>" . ucwords("$instructor->first_name " . $instructor->middle_name[0] . ". $instructor->last_name") . "</strong> </h6>";
          } else {
            echo "<h6> Instructor: <em>No instructor assigned yet.</em> </h6>";
          }

          if ($thesisGroupData->panel_id != null) {
            $panel = get_user_by_id($thesisGroupData->panel_id);
            echo "<h6> Panel: <strong>" . ucwords("$panel->first_name " . $panel->middle_name[0] . ". $panel->last_name") . "</strong> </h6>";
          } else {
            echo "<h6> Panel: <em>No panel assigned yet.</em> </h6>";
          }

          if ($thesisGroupData->adviser_id != null) {
            $adviser = get_user_by_id($thesisGroupData->adviser_id);
            echo "<h6> Adviser: <strong>" . ucwords("$adviser->first_name " . $adviser->middle_name[0] . ". $adviser->last_name") . "</strong> </h6>";
          } else {
            if (mysqli_num_rows($inviteQuery) > 0) {
              $inviteData = mysqli_fetch_object($inviteQuery);
              $inviteAdviser = get_user_by_id($inviteData->adviser_id);

              if ($inviteData->status == "PENDING") {
                echo "<h6> Adviser: <strong>" . ucwords("$inviteAdviser->first_name " . $inviteAdviser->middle_name[0] . ". $inviteAdviser->last_name") . "</strong> <em>(PENDING INVITATION)</em> </h6>";
              } else {
                echo "<h6> Adviser: <strong>" . ucwords("$inviteAdviser->first_name " . $inviteAdviser->middle_name[0] . ". $inviteAdviser->last_name") . "</strong></h6>";
              }
            } else {
              echo "<h6> Adviser: <em>No adviser assigned yet.</em> </h6>";
            }
          }
        } else {
          echo "<h6> Instructor: <em>No instructor assigned yet.</em> </h6>";
          echo "<h6> Panel: <em>No panel assigned yet.</em> </h6>";
          echo "<h6> Adviser: <em>No adviser assigned yet.</em> </h6>";
        }
        ?>
      </div>
    </h3>

    <div class="card-tools">
      <?php if ($inviteData == null) : ?>
        <button type="button" id="btnSendInvite" class="btn btn-primary btn-sm">
          <i class="fa fa-paper-plane"></i>
          Send adviser invite
        </button>
      <?php elseif ($inviteData != null && $inviteData->status == "PENDING") : ?>
        <button type="button" id="btnCancelInvite" class="btn btn-danger btn-sm">
          <i class="fa fa-user-times"></i>
          Cancel Invite
        </button>
      <?php elseif ($inviteData != null && $inviteData->status == "APPROVED" && $thesisGroupData != null && $thesisGroupData->instructor_id != null && $thesisGroupData->panel_id != null) : ?>
        <button type="button" id="btnSubmitDocuments" onclick="return window.location.href = './submit-documents'" class="btn btn-success btn-sm">
          <i class="fa fa-check"></i>
          Submit Documents
        </button>
      <?php endif; ?>

      <button type="button" id="btnChangeInstructor" class="btn btn-success btn-sm" style="display: none;">
        <i class="fa fa-check"></i>
        Submit list to instructor
      </button>

      <button type="button" id="btnSubmitToInstructor" class="btn btn-success btn-sm" style="display: none;">
        <i class="fa fa-check"></i>
        Submit list to instructor
      </button>

      <button type="button" id="btnEditInstructor" class="btn btn-warning btn-sm" style="display: none;">
        <i class="fa fa-edit"></i>
        Edit instructor
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
        <table id="groupings-table" class="table table-bordered table-hover table-striped">
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