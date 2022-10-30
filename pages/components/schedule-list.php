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
              <input type="datetime-local" min="<?= date("Y-m-d\TH:i") ?>" name="schedule_to" class="form-control" />
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
              <dd class="pl-4"><?= ucwords("$taskBy->first_name " . $taskBy->middle_name[0] . ". $taskBy->last_name") ?></dd>
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