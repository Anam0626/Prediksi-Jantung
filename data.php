<?php
//koneksi ke database
$db = mysqli_connect("localhost", "root", "", "heart");

//query untuk mengambil data dari tabel
$query = "SELECT * FROM dataset";
$result = mysqli_query($db, $query);

//menyimpan data dalam array
$data = array();
while ($row = mysqli_fetch_assoc($result)) {
$data[] = $row;
}

//inisialisasi nilai K (jumlah cluster yang diinginkan) dan iterasinya
$kluster = 3;
$iterasi = 0;
$maks_iterasi = 100;
$centroids = array();

// inisialisasi centroid awal secara acak
// $centroids = array(
//     array("age" => 29,"thalachh" => 202),
//     array("age" => 76,"thalachh" => 116)
// );
for ($i = 0; $i < $kluster; $i++) {
$rand_keys = array_rand($data);
$centroids[] = $data[$rand_keys];
}

// print_r($centroids);


//iterasi algoritma K-means
while($iterasi < $maks_iterasi ){
    //menghitung jarak antara setiap data dengan centroid
    $jarak = array();
    foreach ($data as $row) {
        $jarak_row = array();
        foreach ($centroids as $centroid) {
        $distance = sqrt(pow($row['age'] - $centroid['age'], 2) + pow($row['chol'] - $centroid['chol'], 2) + pow($row['fbs'] - $centroid['fbs'], 2));
        $jarak_row[] = $distance;
        }
        $jarak[] = $jarak_row;
    }

    // print_r($jarak);  

    //menentukan kluster untuk setiap data berdasarkan jarak terdekat
    $clusters = array_fill(0, $kluster, array());
    foreach ($jarak as $i => $row) {
        $min_jarak = min($row);
        $cluster_index = array_search($min_jarak, $row);
        $clusters[$cluster_index][] = $data[$i];
    }


    //menghitung ulang posisi centroid
    $new_centroids = array();
    foreach ($clusters as $cluster) {
        $cluster_size = count($cluster);
        $age_sum = 0;
        $chol_sum = 0;
        $fbs_sum = 0;
        foreach ($cluster as $data_point) {
        $age_sum += $data_point['age'];
        $chol_sum += $data_point['chol'];
        $fbs_sum += $data_point['fbs'];
        }
        $new_centroids[] = array('age' => $age_sum / $cluster_size, 'chol' => $chol_sum / $cluster_size, 'fbs' => $fbs_sum / $cluster_size);
    }

    // print_r($new_centroids);

    //jika posisi centroid tidak berubah, hentikan iterasi
    if ($centroids === $new_centroids) {
    break;
    } else {
    //simpan posisi centroid baru
    $centroids = $new_centroids;
    $iterasi++;
    }
}

asort($centroids);
$data_cluster = array();
foreach ($clusters as $cluster_index => $cluster) {
    foreach ($cluster as $data_point) {
        $data_cluster[] = array('Cluster ' . ($cluster_index + 1), $data_point['chol'], $data_point['age']);
    }
}
$json_data = json_encode($data_cluster);