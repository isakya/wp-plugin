<?php
/*
Plugin Name: hc-book
Plugin URI: http://hcsem.com/hc-book
Description: 自定义文章类型
Version: 1.0
Author: 黄聪
Author URI: http://hcsem.com
License: GPLv2
*/

//设置时区为 亚洲/上海
date_default_timezone_set('Asia/Shanghai');

class hc_book {
	
	function hc_book() {
		
		add_action( 'init', array( $this, 'create_hc_book' ) );
		
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes_hc_book' ) );
		
		add_action( 'save_post',  array( $this, 'save_hc_book_fields' ), 10, 2 );
		
		add_filter( 'template_include', array( $this, 'hc_book_template_function' ), 1 );
	}
	
	function hc_book_template_function( $template_path ) {
		
		if ( is_single() && get_post_type() == 'hc_book' ) {
			
			if ( $theme_file = locate_template( array ( 'single-hc_book.php' ) ) ) {
				return $theme_file;
			}
		}
		return $template_path;
	}
	
	function save_hc_book_fields( $post_id, $post ) {
		
		if ( $post->post_type == 'hc_book' ) {
			
			$hc_book_url = $_POST['hc_book_url'];
			update_post_meta( $post_id, 'hc_book_url', $hc_book_url );
			
		}
	}
	
	function add_meta_boxes_hc_book() {
		
		add_meta_box( 'hc_book_admin_meta_box',  //ID
			'作品链接', //标题
			array( $this, 'display_hc_book_meta_box' ), //显示HTML代码的回调函数
			'hc_book', //显示的自定义文章类型的名字，设置为我们新创建的文章类型
			'side'
		);
	}
	
	function display_hc_book_meta_box(  $post ) {
		
		$hc_book_url = get_post_meta( $post->ID, 'hc_book_url', true );
		echo '<input type="text" id="hc_book_url" name="hc_book_url" value="' . $hc_book_url . '" />';
	}
	
	function create_hc_book() {
		
		register_post_type( 'hc_book',
		array(
			'labels' => array(
				'name' => '我的作品',
				'add_new' => '添加作品',
				'add_new_item' => '添加一个新作品',
				'edit' => '编辑',
				'edit_item' => '编辑作品',
				'new_item' => '新作品',
				'view' => '查看',
				'view_item' => '查看作品',
				'search_items' => '搜索作品',
				'not_found' => '没有任何作品',
				'not_found_in_trash' => '没有任何被删除的作品'
			),
			
			'public' => true, //可见性
			'menu_position' => 15, //菜单的位置
			'supports' => array( 'title', 'editor', 'comments', 'thumbnail', 'custom-fields' ), //显示哪些自定义文章类型的功能
			'taxonomies' => array( '' ) //自定义分类。在这里没有定义
			)
		);
	}
}

new hc_book();