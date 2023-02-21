<?php
class ChatGPTScheduler_Settings_Page {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'wph_create_settings' ) );
        add_action( 'admin_enqueue_scripts', array( $this,'enqueue_custom_styles') );
	}

    public function enqueue_custom_styles() {
        wp_enqueue_script( 'adminchatgpt', chatgpt_scheduler_URL.'/js/admin.js', array('jquery'));
        wp_enqueue_style( 'adminchatgpt_css',chatgpt_scheduler_URL.'/css/admin.css');
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
        add_submenu_page('ChatGPTScheduler', "Settings", "Settings", $capability, 'ChatGPTScheduler_settings',array($this,'ChatGPTScheduler_settings_CBF'),$position);
	}
    
	public function wph_settings_content() { 
        $message;
        $helper = new chatgpt_scheduler_Helper;
        if(isset($_POST['noncetoken_name_chatGPT_schedule_settings']) && wp_verify_nonce($_POST['noncetoken_name_chatGPT_schedule_settings'],'noncetoken_chatGPT_schedule_settings') && isset($_POST['chatGPT_schedule_settings_submitBtn']) ){
            update_option('chatGPT_schedule_settings',$_POST['chatGPT_schedule_settings']);
            $message.= '<div style=" display: block !important;" class="notice inline notice-info notice-alt"><p>Updated</p></div>';

            $ChatGPTScheduler_settings_CBF =  get_option('ChatGPTScheduler_settings_CBF',array());
            $chatGPT_schedule_settings =  get_option('chatGPT_schedule_settings',array());
           
                foreach ($chatGPT_schedule_settings['Pattern'] as $key => $value) {
                    //if($value == 'once' && !wp_next_scheduled( 'cgpt_single_event_cron_schedule_event'))
                        wp_schedule_single_event( strtotime($chatGPT_schedule_settings['time'][$key]), 'chatgpt_cron_schedules_schedule_it',array($value,$key));
                } // foreach
        //echo '<pre>';print_r($_POST);echo '</pre>';

        } // if(isset($_POST['noncetoken_name_chatGPT_schedule_settings
        $chatGPT_schedule_settings =  get_option('chatGPT_schedule_settings',array());

        //echo '<pre>';print_r($chatGPT_schedule_settings);echo '</pre>';
      
        $Primary_Keyword_0 = isset($chatGPT_schedule_settings['Primary_Keyword'][0]) ? $chatGPT_schedule_settings['Primary_Keyword'][0] : '';
        $Template_Post_0 = isset($chatGPT_schedule_settings['Template_Post'][0]) ? $chatGPT_schedule_settings['Template_Post'][0] : '';
        $tax_slug_0 = isset($chatGPT_schedule_settings['tax_slug'][0]) ? $chatGPT_schedule_settings['tax_slug'][0] : '';
        $tax_label_0 = isset($chatGPT_schedule_settings['tax_label'][0]) ? $chatGPT_schedule_settings['tax_label'][0] : '';
        $tax_terms = isset($chatGPT_schedule_settings['taxonomy_terms']) ? $chatGPT_schedule_settings['taxonomy_terms'] : array();
        $time_0 = isset($chatGPT_schedule_settings['time'][0]) ? $chatGPT_schedule_settings['time'][0] : '';
        $Pattern_0 = isset($chatGPT_schedule_settings['Pattern'][0]) ? $chatGPT_schedule_settings['Pattern'][0] : '';
        $post_status_0 = isset($chatGPT_schedule_settings['post_status'][0]) ? $chatGPT_schedule_settings['post_status'][0] : '';
        $Temperature_0 = isset($chatGPT_schedule_settings['Temperature'][0]) ? $chatGPT_schedule_settings['Temperature'][0] : '';

   
        ?>
		<div class="wrap">
			<h1>ChatGPT Scheduler</h1>
            <?php echo $message?>
            <form name="chatGPT_schedule_settings" id="chatGPT_schedule_settings" method="POST">
                <table id="wrapper_content" class="wp-list-table widefat striped ChatGPT_scheduler_Table">
                    <tr>
                        <th>Prompt Type</th>
                        <th>Topic</th>
                        <th>Temperature</th>
                        <th>Template Posts</td>
                        <th>Schedule Time</th>
                        <th>Schedule Pattern</th>
                        <th>Post Status</th>
                        <th>Add/ Remove</th>
                    </tr>
                    <tr id="copy_content">
                        <td>ChatGPT</td>
                        <td><input type="text" name="chatGPT_schedule_settings[Primary_Keyword][]" class="Primary_Keyword" value="<?php echo $Primary_Keyword_0?>" /></td>
                        <td><input class="range-slider__range" name="chatGPT_schedule_settings[Temperature][]" type="range" value="<?php echo $Temperature_0?>" min="0" max="1" step="0.1" /><span class="range-slider__value"><?php echo $Temperature_0?></span></td>
                        <td><?php echo $helper->get_template_posts_dropdown($Template_Post_0)?></td>
                        <td><input type="datetime-local" class="time" name="chatGPT_schedule_settings[time][]" value="<?php echo $time_0?>" /></td>
                        <td><?php echo $helper->schedule_pattern_dropdown($Pattern_0)?></td>
                        <td><?php echo $helper->schedule_post_status_dropdown($post_status_0)?></td>
                        <td><span id="ChatGPT_scheduler_copy" class="dashicons dashicons-plus-alt add_record"></span></td>
                    <?php 
                  if(isset($chatGPT_schedule_settings['Primary_Keyword']) && count($chatGPT_schedule_settings['Primary_Keyword']) > 1)
                    echo $helper->get_saved_schedules()?>

                        
                </table>
                
                <p>
                    <input type="submit" name="chatGPT_schedule_settings_submitBtn" value="Save Settings" class="button button-primary" />
                </p>
                <?php wp_nonce_field( 'noncetoken_chatGPT_schedule_settings', 'noncetoken_name_chatGPT_schedule_settings' );?>
        </form>
		</div> <?php
	}


    function ChatGPTScheduler_settings_CBF(){
        $message='';
        if(isset($_POST['noncetoken_name_chatGPT_schedule_settings']) && wp_verify_nonce($_POST['noncetoken_name_chatGPT_schedule_settings'],'noncetoken_chatGPT_schedule_settings')){
            update_option('ChatGPTScheduler_settings_CBF',$_POST['ChatGPTScheduler_settings_CBF']);
            $message.= '<div style=" display: block !important;" class="notice inline notice-info notice-alt"><p>Updated</p></div>';
        }
        $ChatGPTScheduler_settings_CBF =  get_option('ChatGPTScheduler_settings_CBF',array());
      
      
        ?>
    <h1>Settings</h1>
    <form name="ChatGPTScheduler_settings_CBF" method="POST">
        <table class="form-table" role="presentation">
            <tr>
                <th>Gigsix Connect Key</th>
                <td><input name="ChatGPTScheduler_settings_CBF[key]" type="text" value="<?php echo (isset($ChatGPTScheduler_settings_CBF['key']) ? $ChatGPTScheduler_settings_CBF['key'] : '')?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th>OPENAI Token</th>
                <td><input name="ChatGPTScheduler_settings_CBF[token]" type="text" value="<?php echo (isset($ChatGPTScheduler_settings_CBF['token']) ? $ChatGPTScheduler_settings_CBF['token'] : '')?>" class="regular-text" /></td>
            </tr>
        </table>
        <?php wp_nonce_field( 'noncetoken_chatGPT_schedule_settings', 'noncetoken_name_chatGPT_schedule_settings' );?>
        <p>
            <input type="submit" name="ChatGPTScheduler_settings_CBF_submitBtn" value="Save Settings" class="button button-primary" />
        </p>
    </form>
<?php
    }
    
}//class
new ChatGPTScheduler_Settings_Page();
                