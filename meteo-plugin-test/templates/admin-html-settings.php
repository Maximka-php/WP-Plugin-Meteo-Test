
<div class="settings-content">
    <form method="post" action="options.php">
        <?php
        settings_fields('meteo_group_settings');
        do_settings_sections('meteo_plugin_test_settings');
        submit_button('Save');
        echo '<br><h3>Используйте шорткод <b>[meteo_yandex]</b> для вывода прогноза погоды в нужном месте</h3>';
        ?>
    </form>
</div>