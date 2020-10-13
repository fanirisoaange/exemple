<div class="content-wrapper">
  <input type="hidden" id="session_message" value="<?php echo $message; ?>">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1><i class="nav-icon far fa-envelope"></i> <?php echo trad(ucfirst($type)); ?></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo trad('Home'); ?></a></li>
              <li class="breadcrumb-item active"><?php echo trad(ucfirst($type)) ?></li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">
          <a href="/inbox/compose" class="btn btn-primary btn-block mb-3"><?php echo trad('Compose'); ?></a>

          <?php include('nav.php'); ?>
          <!-- /.card -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="card card-primary card-outline">
              <?php if (count($messages)) { ?> 
            <div class="card-header">
              <h3 class="card-title"><?php echo trad(ucfirst($type)); ?></h3>

              <div class="card-tools" <?php if($type != "inbox") echo 'style="display: none;"'; ?>>
                <div class="input-group input-group-sm">
                  <input type="text" id="searchMail" class="form-control" placeholder="<?php echo trad('Search Mail'); ?>">
                  <div class="input-group-append">
                    <div class="btn btn-primary" id="searchBtn"> 
                      <i class="fas fa-search"></i>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->

          
            <input type="hidden" id="pageType" value="<?php echo $type; ?>">
            <input type="hidden" id="mailboxPage" value=<?php echo $page; ?>>
            <div class="card-body p-0">
              <div class="mailbox-controls">
                <!-- Check all button -->
                <button type="button" class="btnCheck btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="<?php echo $type=='trash' ? 'deleteMultipleBtn' : 'deleteMultipleTrashBtn'; ?> btn btn-default btn-sm"><i class="far fa-trash-alt"></i></button>
                 
                </div>
                <!-- /.btn-group -->
                <button type="button" class="btnRefresh btn btn-default btn-sm"><i class="fas fa-sync-alt"></i></button>
                
                 <div class="paginationWrp float-right" <?php if(!($page > 1 || $totalCount >= 15)) echo 'style="display:none;"'; ?>>
                  <?php echo($page-1)*15+1; ?>-<span class="currentCount"> <?php echo $currentCount; ?></span>/<span class="totalCount"><?php echo $totalCount; ?></span>
                   <?php if ($page == 1) { ?>
                      <button type="button" disabled class="btn btn-default btn-sm"><i class="fas fa-chevron-left"></i></button>
                      <?php } else { ?>
                        <a href="<?php echo base_url() . "/inbox?page=" . ($page-1); ?>"> <button type="button"  class="btn btn-default btn-sm"><i class="fas fa-chevron-left"></i></button></a>
                      <?php }  if ($page * 15 >= $totalCount) { ?>
                      <button type="button" disabled class="btn btn-default btn-sm"><i class="fas fa-chevron-right"></i></button>
                      <?php } else { ?>
                        <a href="<?php echo base_url() . "/inbox?page=" . ($page+1); ?>"> <button type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-right"></i></button></a>
                      <?php } ?>
                  
                  <!-- /.btn-group -->
                </div>
                 
                <!-- /.float-right -->
              </div>
              <div class="table-responsive mailbox-messages">
                <table class="table table-hover table-striped">
                  
                  <tbody>
                    <tr id="mailboxTrSample" style="display: none">
                    <td>
                      <div class="icheck-primary">
                        <input type="checkbox" class="checkRow" data-id="" value="" id="check>">
                        <label for="check"></label>
                      </div>
                    </td>
                    <td class="mailbox-star"><a href="#"><i  class="fas fa-star text-warning"></i></a></td>
                    <td class="mailbox-name"><a 
                    href=""></a></td>
                    <td class="mailbox-subject"><b></b> - <span></span>
                    </td>
                    <td class="mailbox-attachment"><i class="fas fa-paperclip"></i> </td> 
                    <td class="mailbox-date"></td>
                  </tr>
                    <?php foreach ($messages as $m) { ?> 
                  <tr>
                    <td>
                      <div class="icheck-primary">
                        <input type="checkbox" class="checkRow" data-id="<?php echo $m['id']; ?>" value="" id="check<?php echo $m['id']; ?>">
                        <label for="check<?php echo $m['id']; ?>"></label>
                      </div>
                    </td>
                    <td class="mailbox-star"><a href="#"><?php if($m['favorite']) { ?> <i  class="fas fa-star text-warning"></i> <?php } ?></a></td>
                    <td class="mailbox-name"><a <?php if (!$m['seen']) {
                      echo 'style="font-weight:bold"';
                  } ?> href="<?php echo base_url() . ($type == "inbox" ? '/inbox/read/' : '/inbox/compose/') . $m['id']; ?>"> <?php if(isset($m['sender'])) echo $m['sender']; else if(isset($m['recipient'])) echo $m['recipient']; ?></a></td>
                    <td class="mailbox-subject"><b><?php echo $m['subject']; ?></b> - <span><?php echo $m['content']; ?></span>
                    </td>
                    <td class="mailbox-attachment"><?php if($m['attachments']) { ?><i class="fas fa-paperclip"></i><?php } ?> </td> 
                    <td class="mailbox-date"><?php echo $m['send_at']; ?></td>
                  </tr>
 
               <?php } ?>
                  </tbody>
                </table>
                <!-- /.table -->
              </div>
              <!-- /.mail-box-messages -->
            </div>
            <span id="noResults" style="margin: 0.9em; text-align: center; letter-spacing: 0.01px; display: none">No results</span>
            <!-- /.card-body -->
            <div class="card-footer p-0">
              <div class="mailbox-controls">
                <!-- Check all button -->
                <button type="button" class="btnCheck btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="<?php echo $type=='trash' ? 'deleteMultipleBtn' : 'deleteMultipleTrashBtn'; ?>  btn btn-default btn-sm"><i class="far fa-trash-alt"></i></button>
                 
                </div>
                <!-- /.btn-group -->
                <button type="button" class="btnRefresh btn btn-default btn-sm"><i class="fas fa-sync-alt"></i></button>
                <div class="paginationWrp float-right" <?php if(!($page > 1 || $totalCount >= 15)) echo 'style="display:none;"'; ?>>
                   <?php echo($page-1)*15+1; ?>-<span class="currentCount"> <?php echo $currentCount; ?></span>/<span class="totalCount"><?php echo $totalCount; ?></span>
                  <div class="btn-group">
                    <?php if ($page == 1) { ?>
                      <button type="button" disabled class="btn btn-default btn-sm"><i class="fas fa-chevron-left"></i></button>
                      <?php } else { ?>
                        <a href="<?php echo base_url() . "/inbox?page=" . ($page-1); ?>"> <button type="button"  class="btn btn-default btn-sm"><i class="fas fa-chevron-left"></i></button></a>
                      <?php }  if ($page * 15 >= $totalCount) { ?>
                      <button type="button" disabled class="btn btn-default btn-sm"><i class="fas fa-chevron-right"></i></button>
                      <?php } else { ?>
                        <a href="<?php echo base_url() . "/inbox?page=" . ($page+1); ?>"> <button type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-right"></i></button></a>
                      <?php } ?>
                  
                  </div>
                  <!-- /.btn-group -->
                </div>
           
                <!-- /.float-right -->
              </div>
            </div>
              <?php } else { ?>
              <h3 style="font-size: 115%; margin: auto; margin-top: 2em; margin-bottom: 2em; "><?php echo trad('No messages yet'); ?></h3>
            <?php } ?>
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>

