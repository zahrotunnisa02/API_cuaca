<?php
if (isset($_GET['city'])) {
    $city = urlencode($_GET['city']);
    $apiKey = '84b34d10eaef926c3467cbf44f71045b'; // Ganti dengan API Key dari OpenWeather
    $url = "https://api.openweathermap.org/data/2.5/weather?q=$city&appid=$apiKey&units=metric";
    
    $response = file_get_contents($url);
    $weatherData = json_decode($response, true);

    $forecastUrl = "https://api.openweathermap.org/data/2.5/forecast?q=$city&appid=$apiKey&units=metric";
        $forecastResponse = file_get_contents($forecastUrl);
        $forecastData = json_decode($forecastResponse, true);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuaca Web</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js" defer></script>
</head>
<body>
    <div class="container text-center mt-5">
        <h2 class="mb-4">Cari Cuaca</h2>
        <form method="GET" class="d-flex justify-content-center mb-4">
            <input type="text" name="city" class="form-control w-25" placeholder="Masukkan nama kota" required>
            <button type="submit" class="btn btn-primary ms-2">Cari</button>
        </form>
        <!-- current weather -->
        <?php if (isset($weatherData) && $weatherData['cod'] == 200): ?>
            <?php
                $timestamp = $weatherData['dt'];
                $tanggalCuaca = date('l, d F Y H:i', $timestamp); // waktu prediksi
            ?>
            <div class="card mx-auto" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">Cuaca di <?= htmlspecialchars($weatherData['name']) ?></h5>
                    <p><small class="text-muted"><?= $tanggalCuaca ?></small></p>
                    <p><img src="https://openweathermap.org/img/wn/<?= $weatherData['weather'][0]['icon'] ?>@2x.png"></p>
                    <p class="card-text"><strong><?= $weatherData['main']['temp'] ?>°C</strong> - <?= ucfirst($weatherData['weather'][0]['description']) ?></p>
                </div>
            </div>
        <?php elseif (isset($weatherData)): ?>
            <p class="text-danger">Data tidak ditemukan. Coba masukkan kota yang lain.</p>
        <?php endif; ?>
        <!-- Prediksi Cuaca 5 Hari -->
        <?php if (isset($forecastData) && $forecastData['cod'] == "200"): ?>
            <h3 class="mt-5">Prediksi Cuaca 5 Hari</h3>
            <div class="d-flex justify-content-center flex-wrap">
                <?php
                $forecastList = [];
                foreach ($forecastData['list'] as $forecast) {
                    $date = date('Y-m-d', $forecast['dt']); // Format YYYY-MM-DD
                    if (!isset($forecastList[$date])) {
                        $forecastList[$date] = $forecast;
                    }
                }

                foreach ($forecastList as $date => $dayForecast): ?>
                    <div class="card m-2" style="width: 10rem;">
                        <div class="card-body">
                            <h6 class="card-title"><?= date('D, d M', strtotime($date)) ?></h6>
                            <p><img src="https://openweathermap.org/img/wn/<?= $dayForecast['weather'][0]['icon'] ?>.png"></p>
                            <p class="card-text"><strong><?= $dayForecast['main']['temp'] ?>°C</strong></p>
                            <p class="text-muted"><?= ucfirst($dayForecast['weather'][0]['description']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
