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
        wp_enqueue_script( 'adminchatgpt', chatgpt_scheduler_PATH.'/js/admin.js', array('jQuery'),false);
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
            $message.= '<div style=" display: block !important;" class="notice inline notice-info notice-alt"><p>Updated</p></div>';
           echo '<pre>';print_r($_POST);echo '</pre>';
        }
        $chatGPT_schedule_settings =  get_option('chatGPT_schedule_settings',array());

       // $taxonomies = get_object_taxonomies(get_post_types(array('public'=> true,'_builtin'=>false ), 'names', 'and' ));

        $args = array(
            'public'=>true,
            'object_type'=> array('product')
            );
        //   $output = 'objects'; // or objects
        //    $operator = ''; // 'and' or 'or'
//$taxonomies = get_taxonomies( $args,'objects');
      $taxonomies = get_taxonomies(array('object_type' => array('product'),'public'=>true),'objects');
      $taxonomy_objects = get_terms('category',array('hide_empty'=>0));
      echo '<pre>';print_r($chatGPT_schedule_settings);echo '</pre>';
      
        $Primary_Keyword_0 = isset($chatGPT_schedule_settings['Primary_Keyword'][0]) ? $chatGPT_schedule_settings['Primary_Keyword'][0] : '';
        $Post_Type_0 = isset($chatGPT_schedule_settings['Post_Type'][0]) ? $chatGPT_schedule_settings['Post_Type'][0] : '';
        $tax_slug_0 = isset($chatGPT_schedule_settings['tax_slug'][0]) ? $chatGPT_schedule_settings['tax_slug'][0] : '';
        $tax_label_0 = isset($chatGPT_schedule_settings['tax_label'][0]) ? $chatGPT_schedule_settings['tax_label'][0] : '';
        $tax_terms = isset($chatGPT_schedule_settings['taxonomy_terms']) ? $chatGPT_schedule_settings['taxonomy_terms'] : array();
        $time_0 = isset($chatGPT_schedule_settings['time'][0]) ? $chatGPT_schedule_settings['time'][0] : '';
        $Pattern_0 = isset($chatGPT_schedule_settings['Pattern'][0]) ? $chatGPT_schedule_settings['Pattern'][0] : '';
        $post_status_0 = isset($chatGPT_schedule_settings['post_status'][0]) ? $chatGPT_schedule_settings['post_status'][0] : '';

       

        ?>
		<div class="wrap">
			<h1>ChatGPT Scheduler</h1>
            <?php echo $message?>
            <form name="chatGPT_schedule_settings" id="chatGPT_schedule_settings" method="POST">
                <table id="wrapper_content" class="wp-list-table widefat striped ChatGPT_scheduler_Table">
                <thead>
                    <tr>
                        <th>Prompt Type</th>
                        <th>Prompt</th>
                        <th>Post Type</td>
                        <th>Taxonomy</td>
                        <th>Taxonomy Terms</td>
                        <th>Schedule Time</th>
                        <th>Schedule Pattern</th>
                        <th>Post Status</th>
                        <th>Add/ Remove</th>
                    </tr>
                <thead>
                <tbody id="copy_content">
                    <tr>
                        <td>ChatGPT</td>
                        <td><input type="text" name="chatGPT_schedule_settings[Primary_Keyword][]" class="Primary_Keyword" value="<?php echo $Primary_Keyword_0?>" /></td>
                        <td><?php echo $helper->get_post_types_dropdown($Post_Type_0)?></td>
                        <td><span class="ChatGPT_taxonomy"><?php echo $tax_label_0?></span></td>
                        <td><span class="ChatGPT_taxonomy_terms"><?php
                            $tax_terms_html = '<input type="hidden" name="chatGPT_schedule_settings[tax_label][]" value="'.$tax_label_0.'" /><input type="hidden" name="chatGPT_schedule_settings[tax_slug][]" value="'.$tax_slug_0.'" />';
                            $taxonomy_terms = get_terms($tax_slug_0,array('hide_empty'=>0));
                            if($tax_slug_0){
                                if(isset($taxonomy_terms[0])){
                                    foreach ($taxonomy_terms as $key => $value){
                                        $tax_terms_html .= '<div><input type="checkbox" '.(array_search($value->term_id,$tax_terms)!==false ? 'checked' : '').' name="chatGPT_schedule_settings[taxonomy_terms][]" value="'.($value->term_id).'" /> '.($value->name).'</div>';
                                    }
                                    echo $tax_terms_html;
                                }
                            }
                        ?></span></td>
                        <td><input type="time" name="chatGPT_schedule_settings[time][]" value="<?php echo $time_0?>" /></td>
                        <td><?php echo $helper->schedule_pattern_dropdown($Pattern_0)?></td>
                        <td><?php echo $helper->schedule_post_status_dropdown($post_status_0)?></td>
                        <td><span id="ChatGPT_scheduler_copy" class="dashicons dashicons-plus-alt add_record"></span></td>
                    </tr>
                </tbody>
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
                