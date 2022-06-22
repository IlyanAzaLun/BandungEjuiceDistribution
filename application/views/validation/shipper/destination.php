<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            /** 
                Set the margins of the page to 0, so the footer and the header
                can be of the full height and width !
             **/
            @page {
                margin: 10px;
                font-family: helvetica;
            }
            /** Define now the real margins of every page in the PDF **/
            body {
                margin-top: 2px;
                margin-left: 2px;
                margin-right: 2px;
                margin-bottom: 2px;
            }
            /** Define the footer rules **/
            footer {
                position: fixed; 
                bottom: 0cm; 
                left: 0cm; 
                right: 0cm;
                height: 2cm;

                /** Extra personal styles **/
                background-color: #03a9f4;
                color: white;
                text-align: center;
                line-height: 1.5cm;
            }
            main{                
                top: 0px;
                left: 0px; 
                right: 0px;
                border-collapse: collapse;
                width: 100%;
            }
            table{
                font-size: 14px;
                width: 100%;
            }
            th, td{
                border: 1px solid;
                padding: 10px;
            }
            .text-right{
                text-align: right;
            }
            .text-center{
                text-align: center;
            }
            .text-left{
                text-align: left;
            }
            .text-top{
                vertical-align: top;
            }
        </style>
    </head>
    <?php for($i = 1; $i <= $loop?$loop:0; $i++):?>
    <body>
        <main>
            <table>
                <thead>
                    <tr class="text-right">
                        <!-- <th rowspan="4" class="text-center"><img src="<?php echo url('uploads/company/').setting('company_icon')?>" alt="" width="50px"></th> -->
                        <th class="text-left" colspan="2">PENERIMA: <?=$customer->owner_name?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th colspan="2" class="text-top text-left">
                            
                            <?php echo $customer->address; ?>
                            <?php echo ($customer->province)?"$customer->province, ":false;?>
                            <?php echo ($customer->city)?"$customer->city, ":false;?>
                            <?php echo ($customer->sub_district)?"$customer->sub_district, ":false;?>
                            <?php echo ($customer->village)?"$customer->village, ":false;?><br>
                            
                            <?php echo ($customer->contact_phone)?$customer->contact_phone:false?>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-top text-left">
                            <?php $expedition = $invoice->services_expedition?" - $invoice->services_expedition":''?>
                            <?php $payment = $invoice->type_payment_shipping?" - $invoice->type_payment_shipping":''?>
                            EKSPEDISI: <?= "$invoice->expedition$expedition$payment"; ?><br>
                            KOLI: <?="$i/$invoice->pack"?>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-top text-left">
                            PENGIRIM: <?= $customer->note ?>
                        </th>
                    </tr>
                </tbody>
            </table>
        </main>
    </body>
    <?php endfor; ?>
</html>