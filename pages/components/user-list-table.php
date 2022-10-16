<div class="card mt-2">
  <div class="card-header">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h4>Student List</h4>
      </div>

    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body">
    <table id="student_list" class="table table-bordered table-hover">
      <thead>
        <tr class="bg-gradient-dark text-light">
          <th>Group #</th>
          <th>Leader</th>
          <th>Members</th>
          <th>Instructor</th>
          <th>Panel</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $query = mysqli_query(
          $conn,
          "SELECT * FROM users WHERE `role` = 'student' and isLeader = '1' ORDER BY id DESC"
        );
        while ($leader = mysqli_fetch_object($query)) :
          $leaderName = ucwords("$leader->first_name " . $leader->middle_name[0] . ". $leader->last_name");
          $memberData = json_decode(getMemberData($leader->group_number, $leader->id));

          $thesisGroupQuery = mysqli_query(
            $conn,
            "SELECT * FROM thesis_groups WHERE group_leader_id='$leader->id' and group_number='$leader->group_number'"
          );

          $hasSubmittedGroup = mysqli_num_rows($thesisGroupQuery) > 0;
          $thesisGroupData = $hasSubmittedGroup ? mysqli_fetch_object($thesisGroupQuery) : null;
        ?>
          <tr>
            <td><?= $leader->group_number ?></td>
            <td>
              <div class="mt-2 mb-2 d-flex justify-content-start align-items-center">
                <div class="mr-1">
                  <img src="<?= $leader->avatar ?>" class="img-circle" style="width: 3rem; height: 3rem" alt="User Image">
                </div>
                <div>
                  <?= $leaderName ?>
                </div>
              </div>
            </td>
            <td>
              <?php
              foreach ($memberData as $member) :
                $memberName = ucwords("$member->first_name " . $member->middle_name[0] . ". $member->last_name");
              ?>
                <div class="mt-2 mb-2 d-flex justify-content-start align-items-center">
                  <div class="mr-1">
                    <img src="<?= $member->avatar ?>" class="img-circle" style="width: 3rem; height: 3rem" alt="User Image">
                  </div>
                  <div>
                    <?= $memberName ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </td>
            <td>
              <?php
              if ($hasSubmittedGroup && $thesisGroupData != null) {
                if ($thesisGroupData->instructor_id != null) :
                  $instructor = get_user_by_id($thesisGroupData->instructor_id);
                  $instructorName = ucwords("$instructor->first_name " . $instructor->middle_name[0] . ". $instructor->last_name");
              ?>
                  <div class="mt-2 mb-2 d-flex justify-content-start align-items-center">
                    <div class="mr-1">
                      <img src="<?= $instructor->avatar ?>" class="img-circle" style="width: 3rem; height: 3rem" alt="User Image">
                    </div>
                    <div>
                      <h6>
                        <strong>
                          <?= $instructorName ?>
                        </strong>
                      </h6>
                    </div>
                  </div>
              <?php
                else :
                  echo "<h6><em>No instructor assigned yet.</em> </h6>";
                endif;
              } else {
                echo "<h6><em>Not yet submitted group to instructor</em> </h6>";
              }
              ?>
            </td>
            <td>
              <?php
              if ($hasSubmittedGroup && $thesisGroupData != null) {
                if ($thesisGroupData->panel_id != null) :
                  $panel = get_user_by_id($thesisGroupData->panel_id);
                  $panelName = ucwords("$panel->first_name " . $panel->middle_name[0] . ". $panel->last_name");
              ?>
                  <div class="mt-2 mb-2 d-flex justify-content-start align-items-center">
                    <div class="mr-1">
                      <img src="<?= $panel->avatar ?>" class="img-circle" style="width: 3rem; height: 3rem" alt="User Image">
                    </div>
                    <div>
                      <h6>
                        <strong>
                          <?= $panelName ?>
                        </strong>
                      </h6>
                    </div>
                  </div>
              <?php
                else :
                  echo "<h6><em>No panel assigned yet.</em> </h6>";
                endif;
              } else {
                echo "<h6><em>Not yet submitted group to instructor</em> </h6>";
              }
              ?>
            </td>
            <td style="width: 140px;">
              <?php
              $thesisGroupId = $hasSubmittedGroup && $thesisGroupData != null ? $thesisGroupData->id : null;
              ?>
              <button type="button" class="btn btn-primary m-1" style="width: 145px;" onclick="handleOnclickUpdateInstructorAndPanel('<?= $thesisGroupId ?>', 'updateGroupInstructor')">
                Update Instructor
              </button>
              <button type="button" class="btn btn-primary m-1" style="width: 145px;" onclick="handleOnclickUpdateInstructorAndPanel('<?= $thesisGroupId ?>', 'updateGroupPanel')">
                Update Panel
              </button>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>

    </table>
  </div>
  <!-- /.card-body -->
</div>