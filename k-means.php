<?php include 'heart.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        :root {
            --tlt-br-cnt: 50;
            --i: 0;
        }

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            width: 100vw;
            height: 100vh;

            background: hsl(216, 28%, 7%);;

            overflow: hidden;

            display: flex;
            justify-content: space-evenly;
            align-items: center;
        }

        .progress {
            width: 200px;
            height: 200px;
            border-radius: 50%;

            display: flex;
            justify-content: center;
            align-items: center;

            position: relative;
        }

        .progress i {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            transform: rotate(calc(45deg + calc(calc(360deg / var(--tlt-br-cnt)) * var(--i))));
        }

        .progress i::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            background: hsla(0, 0%,100%, 12%);;
            width: 5px;
            height: 20px;
            border-radius: 999rem;
            transform: rotate(-45deg);
            transform-origin: top;
            opacity: 0;

            animation: barCreationAnimation 100ms ease forwards;
            animation-delay: calc(var(--i) * 15ms);
        }

        .progress .selected1::after {
            background: hsl(130, 100%, 50%);
            box-shadow: 0 0 1px hsl(130, 100%, 50%),
                        0 0 3px hsl(130, 100%, 30%),
                        0 0 4px hsl(130, 100%, 10%);
        }

        .progress .selected2::after {
            background: hsl(64, 100%, 50%);
            box-shadow: 0 0 1px hsl(64, 100%, 50%),
                        0 0 3px hsl(64, 100%, 30%),
                        0 0 4px hsl(64, 100%, 10%);
        }

        .progress .selected3::after {
            background: hsl(8, 100%, 50%);
            box-shadow: 0 0 1px hsl(8, 100%, 50%),
                        0 0 3px hsl(8, 100%, 30%),
                        0 0 4px hsl(8, 100%, 10%);
        }

        .percent-text {
            font-size: 3rem;
            animation: barCreationAnimation 500ms ease forwards;
            animation-delay: calc(var(--tlt-br-cnt) * 15ms / 2);
        }

        .text1{
            color: hsl(130, 100%, 50%);
            text-shadow: 0 0 1px hsl(130, 100%, 50%),
                            0 0 3px hsl(130, 100%, 30%),
                            0 0 4px hsl(130, 100%, 10%);
            opacity: 0;
        }

        .text2{
            color: hsl(64, 100%, 50%);
            text-shadow: 0 0 1px hsl(64, 100%, 50%),
                        0 0 3px hsl(64, 100%, 30%),
                        0 0 4px hsl(64, 100%, 10%);
            opacity: 0;
        }
        .text3{
            color: hsl(8, 100%, 50%);
            text-shadow: 0 0 1px hsl(8, 100%, 50%),
            0 0 3px hsl(8, 100%, 30%),
            0 0 4px hsl(8, 100%, 10%);
            opacity: 0;
        }

        @keyframes barCreationAnimation {
            from {opacity: 0}
            to {opacity: 1}
        }
        .speedometer-container {
        display: flex;
        justify-content: center;
        align-items: center;
        }

        .speedometer {
        position: relative;
        width: 250px;
        height: 250px;
        margin: 20px;
        }

        .meter {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background-color: #eee;
        box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .needle {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        transform-origin: bottom center;
        }

        .needle-inner {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: red;
        transform: translate(-50%, -50%);
        }

        .reading {
        position: absolute;
        top: 50%;
        left: 50%;
        font-size: 24px;
        transform: translate(-50%, -50%);
        }

    </style>
</head>
<body>
    <div class="progress"></div><br>
    <div class="container">
        <?php if (($kluster_terdekat+1) === 1):?>
            <div class="class">
                <span style='font-size:100px;'>&#128513;</span>
                <h3 style="color: white;">Alhamdulillah Kamu Aman dari Penyakit Jantung</h3>
            </div>
        <?php elseif(($kluster_terdekat+1) === 2):?>
        <span style='font-size:100px;'>&#128516;</span>
        <h3 style="color: white;">Kamu Harus Lebih Berhati hati lagi</h3>
        <?php elseif(($kluster_terdekat+1) === 3):?>
        <span style='font-size:100px;'>&#128546;</span>
        <h3 style="color: white;">Yahhh, Kamu terkena penyakit jantung, TETAP SEMANGAT YAA</h3>
        <?php endif;?>
    </div>
</body>
<script>
    const wrapper = document.querySelectorAll('.progress');

    const barCount = 50;
    const percent1 = 50 * "<?php echo $naive_bayes; ?>" / 100;

    for (let index = 0; index < barCount; index++) {
        const className = index < percent1 ? 'selected1' : '';
        wrapper[0].innerHTML += `<i style="--i: ${index};" class="${className}"></i>`;
    }

    wrapper[0].innerHTML += `<p class="selected percent-text text1"><?php echo $naive_bayes; ?>%</p>`
</script>
</html>