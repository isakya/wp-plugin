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

class hcsem_change_font_style {

    var $option_group = "general";

    //构造方法，创建类的时候调用
    function hcsem_change_font_style() {

        add_action( 'admin_init', array( $this, 'register_hc_test_setting' ) );
        add_action( 'wp_head',  array( $this,  'hc_test_head_fun' ) );
    }

    //使用register_setting()注册要存储的字段
    function register_hc_test_setting() {

        //注册一个选项，用于装载所有插件设置项
        register_setting( $this->option_group, 'hc_test_option' );

        //设置字体颜色
        add_settings_field(
            'hc_test_color',
            '字体颜色',
            array( $this, 'hc_test_color_function' ),
            $this->option_group
        );

        //设置字体大小
        add_settings_field(
            'hc_test_size',
            '字体大小',
            array( $this, 'hc_test_size_function' ),
            $this->option_group
        );

        //设置字体加粗
        add_settings_field(
            'hc_test_bold',
            '字体加粗',
            array( $this, 'hc_test_bold_function' ),
            $this->option_group
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

    function hc_test_head_fun() {

        $hc_test_option = get_option( "hc_test_option" );

        $bold = $hc_test_option["bold"] == 1 ? "bold" : "normal";
        ?><style>body{color:<? echo $hc_test_option["color"] ?>;font-size:<? echo $hc_test_option["size"] ?>px;font-weight:<? echo $bold; ?>;}</style><?
    }
}

new hcsem_change_font_style();


