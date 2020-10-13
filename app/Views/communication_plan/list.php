<?php if ( ! $companyId): ?>
  <div class="card card-primary card-outline">
    <div class="card-body">
      <?php echo trad(
          'Please select the company for which to display the communication plan'
      ) ?>
    </div>
  </div>
<?php else: ?>
  <div class="card card-primary card-outline">
    <div class="card-header"><?php echo trad('Filters'); ?></div>
    <div class="card-body">
      <form method="post" action="<?= route_to('communicationplan_list') ?>">
        <div class="row"> 
            <div class="col-sm-5">
              <span class="input-group">
                  <input type="date" name="startDate" id="startDate" data="<?php echo $startDateSelected; ?>" class="form-control" placeholder="dd/mm/aaaa">
                  <input type="date" name="endDate" id="endDate" data="<?php echo $endDateSelected; ?>" class="form-control" placeholder="dd/mm/aaaa">
                  <span class="input-group-append">
                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                  </span>
              </span>
            </div>
            <?php companySelector(false, 'col-sm-3', false); ?>
            <input type="hidden" name="companySelected" id="companySelected" positionCompany="<?php echo $positionCompany; ?>" value="<?php echo $_SESSION['current_sub_company']; ?>" >
            <div class="col-sm-2">
              <div class="form-group">
                <select name="users" class="custom-select" id="users" data="<?php echo $usersSelected; ?>">
                    <option <?php if ('ALL' == $usersSelected) { ?>selected<?php } ?> value="ALL">
                      <?php echo trad('--ALL--'); ?>
                    </option>
                  <?php foreach ($users as $user) { ?>
                    <option <?php if ($user['sender'] == $usersSelected) { ?>selected<?php } ?> value="<?php echo $user['sender']; ?>">
                      <?php echo $user['sender']; ?>
                    </option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-sm-2 text-right">
              <button type="submit" class="btn btn-primary mb-3" >
                <i class="nav-icon fas fa-search"></i> <?php echo trad('Search') ?>
              </button>
            </div>
        </div>
      </form>
    </div>
  </div>
  <div class="card card-primary card-outline">
    <div class="card-body">
      <div class="row">
        <?php if (isMemberAdmin()) { ?>
          <a href="#" data-toggle="modal" data-target="#externeCampaignModal" id="addOperation" class="btn btn-success mb-3" style="max-height: 38px;"><i class="nav-icon fas fa-plus"></i> Ajouter une opération</a>
        <?php } ?>
        <div id="calendar" data-communication-plan="<?php echo htmlentities($communicationPlans, ENT_QUOTES, 'UTF-8'); ?>">
        </div>
      </div>
      <div class="row" style="margin:20px!important">
        <div style="background-color: #ff9f89; width:20px; height: 20px; margin-right:5px"></div> <?php echo trad('National campaign'); ?>
        <div style="background-color: #FFA500; width:20px; height: 20px; margin-left:20px; margin-right:5px"></div> <?php echo trad('Regional campaign'); ?>
        <div style="background-color: #3a87ad; width:20px; height: 20px; margin-left:20px; margin-right:5px"></div> <?php echo trad('Local campaign'); ?>
      </div>
    </div>
  </div>

  <?php echo view('communication_plan/modal.php'); ?>
  
<?php endif; ?>

