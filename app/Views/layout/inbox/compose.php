 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1><?php echo trad('Compose'); ?></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo trad('Home'); ?></a></li>
              <li class="breadcrumb-item active"><?php echo trad('Compose'); ?></li>
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
            <a href="/inbox" class="btn btn-primary btn-block mb-3"><?php echo trad('Back to Inbox'); ?></a>

            <?php include('nav.php'); ?>
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <form action="<?php echo base_url(); ?>/inbox/send" enctype="multipart/form-data" method="post">

              <?php if (isset($mail)) { ?>
                <input type="hidden" id="draftTo" value="<?php echo $to; ?>">
                <?php if($type == "draft") { ?> <input type="hidden" id="mid" name="mid" value="<?php echo $mail->id; ?>"> <?php } ?>
              <?php } ?>
              <input type="hidden" id="formMethod" name="method" value="send">

              <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title"><?php echo trad('Compose New Message'); ?></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="form-group">
                   <select class="select2" multiple="multiple" id="composeTo" name="to[]" data-placeholder="<?php echo trad('To') . ':'; ?>" style="width: 100%;">
                    <?php foreach ($contacts as $c) { ?>
                      <option value="<?php echo $c["id"]; ?>"><?php echo $c["first_name"] . " " . $c["last_name"]; ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="form-group">
                  <input class="form-control" id="subject" name="subject" placeholder="<?php echo trad('Subject') .':'; ?>" <?php if (isset($mail)) {
                      echo "value='" . $mail->subject . "'";
                  } ?>>
                </div>
                <div class="form-group">
                    <textarea id="compose-textarea" name="content" class="form-control" style="height: 500px">
                     <?php if (isset($mail)) {
                        echo $mail->content;
                    } ?>
                    </textarea>
                </div>
                <div class="form-group">
                  <div class="btn btn-default btn-file">
                    <i class="fas fa-paperclip"></i> <?php echo trad('Attachment'); ?>
                   <input type="file" id="attachments" name="attachments[]" multiple>
                  </div>
                  <?php if($mail && $mail->attachments) { ?>
                    <input type="hidden" name="attachment" value='<?php echo $mail->att_string; ?>'> <?php } ?>
                  <div id="attachmentList" style="display:flex">
                      <?php if($mail && $mail->attachments) { foreach($mail->attachments as $att) {
                        ?> <span style="margin-right: 0.6em">
                          <strong><?php echo $att->name; ?></strong>
                          <?php echo $att->size; ?></span>
                          <?php 
                      }
                    }
                    ?>


                  </div>
                  <p class="help-block">Max. 32MB</p>
                </div>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <div class="float-right">
                  <button id="btnDraft" type="button" class="btn btn-default"><i class="fas fa-pencil-alt"></i> <?php echo trad('Draft'); ?></button>
                  <button id="btnSend" type="submit" class="btn btn-primary"><i class="far fa-envelope"></i> <?php echo trad('Send'); ?></button>
                </div>
                <button type="reset" class="btn btn-default"><i class="fas fa-times"></i> <?php echo trad('Discard'); ?></button>
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </form>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

