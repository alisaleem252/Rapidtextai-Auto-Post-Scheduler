<?php
require rapidtextai_chatgpt_scheduler_PATH.'/lib/vendor/autoload.php';
use Curl\Curl;

class rapidtextai_chatgpt_scheduler_Helper {
    public function rapidtextai_content($topic,$temprature,$lang,$tone){
        $curl = new Curl();
        $curl->disableTimeout();
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
            $ChatGPTScheduler_settings_CBF =  get_option('ChatGPTScheduler_settings_CBF',array('key'=>'trial'));
            if($ChatGPTScheduler_settings_CBF['key'] == 'trial')
            $curl->post(rapidtextai_chatgpt_scheduler_network.'trial/?gigsixkey=trial',array("topic"=>$topic,"temperature"=>$temprature,"language"=>$lang,"tone"=>$tone));
            else
            $curl->post(rapidtextai_chatgpt_scheduler_network.'detailedarticle-v2/?gigsixkey='.$ChatGPTScheduler_settings_CBF['key'],array("topic"=>$topic,"temperature"=>$temprature,"language"=>$lang,"tone"=>$tone));

            if(isset($curl->response->content)){
                //$content = str_replace('```json','',$curl->response->content);
                //$content = str_replace('```','',$content);
                $content = $this->process_content($curl->response);
                
                //$title = $curl->response->title;
                $result = wpautop($content);
            }
            else {
                $result = array('error',$curl->errorMessage);
            }
            return $result;
    }
    public function process_content($content_array){
        $content_array = json_decode($content_array);
        $context = '<ul>';
        $main_content = '';
        $paras =$content_array->paragraphs;
        $intro ='<p>'.$content_array->intro.'</p>';
        foreach($content_array->headings as $k => $headings){
            $context.='<li><a href="#section_'.$k.'">'.$headings.'</a></li>';
            $main_content.='<h2 id="section_'.$k.'">'.$headings.'</h2>';
            $main_content.='<p>'.$paras[$k].'</p>';
        }
        $context.='</ul>';
        $result = $context.$intro.$main_content;
        return $result;

    }
    public function process_content_scheduler($content){
        
        $content_array = $content;
        if(isset($content_array->paragraphs) && is_array($content_array->paragraphs)){
            //$content_array = $content_array[0];
            $this->log('Content Array');
            $this->log(print_r($content_array,true));
            $paras =$content_array->paragraphs;
            $context = '<ul>';
            $main_content = '';
            $intro ='<p>'.$content_array->intro.'</p>';
            foreach($content_array->headings as $k => $headings){
                $context.='<li><a href="#section_'.$k.'">'.$headings.'</a></li>';
                $main_content.='<h2 id="section_'.$k.'">'.$headings.'</h2>';
                $main_content.='<p>'.(is_array($paras[$k]) ? $paras[$k][0]  : $paras[$k]).'</p>';
            }
            $context.='</ul>';
            $result = $context.$intro.$main_content;        
            //var_dump($content);
            //exit;
            return ['title'=>$content_array->title,'content'=>$result];
        }
        else {
            return ['title'=>'','content'=>''];
        }
    }
    public function get_post_types_dropdown($selected='') {
        $post_types = get_post_types(array('public' => true), 'objects');
        echo '<select class="chatGPT_schedule_settings_post_type" name="chatGPT_schedule_settings[Post_Type][]">';
        echo '<option>Select Post Type</option>';
        foreach ($post_types as $post_type) {
        echo '<option '.($selected == $post_type->name ? 'selected' : '').' value="' . $post_type->name . '">' . $post_type->labels->singular_name . '</option>';
        }
        echo '</select>';
    }
    public function get_template_posts_dropdown($selected='') {
        global $wpdb;
        $template_posts = $wpdb->get_results("SELECT p.ID,p.post_title FROM $wpdb->posts p,$wpdb->postmeta pm WHERE p.ID=pm.post_id AND pm.meta_key='chatgpt_used_as_cgpt_templater' AND pm.meta_value='yes' AND p.post_status NOT LIKE 'inherit' AND p.post_status NOT LIKE 'trash'");
        echo '<select class="chatGPT_schedule_settings_post_type" name="chatGPT_schedule_settings[Template_Post][]">';
        if(isset($template_posts[0])){
            foreach ($template_posts as $template_post) 
            echo '<option '.($selected == $template_post->ID ? 'selected' : '').' value="' . $template_post->ID . '">' . $template_post->post_title . '</option>';
        }
        echo '</select>';
    }
    public function schedule_pattern_dropdown($selected=''){
        $patterns = array('cgpt_single_event'=>'Once','cgpt_everyhour'=>'Repeat Hourly','cgpt_everyday'=>'Repeat Daily','cgpt_everytwelve_hour'=>'Repeat Twice Daily','cgpt_everyweek'=>'Repeat Weekly','cgpt_everymonth'=>'Repeat Monthly','cgpt_everyyear'=>'Repeat Yearly');
        echo '<select name="chatGPT_schedule_settings[Pattern][]" class="Pattern">';
        foreach ($patterns as $key=>$pattern) {
        echo '<option '.($selected == $key ? 'selected' : '').'  value="' . $key . '">' . $pattern . '</option>';
        }
        echo '</select>';
    }
    public function schedule_post_status_dropdown($selected=''){
        $post_statuses = array('publish','draft');
        echo '<select name="chatGPT_schedule_settings[post_status][]">';
        foreach ($post_statuses as $post_status) {
        echo '<option '.($selected == $post_status ? 'selected' : '').'  value="' . $post_status . '">' . ucfirst($post_status) . '</option>';
        }
        echo '</select>';
    }
    public function get_saved_schedules(){
        $chatGPT_schedule_settings = get_option('chatGPT_schedule_settings',false);
        if($chatGPT_schedule_settings){
            foreach($chatGPT_schedule_settings['Primary_Keyword'] as $index => $chatGPT){
                if($index == 0)
                    continue;
                    
                $tax_terms = isset($chatGPT_schedule_settings['taxonomy_terms']) ? $chatGPT_schedule_settings['taxonomy_terms'] : array();
                    ?>
                 <table class="wp-list-table widefat striped ChatGPT_scheduler_Table">
                    <tbody>
                        <tr>
                            <td><?php _e('Prompt Type','rapidtextai_chatgpt_scheduler') ?></td>
                            <td><?php _e('Custom','rapidtextai_chatgpt_scheduler') ?></td>
                        </tr>
                        <tr>
                            <td><?php _e('Topic','rapidtextai_chatgpt_scheduler') ?></td>
                            <td><input type="text" name="chatGPT_schedule_settings[Primary_Keyword][]" class="Primary_Keyword" value="<?php echo $chatGPT_schedule_settings['Primary_Keyword'][$index]?>" /></td>
                        </tr>
                        <tr>
                            <td><?php _e('Keywords','rapidtextai_chatgpt_scheduler') ?></td>
                            <td><input type="text" name="chatGPT_schedule_settings[Primary_Keyword2][]" class="Primary_Keyword" value="<?php echo $chatGPT_schedule_settings['Primary_Keyword2'][$index]?>" /></td>
                        </tr>
                        <tr>
                            <td><?php _e('Temperature','rapidtextai_chatgpt_scheduler') ?></td>
                            <td><input type="range" name="chatGPT_schedule_settings[Temperature][]" class="range-slider__range" value="<?php echo $chatGPT_schedule_settings['Temperature'][$index]?>" min="0" max="1" step="0.1" /> <span class="range-slider__value"><?php echo $chatGPT_schedule_settings['Temperature'][$index]?></span></td>
                        </tr>
                        <tr>
                            <td><?php _e('Template Posts','rapidtextai_chatgpt_scheduler') ?></td>
                            <td><?php echo $this->get_template_posts_dropdown($chatGPT_schedule_settings['Template_Post'][$index])?></td>
                        </tr>
                        <tr>
                            <td><?php _e('Schedule Time','rapidtextai_chatgpt_scheduler') ?></td>
                            <td><input type="datetime-local" name="chatGPT_schedule_settings[time][]" class="time" value="<?php echo $chatGPT_schedule_settings['time'][$index]?>" /></td>
                        </tr>
                        <tr>
                            <td><?php _e('Schedule Pattern','rapidtextai_chatgpt_scheduler') ?></td>
                            <td><?php echo $this->schedule_pattern_dropdown($chatGPT_schedule_settings['Pattern'][$index])?></td>
                        </tr>
                        <tr>
                            <td><?php _e('Post Status','rapidtextai_chatgpt_scheduler') ?></td>
                            <td><?php echo $this->schedule_post_status_dropdown($chatGPT_schedule_settings['post_status'][$index])?></td>
                        </tr>
                        <tr>
                            <td><?php _e('Add/ Remove','rapidtextai_chatgpt_scheduler') ?></td>
                            <td><span id="ChatGPT_scheduler_copy_<?php echo $index?>" class="dashicons dashicons-dismiss remove_record"></span>
                            <span class="remove_record dashicons dashicons-trash"></span>        
                        </td>
                        </tr>
                    </tbody>
                </table>

            <?php }
        }
    }
    public function duplicate_post($template_post_id,$title,$content,$status){
       
	// Get the original post id
	$post_id = absint( $template_post_id );

	// And all the original post data then
	$post = get_post( $post_id );

	/*
	 * if you don't want current user to be the new post author,
	 * then change next couple of lines to this: $new_post_author = $post->post_author;
	 */

	// if post data exists (I am sure it is, but just in a case), create the post duplicate
	if ( $post ) {

		// new post data array
		$args = array(
			'comment_status' => $post->comment_status,
			'ping_status'    => $post->ping_status,
			'post_content'   => $content,
			'post_parent'    => $post->post_parent,
			'post_password'  => $post->post_password,
			'post_status'    => $status,
			'post_title'     => $title,
			'post_type'      => $post->post_type,
			'to_ping'        => $post->to_ping,
			'menu_order'     => $post->menu_order
		);

		// insert the post by wp_insert_post() function
		$new_post_id = wp_insert_post( $args );

		/*
		 * get all current post terms ad set them to the new post draft
		 */
		$taxonomies = get_object_taxonomies( get_post_type( $post ) ); // returns array of taxonomy names for post type, ex array("category", "post_tag");
		if( $taxonomies ) {
			foreach ( $taxonomies as $taxonomy ) {
				$post_terms = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'slugs' ) );
				wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, false );
			}
		}

		// duplicate all post meta
		$post_meta = get_post_meta( $post_id );
		if( $post_meta ) {

			foreach ( $post_meta as $meta_key => $meta_values ) {

				if( '_wp_old_slug' == $meta_key) { // do nothing for this meta key
					continue;
				}

				foreach ( $meta_values as $meta_value ) {
					add_post_meta( $new_post_id, $meta_key, $meta_value );
				}
			}
		}


	    return $new_post_id;
	}
    }

    /**
	 * This function Logs all errors in the WordPress Facebook Ads Menu Text Area.
	 * $log (string)
	 * $append (bolean)
	 */
	function log($log,$append = true){
		$logs = get_option('mam_fbads_debug');
		if($append){
			$logs.=date("Y/m/d h:i:sa") ."\r\n ".$log." \r\n";
			update_option('mam_fbads_debug',$logs);
			//echo '<pre>';
			//print_r($logs);
		}
		else {
			update_option('mam_fbads_debug',date("Y/m/d h:i:sa") ."\r\n ".$log." \r\n");
		}
		if(strlen($log) > 100000)

			update_option('mam_fbads_debug','');
			//

	}

    function rapidtextai_simple_markdown_to_html($markdown) {
        // Convert headers (we'll use a callback inside preg_replace_callback instead of preg_replace)
        $markdown = preg_replace_callback('/^(#{1,6})\s*(.*?)\s*#*\s*(?:\n+|$)/m', function($matches) {
            $level = strlen($matches[1]);
            return "<h{$level}>{$matches[2]}</h{$level}>";
        }, $markdown);
    
        // Convert bold (**text** or __text__)
        $markdown = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $markdown);
        $markdown = preg_replace('/__(.*?)__/', '<strong>$1</strong>', $markdown);
    
        // Convert italic (*text* or _text_)
        $markdown = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $markdown);
        $markdown = preg_replace('/_(.*?)_/', '<em>$1</em>', $markdown);
    
        // Convert links [text](url)
        $markdown = preg_replace('/\[([^\[]+)\]\((.*?)\)/', '<a href="$2">$1</a>', $markdown);
    
        // Convert unordered lists
        $markdown = preg_replace('/^\s*[\*\+\-]\s+(.*)/m', '<li>$1</li>', $markdown);
        $markdown = preg_replace('/(<li>.*<\/li>)/s', '<ul>$1</ul>', $markdown);
    
        // Convert ordered lists
        $markdown = preg_replace('/^\d+\.\s+(.*)/m', '<li>$1</li>', $markdown);
        $markdown = preg_replace('/(<li>.*<\/li>)/s', '<ol>$1</ol>', $markdown);
    
        // Convert blockquotes
        $markdown = preg_replace('/^\s*>\s+(.*)/m', '<blockquote>$1</blockquote>', $markdown);
    
        // Convert code blocks
        $markdown = preg_replace('/```(.*?)```/s', '<pre><code>$1</code></pre>', $markdown);
    
        // Convert inline code
        $markdown = preg_replace('/`([^`]+)`/', '<code>$1</code>', $markdown);
    
        // Convert newlines to paragraphs
        $markdown = preg_replace('/\n\n/', '</p><p>', $markdown);
        $markdown = '<p>' . $markdown . '</p>';  // Wrap with paragraph tags
    
        // Cleanup multiple paragraph tags
        $markdown = str_replace('<p></p>', '', $markdown);
    
        return $markdown;
    }
    
}
