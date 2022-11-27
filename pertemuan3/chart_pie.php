<?php
// untuk memanggil file koneksi agar bisa tersambung/mengakses database
include "koneksi.php";
// variable array label untuk menampung list nama-nama bulan
$label = ["januari", "februari", "maret","april", "mei", "juni", "juli", "agustus", "september", "oktober", "november", "desember"];
// membuat looping for untuk perhitungan data penjualan perbulannya
for($bulan = 1; $bulan <= 13; $bulan++){
    // query untuk menghitung jumlah dari kolom amount pada tbl_penjualan
    $query = mysqli_query($conn, "SELECT SUM(amount) AS jumlah FROM tbl_penjualan WHERE MONTH(tgl_penjualan)='$bulan'");
    // untuk menyimpan hasil query
    $row = $query->fetch_array();
    // membuat array $jumlah_produk untuk menyimpan jumlah penjumlahan/kalkulasi setiap bulannya
    $jumlah_produk[] = $row['jumlah'];
}
?>
<!-- untuk menandakan document html -->
<!DOCTYPE html>
<!-- synstax ini berfungsi untuk menandakan bahwa code ditulis dengan bahasa inggris -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- code dibwh ini berfungsi untuk title bar pada browser -->
    <title>grafik bulan</title>
    <!-- untuk memanggil chartJS agar bisa membuat grafik -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div id="canvas-holder" style="width: 600px; margin-left: 390px; margin-top: 100px;">
    <!-- membuat sebuah object dengan tag canvas -->
    <canvas id="chart-area"></canvas>
</div>
<center>
<script>
    // menuliskan chart itu adalahdari id object yang dibuat
    var ctx = document.getElementById("chart-area").getContext('2d');
    var myChart = new Chart(ctx, {
        // type untuk menentukan bentuk diagram
        type: 'pie',
            data: {
                // menuliskan label dari chart, karena sebelumnya kita memiliki array dengan nama $jumlah_produk yang berisi daftar nama barang,
                // jadi tinggal gunakan perintah json_encode untuk konversi array $jumlah_produk menjadi bentuk json
                labels: <?php echo json_encode($label);?>,
                datasets: [{
                    // menuliskan bagian data dari chart, karena sbelumnya kita telah memiliki array dengan nama $jumlah_produk yang berisis jumlah dari penjualan
                    // jadi tinggal gunakan perintah json_encode untuk konversi array $jumlah_produk menjadi bentuk json
                    label: 'Grafik Penjualan',
                    data: <?php echo json_encode($jumlah_produk); ?>,
                    // untuk memodifikasi chart
                    backgroundColor: ['rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)'],
                    borderColor: ['rgba(255,99,132,1)',
                    'rgba(54,162,235,1)',
                    'rgba(255,206,86,1)',
                    'rgba(75,192,192,1)',
                    'rgba(255,122,111,1)'
                    ],
                    borderWidth: 5
                }]
            },
            options: {
                scales: {
                    yAxes:[{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            }
    });
</script>
</center>
</body>
</html>