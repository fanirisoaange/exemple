<!DOCTYPE html> 
<html lang="<?= session()->lang ?>" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?php echo $title; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="author" content="AV" />
        <meta name="Description" content="<?php echo $metadescription; ?>" />
        <meta name="viewport" content="initial-scale=1.0; maximum-scale=1.0" />
        <?php if (!$nofollow && (!empty($_SERVER['HTTP_HOST']) && !preg_match('/test./', $_SERVER['HTTP_HOST']) && !preg_match('/local./', $_SERVER['HTTP_HOST']))): ?>
            <meta name="robots" content="index, follow" />
        <?php else: ?>
            <meta name="robots" content="noindex, nofollow" />
        <?php endif; ?>
        <?php if (file_exists(ROOTPATH . ASSETS . 'img/favicon.ico')): ?>
            <link rel="icon" href="<?= ASSETS . 'img/favicon.ico' ?>" />
        <?php endif; ?>
        <?php if (isset($css_min) && !empty($css_min)): ?>  
            <?php echo trim($css_min) . "\n"; ?>
        <?php else: ?>
            <?php foreach ($css as $url): ?>
                <link rel="stylesheet" type="text/css" <?php if(stristr($url,'fullcalendar.print.min.css')!=false){ ?>media="print"<?php } ?> href="<?php echo $url; ?>" />
            <?php endforeach; ?> 
        <?php endif; ?>

    </head>
    <body<?= isset($body_id) ? ' id="' . $body_id . '"' : '' ?><?= isset($body_class) ? ' class="' . $body_class . '"' : '' ?>>

        <?php
        ## HEADER ##
        if ($top_content && !$content_only):
            if (is_array($top_content)):
                foreach ($top_content as $v) :
                    echo view($v);
                endforeach;
            else:
                echo view($top_content);
            endif;
        endif;
        ## CONTENT ##
        if ($content):
            echo view($content);
        endif;

        ## FOOTER ##
        if ($bottom_content && !$content_only):
            if (is_array($bottom_content)):
                foreach ($bottom_content as $v) :
                    echo view($v);
                endforeach;
            else:
                echo view($bottom_content);
            endif;
        endif;
        ?>   
        <!-- JS -->
       
        <!-- CONTENT JS -->
        <?= $this->renderSection('content_js'); ?>

        <?php if (isset($js_min) && !empty($js_min)): ?>
            <?= $js_min . "\n"; ?>
        <?php elseif (!$no_js): ?>
            <?php foreach ($js as $url): ?>
                <script type="text/javascript" src="<?php echo $url; ?>"></script>
            <?php endforeach; ?>
        <?php endif; ?>

        <script>
            function timeDifference(current, previous) {
                var msPerMinute = 60 * 1000;
                var msPerHour = msPerMinute * 60;
                var msPerDay = msPerHour * 24;
                var msPerMonth = msPerDay * 30;
                var msPerYear = msPerDay * 365;

                var elapsed = current - previous;

                if (elapsed < msPerMinute) {

                    var text = '<?=lang("View.sec_ago");?>';
                    return text.replace('%s', Math.round(elapsed/1000));   
                }

                else if (elapsed < msPerHour) {
                    var text = '<?=lang("View.min_ago");?>';
                    return text.replace('%s', Math.round(elapsed/msPerMinute)); 
                }

                else if (elapsed < msPerDay ) {
                    var text = '<?=lang("View.hours_ago");?>';
                    return text.replace('%s', Math.round(elapsed/msPerHour));   
                }

                else if (elapsed < msPerMonth) {     
                    var text = '<?=lang("View.day_ago");?>';
                    return text.replace('%s', Math.round(elapsed/msPerDay));   
                }

                else if (elapsed < msPerYear) {
                    var text = '<?=lang("View.mon_ago");?>';
                    return text.replace('%s', Math.round(elapsed/msPerMonth));   
                }

                else {
                    var text = '<?=lang("View.years_ago");?>';
                    return text.replace('%s', Math.round(elapsed/msPerYear));   
                }
            }

        </script>

		 <script type="text/javascript" src="/assets/js/notification.js"></script>
    </body>
</html>