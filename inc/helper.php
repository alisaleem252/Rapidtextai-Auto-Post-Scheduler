<?php

class chatgpt_scheduler_Helper {
    public function get_post_types_dropdown() {
        $post_types = get_post_types(array('public' => true), 'objects');
        echo '<select name="chatGPT_schedule_settings[Post_Type]">';
        foreach ($post_types as $post_type) {
        echo '<option value="' . $post_type->name . '">' . $post_type->labels->singular_name . '</option>';
        }
        echo '</select>';
    }
    public function schedule_pattern_dropdown(){
        $patterns = array('Repeat','Once');
        echo '<select name="chatGPT_schedule_settings[Pattern]">';
        foreach ($patterns as $pattern) {
        echo '<option value="' . $pattern . '">' . $pattern . '</option>';
        }
        echo '</select>';
    }
}