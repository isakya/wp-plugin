<?php

/**
 * Plugin Name: 我的插件
 */

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
