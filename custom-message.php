<?php
/*
 * Plugin Name:       Custom Shortcode Message
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Version:           1.0
 * Description:       Transform your text into a shortcode.
 * Author:            Mikołaj Wojtyś
 * Author URI:        https://www.linkedin.com/in/mikolaj-wojtys/
 */

function ctmsg_enqueue_script() {
    wp_enqueue_style('style', plugins_url('css/custom-message.css', __FILE__));
    wp_enqueue_script('ctmsg_ajax_script', plugins_url('js/custom-message.js', __FILE__), array('jquery'));
    wp_localize_script(
	    'ctmsg_ajax_script',
	    'ctmsg_ajax_object',
	    array('ajax_url' => admin_url('admin-ajax.php'))
    );
}

function ctmsg_ajax_handler() {
    if(isset($_POST['data'])) {
        $ajax_data = sanitize_text_field($_POST['data']);
        update_option('ctmsg_message', $ajax_data);
        wp_send_json_success($ajax_data);
    }
    wp_die();
}

function ctmsg_shortcode_define() {
    $message = get_option('ctmsg_message', '');
    if(empty($message)) return "<b>Empty shortcode text. Please input any text in the settings.</b>";
    return '<p>'.$message.'</p>';
}

function ctmsg_admin_settings() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <p class="ctmsg-p-description"><b>Use [custom_message] shortcode to display your text on the website</b></p>
        <input type="text" name="ctmsg-text" id="ctmsg-text" placeholder="Insert your text here" value=<?= get_option('ctmsg_message', ''); ?>>  
        <button id="ctmsg-submit">Confirm</button>      
    </div>
<?php
}

function ctmsg_admin_load() {
    add_menu_page(
        'Custom Shortcode Message', 
        'Custom Shortcode Message', 
        'manage_options', 
        'custom-shortcode-message', 
        'ctmsg_admin_settings', 
        'dashicons-shortcode'
    );
};

add_action('admin_menu', 'ctmsg_admin_load');
add_action('admin_enqueue_scripts', 'ctmsg_enqueue_script');
add_action('wp_ajax_ctmsg_ajax_action', 'ctmsg_ajax_handler'); 
add_action( "wp_ajax_nopriv_ctmsg_ajax_action", "ctmsg_ajax_handler");
add_shortcode('custom_message', 'ctmsg_shortcode_define');
?>