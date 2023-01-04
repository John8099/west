<div class="card card-outline rounded-0 card-navy mt-2">
  <?php
  if ($user->role != "student" && $user->role != "instructor") :
  ?>
    <div class="card-header">
      <h3 class="card-title">List of Rooms</h3>
      <div class="card-tools">
        <a data-toggle="modal" data-target="#addSchedule" class="btn btn-primary btn-gradient-primary">
          <span class="fas fa-plus"></span> Create New
        </a>
      </div>
    </div>
  <?php endif; ?>
  <div class="card-body">
    <div class="container-fluid">
      <div id="calendar"></div>
    </div>
  </div>
</div>

<div class="modal fade" id="addSchedule" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa fa-plus"></i> Add New Task Schedule</h5>
      </div>
      <form method="POST" id="schedule-form">
        <div class="modal-body">
          <div class="container-fluid">
            <div class="form-group">
              <label for="category_id" class="control-label">Category</label>
              <select name="category_id" class="form-control" required>
                <option value="" selected disabled>-- select category --</option>
                <?php
                $category_q = mysqli_query(
                  $conn,
                  "SELECT * FROM category_list"
                );
                while ($category = mysqli_fetch_object($category_q)) :
                ?>
                  <option value="<?= $category->id ?>"><?= $category->name ?></option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="form-group">
              <label for="leader_id" class="control-label">Leader</label>
              <select name="leader_id" class="form-control" required>
                <option value="" selected disabled>-- select leader --</option>
                <?php
                $leaderQ = mysqli_query(
                  $conn,
                  "SELECT * FROM users u INNER JOIN courses c ON u.course_id = c.course_id WHERE u.role='student' and u.isLeader='1'"
                );
                while ($leaderData = mysqli_fetch_object($leaderQ)) :
                  $leader = get_user_by_id($leaderData->id);
                ?>
                  <option value="<?= $leader->id ?>">
                    <?=
                    ucwords("$leader->first_name " . ($leader->middle_name != null ? $leader->middle_name[0] . "." : "") . " $leader->last_name") . " (Group #$leader->group_number $leaderData->short_name $leader->year_and_section)"
                    ?>
                  </option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="form-group">
              <label for="title" class="control-label">Task Title</label>
              <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="description" class="control-label">Description</label>
              <textarea name="description" class="form-control" required></textarea>
            </div>
            <div class="form-group">
              <label for="schedule_from" class="control-label">Schedule Start</label>
              <input type="datetime-local" min="<?= date("Y-m-d\TH:i") ?>" name="schedule_from" class="form-control" required />
            </div>
            <div class="form-group">
              <label for="schedule_to" class="control-label">Schedule End <small>(Leave it blank if you want it whole day)</small></label>
              <input type="datetime-local" name="schedule_to" class="form-control" />
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary btn-gradient-primary m-1">Save</button>
          <button type="button" class="btn btn-danger btn-gradient-danger m-1" data-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<?php
$query = mysqli_query(
  $conn,
  "SELECT * FROM schedule_list"
);

while ($schedule = mysqli_fetch_object($query)) :
  $category = getCategoryById($schedule->category_id);
  $taskBy = get_user_by_id($schedule->user_id);

  $leader = get_user_by_id($schedule->leader_id);
  $leaderName = ucwords("$leader->first_name " . ($leader->middle_name != null ? $leader->middle_name[0] . "." : "") . " $leader->last_name");
  $memberData = json_decode(getMemberData($leader->group_number, $leader->id));
  $courseData = getCourseData($leader->course_id);

  $groupQ = mysqli_query(
    $conn,
    "SELECT * FROM thesis_groups WHERE group_leader_id='$leader->id'"
  );
  $groupData = mysqli_fetch_object($groupQ);
  $panel_ids = $groupData->panel_ids ? json_decode($groupData->panel_ids) : null;
