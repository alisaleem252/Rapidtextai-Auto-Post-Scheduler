<?php
require gigsix_chatgpt_scheduler_PATH.'/lib/vendor/autoload.php';
use Curl\Curl;

  class ChatGPT_Cron_Schedules{
      function __construct(){
        add_filter( 'cron_schedules', array($this,'cron_schedules_CBF'));
        add_action('cgpt_all_event_cron_schedule_event',array($this,'cgpt_cron_schedule_eventCBF'),1,2);
        add_action('chatgpt_cron_schedules_schedule_it',array($this,'schedule_it'),1,2);
      }
      public function schedule_it($pattern,$key){
        $chatGPT_schedule_settings =  get_option('chatGPT_schedule_settings',array());
        if($pattern == 'cgpt_single_event')
        wp_schedule_single_event( strtotime($chatGPT_schedule_settings['time'][$key]), 'cgpt_all_event_cron_schedule_event',array($pattern,$key));
        else
        wp_schedule_event( time(), $pattern, 'cgpt_all_event_cron_schedule_event',array($pattern,$key),true);
      }

      function cron_schedules_CBF( $schedules ) {
          $schedules['cgpt_everyhour'] = array(
            'interval' => 60*60,
            'display'  => __( 'Cron Job Executed at Every Hour', 'gigsix_chatgpt_scheduler' ),
          );
          $schedules['cgpt_everytwelve_hour'] = array(
            'interval' => 60*60*12,
            'display'  => __( 'Cron Job Executed at Every 12th Hour', 'gigsix_chatgpt_scheduler' ),
          );
          $schedules['cgpt_everyday'] = array(
            'interval' => 60*60*24,
            'display'  => __( 'Cron Job Executed at Every Day', 'gigsix_chatgpt_scheduler' ),
          );
          $schedules['cgpt_everyweek'] = array(
            'interval' => 60*60*24*7,
            'display'  => __( 'Cron Job Executed at Every Week', 'gigsix_chatgpt_scheduler' ),
          );
          $schedules['cgpt_everymonth'] = array(
            'interval' => 60*60*24*30,
            'display'  => __( 'Cron Job Executed at Every Month', 'gigsix_chatgpt_scheduler' ),
          );
          $schedules['cgpt_everyyear'] = array(
            'interval' => 60*60*24*360,
            'display'  => __( 'Cron Job Executed at Every Year', 'gigsix_chatgpt_scheduler' ),
          );
        return $schedules;
      }
      function cgpt_cron_schedule_eventCBF($pattern, $key){
        $helper = new gigsix_chatgpt_scheduler_Helper;
        $ChatGPTScheduler_settings_CBF =  get_option('ChatGPTScheduler_settings_CBF',array('key'=>'trial'));
          $chatGPT_schedule_settings =  get_option('chatGPT_schedule_settings',array());
          $Primary_Keyword=$chatGPT_schedule_settings['Primary_Keyword'][$key];
          $Template_Post=$chatGPT_schedule_settings['Template_Post'][$key];
          $time=$chatGPT_schedule_settings['time'][$key];
          $Pattern=$chatGPT_schedule_settings['Pattern'][$key];
          $post_status=$chatGPT_schedule_settings['post_status'][$key];
          $Temperature=$chatGPT_schedule_settings['Temperature'][$key];
          
          if(isset($ChatGPTScheduler_settings_CBF['key']) && trim($ChatGPTScheduler_settings_CBF['key']) !=''){
            $curl = new Curl();
            if($ChatGPTScheduler_settings_CBF['key'] == 'trial')
            $curl->post(gigsix_chatgpt_scheduler_network.'trial?gigsixkey=trial',array("topic"=>$Primary_Keyword,"temperature"=>$Temperature));
            else
            $curl->post(gigsix_chatgpt_scheduler_network.'detailedcontent?gigsixkey='.$ChatGPTScheduler_settings_CBF['key'],array("topic"=>$Primary_Keyword,"temperature"=>$Temperature));
            if (isset($curl->response->content)){
                $content = $curl->response->headings ? $this->process_content($curl->response) : $curl->response->content;
                $title = $curl->response->title;
                $new_post_id = $helper->duplicate_post($Template_Post,$title,$content,$post_status);
            }
          }

      }
      
  } // class
  $ChatGPT_Cron_Schedules = new ChatGPT_Cron_Schedules;