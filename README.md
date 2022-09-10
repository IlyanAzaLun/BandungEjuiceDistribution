# 
    Development Goal
    to accomodate (support), each division will validate the activity other division.
    meanampung (menunjang) , memvalidasi kegiatan yang sedang berjalan, tiap divisi akan memvalidasi kegiatan divisi lain.
# 
    Flowmap
    • Input Item (Admin), jika pada pembelian barang belum tersedia. isi sesuai informasi barang...
    • Purchase (Admin), pembelian item dari supplier, untuk mengisi persediaan barang, dan menentukan harga modal pada tiap penjualn nantinya.
    • Order (Marketing), dalam pemesanan diharuskan barang tersedia. dann diharapkan sesuai dengan permintaan pelanggan. 
    • Confirmation item (Warehouse), bagian gudang memeriksa dan memisahkan barang yang telah di pesan oleh Marketing. (Lama pending di gudang)
    • Create Invoice (Marketing), jika bagian gudang sudah menyetujui invoice sudah sesuai dengan barang yang dipisahkan.    
    • Quality Control (Shipping), tentunya hasil dari gudang akan diperiksa terlebih dahulu oleh shipping untuk quality control.
    • Packing (Shipping), lolos dari quality control akan di pack. untuk pengiriman,    
    • Input Receipt (Shipping), input resi jika bagian shipping sudah melakukan pengiriman
    • Payment (Admin), membutuhkan konfirmasi admin dalam pembayaran penjualan dan pembelian item.
# 
    Feature
    • Manajemen Barang
    • Informasi Pembelian     : Informasi Pengembalian pemebelian
    • Informasi Penjualan     : Informasi Order
                              : Informasi Barang Terjual, (Spesific items or all)
                              : Informasi Laba (Spesifict user and add graft, adn date month)
                              : Informasi Pengembalian penjualan
    • Informasi Pengiriman
    • Informasi Pembayaran    : Piutang
                              : Hutang
    • Gudang Alamat           : Informasi Pelanggan
                              : Informasi Supplirs
    • Surat Jalan
    • Manajemen pengguna
    • Manajemen peran
    • Manajemen izin
    • Backups
# 
    NOTE
    kegiatan validasi akan memperlambat sebuah alur kerja...