?>
  <div class="modal fade" id="preview<?= $schedule->id ?>">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            <i class="fa fa-calendar-day"></i>
            Scheduled Task Details
          </h5>
        </div>
        <div class="modal-body">
          <div class="container-fluid">
            <dl>
              <dt class="text-muted">User</dt>
              <dd class="pl-4"><?= ucwords("$taskBy->first_name " . ($taskBy->middle_name != null ? $taskBy->middle_name[0] . "." : "") . " $taskBy->last_name") ?></dd>
              <dt class="text-muted">Group #</dt>
              <dd class="pl-4"><?= $leader->group_number ?></dd>
              <dt class="text-muted">Course</dt>
              <dd class="pl-4"><?= $courseData->name ?></dd>
              <dt class="text-muted">Year and section</dt>
              <dd class="pl-4"><?= $leader->year_and_section ?></dd>
              <dt class="text-muted">Group List</dt>
              <dd class="pl-4">
                <h6>Leader:</h6>
                <div class="mt-2 mb-2 d-flex justify-content-start align-items-center">
                  <div class="mr-1">
                    <img src="<?= $leader->avatar != null ? $SERVER_NAME . $leader->avatar : $SERVER_NAME . "/public/default.png" ?>" class="img-circle" style="width: 3rem; height: 3rem" alt="User Image">
                  </div>
                  <div>
                    <?= $leaderName ?>
                  </div>
                </div>
                <h6>Members:</h6>
                <?php
                foreach ($memberData as $member) :
                  $memberName = ucwords("$member->first_name " . ($member->middle_name != null ? $member->middle_name[0] . "." : "") . " $member->last_name");
                ?>
                  <div class="mt-2 mb-2 d-flex justify-content-start align-items-center">
                    <div class="mr-1">
                      <img src="<?= $member->avatar != null ? $SERVER_NAME . $member->avatar : $SERVER_NAME . "/public/default.png" ?>" class="img-circle" style="width: 3rem; height: 3rem" alt="User Image">
                    </div>
                    <div>
                      <?= $memberName ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              </dd>
              <?php if ($user->role == "panel") : ?>
                <dd class="pl-4">
                  <h6>Panels:</h6>
                  <?php
                  if ($panel_ids) :
                    foreach ($panel_ids as $panel_id) :
                      $panelData = get_user_by_id($panel_id);
                      $panelName = ucwords("$panelData->first_name " . ($panelData->middle_name != null ? $panelData->middle_name[0] . "." : "") . " $panelData->last_name");
                  ?>
                      <div class="mt-2 mb-2 d-flex justify-content-start align-items-center">
                        <div class="mr-1">
                          <img src="<?= $panelData->avatar != null ? $SERVER_NAME . $panelData->avatar : $SERVER_NAME . "/public/default.png" ?>" class="img-circle" style="width: 3rem; height: 3rem" alt="User Image">
                        </div>
                        <div>
                          <?= $panelName ?>
                        </div>
                      </div>
                  <?php endforeach;
                  endif; ?>
                </dd>
              <?php endif; ?>
              <dt class="text-muted">Category</dt>
              <dd class="pl-4"><?= $category->name ?></dd>
              <dt class="text-muted">Schedule Start</dt>
              <dd class="pl-4"><?= date("F d, Y h:i A", strtotime($schedule->schedule_from)) ?></dd>
              <dt class="text-muted">Schedule End</dt>
              <dd class="pl-4">
                <?php
                if ($schedule->is_whole == 0) {
                  echo date('F d, Y h:i A', strtotime($schedule->schedule_to));
                } else {
                  echo "Whole day";
                }
                ?>
              </dd>
              <dt class="text-muted">Title</dt>
              <dd class="pl-4"><?= $schedule->title ?></dd>
              <dt class="text-muted">Description</dt>
              <dd class="pl-4"><?= $schedule->description ?></dd>
            </dl>
          </div>
        </div>
        <div class="modal-footer">
          <?php if ($taskBy->username == $_SESSION['username']) : ?>
            <button type="button" class="btn btn-primary btn-gradient-primary m-1" data-dismiss="modal" onclick="handleOnClickEdit('<?= $schedule->id ?>', 'openEdit')">Edit</button>
            <button type="button" class="btn btn-danger btn-gradient-danger m-1" onclick="handleDeleteSchedule('<?= $schedule->id ?>')">Delete</button>
          <?php endif;
          if ($panel_ids && in_array($user->id, $panel_ids) && hasSubmittedThreeDocuments($leader) && $category->name == "Concept Presentation") :
          ?>
            <button type="button" class="btn btn-primary btn-gradient-primary m-1" onclick="return window.location.href = 'panel-preview-concept?leader_id=<?= $leader->id ?>'">Preview Concept</button>
          <?php endif; ?>

          <button type="button" class="btn btn-dark m-1" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="editScheduleModal<?= $schedule->id ?>">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            <i class="fa fa-edit"></i>
            Edit Schedule Details
          </h5>
        </div>
        <form method="POST" id="schedule-form">
          <div class="modal-body">
            <div class="container-fluid">
              <input type="text" name="id" value="<?= $schedule->id ?>" hidden readonly>
              <div class="form-group">
                <label for="category_id" class="control-label">Category</label>
                <select name="category_id" class="form-control" required>
                  <?php
                  $category_q = mysqli_query(
                    $conn,
                    "SELECT * FROM category_list"
                  );
                  while ($category = mysqli_fetch_object($category_q)) :
                  ?>
                    <option value="<?= $category->id ?>" <?= $schedule->category_id == $category->id ? "selected" : "" ?>>
                      <?= $category->name ?>
                    </option>
                  <?php endwhile; ?>
                </select>
              </div>
              <div class="form-group">
                <label for="leader_id" class="control-label">Leader</label>
                <select name="leader_id" class="form-control" required>
                  <option value="" selected disabled>-- select leader --</option>
                  <?php
                  $leaderQ = mysqli_query(
                    $conn,
                    "SELECT * FROM users u INNER JOIN courses c ON u.course_id = c.course_id WHERE u.role='student' and u.id='$schedule->leader_id'"
                  );
                  while ($leaderData = mysqli_fetch_object($leaderQ)) :
                    $leader = get_user_by_id($leaderData->id);
                  ?>
                    <option value="<?= $leader->id ?>" <?= $leader->id == $schedule->leader_id ? "selected" : "" ?>>
                      <?=
                      ucwords("$leader->first_name " . ($leader->middle_name != null ? $leader->middle_name[0] . "." : "") . " $leader->last_name") . " (Group #$leader->group_number $leaderData->short_name $leader->year_and_section)"
                      ?>
                    </option>
                  <?php endwhile; ?>
                </select>
              </div>
              <div class="form-group">
                <label for="title" class="control-label">Task Title</label>
                <input type="text" name="title" value="<?= $schedule->title ?>" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="description" class="control-label">Description</label>
                <textarea name="description" class="form-control" required><?= $schedule->description ?></textarea>
              </div>
              <div class="form-group">
                <label for="schedule_from" class="control-label">Schedule Start</label>
                <input type="datetime-local" value="<?= date("Y-m-d\TH:i", strtotime($schedule->schedule_from)) ?>" name="schedule_from" class="form-control" required />
              </div>
              <div class="form-group">
                <label for="schedule_to" class="control-label">Schedule End <small>(clear field if you want it whole day)</small></label>
                <input type="datetime-local" min="<?= date("Y-m-d\TH:i") ?>" value="<?= isset($schedule->schedule_to) ? date("Y-m-d\TH:i", strtotime($schedule->schedule_to)) : "" ?>" name="schedule_to" class="form-control" />
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary btn-gradient-primary m-1" onclick="handleSaveEditForm($(this))">
              Save
            </button>
            <button type="button" class="btn btn-danger btn-gradient-danger m-1" data-dismiss="modal" onclick="handleOnClickEdit('<?= $schedule->id ?>', 'openPreview')">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php endwhile; ?>