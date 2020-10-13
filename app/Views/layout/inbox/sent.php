<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1><i class="nav-icon far fa-envelope"></i> <?php echo trad('Sent'); ?></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo trad('Home'); ?></a></li>
              <li class="breadcrumb-item active"><?php echo trad('Sent'); ?></li>
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
            <!-- /.card-body -->
          
          <!-- /.card -->
        </div>
        <!-- /.col -->

        <input type="hidden" id="pageType" value="sent">
        <div class="col-md-9">
          <div class="card card-primary card-outline">
             <?php if (count($messages)) { ?>
            <div class="card-header">
              <h3 class="card-title"><?php echo trad('Sent'); ?></h3>

              <div class="card-tools" style="display: none">
                <div class="input-group input-group-sm">
                  <input type="text" class="form-control" placeholder="Search Mail">
                  <div class="input-group-append">
                    <div class="btn btn-primary">
                      <i class="fas fa-search"></i>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
              <div class="mailbox-controls">
                <!-- Check all button -->
                <button type="button" class="btnCheck btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="deleteMultipleTrashBtn btn btn-default btn-sm"><i class="far fa-trash-alt"></i></button>
                  
                </div>
                <!-- /.btn-group -->
                
                <div class="float-right">
                 <?php echo($page-1)*15+1; ?>-<span class="currentCount"> <?php echo $currentCount; ?></span>/<span class="totalCount"><?php echo $totalCount; ?></span>
                   <?php if ($page == 1) { ?>
                      <button type="button" disabled class="btn btn-default btn-sm"><i class="fas fa-chevron-left"></i></button>
                      <?php } else { ?>
                        <a href="<?php echo base_url() . "/inbox/sent?page=" . ($page-1); ?>"> <button type="button"  class="btn btn-default btn-sm"><i class="fas fa-chevron-left"></i></button></a>
                      <?php }  if ($page * 15 >= $totalCount) { ?>
                      <button type="button" disabled class="btn btn-default btn-sm"><i class="fas fa-chevron-right"></i></button>
                      <?php } else { ?>
                        <a href="<?php echo base_url() . "/inbox/sent?page=" . ($page+1); ?>"> <button type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-right"></i></button></a>
                      <?php } ?>
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
                    <td style="display: none;" class="mailbox-star"><a href="#"><i  class="fas fa-star text-warning"></i></a></td>
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
                    <td class="mailbox-name"><a href="<?php echo base_url(); ?>/inbox/read/<?php echo $m['id']; ?>"><?php echo $m['recipient']; ?></a></td>
                    <td class="mailbox-subject"><b><?php echo $m["subject"]; ?></b> - <?php echo $m['content']; ?>
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
            <!-- /.card-body -->
            <div class="card-footer p-0">
              <div class="mailbox-controls">
                <!-- Check all button -->
                <button type="button" class="btnCheck btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="deleteMultipleTrashBtn btn btn-default btn-sm"><i class="far fa-trash-alt"></i></button>
                  
                </div>
                <!-- /.btn-group -->
                
                <div class="float-right">
                 <?php echo($page-1)*15+1; ?>-<span class="currentCount"> <?php echo $currentCount; ?></span>/<span class="totalCount"><?php echo $totalCount; ?></span>
                   <?php if ($page == 1) { ?>
                      <button type="button" disabled class="btn btn-default btn-sm"><i class="fas fa-chevron-left"></i></button>
                      <?php } else { ?>
                        <a href="<?php echo base_url() . "/inbox/sent?page=" . ($page-1); ?>"> <button type="button"  class="btn btn-default btn-sm"><i class="fas fa-chevron-left"></i></button></a>
                      <?php }  if ($page * 15 >= $totalCount) { ?>
                      <button type="button" disabled class="btn btn-default btn-sm"><i class="fas fa-chevron-right"></i></button>
                      <?php } else { ?>
                        <a href="<?php echo base_url() . "/inbox/sent?page=" . ($page+1); ?>"> <button type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-right"></i></button></a>
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

