<?php
class ChatGPT_scheduler_ajax{
    function __construct(){
       add_action('wp_ajax_chatGPT_schedule_get_taxonomy', array($this,'get_taxonomy_callback'));
       add_action('wp_ajax_chatGPT_schedule_get_taxonomy_terms', array($this,'get_taxonomy_terms_callback'));
    }
    function get_taxonomy_callback() {
        $post_type = $_POST['post_type'];
        $taxonomies = get_object_taxonomies($post_type, 'objects');
        $select = '<select id="ChatGPT_taxonomies_select">';
        $select .= '<option value="">Select Taxonomy</option>';
        foreach ($taxonomies as $taxonomy) {
            $select .= '<option value="' . $taxonomy->name . '">' . $taxonomy->label . '</option>';
        }
        $select .= '</select>';
        echo $select;
        wp_die();
    }
    
    function get_taxonomy_terms_callback() {
      $taxonomy = $_POST['taxonomy'];
      $terms = get_terms(array(
        'taxonomy' => $taxonomy,
        'hide_empty' => false,
      ));
      $select = '<select id="ChatGPT_taxonomy_terms_select">';
      $select .= '<option value="">Select Term</option>';
      foreach ($terms as $term) {
        $select .= '<option value="' . $term->term_id . '">' . $term->name . '</option>';
      }
      $select .= '</select>';
      echo $select;
      wp_die();
    }
    
}
