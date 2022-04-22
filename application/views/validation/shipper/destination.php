<?php 
$destination = "$customer->address $customer->village $customer->subdistric $customer->city $customer->province $customer->zip";
$contact = "$customer->contact_phone $customer->contact_mail";
?>
<html>
    <head>
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
    <body>
        <main>
            <table>
                <thead>
                    <tr class="text-right">
                        <th rowspan="3" class="text-center"><img src="https://external-preview.redd.it/6l63BffeDoLRZw5EiCuApqXRMrViJK5RhHjnD1eEQ0M.jpg?auto=webp&s=34697fb3fc0b37b6d78c4859f99472bf5ac2b50e" alt="" width="50px"></th>
                        <th>Nama Toko</th>
                        <th>Nama Pemilik</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th class="text-top text-left"><?=$customer->store_name?></th>
                        <th class="text-top text-left"><?=$customer->owner_name?></th>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-top text-left">
                            Alamat: <?=$destination?><br>
                            Kontak: <?=$contact?>
                        </th>
                    </tr>
                </tbody>
            </table>
        </main>
    </body>
</html>