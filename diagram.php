<?php include 'data.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Scatter Plot Example</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
      body {
            overflow: hidden;
            display: flex;
            justify-content: space-evenly;
            align-items: center;
        }
    </style>
</head>
<body>
    <canvas id="myChart"></canvas>
    <script>
        // Mendapatkan data dari PHP
        var data = <?php echo $json_data; ?>;
        
        // Membuat array untuk menyimpan data cluster
        var cluster1 = [];
        var cluster2 = [];
        var cluster3 = [];
        
        // Memasukkan data ke dalam array cluster sesuai dengan nama cluster
        for (var i = 0; i < data.length; i++) {
            if (data[i][0] == "Cluster 1") {
                cluster1.push({x: data[i][1], y: data[i][2]});
            } else if (data[i][0] == "Cluster 2") {
                cluster2.push({x: data[i][1], y: data[i][2]});
            } else if (data[i][0] == "Cluster 3") {
                cluster3.push({x: data[i][1], y: data[i][2]});
            }
        }
        
        // Menggambar scatter plot dengan menggunakan Chart.js
        var ctx = document.getElementById('myChart').getContext('2d');
        var scatterChart = new Chart(ctx, {
            type: 'scatter',
            data: {
                datasets: [{
                    label: 'Cluster 1',
                    data: cluster1,
                    backgroundColor: 'rgba(255, 99, 132, 1)',
                }, {
                    label: 'Cluster 2',
                    data: cluster2,
                    backgroundColor: 'rgba(54, 162, 235, 1)',
                }, {
                    label: 'Cluster 3',
                    data: cluster3,
                    backgroundColor: 'rgba(255, 206, 86, 1)',
                }]
            },
            options: {
              scales: {
                  xAxes: [{
                      type: 'linear',
                      position: 'bottom',
                      ticks: {
                          fontColor: 'red'
                      }
                  }],
                  yAxes: [{
                      ticks: {
                          fontColor: 'red'
                      }
                  }],
                  zAxes: [{
                      ticks: {
                          fontColor: 'red'
                      }
                  }]
              },
              legend: {
                  labels: {
                      fontColor: 'red'
                  }
              }
          }
        });
    </script>
</body>
</html>

