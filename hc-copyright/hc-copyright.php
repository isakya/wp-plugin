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
    <h3>子菜单设置页面</h3>
    <?
}

function hc_settings_page() {
    ?>
    <div class="wrap">
        <h2>插件顶级菜单</h2>
        <div id="message" class="updated">设置保存成功</div>
        <div id="message" class="error">保存出现错误</div>
        <p>
            <input type="submit" name="Save" value="保存设置" />
            <input type="submit" name="Save" value="保存设置" class="button" />
            <input type="submit" name="Save" value="保存设置" class="button button-primary" />
            <input type="submit" name="Save" value="保存设置" class="button button-secondary" />
            <input type="submit" name="Save" value="保存设置" class="button button-large" />
            <input type="submit" name="Save" value="保存设置" class="button button-small" />
            <input type="submit" name="Save" value="保存设置" class="button button-hero" />
        </p>
        <p>
            <a href="#">搜索</a>
            <a href="#" class="button">搜索</a>
            <a href="#" class="button button-primary">搜索</a>
            <a href="#" class="button button-secondary">搜索</a>
            <a href="#" class="button button-large">搜索</a>
            <a href="#" class="button button-small">搜索</a>
            <a href="#" class="button button-hero">搜索</a>
        </p>

        <form method="POST" action="">
            <table class="form-table">
                <tr valign="top">
                    <th><label for="xingming">姓名：</label></th>
                    <td><input id="xingming" name="xingming" /></td>
                </tr>
                <tr valign="top">
                    <th><label for="shenfen">身份：</label></th>
                    <td>
                        <select name="shenfen">
                            <option value="在校">在校</option>
                            <option value="毕业">毕业</option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th><label for="tongyi">同意注册</label></th>
                    <td><input type="checkbox" name="tongyi" /></td>
                </tr>
                <tr valign="top">
                    <th><label for="xingbie">性别</label></th>
                    <td>
                        <input type="radio" name="xingbie" value="男" /> 男
                        <input type="radio" name="xingbie" value="女" /> 女
                    </td>
                </tr>
                <tr valign="top">
                    <th><label for="beizhu">备注</label></th>
                    <td><textarea name="beizhu"></textarea></td>
                </tr>
                <tr valign="top">
                    <td>
                        <input type="submit" name="save" value="保存" class="button-primary" />
                        <input type="submit" name="reset" value="重置" class="button-secondary" />
                    </td>
                </tr>
            </table>
        </form>

        <table class="widefat striped">
            <thead>
            <tr>
                <th>序号</th>
                <th>姓名</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>1</td>
                <td>黄聪</td>
            </tr>
            <tr>
                <td>2</td>
                <td>黄聪</td>
            </tr>
            <tr>
                <td>3</td>
                <td>黄聪</td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <th>序号</th>
                <th>姓名</th>
            </tr>
            </tfoot>
        </table>

        <div class="tablenav">
            <div class="tablenav-pages">
                <span class="displaying-num">第1页，共458页</span>
                <span class="page-numbers current">1</span>
                <a href="#" class="page-numbers">2</a>
                <a href="#" class="page-numbers">3</a>
                <a href="#" class="page-numbers">4</a>
                <a href="#" class="next page-numbers">»</a>
            </div>
        </div>
    </div>
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
    wp_enqueue_script( 'my-js', plugins_url('js/hc_test.js', __FILE__), false );
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


//文章保存之前，给文章中的“hc”自动加上链接
add_filter( 'content_save_pre','hc_auto_link' );
function hc_auto_link( $content ){
    return str_replace( "hc", "<a href='http://aaaaaaa.com'>bbb</a>", $content);
}




// 使用 widgets_init 动作钩子来执行自定义的函数
add_action( 'widgets_init', 'hc_register_widgets' );

// 注册小工具
function hc_register_widgets() {
    register_widget( 'hc_widget_info' );
}

//使用 WP_Widget 类来创建小工具
class hc_widget_info extends WP_Widget {

    //构造函数
    public function __construct() {
        $widget_ops = array(
            'classname' => 'hc_widget_info',
            'description' => '显示hc的个人信息'
        );
        $this->WP_Widget( '显示个人信息', 'hc的小工具', $widget_ops );
    }

    //小工具管理界面
    public function form( $instance ) {

        $defaults = array( 'title' => 'hc的个人信息', 'xingming' => 'hc', 'book' => '《SEO》、《wordpress主题开发》' );
        $instance = wp_parse_args( (array) $instance, $defaults );

        $title = $instance['title'];
        $xingming = $instance['xingming'];
        $book = $instance['book'];
        ?>
        <p>标题: <input class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>
        <p>姓名: <input class="widefat" name="<?php echo $this->get_field_name( 'xingming' ); ?> "type="text" value="<?php echo esc_attr( $xingming ); ?> " /></p>
        <p>著作: <textarea class="widefat" name=" <?php echo $this->get_field_name( 'book' ); ?> " /><?php echo esc_attr( $book ); ?></textarea> </p>
        <?php
    }

    //保存小工具设置
    public function update( $new_instance, $old_instance ) {

        $instance = $old_instance;

        $instance['title'] = strip_tags( trim( $new_instance['title'] ) );
        $instance['xingming'] = strip_tags( trim(  $new_instance['xingming'] ) );
        $instance['book'] = strip_tags( trim( $new_instance['book'] ) );
    }

    //显示小工具
    public function widget( $args, $instance ) {

        extract( $args );

        $title = apply_filters( 'widget_title', $instance['title'] );
        $xingming = empty( $instance['xingming'] ) ? ' ' : $instance['xingming'];
        $book = empty( $instance['book'] ) ? ' ' : $instance['book'];

        echo $before_widget;
        echo '<p> 标题: ' . $title . '</p>';
        echo '<p> 姓名: ' . $xingming . '</p>';
        echo '<p> 著作: ' . $book . '</p>';
        echo $after_widget;
    }
}







/**
 * 添加一个元数据框到 post 和 page 的管理界面中
 */
function myplugin_add_meta_box() {

    $screens = array( 'post', 'page' );

    add_meta_box(
        'myplugin_sectionid',
        '转载自',
        'myplugin_meta_box_callback',
        $screens
    );
}

//需要给 add_meta_boxes 钩子，挂载一个自定义的方法
add_action( 'add_meta_boxes', 'myplugin_add_meta_box' );

/**
 * 元数据框展示代码
 */
function myplugin_meta_box_callback( $post ) {

    // 添加一个验证信息，这个在保存元数据的时候用到
    wp_nonce_field( 'myplugin_save_meta_box_data', 'myplugin_meta_box_nonce' );

    /*
     * 输出元数据信息
     */
    $value = get_post_meta( $post->ID, '_zzurl', true );

    echo '<label for="myplugin_new_field">';
    _e( '本文章转载自：' );
    echo '</label> ';
    echo '<input type="text" id="_zzurl" name="_zzurl" value="' . esc_attr( $value ) . '" size="25" />';
}

function myplugin_save_meta_box_data( $post_id ) {

    //验证是否为有效信息
    if ( ! isset( $_POST['myplugin_meta_box_nonce'] ) ) {
        return;
    }

    if ( ! wp_verify_nonce( $_POST['myplugin_meta_box_nonce'], 'myplugin_save_meta_box_data' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions.
    if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }

    } else {

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    if ( ! isset( $_POST['_zzurl'] ) ) {
        return;
    }

    $my_data = sanitize_text_field( $_POST['_zzurl'] );

    update_post_meta( $post_id, '_zzurl', $my_data );
}

//文章保存的时候，会调用 save_post 钩子，因此我们要借助这个钩子来保存元数据框内的数据
add_action( 'save_post', 'myplugin_save_meta_box_data' );



