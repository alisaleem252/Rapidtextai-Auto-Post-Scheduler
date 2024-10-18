<?php
use Curl\Curl;
class ChatGPTScheduler_Settings_Page {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'wph_create_settings' ) );
        add_action( 'admin_enqueue_scripts', array( $this,'enqueue_custom_styles') );
        add_action( 'wp_ajax_rapidtextai_save_api_key', array( $this, 'rapidtextai_save_api_key' ) );
	}

    public function enqueue_custom_styles() {
        $ChatGPTScheduler_settings_CBF =  get_option('ChatGPTScheduler_settings_CBF',array('key'=>'trial'));
        wp_enqueue_script( 'adminchatgpt', rapidtextai_chatgpt_scheduler_URL.'/js/admin.js?v=4.1146', array('jquery'));
        wp_add_inline_script('adminchatgpt', 'var rapidtextaiURL = "' . rapidtextai_chatgpt_scheduler_network . '"; var gigsixkey = "' . $ChatGPTScheduler_settings_CBF['key'] . '";');
        wp_enqueue_script( 'adminchatgptTyped', rapidtextai_chatgpt_scheduler_URL.'/js/typed.min.js', array('jquery'));
        wp_enqueue_style( 'adminchatgpt_css',rapidtextai_chatgpt_scheduler_URL.'/css/admin.css');
    }              

	public function wph_create_settings() {
		$page_title = 'RapidTextAI Scheduler';
		$menu_title = 'RapidTextAI Scheduler';
		$capability = 'manage_options';
		$slug = 'ChatGPTScheduler';
		$callback = array($this, 'wph_settings_content');
                $icon = 'dashicons-welcome-write-blog';
		$position = 75;
		add_menu_page($page_title, $menu_title, $capability, $slug, $callback, $icon, $position);
        add_submenu_page('ChatGPTScheduler', "Settings", "Settings", $capability, 'ChatGPTScheduler_settings',array($this,'ChatGPTScheduler_settings_CBF'),$position);
	}
    
	public function wph_settings_content() { 
        $message = '';
        $helper = new rapidtextai_chatgpt_scheduler_Helper;
        $scheduler = new ChatGPT_Cron_Schedules;
        if(isset($_POST['noncetoken_name_chatGPT_schedule_settings']) && wp_verify_nonce($_POST['noncetoken_name_chatGPT_schedule_settings'],'noncetoken_chatGPT_schedule_settings') && isset($_POST['chatGPT_schedule_settings_submitBtn']) ){
            update_option('chatGPT_schedule_settings',$_POST['chatGPT_schedule_settings']);
            $message.= '<div style=" display: block !important;" class="notice inline notice-info notice-alt"><p>Updated</p></div>';

            $ChatGPTScheduler_settings_CBF =  get_option('ChatGPTScheduler_settings_CBF',array('key'=>'trial'));
            $chatGPT_schedule_settings =  get_option('chatGPT_schedule_settings',array());
                foreach ($chatGPT_schedule_settings['Pattern'] as $key => $value) {
                    $scheduler->schedule_it($value,$key);
                } // foreach
        } // if(isset($_POST['noncetoken_name_chatGPT_schedule_settings
        $chatGPT_schedule_settings =  get_option('chatGPT_schedule_settings',array());
      
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
			<h1><?php _e('RapidTextAI Scheduler','rapidtextai_chatgpt_scheduler') ?></h1>
            <?php echo $message?>
            <form name="chatGPT_schedule_settings" id="chatGPT_schedule_settings" method="POST">
                <table id="wrapper_content" class="wp-list-table widefat striped ChatGPT_scheduler_Table">
                    <tr>
                        <th><?php _e('Prompt Type','rapidtextai_chatgpt_scheduler') ?></th>
                        <th><?php _e('Topic','rapidtextai_chatgpt_scheduler') ?></th>
                        <th><?php _e('Temperature','rapidtextai_chatgpt_scheduler') ?></th>
                        <th><?php _e('Template Posts','rapidtextai_chatgpt_scheduler') ?></td>
                        <th><?php _e('Schedule Time ','rapidtextai_chatgpt_scheduler'); echo date('d/m/y h:i A') ?></th>
                        <th><?php _e('Schedule Pattern','rapidtextai_chatgpt_scheduler') ?></th>
                        <th><?php _e('Post Status','rapidtextai_chatgpt_scheduler') ?></th>
                        <th><?php _e('Add/ Remove','rapidtextai_chatgpt_scheduler') ?></th>
                    </tr>
                    <tr id="copy_content">
                        <td><?php _e('Custom','rapidtextai_chatgpt_scheduler') ?></td>
                        <td><input type="text" name="chatGPT_schedule_settings[Primary_Keyword][]" class="Primary_Keyword" value="<?php echo $Primary_Keyword_0?>" /></td>
                        <td><input class="range-slider__range" name="chatGPT_schedule_settings[Temperature][]" type="range" value="<?php echo $Temperature_0?>" min="0" max="1" step="0.1" /><span class="range-slider__value"><?php echo $Temperature_0?></span></td>
                        <td><?php echo $helper->get_template_posts_dropdown($Template_Post_0)?></td>
                        <td><input type="datetime-local" class="time" name="chatGPT_schedule_settings[time][]" value="<?php echo $time_0?>" /></td>
                        <td><?php echo $helper->schedule_pattern_dropdown($Pattern_0)?></td>
                        <td><?php echo $helper->schedule_post_status_dropdown($post_status_0)?></td>
                        <td><span id="ChatGPT_scheduler_copy" class="dashicons dashicons-plus-alt add_record"></span></td>
                    <?php 
                  if(isset($chatGPT_schedule_settings['Primary_Keyword']) && is_array($chatGPT_schedule_settings['Primary_Keyword']) && count($chatGPT_schedule_settings['Primary_Keyword']) > 1)
                    echo $helper->get_saved_schedules()?>

                        
                </table>
                
                <p>
                    <input type="submit" name="chatGPT_schedule_settings_submitBtn" value="<?php _e('Save Settings','rapidtextai_chatgpt_scheduler') ?>" class="button button-primary" />
                </p>
                <?php wp_nonce_field( 'noncetoken_chatGPT_schedule_settings', 'noncetoken_name_chatGPT_schedule_settings' );?>
        </form>
		</div> <?php
	}


    function ChatGPTScheduler_settings_CBF(){
      
       // print_r($curl);
        $message='';
        if(isset($_POST['noncetoken_name_chatGPT_schedule_settings']) && wp_verify_nonce($_POST['noncetoken_name_chatGPT_schedule_settings'],'noncetoken_chatGPT_schedule_settings')){
            update_option('ChatGPTScheduler_settings_CBF',$_POST['ChatGPTScheduler_settings_CBF']);
            $message.= '<div style=" display: block !important;" class="notice inline notice-info notice-alt"><p>Updated</p></div>';
        }
        $ChatGPTScheduler_settings_CBF =  get_option('ChatGPTScheduler_settings_CBF',array('key'=>'trial'));
      
        $curl = new Curl();
        $curl->disableTimeout();
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $curl->get('https://app.rapidtextai.com/api.php?gigsixkey='.(isset($ChatGPTScheduler_settings_CBF['key']) ? $ChatGPTScheduler_settings_CBF['key'] : ''));
        ?>
    <h1><?php _e('Settings','rapidtextai_chatgpt_scheduler') ?></h1>
    <form name="ChatGPTScheduler_settings_CBF" method="POST">
        <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><label><?php esc_html_e('RapidTextAI Authentication', 'rapidtextai'); ?></label></th>
                    <td>
                        <input id="api_key" type="hidden" name="ChatGPTScheduler_settings_CBF[key]" value="<?php echo $ChatGPTScheduler_settings_CBF['key']; ?>" class="regular-text" />
                        <button type="button" id="rapidtextai_auth_button" class="button button-primary"><?php esc_html_e('Authenticate with RapidTextAI', 'rapidtextai'); ?></button>
                        <p id="rapidtextai_status_message"></p>
                        <?php if (!empty($ChatGPTScheduler_settings_CBF['key'])) { ?>
                            <p><?php esc_html_e('API Key is already set. You can authenticate again to refresh the key.', 'rapidtextai'); ?></p>
                        <?php } ?>
                    </td>
                </tr>
            <!-- add a radio to enable to disable service -->
            <tr>
                <th><?php _e('Enable/Disable Auto Blogging','rapidtextai_chatgpt_scheduler') ?></th>
                <td>
                    <input type="radio" name="ChatGPTScheduler_settings_CBF[service]" value="enable" <?php echo (isset($ChatGPTScheduler_settings_CBF['service']) && $ChatGPTScheduler_settings_CBF['service'] == 'enable' ? 'checked' : '')?> /> <?php _e('Enable','rapidtextai_chatgpt_scheduler') ?>
                    <input type="radio" name="ChatGPTScheduler_settings_CBF[service]" value="disable" <?php echo (isset($ChatGPTScheduler_settings_CBF['service']) && $ChatGPTScheduler_settings_CBF['service'] == 'disable' ? 'checked' : '')?> /> <?php _e('Disable','rapidtextai_chatgpt_scheduler') ?>
                </td>
            </tr>
            <tr>
                <th><?php _e('Status of rapidtextai Connect Key Subscription','rapidtextai_chatgpt_scheduler') ?></th>
                <td>
                    <div id="rapidtextai_status">Loading...</div>
                </td>                
            </tr>
            <tr>
                <th>About the AI Model</th>
                <td>
                <h2>RapidTextAI</h2>
                <p>RAPIDTEXTAI.COM harness the power of artificial intelligence. Users can input information such as topic, keywords, industry, and desired highlights. Our AI system then generates content based on these inputs, helping you streamline your copywriting process.                .</p>
            </td>
            </tr>
            <tr>
                <th>Cron Debug</th>
                <td><a href="?page=ChatGPTScheduler_settings&debug=1">Enable </a> | <a href="?page=ChatGPTScheduler_settings">Disable </a> </td>
            </tr>
        </table>
        <?php wp_nonce_field( 'noncetoken_chatGPT_schedule_settings', 'noncetoken_name_chatGPT_schedule_settings' );?>
        <p>
            <input type="submit" name="ChatGPTScheduler_settings_CBF_submitBtn" value="Save Settings" class="button button-primary" />
        </p>
    </form>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('#rapidtextai_auth_button').on('click', function(e) {
                e.preventDefault();
                var authWindow = window.open('https://app.rapidtextai.com/log-in?action=popup', 'RapidTextAIAuth', 'width=500,height=600');
            });

            window.addEventListener('message', function(event) {
                // Only accept messages from the trusted RapidTextAI origin
                alert('Authenticated');
                if (event.origin === 'https://app.rapidtextai.com') {
                    var apiKey = event.data.api_key;
                    if (apiKey) {
                        $('#rapidtextai_status_message').html('Authentication successful! Saving API key...');

                        $.post(ajaxurl, {
                            action: 'rapidtextai_save_api_key',
                            api_key: apiKey,
                            _wpnonce: '<?php echo wp_create_nonce('rapidtextai_save_api_key_nonce'); ?>'
                        }, function(response) {
                            $('#rapidtextai_status_message').html(response.message);
                            $('#api_key').val(apiKey);
                        });
                    }
                }
            });

            /** Get Response using API */
        });

        jQuery(document).ready(function($) {
            // Get the connect key from the input field
            var connectKey = '<?php echo $ChatGPTScheduler_settings_CBF['key']; ?>';

            // Make the AJAX request using jQuery
            $.ajax({
                url: 'https://app.rapidtextai.com/api.php',
                type: 'GET',
                data: {
                    gigsixkey: connectKey
                },
                dataType: 'json',
                success: function(response_data) {
                    var output = '';

                    if (response_data.response_code) {
                        var code = response_data.response_code;

                        if (code == 1 || code == 2 || code == 4) {
                            output += '<table class="form-table">';
                            output += '<tr><th>Created</th><td>' + (code == 1 ? response_data.create_at : 'N/A') + '</td></tr>';
                            output += '<tr><th>Status</th><td>' + (code == 1 ? response_data.subscription_status : 'Trial') + '</td></tr>';
                            output += '<tr><th>Interval</th><td>' + (code == 1 ? response_data.subscription_interval : 'N/A') + '</td></tr>';
                            output += '<tr><th>Start</th><td>' + (code == 1 ? response_data.current_period_start : 'N/A') + '</td></tr>';
                            output += '<tr><th>End</th><td>' + (code == 1 ? response_data.current_period_end : 'N/A') + '</td></tr>';
                            output += '<tr><th>Requests</th><td>' + (code == 1 ? response_data.requests + '/ ∞' : response_data.requests + '/ 100') + '</td></tr>';
                            output += '</table>';
                        } else {
                            output = response_data.message;
                        }
                    } else {
                        output = 'Error retrieving data';
                    }

                    // Place the response in the div with id rapidtextai_status
                    $('#rapidtextai_status').html(output);
                },
                error: function() {
                    $('#rapidtextai_status').html('Error connecting to the server');
                }
            });
        });

    </script>
<?php
    if(isset($_REQUEST['debug'])){
    echo '<textarea rows="20" cols="150">'.get_option('mam_fbads_debug').'</textarea>';}
    }

    function rapidtextai_save_api_key() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Permission denied.'));
        }

        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field($_POST['_wpnonce']), 'rapidtextai_save_api_key_nonce')) {
            wp_send_json_error(array('message' => 'Nonce verification failed.'));
        }

        $api_key = sanitize_text_field($_POST['api_key']);
        $ChatGPTScheduler_settings_CBF = get_option('ChatGPTScheduler_settings_CBF');
        $ChatGPTScheduler_settings_CBF['key'] = $api_key;
        update_option('ChatGPTScheduler_settings_CBF', $ChatGPTScheduler_settings_CBF);

        wp_send_json_success(array('message' => 'API Key saved successfully.'));
    }

    
}//class
new ChatGPTScheduler_Settings_Page();
