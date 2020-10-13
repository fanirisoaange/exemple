<style type="text/css">
    table { 
        width: 100%; 
        color: #717375; 
        font-family: helvetica; 
        line-height: 5mm; 
        border-collapse: collapse; 
    }
    h2 { margin: 0; padding: 0; }
    p { margin: 5px; }
 
    .border th { 
       
        color: white; 
        background: rgb(14, 112, 154); 
        padding: 5px; 
        font-weight: normal; 
        font-size: 10px; 
        text-align: center; 
        }
    .border td { 
        border: 1px solid #CFD1D2; 
        padding: 5px 10px; 
        font-size: 10px;
        text-align: center; 
    }
    .no-border { 
        border-right: 1px solid #CFD1D2; 
        border-left: none; 
        border-top: none; 
        border-bottom: none;
    }
    .no-border-r {
        border: 1px solid #CFD1D2; 
        border-top: none;
        border-right: none;
    }
    .space { padding-top: 120px; }
 
    .10p { width: 10%; }
    .15p { width: 15%; } 
    .25p { width: 25%; }
    .30p { width: 30%; } 
    .40p { width: 40%; } 
    .50p { width: 50%; } 
    .60p { width: 60%; }
    .70p { width: 70%; }
    .75p { width: 75%; }

</style>

<?php $total = 1; $total_tva = 1; ?>

<page backtop="10mm" backleft="10mm" backright="10mm" backbottom="10mm" footer="page;">
 
    <page_footer>
        
    </page_footer>
 
    <table style="vertical-align: top;">
        <tr>    
            <td class="70p"><img src="https://plateforme.cardata.fr/assets/img/logo-cardata-black.png"></td>
            <td class="30p"><h2><?= trad('Order #') . $order['id']; ?></h2></td>
            
        </tr>
        <tr>
            <td class="70p"><br />
            </td>
            <td class="30p">
                <br />
            </td>
        </tr>
      
        <tr>
            <td class="70p">
                <strong><?php echo $order['from']['fiscal_name']; ?></strong><br />
                <?php echo $order['from']['address_1']; ?><br />
                 <?php echo $order['from']['zip_code'] . " " . $order['from']['city_display']; ?><br />
                <?= $order['from']['phone_number']; ?>
                <br />
                <?php echo $order['from']['email']; ?>
            </td>
                 
            <td class="30p">
                <strong><?php echo $order['to']['fiscal_name']; ?></strong><br />
                <?php echo $order['to']['address_1']; ?><br />
                 <?php echo $order['to']['zip_code'] . " " . $order['to']['city_display']; ?><br />
                <?= $order['to']['phone_number']; ?>
                <br />
                <?php echo $order['to']['email']; ?>
            </td>
        </tr>
    </table>
 
    <table style="margin-top: 50px;">
        <tr>
            <td class="50p"></td>
            <td class="50p" style="text-align: right;"> <?= trad('Issued ') . $order['order_at']; ?></td>
        </tr>
    </table>
 
    <table style="margin-top: 30px;" class="border">
        <thead>
            <tr>
                <th class="10p"><?= trad('Order'); ?></th>
                <th class="40p"><?= trad('Product'); ?></th>
                <th class="10p"><?= trad('Quantity'); ?></th>
                <th class="10p"><?= trad('Unity'); ?></th>
                <th class="15p"><?= trad('Price'); ?></th>
                <th class="15p"><?= trad('Subtotal');?></th>
            </tr>
        </thead>
        <tbody>
            <?php $order['subtotal'] = 0.00 ?>
            <?php foreach($order['orderProducts'] as $o1) { ?>
                <tr>
                    <td class="10p">#<?= $o1['order_id']; ?></td>
                    <td class="40p" style="font-size: 9px"><?= $o1['name']; ?></td>
                    <td class="10p"><?= $o1['quantity']; ?></td>
                    <td class="10p"><?= product_price_type($o1['product_price_type']); ?></td>
                    <td class="15p"><?= $o1['price']; ?>€</td>
                    <td class="15p"><?= $o1['quantity'] * $o1['price']; ?>€</td>
                </tr>
                <?php $order['subtotal'] = $order['subtotal'] + ($o1['quantity'] * $o1['price']) ?>
            <?php } ?>
           
          
 
            <tr>
                <td colspan="4" style="text-align: left" class="no-border">
                </td>
                <td style="text-align: center;" rowspan="3"><strong><?= trad('Total'); ?>:</strong></td>
                <td><?= trad('HT'); ?>: <?php echo $order['subtotal']; ?>€</td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: left" class="no-border"></td>
                <td><?= trad('TVA'); ?>: <?php echo $order['vat']; ?>€</td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: left" class="no-border">
                </td>
                <td><?= trad('TTC');?>: <?php echo ($order['subtotal'] + (0.2 * $order['subtotal'])) ?> €</td>
            </tr>
        </tbody>
    </table>
 
</page>
