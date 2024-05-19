<?php
/*
Plugin Name: hc-test
Plugin URI: http://aaaaaa.com/hc-test
Description: 测试插件
Version: 1.0
Author: hc
Author URI: http://aaaaaa.com
License: GPLv2
*/

//设置时区为 亚洲/上海
date_default_timezone_set('Asia/Shanghai');

add_action( 'admin_menu', 'hc_create_menu' );

function hc_create_menu() {

    //创建顶级菜单
    add_menu_page(
        'hc的插件首页',
        'hc的插件',
        'manage_options',
        'hc_test' ,
        'hc_settings_page',
        plugins_url( '/images/icon.png', __FILE__ )
    );
}

add_action( 'admin_init', 'register_hc_test_setting' );

//使用register_setting()注册要存储的字段
function register_hc_test_setting() {

    //注册一个选项，用于装载所有插件设置项
    $option_group = "hc_test_group";
    register_setting( $option_group, 'hc_test_option' );

    //添加选项设置区域
    $setting_section = "hc_test_setting_section";
    add_settings_section(
        $setting_section,
        '',
        '',
        $option_group
    );

    //设置字体颜色
    add_settings_field(
        'hc_test_color',
        '字体颜色',
        'hc_test_color_function',
        $option_group,
        $setting_section
    );

    //设置字体大小
    add_settings_field(
        'hc_test_size',
        '字体大小',
        'hc_test_size_function',
        $option_group,
        $setting_section
    );

    //设置字体加粗
    add_settings_field(
        'hc_test_bold',
        '字体加粗',
        'hc_test_bold_function',
        $option_group,
        $setting_section
    );
}

function hc_test_bold_function() {
    $hc_test_option = get_option( "hc_test_option" );
    ?>
    <input name="hc_test_option[bold]" type="checkbox"  value="1" <? checked( 1, $hc_test_option["bold"] ); ?> /> 加粗
    <?
}

function hc_test_size_function() {
    $hc_test_option = get_option( "hc_test_option" );
    $size = $hc_test_option["size"];
    ?>
    <select name="hc_test_option[size]">
        <option value="12" <? selected( '12', $size ); ?>>12</option>
        <option value="14" <? selected( '14', $size ); ?>>14</option>
        <option value="16" <? selected( '16', $size ); ?>>16</option>
        <option value="18" <? selected( '18', $size ); ?>>18</option>
        <option value="20" <? selected( '20', $size ); ?>>20</option>
    </select>
    <?
}

function hc_test_color_function() {
    $hc_test_option = get_option( "hc_test_option" );
    ?>
    <input name='hc_test_option[color]' type='text' value='<? echo $hc_test_option["color"]; ?>' />
    <?
}

function hc_settings_page() {
    ?>
    <div class="wrap">
        <h2>插件顶级菜单</h2>
        <form action="options.php" method="post">
            <?
            $option_group = "hc_test_group";

            //输出一些必要的字段，包括验证信息等
            settings_fields( $option_group );

            //输出选项设置区域
            do_settings_sections( $option_group );

            //输出按钮
            submit_button();
            ?>
        </form>
    </div>
    <?
}


add_action( 'wp_head', 'hc_test_head_fun' );

function hc_test_head_fun() {

    $hc_test_option = get_option( "hc_test_option" );

    $bold = $hc_test_option["bold"] == 1 ? "bold" : "normal";
    ?><style>body{color:<? echo $hc_test_option["color"] ?>;font-size:<? echo $hc_test_option["size"] ?>px;font-weight:<? echo $bold; ?>;}</style><?
}