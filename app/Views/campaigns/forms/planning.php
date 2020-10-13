<?= form_open('', 'id="campaignForm"', ['action' => 'planning']) ?>
        <div class="container">
            <div class="row">
                <?php foreach(explode(',', $channel['channel_id']) as $key => $channel): ?>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header"><span>Channel <?= \App\Enum\campaignChannelType::getDescriptionById($channel); ?></span></div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="">Date d'envoye</label>
                                    <input type="text" class="form-control campaignDate" value="<?= $planning ? date('d-m-Y H:i:s', $planning[$key]['date_send']) : ''; ?>" name="dateSend[]">
                                </div>
                                <div class="form-group">
                                    <label for="">Volume</label>
                                    <input type="number" class="form-control changeVolume" value="<?= $planning ? $planning[$key]['volume'] : $segmentationVolume[$channel]; ?>" data-volume-max="<?= $segmentationVolume[$channel]; ?>" name="volume[]">
                                    <input type="hidden" value="<?= $channel; ?>" name="channel[]">
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-success" style="float:right;">Send</button>
                </div>
            </div>
        </div>
<?= form_close() ?>