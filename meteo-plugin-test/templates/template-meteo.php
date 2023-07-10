<div class="main-meteo">
<h5 class="meteo-h5">Данные о погоде</h5>
<div class="meteo-content">
    <div class="meteo-geo">Погода в городе <?= $array_info['city'] ?></div>
    <div class="meteo-temp">Температура: <?= $array_info['temp'] ?>&#176;C</div>
    <div class="meteo-wind">Скорость ветра: <?= $array_info['wind_speed'] ?> м/с</div>
    <div class="meteo-wind-dir">Направление ветра: <?= $array_info['wind_dir'] ?></div>
</div>
</div>