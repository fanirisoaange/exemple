 <div class="card">
              <div class="card-header">
                <h3 class="card-title">Folders</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body p-0">
                <ul class="nav nav-pills flex-column">
                  <li class="nav-item <?php if($type == "inbox") echo "active"; ?>">
                    <a href="<?php echo base_url(); ?>/inbox/" class="nav-link">
                      <i class="fas fa-inbox"></i> <?php echo trad('Inbox'); ?>
                      <?php if ($countUnread) { ?> <span class="badge bg-primary float-right"><?php echo $countUnread; ?></span> <?php } ?>
                    </a>
                  </li>
                  <li class="nav-item <?php if($type == "sent") echo "active"; ?>">
                    <a href="<?php echo base_url(); ?>/inbox/sent/"  class="nav-link">
                      <i class="far fa-envelope"></i> <?php echo trad('Sent'); ?>
                    </a>
                  </li>
                  <li class="nav-item <?php if($type == "draft") echo "active"; ?>">
                    <a href="<?php echo base_url(); ?>/inbox/draft/" class="nav-link">
                      <i class="far fa-file-alt"></i> <?php echo trad('Drafts'); ?>
                    </a>
                  </li>
                  <li class="nav-item" style="display: none">
                    <a href="#" class="nav-link">
                      <i class="fas fa-filter"></i> Junk
                      <span class="badge bg-warning float-right">65</span>
                    </a>
                  </li>
                  <li class="nav-item <?php if($type == "trash") echo "active"; ?>">
                    <a href="<?php echo base_url(); ?>/inbox/trash/" class="nav-link">
                      <i class="far fa-trash-alt"></i> <?php echo trad('Trash'); ?>
                    </a>
                  </li>
                   
                </ul>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <div class="card" style="display: none">
              <div class="card-header">
                <h3 class="card-title">Labels</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <ul class="nav nav-pills flex-column">
                  <li class="nav-item">
                    <a class="nav-link" href="#"><i class="far fa-circle text-danger"></i> Important</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#"><i class="far fa-circle text-warning"></i> Promotions</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#"><i class="far fa-circle text-primary"></i> Social</a>
                  </li>
                </ul>
              </div>
              <!-- /.card-body -->
            </div>