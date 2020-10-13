<section class="content">
    <div class="container-fluid">
        <?php if ( ! $companyId): ?>
            <?php echo trad(
                'Please select the company for which to display the dashboard'
            ) ?>
        <?php else: ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">
                                    <?php echo trad('Period') ?> : 
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="button" id="btn-month" onclick="getCampaignByAjax(1)" class="btn btn-primary active"><?php echo trad('this month') ?></button>
                                        <button type="button" id="btn-quater" onclick="getCampaignByAjax(3)" class="btn btn-secondary"><?php echo trad('the last 3 months') ?></button>
                                        <button type="button" id="btn-year" onclick="getCampaignByAjax(12)" class="btn btn-secondary"><?php echo trad('this year') ?></button>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <?php echo trad('Customized') ?> :
                                    <span class="input-group">                          
                                        <input type="date" name="startDate" id="startDate" class="form-control" placeholder="dd/mm/aaaa">
                                        <input type="date" name="endDate" id="endDate" class="form-control" placeholder="dd/mm/aaaa">
                                        <span class="input-group-append">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </span>
                                    </span>
                                </div>
                                <div class="col-md-3 text-right" id="labelStartDateEndDate">
                                    <?php echo $labelStartDateEndDate; ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row" style="margin-top:20px;">
                                <?php echo trad('Filters') ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select name="users" class="custom-select" id="users" data="">
                                            <?php foreach ($users as $user) { ?>
                                                <option <?php if ($user['sender'] == $usersSelected) { ?>selected<?php } ?> value="<?php echo $user['sender']; ?>">
                                                    <?php echo $user['sender']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <?php companySelector(false, 'col-md-3', false); ?>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <select name="campaignChannelType" class="custom-select" id="campaignChannelType" data="">
                                            <option value="0">
                                              <?php echo trad('ALL') ?>
                                            </option>
                                            <option value="<?php echo App\Enum\campaignChannelType::EMAIL; ?>">
                                              <?php echo trad('MAIL') ?>
                                            </option>
                                            <option value="<?php echo App\Enum\campaignChannelType::SMS; ?>">
                                              <?php echo trad('SMS') ?>
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                            
                        <!-- /.row -->
                    </div>
                        <!-- ./card-body -->
                </div>
                <!-- /.card -->
            </div>
              <!-- /.col -->

            <!-- Info boxes -->
            <div class="row">
              <div class="col-12 col-sm-6 col-md-3" id="divMailSent">
                <div class="info-box">
                  <span class="info-box-icon bg-info elevation-1"><i class="fas fa-envelope"></i></span>

                  <div class="info-box-content">
                    <span class="info-box-text"><?php echo trad('Mails sent') ?></span>
                    <span class="info-box-number" id="qtyCampaignEmailSend"><?php echo $qtyCampaignEmailSend ?></span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              <!-- /.col -->
              <div class="col-12 col-sm-6 col-md-3" id="divSMSSent">
                <div class="info-box mb-3">
                  <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-mobile"></i></span>

                  <div class="info-box-content">
                    <span class="info-box-text"><?php echo trad('SMS sent') ?></span>
                    <span class="info-box-number" id="qtyCampaignSMSSend"><?php echo $qtyCampaignSMSSend ?></span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              <!-- /.col -->

              <!-- fix for small devices only -->
              <div class="clearfix hidden-md-up"></div>

              <div class="col-12 col-sm-6 col-md-3" style="display:none">
                <div class="info-box mb-3">
                  <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

                  <div class="info-box-content">
                    <span class="info-box-text"><?php echo trad('Leads generated') ?></span>
                    <span class="info-box-number">125 252</span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              <!-- /.col -->
              <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                  <span class="info-box-icon bg-success elevation-1"><i class="fas fa-envelope"></i></span>

                  <div class="info-box-content">
                    <span class="info-box-text"><?php echo trad('Budget spent') ?></span>
                    <span class="info-box-number" id="budget"><?php echo $budget; ?></span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row">
              <div class="col-md-6">
                <div class="card">
                  <div class="card-header">
                    <h5 class="card-title" id="title-card-piechart"><?php echo trad('Monthly campaign recap') ?></h5>

                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                      </button>
                      <div class="btn-group">
                        <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                          <i class="fas fa-wrench"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" role="menu">
                          <a href="#" class="dropdown-item"><?php echo trad('Action') ?></a>
                          <a href="#" class="dropdown-item"><?php echo trad('Another action') ?></a>
                          <a href="#" class="dropdown-item"><?php echo trad('Something else here') ?></a>
                          <a class="dropdown-divider"></a>
                          <a href="#" class="dropdown-item"><?php echo trad('Separated link') ?></a>
                        </div>
                      </div>
                      <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                      </button>
                    </div>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6">
                        <p class="text-center">
                          
                        </p>

                        <div class="chart"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                          <!-- Sales Chart Canvas -->
                          <canvas id="pieChart" 
                              qtyCampaignEmailExecuted="<?php echo $qtyCampaignEmailExecuted ?>"
                              qtyCampaignSMSExecuted="<?php echo $qtyCampaignSMSExecuted ?>"
                              legendLabelMail="<?php echo trad('Email campaigns executed: ') ?>" 
                              legendLabelSMS="<?php echo trad('SMS campaigns executed: ') ?>"
                              style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 487px;" 
                              width="487" height="250" class="chartjs-render-monitor">
                          </canvas>
                        </div>
                        <!-- /.chart-responsive -->
                      </div>
                    </div>
                    <!-- /.row -->
                  </div>
                  <!-- ./card-body -->
                </div>
                <!-- /.card -->
              </div>
              <!-- /.col -->
            </div>

            <div class="row" style="display:none">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <h5 class="card-title"><?php echo trad('PERFORMANCES') ?></h5>

                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                      </button>
                      <div class="btn-group">
                        <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                          <i class="fas fa-wrench"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" role="menu">
                          <a href="#" class="dropdown-item"><?php echo trad('Action') ?></a>
                          <a href="#" class="dropdown-item"><?php echo trad('Another action') ?></a>
                          <a href="#" class="dropdown-item"><?php echo trad('Something else here') ?></a>
                          <a class="dropdown-divider"></a>
                          <a href="#" class="dropdown-item"><?php echo trad('Separated link') ?></a>
                        </div>
                      </div>
                      <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                      </button>
                    </div>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-12">
                        <p class="text-center">
                          <strong><?php echo $labelLeadGenerated ?></strong>
                        </p>

                        <div class="chart"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                          <!-- Sales Chart Canvas -->
                          <canvas id="barChart" legendLabelLead="<?php echo trad('Leads generated') ?>"
                              style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 100%;" 
                              width="100%" height="250" class="chartjs-render-monitor">
                          </canvas>
                          <!-- <canvas id="salesChart" height="180" style="height: 180px; display: block; width: 680px;" width="680" class="chartjs-render-monitor"></canvas>
                        -->
                        </div>
                        <!-- /.chart-responsive -->
                      </div>

                    </div>
                    <!-- /.row -->
                  </div>
                  <!-- ./card-body -->
                  <div class="card-footer">
                    <div class="row">
                      <div class="col-sm-3 col-6">
                        <div class="description-block border-right">
                          <span class="description-percentage text-danger"></i> </span>
                        </div>
                        <!-- /.description-block -->
                      </div>
                    </div>
                  </div>
                  <!-- /.card-footer -->
                </div>
                <!-- /.card -->
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->

        <?php endif; ?>

    </div><!--/. container-fluid -->
</section>