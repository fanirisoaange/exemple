 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1><?php echo trad('Read'); ?></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo trad('Home'); ?></a></li>
              <li class="breadcrumb-item active"><?php echo trad('Read'); ?></li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3">
            <a href="/inbox" class="btn btn-primary btn-block mb-3"><?php echo trad('Back To Inbox'); ?></a>

           <?php include('nav.php'); ?>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        <div class="col-md-9">
          <div id="cardRead" class="card card-primary card-outline">
           <div class="card-header">
              <h3 class="card-title"><?php echo trad('Read Mail'); ?></h3>

              <div class="card-tools">
                <a href="#" class="btn btn-tool" data-toggle="tooltip" title="Previous"><i class="fas fa-chevron-left"></i></a>
                <a href="#" class="btn btn-tool" data-toggle="tooltip" title="Next"><i class="fas fa-chevron-right"></i></a>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
              <div class="mailbox-read-info"  style="display:flex; align-items:center; justify-content: space-between; font-size: 90%; opacity: 0.9">
                <div style="display: flex; flex-direction: column">
                  <h5 style=""><?php echo ucfirst($message->subject); ?></h5>
                <h6><?php if ($message->type == "sent") {
                    echo "To: " . $message->recipients;
                } else {
                    echo 'From: ' . $message->sender;
                }?></h6>
               </div>

                  <span class="mailbox-read-time float-right"><?php echo date_format(date_create($message->send_at), "d M Y h:i a"); ?></span></h6>
              </div>
              <!-- /.mailbox-read-info -->
              <div class="mailbox-controls with-border text-center">
                <div class="btn-group">
                  <button type="button" data-href="<?php echo base_url();?>/inbox/deleteOne/<?php echo $message->id; ?>" class="deleteBtn btn btn-default btn-sm" data-toggle="tooltip" data-container="body" title="Delete">
                    <i class="far fa-trash-alt"></i></button>
                  <a href="<?php echo base_url(); ?>/inbox/replyTo/<?php echo $message->id; ?>"><button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-container="body" title="Reply">
                    <i class="fas fa-reply"></i></button></a>
                  <a href="<?php echo base_url(); ?>/inbox/forward/<?php echo $message->id; ?>"><button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-container="body" title="Forward">
                    <i class="fas fa-share"></i></button></a>
                </div>
                <!-- /.btn-group -->
                <button type="button" class="btnPrint btn btn-default btn-sm" data-toggle="tooltip" title="Print">
                  <i class="fas fa-print"></i></button>
                <button type="button" data-id="<?= $message->id; ?>" data-fav="<?= $message->favorite; ?>"class="btnFavorite btn btn-default btn-sm" data-toggle="tooltip" title="Print">
                  <i  class="fas fa-star <?php if($message->favorite) echo 'text-warning'; ?>"></i></button>
              </div>
              <!-- /.mailbox-controls -->
              <div class="mailbox-read-message">
                <?php echo $message->content; ?>
              </div>
              <!-- /.mailbox-read-message -->
            </div>
            <!-- /.card-body -->
            <div <?php if(!$message->attachments) echo 'style="display: none"'; ?> class="card-footer bg-white">
              <ul  class="mailbox-attachments d-flex align-items-stretch clearfix">
                <?php if($message->attachments) { 
                  foreach($message->attachments as $a) { ?>
                    <li>
                  <?php switch($a->type) { 
                    case 'pdf': ?>
                    <span class="mailbox-attachment-icon"><i class="far fa-file-pdf"></i></span>
                    <?php break; 
                    case 'doc':case'docx': ?>
                    <span class="mailbox-attachment-icon"><i class="far fa-file-word"></i></span>
                    <?php break;
                    case 'bmp':case'gif':case'jpg':case'jpeg':case'png':case'jfif':?>
                    <span class="mailbox-attachment-icon has-img" style="height:100%"><img src="<?php echo $a->url; ?>" alt=""></span>
                    <?php break; } ?>

                  <div class="mailbox-attachment-info">
                    <a href="<?php echo $a->url; ?>" class="mailbox-attachment-name"><i class="fas fa-<?php echo (in_array($a->type, ['jpg', 'png', 'jpeg', 'gif', 'bmp', 'jfif'])) ? 'camera' : 'paperclip'; ?>" download></i> <?php echo $a->name; ?></a>
                        <span class="mailbox-attachment-size clearfix mt-1">
                          <span><?php echo $a->size; ?></span>
                          <a href="<?php echo $a->url; ?>" download class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
                        </span>
                  </div>
                </li>
                    <?php } }?>
              
              </ul>
            </div>
            <!-- /.card-footer -->
            <div class="card-footer">
              <div class="float-right">
                <a href="<?php echo base_url(); ?>/inbox/replyTo/<?php echo $message->id; ?>"><button type="button" class="btn btn-default"><i class="fas fa-reply"></i> <?php echo trad('Reply'); ?></button></a>
                <a href="<?php echo base_url(); ?>/inbox/forward/<?php echo $message->id; ?>"><button type="button" class="btn btn-default"><i class="fas fa-share"></i> <?php echo trad('Forward'); ?></button></a>
              </div>
              <button type="button" data-href="<?php echo base_url();?>/inbox/deleteOne/<?php echo $message->id; ?>" class="deleteBtn btn btn-default"><i class="far fa-trash-alt"></i> <?php echo trad('Delete'); ?></button>
              <button type="button" class="btnPrint btn btn-default"><i class="fas fa-print"></i> <?php echo trad('Print'); ?></button>
            </div>
            <!-- /.card-footer -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>