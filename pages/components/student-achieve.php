<?php
$document = getSubmittedDocuments($user);
?>
<div class="card card-outline card-primary shadow rounded-0">
  <div class="card-header">
    <h3 class="card-title">
      <h2>
        <strong>
          <?= ucwords($document->title) ?>
        </strong>
      </h2>
    </h3>
  </div>
  <div class="card-body rounded-0">
    <div class="container-fluid">
      <center>
        <img src="<?= $SERVER_NAME . $document->img_banner ?>" alt="Banner Image" id="banner-img" class="img-fluid border bg-gradient-dark">
      </center>
      <fieldset>
        <legend class="text-navy"> Type:</legend>
        <div class="pl-4">
          <?php
          $typeQ = mysqli_query(
            $conn,
            "SELECT * FROM types WHERE id=$document->type_id"
          );
          echo mysqli_num_rows($typeQ) > 0 ? mysqli_fetch_object($typeQ)->name : "";
          ?>
        </div>
      </fieldset>
      <fieldset>
        <legend class="text-navy"> Year:</legend>
        <div class="pl-4">
          <?= $document->year ?>
        </div>
      </fieldset>
      <fieldset>
        <legend class="text-navy">Description:</legend>
        <div class="pl-4">
          <?= nl2br($document->description) ?>
        </div>
      </fieldset>
      <fieldset>
        <legend class="text-navy">Project Leader:</legend>
        <div class="pl-4">
          <div class="ml-2 mt-2 mb-2 d-flex justify-content-start align-items-center">
            <div class="mr-1">
              <img src="<?= $SERVER_NAME . $user->avatar ?>" class="img-circle" style="width: 3rem; height: 3rem" alt="User Image">
            </div>
            <div>
              <?= ucwords("$user->first_name " . $user->middle_name[0] . ". $user->last_name") ?>
            </div>
          </div>
        </div>
      </fieldset>
      <fieldset>
        <legend class="text-navy">Project Members:</legend>
        <div class="pl-4">
          <?php
          $memberData = json_decode(getMemberData($user->group_number, $user->id));
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
            <iframe src="<?= $SERVER_NAME . $document->project_document ?>#embedded=true&toolbar=0&navpanes=0" class="embed-responsive-item" id="pdfPreview" allowfullscreen></iframe>
          </div>
        </div>
      </fieldset>
    </div>
  </div>
</div>