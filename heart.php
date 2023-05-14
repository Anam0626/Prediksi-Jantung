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
    // print_r($centroids);

    // menampilkan hasil klasterisasi
    // foreach ($clusters as $cluster_index => $cluster) {
    //   echo "Cluster " . ($cluster_index + 1) . ":\n";
    //   foreach ($cluster as $data_point) {
    //     echo " - (" . $data_point['age'] . ", " . $data_point['thalachh'] . ")\n";
    //   }
    // }

    $age = $_POST['age'];
    $chol = $_POST['chol'];
    $fbs = $_POST['fbs'];

    $input = array($age, $chol, $fbs);
    // print_r($input);

    $jarak_input_centroid = array();
    foreach ($centroids as $centroid) {
        $distance = sqrt(pow($age - $centroid['age'], 2) + pow($chol - $centroid['chol'], 2) + pow($fbs - $centroid['fbs'], 2));
        $jarak_input_centroid[] = $distance;
    }

    $kluster_terdekat = array_search(min($jarak_input_centroid), $jarak_input_centroid);

    // print_r($kluster_terdekat+1);

    $kombinasi = array();
    $io = array();
    $output = array();
    foreach ($data as $row) {
        if ($row['age'] > 40) {
            $umur = 1;
        } else {
            $umur = 0;
        }
        if ($row['chol'] > 240) {
            $kolestrol = 1;
        } else {
            $kolestrol = 0;
        }
        $kombinasi[] = $umur.$kolestrol.$row['fbs'].$row['output'];
        $io[] = $umur.$kolestrol.$row['fbs'];
        $output[] = $row['output'];
    }

    // print_r($kombinasi);
    // print_r($io);
    // print_r($output);
    
    // Cari nilai yang setara dengan kombinasi tertentu
    $output0 =  count(array_keys($output, 0));
    $output1 =  count(array_keys($output, 1));
    $all = count($data);
    $counts = array_count_values($io);
    $values = array(
        'nilai_111' => $counts['111'] ?? 0,
        'nilai_110' => $counts['110'] ?? 0,
        'nilai_101' => $counts['101'] ?? 0,
        'nilai_100' => $counts['100'] ?? 0,
        'nilai_011' => $counts['011'] ?? 0,
        'nilai_010' => $counts['010'] ?? 0,
        'nilai_001' => $counts['001'] ?? 0,
        'nilai_000' => $counts['000'] ?? 0,
    );

    // echo "Nilai 111: " . $values['nilai_111'] . "<br>";
    // echo "Nilai 110: " . $values['nilai_110'] . "<br>";
    // echo "Nilai 101: " . $values['nilai_101'] . "<br>";
    // echo "Nilai 100: " . $values['nilai_100'] . "<br>";
    // echo "Nilai 011: " . $values['nilai_011'] . "<br>";
    // echo "Nilai 010: " . $values['nilai_010'] . "<br>";
    // echo "Nilai 001: " . $values['nilai_001'] . "<br>";
    // echo "Nilai 000: " . $values['nilai_000'] . "<br>";
    // // dan seterusnya...
    // print_r($all) . "<br>";
    // print_r($output0) . "<br>";
    // print_r($output1) . "<br>"; 

    $input_bayes = array();
    if ($age > 40) {
        $umur = 1;
    } else {
        $umur = 0;
    }
    if ($chol > 240) {
        $kolestrol = 1;
    } else {
        $kolestrol = 0;
    }
    $input_bayes[] = $umur.$kolestrol.$fbs;

    // print_r($input_bayes);

    $pA = "";
    $prob_0 = "";
    $prob_1 = "";

    foreach (str_split($input_bayes[0]) as $index => $value) {
        if ($index < strlen($input_bayes[0]) - 1) {
            $key = 'nilai_' . $input_bayes[0][$index] . $input_bayes[0][$index + 1] . $value;
            $pA = $values[$key] / $all;
            
            $key0 = $input_bayes[0][$index] . $input_bayes[0][$index + 1] . $value . '0';
            $count_key0 = array_count_values($kombinasi)["$key0"] ?? 0;
            $prob_0 = $count_key0 / $output0;

            $key1 = $input_bayes[0][$index] . $input_bayes[0][$index + 1] . $value . '1';
            $count_key1 = array_count_values($kombinasi)["$key1"] ?? 0;
            $prob_1 = $count_key1 / $output1;
        }
    }

    // echo $pA . "<br>";
    // echo $prob_0. "<br>";
    // echo $prob_1. "<br>";
    
    // // Hitung nilai probabilitas
    $pengali = $pA * $prob_1;
    $pembagi = $pA * $prob_0;

    // echo $pengali. "<br>";
    // echo $pembagi. "<br>";

    $naive_bayes = round(($pengali/($pengali+$pembagi))*100);

    // echo $naive_bayes . "<br>";
    // echo round($naive_bayes * 100);
?>