<?php
  class ChatGPT_Cron_Schedules{
      function __construct(){
        add_filter( 'cron_schedules', array($this,'cron_schedules_CBF'));
        add_action('cgpt_everyhour_cron_schedule_event',array($this,'cgpt_everyhour_cron_schedule_eventCBF'));
        add_action('cgpt_everytwelve_hour_cron_schedule_event',array($this,'cgpt_everytwelve_hour_cron_schedule_eventCBF'));
        add_action('cgpt_everyday_cron_schedule_event',array($this,'cgpt_everyday_cron_schedule_eventCBF'));
        add_action('cgpt_everyweek_cron_schedule_event',array($this,'cgpt_everyweek_cron_schedule_eventCBF'));
        add_action('cgpt_everymonth_cron_schedule_event',array($this,'cgpt_everymonth_cron_schedule_eventCBF'));
        add_action('cgpt_everyyear_cron_schedule_event',array($this,'cgpt_everyyear_cron_schedule_eventCBF'));
        add_action('cgpt_single_event_cron_schedule_event',array($this,'cgpt_single_event_cron_schedule_eventCBF'));
      }

      function cron_schedules_CBF( $schedules ) {
          $schedules['cgpt_everyhour'] = array(
            'interval' => 60*60,
            'display'  => __( 'Cron Job Executed at Every Hour', 'chatgpt_scheduler' ),
          );
          $schedules['cgpt_everytwelve_hour'] = array(
            'interval' => 60*60*12,
            'display'  => __( 'Cron Job Executed at Every 12th Hour', 'chatgpt_scheduler' ),
          );
          $schedules['cgpt_everyday'] = array(
            'interval' => 60*60*24,
            'display'  => __( 'Cron Job Executed at Every Day', 'chatgpt_scheduler' ),
          );
          $schedules['cgpt_everyweek'] = array(
            'interval' => 60*60*24*7,
            'display'  => __( 'Cron Job Executed at Every Week', 'chatgpt_scheduler' ),
          );
          $schedules['cgpt_everymonth'] = array(
            'interval' => 60*60*24*30,
            'display'  => __( 'Cron Job Executed at Every Month', 'chatgpt_scheduler' ),
          );
          $schedules['cgpt_everyyear'] = array(
            'interval' => 60*60*24*360,
            'display'  => __( 'Cron Job Executed at Every Year', 'chatgpt_scheduler' ),
          );
        return $schedules;
      }

      function cgpt_everyhour_cron_schedule_eventCBF(){
          $ChatGPTScheduler_settings_CBF =  get_option('ChatGPTScheduler_settings_CBF',array());
          $chatGPT_schedule_settings =  get_option('chatGPT_schedule_settings',array());

          if(count($chatGPT_schedule_settings) > 0){
              foreach ($chatGPT_schedule_settings['Pattern'] as $key => $value) {
                if($value != 'once'){
                  $concern_terms = array();
                  $tax_slug = $chatGPT_schedule_settings['tax_slug'][$key];
                  $term_ids = get_terms($tax_slug, array('fields' => 'ids') );
                  $settings_tids = $chatGPT_schedule_settings['taxonomy_terms'][$key];
                  $settings_tids = is_array($settings_tids) ? $settings_tids : array();
                  if(isset($term_ids[0])){
                    foreach ($term_ids as $key => $term_id) {
                      if(array_search($term_id,$settings_tids)!==false)
                      $concern_terms[]=$term_id;
                    }
                  }
                  $pid = wp_insert_post(array('post_type'=>$chatGPT_schedule_settings['Post_Type'][$key],'post_title'=>$chatGPT_schedule_settings['Primary_Keyword'][$key],'post_status'=>$chatGPT_schedule_settings['post_status'][$key]));

                  if(isset($concern_terms[0]))
                  wp_set_object_terms($pid,$concern_terms,$tax_slug);
                }
              }
          }
      }
      function cgpt_everytwelve_hour_cron_schedule_eventCBF(){
        $ChatGPTScheduler_settings_CBF =  get_option('ChatGPTScheduler_settings_CBF',array());
        $chatGPT_schedule_settings =  get_option('chatGPT_schedule_settings',array());
        if(count($chatGPT_schedule_settings) > 0){
          foreach ($chatGPT_schedule_settings['Pattern'] as $key => $value) {
            if($value != 'once'){
              $concern_terms = array();
              $tax_slug = $chatGPT_schedule_settings['tax_slug'][$key];
              $term_ids = get_terms($tax_slug, array('fields' => 'ids') );
              $settings_tids = $chatGPT_schedule_settings['taxonomy_terms'][$key];
              $settings_tids = is_array($settings_tids) ? $settings_tids : array();
              if(isset($term_ids[0])){
                foreach ($term_ids as $key => $term_id) {
                  if(array_search($term_id,$settings_tids)!==false)
                  $concern_terms[]=$term_id;
                }
              }
              $pid = wp_insert_post(array('post_type'=>$chatGPT_schedule_settings['Post_Type'][$key],'post_title'=>$chatGPT_schedule_settings['Primary_Keyword'][$key],'post_status'=>$chatGPT_schedule_settings['post_status'][$key]));

              if(isset($concern_terms[0]))
              wp_set_object_terms($pid,$concern_terms,$tax_slug);
            }
          }
        }
      }
      function cgpt_everyday_cron_schedule_eventCBF(){
        $ChatGPTScheduler_settings_CBF =  get_option('ChatGPTScheduler_settings_CBF',array());
        $chatGPT_schedule_settings =  get_option('chatGPT_schedule_settings',array());
          if(count($chatGPT_schedule_settings) > 0){
            foreach ($chatGPT_schedule_settings['Pattern'] as $key => $value) {
              if($value != 'once'){
                $concern_terms = array();
                $tax_slug = $chatGPT_schedule_settings['tax_slug'][$key];
                $term_ids = get_terms($tax_slug, array('fields' => 'ids') );
                $settings_tids = $chatGPT_schedule_settings['taxonomy_terms'][$key];
                $settings_tids = is_array($settings_tids) ? $settings_tids : array();
                if(isset($term_ids[0])){
                  foreach ($term_ids as $key => $term_id) {
                    if(array_search($term_id,$settings_tids)!==false)
                    $concern_terms[]=$term_id;
                  }
                }
                $pid = wp_insert_post(array('post_type'=>$chatGPT_schedule_settings['Post_Type'][$key],'post_title'=>$chatGPT_schedule_settings['Primary_Keyword'][$key],'post_status'=>$chatGPT_schedule_settings['post_status'][$key]));

                if(isset($concern_terms[0]))
                wp_set_object_terms($pid,$concern_terms,$tax_slug);
              }
            }
          }
      }
      function cgpt_everyweek_cron_schedule_eventCBF(){
        $ChatGPTScheduler_settings_CBF =  get_option('ChatGPTScheduler_settings_CBF',array());
        $chatGPT_schedule_settings =  get_option('chatGPT_schedule_settings',array());
          if(count($chatGPT_schedule_settings) > 0){
            foreach ($chatGPT_schedule_settings['Pattern'] as $key => $value) {
              if($value != 'once'){
                $concern_terms = array();
                $tax_slug = $chatGPT_schedule_settings['tax_slug'][$key];
                $term_ids = get_terms($tax_slug, array('fields' => 'ids') );
                $settings_tids = $chatGPT_schedule_settings['taxonomy_terms'][$key];
                $settings_tids = is_array($settings_tids) ? $settings_tids : array();
                if(isset($term_ids[0])){
                  foreach ($term_ids as $key => $term_id) {
                    if(array_search($term_id,$settings_tids)!==false)
                    $concern_terms[]=$term_id;
                  }
                }
                $pid = wp_insert_post(array('post_type'=>$chatGPT_schedule_settings['Post_Type'][$key],'post_title'=>$chatGPT_schedule_settings['Primary_Keyword'][$key],'post_status'=>$chatGPT_schedule_settings['post_status'][$key]));

                if(isset($concern_terms[0]))
                wp_set_object_terms($pid,$concern_terms,$tax_slug);
              }
            }
          }
      }
      function cgpt_everymonth_cron_schedule_eventCBF(){
        $ChatGPTScheduler_settings_CBF =  get_option('ChatGPTScheduler_settings_CBF',array());
        $chatGPT_schedule_settings =  get_option('chatGPT_schedule_settings',array());
          if(count($chatGPT_schedule_settings) > 0){
            foreach ($chatGPT_schedule_settings['Pattern'] as $key => $value) {
              if($value != 'once'){
                $concern_terms = array();
                $tax_slug = $chatGPT_schedule_settings['tax_slug'][$key];
                $term_ids = get_terms($tax_slug, array('fields' => 'ids') );
                $settings_tids = $chatGPT_schedule_settings['taxonomy_terms'][$key];
                $settings_tids = is_array($settings_tids) ? $settings_tids : array();
                if(isset($term_ids[0])){
                  foreach ($term_ids as $key => $term_id) {
                    if(array_search($term_id,$settings_tids)!==false)
                    $concern_terms[]=$term_id;
                  }
                }
                $pid = wp_insert_post(array('post_type'=>$chatGPT_schedule_settings['Post_Type'][$key],'post_title'=>$chatGPT_schedule_settings['Primary_Keyword'][$key],'post_status'=>$chatGPT_schedule_settings['post_status'][$key]));

                if(isset($concern_terms[0]))
                wp_set_object_terms($pid,$concern_terms,$tax_slug);
              }
            }
          }
      }
      function cgpt_everyyear_cron_schedule_eventCBF(){
        $ChatGPTScheduler_settings_CBF =  get_option('ChatGPTScheduler_settings_CBF',array());
        $chatGPT_schedule_settings =  get_option('chatGPT_schedule_settings',array());
        if(count($chatGPT_schedule_settings) > 0){
          foreach ($chatGPT_schedule_settings['Pattern'] as $key => $value) {
            if($value != 'once'){
              $concern_terms = array();
              $tax_slug = $chatGPT_schedule_settings['tax_slug'][$key];
              $term_ids = get_terms($tax_slug, array('fields' => 'ids') );
              $settings_tids = $chatGPT_schedule_settings['taxonomy_terms'][$key];
              $settings_tids = is_array($settings_tids) ? $settings_tids : array();
              if(isset($term_ids[0])){
                foreach ($term_ids as $key => $term_id) {
                  if(array_search($term_id,$settings_tids)!==false)
                  $concern_terms[]=$term_id;
                }
              }
              $pid = wp_insert_post(array('post_type'=>$chatGPT_schedule_settings['Post_Type'][$key],'post_title'=>$chatGPT_schedule_settings['Primary_Keyword'][$key],'post_status'=>$chatGPT_schedule_settings['post_status'][$key]));

              if(isset($concern_terms[0]))
              wp_set_object_terms($pid,$concern_terms,$tax_slug);
            }
          }
        }
      }




      function cgpt_single_event_cron_schedule_eventCBF(){
        $ChatGPTScheduler_settings_CBF =  get_option('ChatGPTScheduler_settings_CBF',array());
        $chatGPT_schedule_settings =  get_option('chatGPT_schedule_settings',array());
          if(count($chatGPT_schedule_settings) > 0){
            foreach ($chatGPT_schedule_settings['Pattern'] as $key => $value) {
              if($value == 'once'){
                $concern_terms = array();
                $tax_slug = $chatGPT_schedule_settings['tax_slug'][$key];
                $term_ids = get_terms($tax_slug, array('fields' => 'ids') );
                $settings_tids = $chatGPT_schedule_settings['taxonomy_terms'][$key];
                $settings_tids = is_array($settings_tids) ? $settings_tids : array();
                if(isset($term_ids[0])){
                  foreach ($term_ids as $key => $term_id) {
                    if(array_search($term_id,$settings_tids)!==false)
                    $concern_terms[]=$term_id;
                  }
                }
                $pid = wp_insert_post(array('post_type'=>$chatGPT_schedule_settings['Post_Type'][$key],'post_title'=>$chatGPT_schedule_settings['Primary_Keyword'][$key],'post_status'=>$chatGPT_schedule_settings['post_status'][$key]));
  
                if(isset($concern_terms[0]))
                wp_set_object_terms($pid,$concern_terms,$tax_slug);
              }
            }
          }
      }
      
      
  } // class
  $ChatGPT_Cron_Schedules = new ChatGPT_Cron_Schedules;