<?php if ($companyId) { ?>
    <div class="row">
        <?php companySelector(); ?>
        <div class="col-6 text-right">
            <a href="<?= route_to('blacklistfile_upload') ?>"
               class="btn btn-success mb-3">
                <i class="nav-icon fas fa-plus"></i> <?php echo trad(
                    'Upload'
                ) ?>
            </a>
            <a href="<?= route_to('blacklistfile_delete') ?>"
                onclick="ajaxBlacklistFileDelete(event, '<?php echo trad(
                   "This operation is irreverssible. Are you sure you want to delete blacklist file of this company ?"
                ); ?>', '<?php echo trad(
                   "Deletion OK"
                ); ?>', '<?php echo trad(
                   " lines deleted"
                ); ?>', '<?php echo trad(
                   "Deletion Error"
                ); ?>');"
               class="btn btn-danger mb-3">
                <i class="nav-icon fas fa-trash"></i> <?php echo trad(
                    'Delete'
                ) ?>
            </a>
        </div>
    </div>
<?php } ?>

<div class="card card-primary card-outline">
    <div class="card-body">
        <?php if ( ! $companyId): ?>
            <?php echo trad(
                'Please select the company for which to display the backlist file'
            ) ?>
        <?php else: ?>
            <table id="blacklistFiles"
                   class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th><?php echo trad('Backlist ID') ?></th>
                    <th><?php echo trad('Name') ?></th>
                    <th><?php echo trad('Upload Date') ?></th>
                    <th><?php echo trad('Send Date') ?></th>
                    <th><?php echo trad('Number of lines') ?></th>
                    <th><?php echo trad('Status') ?></th>
                    <th><?php echo trad('Action') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($blacklistFiles as $blacklistFile) { ?>
                    <?php $blacklistFileStatus = (int)$blacklistFile['status']; ?>
                    <tr>
                        <td><?php echo $blacklistFile['id']; ?></td>
                        <td><?php echo $blacklistFile['name']; ?></td>
                        <td><?php echo \DateTime::createFromFormat('Y-m-d H:i:s',$blacklistFile['upload_date'])->format('d/m/Y H:i:s'); ?></td>
                        <td><?php echo $blacklistFile['send_date'] ? \DateTime::createFromFormat('Y-m-d H:i:s',$blacklistFile['send_date'])->format('d/m/Y H:i:s'):null; ?></td>
                        <td><?php echo blacklistFileNbline($blacklistFile); ?></td>
                        <td>
                            <?php blacklistFileStatus($blacklistFileStatus); ?>
                        </td>
                        <td>

                            <a href="<?= route_to(
                                'blacklistfile_detail',
                                $blacklistFile["id"]
                            ); ?>"
                               title="<?php echo trad('View blacklist File') ?>"
                               class="btn btn-outline-warning"><i
                                        class="far fa-eye"></i>
                            </a>

                            <?php if ($blacklistFileStatus == \App\Enum\BlacklistFileStatus::ACTIF) { ?>
                            <a href="#"
                               onclick="ajaxBlacklistFileSend(event, <?php echo $blacklistFile["id"]; ?>, '<?php echo trad(
                               "Are you sure you want to send this blacklistFile ?"
                               ); ?>', '<?php echo trad(
                                   "Send success"
                               ); ?>', '<?php echo trad(
                                   " imported lines"
                               ); ?>', '<?php echo trad(
                                   "Send fail"
                               ); ?>');"
                               title="<?php echo trad('Send') ?>"
                               class="btn btn-outline-success"><i
                                        class="fas fa-envelope"></i>
                            </a>
                          <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
