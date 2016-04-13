<?php

/**
 * KM Jobs
 *
 * @package   KM Jobs
 * @author    Ksenia Maslennikova <info@php4u.ru>
 * @license   GPL-2.0+
 * @link      https://github.com/kseniamaslennikova/km-jobs
 * @copyright 2016 Ksenia Maslennikova, php4u.ru
 *
 * @wordpress-plugin
 * Plugin Name:       KM Jobs
 * Plugin URI:        https://github.com/kseniamaslennikova/km-jobs
 * Description:       Adding functionality for managing jobs of a company.
 * Version:           1.0.0
 * Author:            Ksenia Maslennikova
 * Author URI:        https://github.com/kseniamaslennikova
 * Text Domain:       km-jobs
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/kseniamaslennikova/km-jobs
 * GitHub Branch:     master
*/

// если файл был вызван напрямую, а не вордпрессом, завершаем
if ( !defined( 'ABSPATH' ) && ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Подключаем functions с набором функций, расширяющих функционал плагина
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/functions.php';

function kmjobs_install() {

    // регистрируем тип постов Вакансии
    kmjobs_setup_post_types();

    //регистрируем таксономии для типа постов Вакансии
    kmjobs_setup_taxonomies();    

    // Обновляем permalinks после регистрации типа постов Вакансии
    flush_rewrite_rules();    

}
/* End of KM Jobs plugin activation functions*/

/* KM Jobs plugin deactivation functions*/
function kmjobs_deactivation() {    

    // Обновляем permalinks после деактивации типа постов Вакансии
    flush_rewrite_rules();

}
/* End of KM Jobs plugin deactivation functions*/

register_activation_hook( __FILE__, 'kmjobs_install' );
register_deactivation_hook( __FILE__, 'kmjobs_deactivation' );

