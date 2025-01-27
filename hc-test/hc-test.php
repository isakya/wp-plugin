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

    //声明类里面的属性，用 var 开头
    var $icon_url = "/images/icon.png";
    var $option_group = "hc_test_group";

    //构造方法，创建类的时候调用
    function hcsem_change_font_style() {

        //创建菜单
        add_action( 'admin_menu', array( $this, 'hc_create_menu' ) );
        add_action( 'admin_init', array( $this, 'register_hc_test_setting' ) );

        add_action( 'wp_head',  array( $this,  'hc_test_head_fun' ) );

        add_action( 'admin_enqueue_scripts', array( $this, 'load_script' ) );

        add_action( 'wp_ajax_color_check_action' , array( $this, 'color_check_action_fun') );
        add_action( 'wp_ajax_nopriv_hcsem_description' , array( $this, 'hcsem_description_fun') );

        add_action( 'init', array( $this, 'hcsem_load_textdomain' ) );


        //添加一个 hcsem 短标签，调用 hcsem_shortcode 方法进行处理
        add_shortcode( 'hcsem', array( $this, 'hcsem_shortcode' ) );
        add_shortcode( 'baztag', array( $this, 'baztag_func' ) );
    }

    function baztag_func( $atts, $content = "" ) {

        return "【{$content}】";
    }

    function hcsem_shortcode( $atts, $content = "" ) {

        $atts = shortcode_atts( array(
            'title' => '《SEO的道与术》',
            'url' => 'http://product.dangdang.com/23709551.html',
            'img' => 'http://images0.cnblogs.com/blog2015/121863/201505/272034378914366.png'
        ), $atts, 'hcsem' );

        $output = "<a href='{$atts['url']}' title='{$atts['title']}'>
					<div class='file-box'>
						<b>【{$atts['title']}】</b>
						<div class='clr'></div>
						<img src='{$atts['img']}' />
						<div class='clr'></div>
						<i>{$content}</i>
						<div class='clr'></div>
					</div>
				</a>";

        return $output;
    }

    function load_script() {

        //使用ajax校验信息
        $screen = get_current_screen();
        if ( is_object( $screen ) && $screen->id == 'hc_test' ) {
            wp_enqueue_script( 'hc_test', plugins_url('js/hc_test.js', __FILE__), array('jquery') );
            wp_localize_script( 'hc_test', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
        }
    }

    function hcsem_load_textdomain() {

        //加载 languages 目录下的翻译文件 zh_CN
        $currentLocale = get_locale();

        if( !empty( $currentLocale ) ) {

            $moFile = dirname(__FILE__) . "/languages/{$currentLocale}.mo";

            if( @file_exists( $moFile ) && is_readable( $moFile ) ) load_textdomain( 'hc-test', $moFile );
        }
    }

    function color_check_action_fun(){

        if( trim( $_POST['color'] ) != "" ){ echo "ok"; }
        wp_die();
    }

    function hcsem_description_fun() {

        echo "hc的笔记本：" . $_POST['description'];
        wp_die();

    }

    //使用register_setting()注册要存储的字段
    function register_hc_test_setting() {

        //注册一个选项，用于装载所有插件设置项
        register_setting( $this->option_group, 'hc_test_option' );

        //添加选项设置区域
        $setting_section = "hc_test_setting_section";
        add_settings_section(
            $setting_section,
            '',
            '',
            $this->option_group
        );


        if( current_user_can('edit_posts') )
        {
            //设置字体颜色
            add_settings_field(
                'hc_test_color',
                __( 'color', 'hc-test' ),
                array( $this, 'hc_test_color_function' ),
                $this->option_group,
                $setting_section
            );
        }
        //设置字体大小
        add_settings_field(
            'hc_test_size',
            __( 'size', 'hc-test' ),
            array( $this, 'hc_test_size_function' ),
            $this->option_group,
            $setting_section
        );

        //设置字体加粗
        add_settings_field(
            'hc_test_bold',
            __( 'bold', 'hc-test' ),
            array( $this, 'hc_test_bold_function' ),
            $this->option_group,
            $setting_section
        );
    }

    function hc_test_bold_function() {
        $hc_test_option = get_option( "hc_test_option" );
        ?>
        <input name="hc_test_option[bold]" type="checkbox"  value="1" <? checked( 1, $hc_test_option["bold"] ); ?> /> <? _e( 'set bold', 'hc-test' ); ?>
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
        <font id="error_color"></font></div>
        <?
    }

    function hc_create_menu() {

        //创建顶级菜单
        add_menu_page(
            'hc的插件首页',
            'hc的插件',
            'read',
            'hc_test' ,
            array( $this, 'hc_settings_page' ),
            plugins_url( $this->icon_url, __FILE__ )
        );
    }

    function hc_settings_page() {
        ?>
        <div class="wrap">
            <h2>插件顶级菜单</h2>
            <form action="options.php" method="post">
                <?
                //输出一些必要的字段，包括验证信息等
                settings_fields( $this->option_group );

                //输出选项设置区域
                do_settings_sections( $this->option_group );

                //输出按钮
                submit_button();
                ?>
            </form>
        </div>
        <?
    }

    function hc_test_head_fun() {

        $hc_test_option = get_option( "hc_test_option" );

        $bold = $hc_test_option["bold"] == 1 ? "bold" : "normal";
        ?><style>body{color:<? echo $hc_test_option["color"] ?>;font-size:<? echo $hc_test_option["size"] ?>px;font-weight:<? echo $bold; ?>;}</style><?
    }
}

new hcsem_change_font_style();





