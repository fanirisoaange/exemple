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
            <td class="30p"><h2><?= trad('Invoice #') . $invoice['id']; ?></h2></td>
            
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
                <strong><?php echo $invoice['from']['fiscal_name']; ?></strong><br />
                <?php echo $invoice['from']['address_1']; ?><br />
                 <?php echo $invoice['from']['zip_code'] . " " . $invoice['from']['city_display']; ?><br />
                <?= $invoice['from']['phone_number']; ?>
                <br />
                <?php echo $invoice['from']['email']; ?>
            </td>
                 
            <td class="30p">
                <strong><?php echo $invoice['to']['fiscal_name']; ?></strong><br />
                <?php echo $invoice['to']['address_1']; ?><br />
                 <?php echo $invoice['to']['zip_code'] . " " . $invoice['to']['city_display']; ?><br />
                <?= $invoice['to']['phone_number']; ?>
                <br />
                <?php echo $invoice['to']['email']; ?>
            </td>
        </tr>
    </table>
 
    <table style="margin-top: 50px;">
        <tr>
            <td class="50p"></td>
            <td class="50p" style="text-align: right;"> <?= trad('Issued ') . $invoice['invoice_date']; ?></td>
        </tr>
        <tr>
        
        </tr>
    </table>
 
    <table style="margin-top: 30px;" class="border">
        <thead>
            <tr>
                <th class="10p"><?= trad('Order'); ?></th>
                <th class="50p"><?= trad('Product'); ?></th>
                <th class="10p"><?= trad('Quantity'); ?></th>
                <th class="15p"><?= trad('CPM'); ?></th>
                <th class="15p"><?= trad('Subtotal');?></th>
            </tr>
        </thead>
        <tbody>
             <?php foreach($invoice['orders'] as $o1) { foreach($o1 as $o) { ?>
                    <tr>
                        <td class="10p">#<?= $o['order_id']; ?></td>
                        <td class="50p" style="font-size: 9px"><?= $o['name']; ?></td>
                        <td class="10p"><?= $o['quantity']; ?></td>
                        <td class="15p"><?= $o['price']; ?>€</td>
                        <td class="15p"><?= $o['quantity'] * $o['price']; ?>€</td>
                    </tr>
                    <?php } }?>
           
          
 
            <tr>
                <td colspan="3" style="text-align: left" class="no-border">
                    
                </td>
                <td style="text-align: center;" rowspan="3"><strong><?= trad('Total'); ?>:</strong></td>
                <td><?= trad('HT'); ?>: <?php echo $invoice['subtotal']; ?>€</td>
            </tr>
            <tr>

                <td colspan="3" style="text-align: left" class="no-border">
                    <?php if($invoice['status'] == 1) { ?><strong width=100> <?= trad('Payment method'); ?> </strong>
                    <img height=30 src="data:image/png;base64,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">
                    <?php } ?>
                   
                </td>
                <td><?= trad('TVA'); ?>: <?php echo $invoice['vat']; ?>€</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: left" class="no-border">
                     <?php if($invoice['status'] == 1) { ?> <strong style="width: 300px"> <?= trad('Paid on'); ?></strong> <?php } ?>
                </td>
                <td><?= trad('TTC');?>: <?php echo $invoice['total'] ?> €</td>
            </tr>
        </tbody>
    </table>
 
</page>
