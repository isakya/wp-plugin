<?php

/**
 * Plugin Name: 我的插件
 */
//设置时区为 亚洲/上海
date_default_timezone_set('Asia/Shanghai');


add_action( 'admin_menu', 'hc_create_menu' );
function hc_create_menu() {

    // 创建顶级菜单
    add_menu_page(
        'hc的插件首页',
        'hc的插件',
        'manage_options',
        'hc_copyright' ,
        'hc_settings_page',
        plugins_url( '/images/icon.png', __FILE__ )
    );

    // 创建子菜单
    add_submenu_page(
        'hc_copyright',
        '关于hc的插件',
        '关于',
        'manage_options',
        'hc_copyright_about',
        'hc_create_submenu_menu'
    );
}

function hc_create_submenu_menu() {

    ?>
    <h2>子菜单</h2>
    <?
}

function hc_settings_page() {
    ?>
    <h2>插件顶级菜单</h2>
    <?
}





//定义插件启动时候调用的方法
register_activation_hook( __FILE__, 'hc_copyright_install');

function hc_copyright_install() {

    //插件启动，添加一个默认的版权信息
    update_option( "hc_copyright_text", "<p style='color:red'>本站点所有文章均为原创，转载请注明出处！</p>" );

}

//定义插件停用时候调用的方法
register_deactivation_hook( __FILE__, 'hc_copyright_deactivation');

function hc_copyright_deactivation() {

    //插件停用，设置停用标识为1
    update_option( "hc_copyright_deactivation", "yes" );

}


//为 wp_footer 钩子挂载一个新的动作 hc_copyright_insert
add_action( "wp_footer", "hc_copyright_insert", 1 );

function hc_copyright_insert(){

    //输出一段字符串
    echo get_option( "hc_copyright_text" );

}


add_action( "wp_footer", "hc_copyright_insert_new", 5 );

function hc_copyright_insert_new() {
    echo "我也输出一段文字";
}

add_action( 'save_post', 'save_post_meta', 10, 2 );

function save_post_meta( $post_id, $post ) {

    update_post_meta( $post_id, "save-time", "更新时间：" . date("Y-m-d H:i:s") );

}

//在输出内容之前，给页面管理添加摘要功能
add_action( 'init', 'hc_add_excerpts_to_pages' );

function hc_add_excerpts_to_pages() {

    //给页面管理添加摘要的功能
    add_post_type_support( 'page', array( 'excerpt' ) );
}

//wp_head钩子
add_action('wp_head','hc_wp_head');

function hc_wp_head() {

    //只有首页输出描述
    if( is_home() ){ ?>
        <meta name="description" content="<? bloginfo('description'); ?>" />
    <? }

}


//自定义引用样式表
function hc_enqueue_style() {
    wp_enqueue_style( 'core', plugins_url('css/hc_copyrighy.css', __FILE__) , false );
}

//自定义引用脚本文件
function hc_enqueue_script() {
    wp_enqueue_script( 'my-js', plugins_url('js/hc_copyrighy.js', __FILE__), false );
}

//引用文件的钩子
add_action( 'wp_enqueue_scripts', 'hc_enqueue_style', 5 );
add_action( 'wp_enqueue_scripts', 'hc_enqueue_script', 7 );

//删除所有挂载在 wp_enqueue_scripts 钩子上的方法
remove_all_actions( 'wp_enqueue_scripts', 5 );

//评论被添加的时候触发
add_action( 'wp_insert_comment', 'comment_inserted', 10, 2 );

//移除 wp_insert_comment 钩子上的 comment_inserted 方法
remove_action( 'wp_insert_comment', 'comment_inserted', 10 );

function comment_inserted($comment_id, $comment_object ) {

    //获取该评论所在文章的评论总数
    $comments_count = wp_count_comments( $comment_object->comment_post_ID );

    $commentarr = array();
    $commentarr['comment_ID'] = $comment_id;

    //修改评论的内容，在评论内容前加上 “第{$comments_count->total_comments}个评论：” 这么一段字符串
    $commentarr['comment_content'] = "第{$comments_count->total_comments}个评论：" . $comment_object->comment_content;

    wp_update_comment( $commentarr );

}


add_action( 'user_register', 'myplugin_registration_save', 10, 1 );

function myplugin_registration_save( $user_id ) {

    //将新用户的个人说明，设置为注册时间
    wp_update_user( array( 'ID' => $user_id, 'description' => "注册时间：" . date("Y-m-d H:i:s") ) );

}



function hc_filter_fun( $value ) {

    return $value . " hc";

}

function hc_filter_fun_add_time( $value ) {

    return date("Y-m-d H:i:s ") . $value;

}


//一开始，我们设置一个变量
$value = "hello";

//给名为 hc_filter 的过滤器，挂载一个 hc_filter_fun 方法，传递给 hc_filter 的变量都会经过 hc_filter_fun 方法进行过滤
//add_filter( "hc_filter", "hc_filter_fun" );

//给名为 hc_filter 的过滤器，再挂载一个 hc_filter_fun_add_time 方法，传递给 hc_filter 的变量都会经过 hc_filter_fun、hc_filter_fun_add_time 两个方法进行过滤
//add_filter( "hc_filter", "hc_filter_fun_add_time" );

//对 $value 值使用名为 hc_filter 的过滤器进行过滤，这个时候，由于 hc_filter 过滤器只挂载了 hc_filter_fun 方法，因此，只使用 hc_filter_fun 方法 过滤了一次，并且返回给 $myvar 变量
//$myvar = apply_filters( "hc_filter", $value );

add_filter( "the_content", "hc_filter_fun" );
add_filter( "the_content", "hc_filter_fun_add_time" );



function suppress_if_blurb( $title, $id = null ) {

    if ( in_category('wpcj', $id ) ) {
        return '不显示标题';
    }

    return $title . "...";
}

//对 the_title 过滤器挂载一个 suppress_if_blurb 方法，优先级为10，传递的参数有2个
add_filter( 'the_title', 'suppress_if_blurb', 10, 2 );



add_filter('wp_handle_upload_prefilter', 'huilang_wp_handle_upload_prefilter');
function huilang_wp_handle_upload_prefilter($file){
    $time=date("Y-m-d-");
    $file['name'] = $time."".mt_rand(1,100).".".pathinfo($file['name'] , PATHINFO_EXTENSION);
    return $file;
}


add_filter( 'comment_text','hc_auto_link' );


//文章保存之前，给文章中的“黄聪”自动加上链接
add_filter( 'content_save_pre','hc_auto_link' );
function hc_auto_link( $content ){
    return str_replace( "hc", "<a href='http://aaaaaaa.com'>bbb</a>", $content);
}
