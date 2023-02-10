<?php

class chatgpt_scheduler_Helper {
    public function get_post_types_dropdown($selected='') {
        $post_types = get_post_types(array('public' => true), 'objects');
        echo '<select id="chatGPT_schedule_settings_post_type" name="chatGPT_schedule_settings[Post_Type][]">';
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
        $post_statuses = array('published','draft');
        echo '<select name="chatGPT_schedule_settings[post_status][]">';
        foreach ($post_statuses as $post_statuse) {
        echo '<option '.($selected == $post_status ? 'selected' : '').'  value="' . $post_status . '">' . $post_status . '</option>';
        }
        echo '</select>';
    }
    public function get_saved_schedules(){
        $chatGPT_schedule_settings = get_option('chatGPT_schedule_settings',false);
        if($chatGPT_schedule_settings){
            foreach($chatGPT_schedule_settings['Primary_Keyword'] as $index->$chatGPT){?>
                <tr>
                        <td>Custom</td>
                        <td colspan=2><input type="text" class="regular-text" name="chatGPT_schedule_settings[Primary_Keyword][]" id="Primary_Keyword" value="<?php echo $chatGPT_schedule_settings['Primary_Keyword'][$index]?>" /></td>
                        <td><?php echo $this->get_post_types_dropdown( $chatGPT_schedule_settings['Post_Type'][$index])?></td>
                        <td><input type="time" name="chatGPT_schedule_settings[time][]" value="<?php echo $chatGPT_schedule_settings['time'][$index]?>" /></td>
                        <td><?php echo $this->schedule_pattern_dropdown($chatGPT_schedule_settings['Pattern'][$index])?></td>
                        <td><span id="ChatGPT_scheduler_copy" class="dashicons dashicons-plus-alt"></span> -- <span id="ChatGPT_scheduler_remove" class="dashicons dashicons-dismiss"></span></td>
                    </tr>
            <?php }
        }
    }
    
}