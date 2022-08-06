<!DOCTYPE html>
<html>
<head>
<style>
    *{
        font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;}
    p{
        font-size: 12px;}
    .center {
        text-align: center;}
    .justify{
        text-align: justify;
        text-justify: auto;}
    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;}
    td, th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 1px;}
    tr:nth-child(even) {
        background-color: #dddddd;}
    
    .footer {
        margin-left: 0;}
    .whosender {
        float: right;
        width: 50%;}
    .whoreceive{
        float: left;
        width: 50%;}
</style>
</head>
<body>
    <div class="center">
        <h4>BANDUNG EJUICE DISTRIBUTION</h4>
        <h5>
            Jl. Sukanegara No.73 & 73, RT. 001 / RW.08<br>
            Kelurahan Antapani Kidul, Kecamatan Antapani<br>
            Bandung, Jawa Barat - 40291<hr>
        </h5>
        <div style="font-size: 12px;" class="justify">Nomor </div>
        <div style="font-size: 12px;" class="justify">Tanggal </div>
        <div style="font-size: 12px;" class="justify">Kepada </div>
        <div style="font-size: 12px;" class="justify">Alamat </div>
        <br>
        <div style="font-size: 12px;" class="justify">Dengan hormat,</div>
        <div style="font-size: 12px;" class="justify">Dengan surat ini kami mengirimkan barang dengan rincian sebagai berikut :</div>
        <br>
        <table style="font-size: 12px;" class="table">
            <thead>
                <tr>
                    <th width="1%">No</th>
                    <th>Nama Barang</th>
                    <th width="15%">Jumlah Barang</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contens as $key => $value):?>
                <tr>
                    <td><?=$key+1?></td>
                    <td><?=$value->item_name?></td>
                    <td><?=$value->item_quantity?></td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <th colspan="2">Total Jumlah Barang</th>
                    <th>10</th>
                </tr>
            </tbody>
        </table>
    </div>
    <br>
    <div class="footer">
        <div class="whoreceive">
            <div style="font-size: 12px;" class="center">Yang Menerima,<br><br><br><br><br><br><br><br>Pt.asal</div>
        </div>
        <div class="whosender">
            <div style="font-size: 12px;" class="center">Yang Meyerahkan,<br><br><br><br><br><br><br><br>(BANDUNG EJUICE DISTRIBUTION)</div>
        </div>
    </div>
</body>
</html>
