<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title"><?= trad('All notification'); ?></h3>
    </div>
    <div class="card-body">
        <table  id="userList" class="table table-bordered table-hover">
            <thead>
            <tr>
                <th style="width: 5%"></th>
                <th style="width: 20%"><?= trad('Type', 'global'); ?></th>
                <th style="width: 55%">Message</th>
                <th style="width: 20%">Date</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if ( ! empty($notification)):
                foreach ($notification as $notif):
                  if($notif->notif_or_message == "message"){
                    ?>
                    <tr <?php if($notif->is_seen == 0) echo 'style="background: #efeaea;"'; ?>>
                        <td></td>
                        <td>
                            <div class="form-group">
                                <?= $notif->notif_or_message; ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <?= $notif->subject; ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <?= $notif->date_notif; ?>
                            </div>
                        </td>
                        
                    </tr>
          <?php
                  }else{ 
          ?>
                    <tr <?php if($notif->is_seen == 0) echo 'style="background: #efeaea;"'; ?>>
                        <td></td>
                        <td>
                            <div class="form-group">
                                <?=  $notif->notif_or_message; ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <?= notification_status($notif->notification_type)?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <?= $notif->date_notif; ?>
                            </div>
                        </td>
                        
                    </tr>

              <?php 
                  }
              endforeach;
              
            endif;
            ?>
            </tbody>
        </table>
    </div>
</div>
