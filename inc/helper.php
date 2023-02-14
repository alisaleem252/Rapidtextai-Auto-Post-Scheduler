<?php

class chatgpt_scheduler_Helper {
    public function get_post_types_dropdown($selected='') {
        $post_types = get_post_types(array('public' => true), 'objects');
        echo '<select class="chatGPT_schedule_settings_post_type" name="chatGPT_schedule_settings[Post_Type][]">';
        foreach ($post_types as $post_type) {
        echo '<option '.($selected == $post_type->name ? 'selected' : '').' value="' . $post_type->name . '">' . $post_type->labels->singular_name . '</option>';
        }
        echo '</select>';
    }
    public function schedule_pattern_dropdown($selected=''){
        $patterns = array('Repeat','Once');
        echo '<select name="chatGPT_schedule_settings[Pattern][]">';
        foreach ($patterns as $pattern) {
        echo '<option '.($selected == $pattern ? 'selected' : '').'  value="' . $pattern . '">' . $pattern . '</option>';
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
                  
                    <tr>
                        <td>ChatGPT</td>
                        <td><input type="text" name="chatGPT_schedule_settings[Primary_Keyword][]" class="Primary_Keyword" value="<?php echo $chatGPT_schedule_settings['Primary_Keyword'][$index]?>" /></td>
                        <td><?php echo $this->get_post_types_dropdown( $chatGPT_schedule_settings['Post_Type'][$index])?></td>
                        <td><span class="ChatGPT_taxonomy"><?php echo $chatGPT_schedule_settings['tax_label'][$index]?></span></td>
                        <td><span class="ChatGPT_taxonomy_terms"><?php
                            $tax_terms_html = '<input type="hidden" name="chatGPT_schedule_settings[tax_label][]" value="'.$chatGPT_schedule_settings['tax_label'][$index].'" /><input type="hidden" name="chatGPT_schedule_settings[tax_slug][]" value="'.$chatGPT_schedule_settings['tax_slug'][$index].'" />';
                            $taxonomy_terms = get_terms($chatGPT_schedule_settings['tax_slug'][$index],array('hide_empty'=>0));
                            if($chatGPT_schedule_settings['tax_slug'][$index]){
                                if(isset($taxonomy_terms[0])){
                                    foreach ($taxonomy_terms as $key => $value){
                                        $tax_terms_html .= '<div><input type="checkbox" '.(array_search($value->term_id,$tax_terms)!==false ? 'checked' : '').' name="chatGPT_schedule_settings[taxonomy_terms][]" value="'.($value->term_id).'" /> '.($value->name).'</div>';
                                    }
                                    echo $tax_terms_html;
                                }
                            }
                        ?></span></td>
                        <td><input type="time" name="chatGPT_schedule_settings[time][]" value="<?php echo $chatGPT_schedule_settings['time'][$index]?>" /></td>
                        <td><?php echo $this->schedule_pattern_dropdown($chatGPT_schedule_settings['Pattern'][$index])?></td>
                        <td><?php echo $this->schedule_pattern_dropdown($chatGPT_schedule_settings['post_status'][$index])?></td>
                        <td><span id="ChatGPT_scheduler_copy_<?php echo $index?>" class="dashicons dashicons-dismiss"></span></td>
                    </tr>

            <?php }
        }
    }
    
}