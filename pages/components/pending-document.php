<div class="card card-outline rounded-0 card-navy mt-2">
  <div class="card-header">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h4>Pending Documents</h4>
      </div>

    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body">
    <table id="pending_documents" class="table table-bordered table-hover">
      <thead>
        <tr class="bg-gradient-dark text-light">
          <th>Date created</th>
          <th>Group#</th>
          <th>Group Members</th>
          <th>Members</th>
          <th>My feedback</th>
          <th>Instructor</th>
          <th>Panel</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $documentData = getDocumentsDataWithUsers($user->id, "adviser");
        foreach ($documentData as $data) :
          $leader = get_user_by_id($data->group_leader_id);
          $leaderName = ucwords("$leader->first_name " . $leader->middle_name[0] . ". $leader->last_name");
          $memberData = json_decode(getMemberData($leader->group_number, $leader->id));

          $feedback = json_decode($data->feedbacks);
          $adviserFeedbackData = $feedback->adviser;
          $instructorFeedbackData = $feedback->instructor;
          $panelFeedbackData = $feedback->panel;
        ?>
          <tr>
            <td><?= date("Y-m-d H:i:s", strtotime($data->date_created)) ?></td>
            <td><?= $leader->group_number ?></td>
            <td>
              <div class="mt-2 mb-2 d-flex justify-content-start align-items-center">
                <div class="mr-1">
                  <img src="<?= $SERVER_NAME . $leader->avatar ?>" class="img-circle" style="width: 3rem; height: 3rem" alt="User Image">
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
                    <img src="<?= $SERVER_NAME . $member->avatar ?>" class="img-circle" style="width: 3rem; height: 3rem" alt="User Image">
                  </div>
                  <div>
                    <?= $memberName ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </td>
            <td>
              <?php
              if (count($adviserFeedbackData->feedback) == 0) :
              ?>
                <p class='text-center'>
                  <span class="badge badge-warning rounded-pill px-4" style="font-size: 18px">
                    <em>No feedback yet</em>
                  </span>
                </p>
                <?php
              else :
                foreach ($adviserFeedbackData->feedback as $adFed) :
                ?>
                  <blockquote class="blockquote my-2 mx-0" style="font-size: 14px; overflow: hidden;">
                    <?php
                    if ($adFed->isResolved == "true") : ?>
                      <span>&#8226; <strong><?= $adFed->date ?></strong></span>
                      <p>
                        <s>
                          <?= nl2br($adFed->message) ?>
                        </s>
                      </p>
                      <span class="badge badge-success rounded-pill px-2" style="float:right;font-size: 14px">Resolved</span>
                    <?php else : ?>
                      <span>&#8226;<strong><?= $adFed->date ?></strong></span>
                      <p>
                        <?= nl2br($adFed->message) ?>
                      </p>
                      <span class="badge badge-warning rounded-pill px-2" style="float:right;font-size: 14px">To update</span>
                    <?php endif; ?>
                  </blockquote>
                <?php
                endforeach;
              endif;
              if ($adviserFeedbackData->isApproved == "true") :
                ?>
                <p class='text-center'>
                  <span class="badge badge-success rounded-pill px-4" style="font-size: 18px">
                    <em>Approved</em>
                  </span>
                </p>
              <?php endif; ?>
            </td>
            <td>
              <?php
              if (count($instructorFeedbackData->feedback) == 0) :
              ?>
                <p class='text-center'>
                  <span class="badge badge-warning rounded-pill px-4" style="font-size: 18px">
                    <em>No feedback yet</em>
                  </span>
                </p>
                <?php
              else :
                foreach ($instructorFeedbackData->feedback as $insFed) :
                ?>
                  <blockquote class="blockquote my-2 mx-0" style="font-size: 14px; overflow: hidden;">
                    <?php
                    if ($insFed->isResolved == "true") : ?>
                      <span>&#8226; <strong><?= $insFed->date ?></strong></span>
                      <p>
                        <s>
                          <?= nl2br($insFed->message) ?>
                        </s>
                      </p>
                      <span class="badge badge-success rounded-pill px-2" style="float:right;font-size: 14px">Resolved</span>
                    <?php else : ?>
                      <span>&#8226;<strong><?= $insFed->date ?></strong></span>
                      <p>
                        <?= nl2br($insFed->message) ?>
                      </p>
                      <span class="badge badge-warning rounded-pill px-2" style="float:right;font-size: 14px">To update</span>
                    <?php endif; ?>
                  </blockquote>
                <?php
                endforeach;
              endif;
              if ($instructorFeedbackData->isApproved == "true") :
                ?>
                <p class='text-center'>
                  <span class="badge badge-success rounded-pill px-4" style="font-size: 18px">
                    <em>Approved</em>
                  </span>
                </p>
              <?php endif; ?>
            </td>
            <td>
              <?php
              if (count($panelFeedbackData->feedback) == 0) :
              ?>
                <p class='text-center'>
                  <span class="badge badge-warning rounded-pill px-4" style="font-size: 18px">
                    <em>No feedback yet</em>
                  </span>
                </p>
                <?php
              else :
                foreach ($panelFeedbackData->feedback as $panFed) :
                ?>
                  <blockquote class="blockquote my-2 mx-0" style="font-size: 14px; overflow: hidden;">
                    <?php
                    if ($panFed->isResolved == "true") : ?>
                      <span>&#8226; <strong><?= $panFed->date ?></strong></span>
                      <p>
                        <s>
                          <?= nl2br($panFed->message) ?>
                        </s>
                      </p>
                      <span class="badge badge-success rounded-pill px-2" style="float:right;font-size: 14px">Resolved</span>
                    <?php else : ?>
                      <span>&#8226;<strong><?= $panFed->date ?></strong></span>
                      <p>
                        <?= nl2br($panFed->message) ?>
                      </p>
                      <span class="badge badge-warning rounded-pill px-2" style="float:right;font-size: 14px">To update</span>
                    <?php endif; ?>
                  </blockquote>
                <?php
                endforeach;
              endif;
              if ($panelFeedbackData->isApproved == "true") :
                ?>
                <p class='text-center'>
                  <span class="badge badge-success rounded-pill px-4" style="font-size: 18px">
                    <em>Approved</em>
                  </span>
                </p>
              <?php endif; ?>
            </td>
            <td class="text-center">
              <button type="button" class="btn btn-secondary btn-gradient-secondary m-1" onclick="handleOpenModal('<?= $data->id ?>')">
                Preview
              </button>
            </td>
          </tr>
          <div class="modal fade" id="preview<?= $data->id ?>">
            <div class="modal-dialog modal-xl modal-dialog-scrollable ">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">
                    <?= ucwords($data->title) ?>
                  </h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="font-size: 30px">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <center>
                    <img src="http://localhost/wvsu4/otas/uploads/banners/archive-3.png?v=1639212829" alt="Banner Image" id="banner-img" class="img-fluid border bg-gradient-dark">
                  </center>
                  <fieldset>
                    <legend class="text-navy"> Field type:</legend>
                    <div class="pl-4">
                      <?= mysqli_fetch_object(mysqli_query($conn, "SELECT `name`, id FROM types WHERE id='$data->type_id'"))->name ?>
                    </div>
                  </fieldset>
                  <fieldset>
                    <legend class="text-navy"> Year:</legend>
                    <div class="pl-4">
                      <?= $data->year ?>
                    </div>
                  </fieldset>
                  <fieldset>
                    <legend class="text-navy">Description:</legend>
                    <div class="pl-4">
                      <?= nl2br($data->description) ?>
                    </div>
                  </fieldset>
                  <fieldset>
                    <legend class="text-navy">Project Leader:</legend>
                    <div class="pl-4">
                      <div class="ml-2 mt-2 mb-2 d-flex justify-content-start align-items-center">
                        <div class="mr-1">
                          <img src="<?= $SERVER_NAME . $leader->avatar ?>" class="img-circle" style="width: 3rem; height: 3rem" alt="User Image">
                        </div>
                        <div>
                          <?= $leaderName ?>
                        </div>
                      </div>
                    </div>
                  </fieldset>
                  <fieldset>
                    <legend class="text-navy">Project Members:</legend>
                    <div class="pl-4">
                      <?php
                      foreach ($memberData as $member) :
                        $memberName = ucwords("$member->first_name " . $member->middle_name[0] . ". $member->last_name");
                      ?>
                        <div class="ml-2 mt-2 mb-2 d-flex justify-content-start align-items-center">
                          <div class="mr-1">
                            <img src="<?= $SERVER_NAME . $member->avatar ?>" class="img-circle" style="width: 3rem; height: 3rem" alt="User Image">
                          </div>
                          <div>
                            <?= $memberName ?>
                          </div>
                        </div>
                      <?php endforeach; ?>
                    </div>
                  </fieldset>
                  <fieldset>
                    <legend class="text-navy"> Document:</legend>
                    <div class="pl-4">
                      <div class="embed-responsive embed-responsive-4by3">
                        <iframe src="<?= $SERVER_NAME . $data->project_document ?>#embedded=true&toolbar=0&navpanes=0" class="embed-responsive-item"></iframe>
                      </div>
                    </div>
                  </fieldset>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-gradient-secondary" onclick="return window.open('./preview-document?d=<?= urlencode($data->project_document) ?>')">
                    Open document in new tab
                  </button>
                  <button type="button" class="btn btn-primary btn-gradient-primary" <?= $feedback->adviser->isApproved == "true" ? "disabled" : "" ?>>
                    File feedback
                  </button>
                  <button type="button" class="btn btn-success btn-gradient-success" <?= $feedback->adviser->isApproved == "true" ? "disabled" : "" ?> onclick="handleApproved('<?= $data->id ?>')">
                    Approve
                  </button>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <!-- /.card-body -->
</div>