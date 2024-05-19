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

//定义插件启动时候调用的方法
register_activation_hook( __FILE__, 'hc_test_install');

function hc_test_install() {
	
	global $wpdb;
		
	if( $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->prefix}test'" ) != "{$wpdb->prefix}test" ) 
	{
		$sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}test` (
			  `id` int(11) NOT NULL auto_increment COMMENT '编号',
			  `color` varchar(10) DEFAULT '' COMMENT '字体颜色',
			  `size`  varchar(10) DEFAULT '' COMMENT '字体大小',
			  PRIMARY KEY  (`id`)
			) DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;";
		$wpdb->query( $sql );
		
		$sql = "REPLACE INTO `{$wpdb->prefix}test` VALUES (1, '#FF0000','20');";
		$wpdb->query( $sql );
	}
}

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

function hc_settings_page() {
	
	global $wpdb;
	
	//当提交了，并且验证信息正确
	if( !empty( $_POST ) && check_admin_referer( 'hc_test_nonce' ) ) {
		
		//更新设置
		update_option( 'hc_test_bold', $_POST['hc_test_bold'] );
		
		$wpdb->update( "{$wpdb->prefix}test", array( 'color' => $_POST['color'], 'size' => $_POST['size'] ), array( 'id' => 1 ) );
		?>
		<div id="message" class="updated">
			<p><strong>保存成功！</strong></p>
		</div>
		<?
	}
	
	$sql = "SELECT * FROM `{$wpdb->prefix}test`";
	$row = $wpdb->get_row( $sql, ARRAY_A );
	
	$color = $row['color'];
	$size = $row['size'];
	?>
	<div class="wrap">
		<h2>插件顶级菜单</h2>
		<form action="" method="post">
			<p><label for="color">字体颜色：</label><input type="text" name="color" value="<?php echo $color; ?>" /></p>
			<p><label for="size">字体大小：</label>
			<select name="size">
				<option value="12" <? selected( '12', $size ); ?>>12</option>
				<option value="14" <? selected( '14', $size ); ?>>14</option>
				<option value="16" <? selected( '16', $size ); ?>>16</option>
				<option value="18" <? selected( '18', $size ); ?>>18</option>
				<option value="20" <? selected( '20', $size ); ?>>20</option>
			</select></p>
			<p><label for="hc_test_obold">字体加粗：</label><input name="hc_test_bold" type="checkbox"  value="1" <? checked( 1, get_option( 'hc_test_bold' ) ); ?> /> 加粗</p>
			<p><input type="submit" name="submit" value="保存设置" /></p>
			<?
				//输出一个验证信息
				wp_nonce_field('hc_test_nonce');
			?>
		</form>
	</div>
	<?
}

add_action( 'wp_head', 'hc_test_head_fun' );

function hc_test_head_fun() {
	
	global $wpdb;
	
	//获取自定义数据库中的设置
	$sql = "SELECT * FROM `{$wpdb->prefix}test`";
	$row = $wpdb->get_row( $sql, ARRAY_A );
	
	//获取options表中的设置选项
	$bold = get_option( "hc_test_bold" ) == 1 ? "bold" : "normal";
	
	?><style>body{color:<? echo $row["color"] ?>;font-size:<? echo $row["size"] ?>px;font-weight:<? echo $bold; ?>;}</style><?
}
