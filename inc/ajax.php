<?php
class ChatGPT_scheduler_ajax{
    function __construct(){
      add_action('wp_ajax_chatGPT_schedule_get_taxonomy', array($this,'get_taxonomy_callback'));
      add_action('wp_ajax_nopriv_chatGPT_schedule_get_taxonomy', array($this,'get_taxonomy_callback'));

       add_action('wp_ajax_chatGPT_schedule_get_taxonomy_terms', array($this,'get_taxonomy_terms_callback'));
    }
    function get_taxonomy_callback() {
        $post_type = $_POST['post_type'];
        $taxonomies = get_taxonomies(array('object_type' => array($post_type),'public'=>true),'objects');
        //print_r($taxonomies);
        foreach ($taxonomies as $taxonomy) {
            $taxonomy_label = $taxonomy->label;
            $taxonomy_slug = $taxonomy->name;
            $tax_terms_html = '<input type="hidden" name="chatGPT_schedule_settings[tax_label][]" value="'.$taxonomy_label.'" /><input type="hidden" name="chatGPT_schedule_settings[tax_slug][]" value="'.$taxonomy_slug.'" />';
            break;
        }

        $taxonomy_terms = get_terms($taxonomy_slug,array('hide_empty'=>0));
        if(isset($taxonomy_terms[0])){
            foreach ($taxonomy_terms as $key => $value)
              $tax_terms_html .= '<div><input type="checkbox" name="chatGPT_schedule_settings[taxonomy_terms][]" value="'.($value->term_id).'" /> '.($value->name).'</div>';
        }

        echo json_encode(array('tax_label'=>$taxonomy_label,'tax_slug'=>$taxonomy_slug,'tax_terms_html'=>$tax_terms_html));
       
       //echo $select;
        die();
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
$scheduler_ajax = new ChatGPT_scheduler_ajax;
