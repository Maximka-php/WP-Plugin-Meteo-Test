<?php
/*
 * Plugin Name: Meteo-test
 *
 */
if (!defined('ABSPATH')) {
    die();
}
require_once 'find-data.php';
class MeteoTest
{
    function __construct()
    {
        $this->register();
    }

    function register()
    {
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), [$this, 'add_settings_link']);
        add_action('admin_menu', [$this, 'add_meteo_menu']);
        add_action('admin_init', [$this, 'settings_init']);
        add_action('admin_enqueue_scripts', [$this, 'enq_admin']);
        add_shortcode('meteo_yandex', [$this, 'find_wiev_meteo']);
    }

    function add_meteo_menu()
    {
        add_menu_page(
            'Settings Meteo', // тайтл страницы
            'Meteo option', // текст ссылки в меню
            'manage_options', // права пользователя, необходимые для доступа к странице
            'meteo_settings', // ярлык страницы
            [$this, 'admin_html_settings'], // функция, которая выводит содержимое страницы
            'dashicons-admin-network', // иконка, в данном случае из Dashicons
            95 // позиция в меню
        );
    }

    function admin_html_settings()
    {
        require_once plugin_dir_path(__FILE__) . 'templates/admin-html-settings.php';
    }

    function add_settings_link($link)
    {
        $custom_link = '<a href="admin.php?page=meteo_settings">Settings</a>';
        array_push($link, $custom_link);
        return $link;
    }

    function settings_init()
    {
        register_setting('meteo_group_settings', 'meteo_test_option');
        add_settings_section('meteo_section_settings', 'Settings MeteoTestPlugin',
            '', 'meteo_plugin_test_settings');
        add_settings_field('on_auto_geo_ip', 'Включить автоопределение по айпи',
            [$this, 'get_html_on_auto_geo_ip'], 'meteo_plugin_test_settings', 'meteo_section_settings');
        add_settings_field('geo_lat', 'широта', [$this, 'get_html_geo_lat'], 'meteo_plugin_test_settings',
            'meteo_section_settings');
        add_settings_field('geo_lon', 'долгота', [$this, 'get_html_geo_lon'], 'meteo_plugin_test_settings',
            'meteo_section_settings');
        add_settings_field('token_yandex', 'Введите токен Яндекс', [$this, 'get_html_for_token_yandex'], 'meteo_plugin_test_settings',
            'meteo_section_settings');
    }

    function get_html_for_token_yandex()
    {
        $options = get_option('meteo_test_option');
        $token = $options['token_yandex'];
        echo "<p>Укажите токен полученный от Яндекс.Погода</p>
                <hr>
            <input  id='token-api' type='text' name='meteo_test_option[token_yandex]' value='{$token}'>";
    }

    function get_html_on_auto_geo_ip()
    {
        $options = get_option('meteo_test_option');
        $checked = '';
        if ($options['on_auto_geo_ip']) {
            $checked = 'checked';
        }
        echo "<p>Если включен - будет осуществляться поиск месторасположения пользователя для вывода 'его' погоды</p>
                <hr>
                <input id=\"ip_geo\" type=\"checkbox\" name=\"meteo_test_option[on_auto_geo_ip]\" value=\"true\" {$checked}> ";
    }

    function get_html_geo_lat()
    {
        $lat = null;
        $options = get_option('meteo_test_option');
        if ($options['geo_lat']) {
            $lat = $options['geo_lat'];
        }
        echo "<p>Если выключена предыдущая настройка - можно фиксированно указать координаты для вывода погоды</p>
                <hr>
            <input id=\"ip_lat\" type=\"text\" name=\"meteo_test_option[geo_lat]\" value=\"{$lat}\">";
    }

    function get_html_geo_lon()
    {
        $lon = null;
        $options = get_option('meteo_test_option');
        if ($options['geo_lon']) {
            $lon = $options['geo_lon'];
        }
        echo "<input id=\"ip_lon\" type=\"text\" name=\"meteo_test_option[geo_lon]\" value=\"{$lon}\">";
    }

    function enq_admin()
    {
        wp_enqueue_script('maximka_admin_script', plugins_url('/assets/admin.js', __FILE__));
    }

    function find_wiev_meteo()
    {
        $a = new YandexData();
        $array_info = $a->request();
        ob_start();
        require_once 'templates/template-meteo.php';
        $html = ob_get_clean();
        return $html;
    }

    static function activation()
    {
        flush_rewrite_rules();
    }

    static function deactivation()
    {
        flush_rewrite_rules();
    }

    static function uninstall()
    {
        flush_rewrite_rules();
    }
}

if (class_exists('MeteoTest')) {
    $book = new MeteoTest();
}
register_activation_hook(__FILE__, [$book, 'activation']);
register_deactivation_hook(__FILE__, [$book, 'deactivation']);
register_uninstall_hook(__FILE__, [$book, 'uninstall']);