<?php

// Settings Page: ChatGPTScheduler
// Retrieving values: get_option( 'your_field_id' )
class ChatGPTScheduler_Settings_Page {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'wph_create_settings' ) );
		add_action( 'admin_init', array( $this, 'wph_setup_sections' ) );
		add_action( 'admin_init', array( $this, 'wph_setup_fields' ) );
        add_action( 'admin_enqueue_scripts', array( $this,'enqueue_custom_styles') );
	}
    // Enqueue own styles
    function enqueue_custom_styles() {
        wp_enqueue_style( 'chatgpt_scheduler_admin',chatgpt_scheduler_PATH.'/css/admin.css', array(),false,'all');
                        
    }
                        

	public function wph_create_settings() {
		$page_title = 'ChatGPT Scheduler';
		$menu_title = 'ChatGPT Scheduler';
		$capability = 'manage_options';
		$slug = 'ChatGPTScheduler';
		$callback = array($this, 'wph_settings_content');
                $icon = 'dashicons-welcome-write-blog';
		$position = 75;
		add_menu_page($page_title, $menu_title, $capability, $slug, $callback, $icon, $position);
		
	}
    
	public function wph_settings_content() { 
        $message;
        $helper = new chatgpt_scheduler_Helper;
        if(isset($_POST['noncetoken_name_chatGPT_schedule_settings']) && wp_verify_nonce($_POST['noncetoken_name_chatGPT_schedule_settings'],'noncetoken_chatGPT_schedule_settings') && isset($_POST['chatGPT_schedule_settings_submitBtn']) ){
            update_option('chatGPT_schedule_settings',$_POST['chatGPT_schedule_settings']);
            $message.= '<div style=" display: block !important;" class="update-message notice inline notice-warning notice-alt"><p>Updated</p></div>';
        }
$chatGPT_schedule_settings =  get_option('chatGPT_schedule_settings',array());
$Primary_Keyword = isset($chatGPT_schedule_settings['Primary_Keyword']) ? $chatGPT_schedule_settings['Primary_Keyword'] : '';
$Post_Type = isset($rap_settings['Post_Type']) ? $rap_settings['Post_Type'] : '';
        ?>
		<div class="wrap">
			<h1>ChatGPT Scheduler</h1>
            <?php echo $message?>
            <form name="chatGPT_schedule_settings" id="chatGPT_schedule_settings" method="POST">
                <table class="wp-list-table widefat fixed striped">
                    <tr>
                        <th>Prompt Type</th>
                        <th>Prompt</th>
                        <th></th>
                        <th>Post Type</td>
                        <th>Schedule Time</th>
                        <th>Schedule Pattern</th>
                        <th>Clone -- Remove</th>
                    </tr>
                    <tr>
                        <td>Custom</td>
                        <td colspan=2><input type="text" class="regular-text" name="chatGPT_schedule_settings[Primary_Keyword]" id="Primary_Keyword" value="<?php echo $Primary_Keyword?>" /></td>
                        <td><?php echo $helper->get_post_types_dropdown()?></td>
                        <td><input type="time" name="chatGPT_schedule_settings[time]" /></td>
                        <td><?php echo $helper->schedule_pattern_dropdown()?></td>
                        <td><span class="dashicons dashicons-plus-alt"></span> -- <span class="dashicons dashicons-dismiss"></span></td>
                    </tr>
                </table>
                
                <p>
                    <input type="submit" name="chatGPT_schedule_settings_submitBtn" value="Save Settings" class="button button-primary" />
                </p>
                <?php wp_nonce_field( 'noncetoken_chatGPT_schedule_settings', 'noncetoken_name_chatGPT_schedule_settings' );?>
        </form>
		</div> <?php
	}

	public function wph_setup_sections() {
		add_settings_section( 'ChatGPTScheduler_section', 'Schedule ChatGPT Content', array(), 'ChatGPTScheduler' );
	}

	public function wph_setup_fields() {
		$fields = array(
                    array(
                        'section' => 'ChatGPTScheduler_section',
                        'label' => 'Write an article about',
                        'placeholder' => 'Pyramids of Egypt',
                        'id' => 'Write an article about _text',
                        'type' => 'text',
                    )
		);
		foreach( $fields as $field ){
			register_setting( 'ChatGPTScheduler', $field['id'] );
		}
	}
    
}
new ChatGPTScheduler_Settings_Page();
                