<?php
require rapidtextai_chatgpt_scheduler_PATH.'/lib/vendor/autoload.php';
use Curl\Curl;

  class ChatGPT_Cron_Schedules{
      function __construct(){
        add_filter( 'cron_schedules', array($this,'cron_schedules_CBF'));
        add_action('cgpt_all_event_cron_schedule_event',array($this,'cgpt_cron_schedule_eventCBF'),1,2);
        //add_action('init',array($this,'cgpt_cron_schedule_eventCBFinit'));
        add_action('chatgpt_cron_schedules_schedule_it',array($this,'schedule_it'),1,2);
        //add_action('init',array($this,'init'));
      }
      public function init(){
        var_dump($this->cgpt_cron_schedule_eventCBF('cgpt_single_event',0));
        exit;
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
            'display'  => __( 'Cron Job Executed at Every Hour', 'rapidtextai_chatgpt_scheduler' ),
          );
          $schedules['cgpt_everytwelve_hour'] = array(
            'interval' => 60*60*12,
            'display'  => __( 'Cron Job Executed at Every 12th Hour', 'rapidtextai_chatgpt_scheduler' ),
          );
          $schedules['cgpt_everyday'] = array(
            'interval' => 60*60*24,
            'display'  => __( 'Cron Job Executed at Every Day', 'rapidtextai_chatgpt_scheduler' ),
          );
          $schedules['cgpt_everyweek'] = array(
            'interval' => 60*60*24*7,
            'display'  => __( 'Cron Job Executed at Every Week', 'rapidtextai_chatgpt_scheduler' ),
          );
          $schedules['cgpt_everymonth'] = array(
            'interval' => 60*60*24*30,
            'display'  => __( 'Cron Job Executed at Every Month', 'rapidtextai_chatgpt_scheduler' ),
          );
          $schedules['cgpt_everyyear'] = array(
            'interval' => 60*60*24*360,
            'display'  => __( 'Cron Job Executed at Every Year', 'rapidtextai_chatgpt_scheduler' ),
          );
        return $schedules;
      }
      public function cgpt_cron_schedule_eventCBF($pattern, $key){

        $helper = new rapidtextai_chatgpt_scheduler_Helper;
        $helper->log('Hook running');
        $ChatGPTScheduler_settings_CBF =  get_option('ChatGPTScheduler_settings_CBF',array('key'=>'trial'));
          $chatGPT_schedule_settings =  get_option('chatGPT_schedule_settings',array());
          $Primary_Keyword=$chatGPT_schedule_settings['Primary_Keyword'][$key];
          $Primary_Keyword2=$chatGPT_schedule_settings['Primary_Keyword2'][$key];
          $Template_Post=$chatGPT_schedule_settings['Template_Post'][$key];
          $time=$chatGPT_schedule_settings['time'][$key];
          $Pattern=$chatGPT_schedule_settings['Pattern'][$key];
          $post_status=$chatGPT_schedule_settings['post_status'][$key];
          $Temperature=$chatGPT_schedule_settings['Temperature'][$key];

          // check if service is enabled
          if($ChatGPTScheduler_settings_CBF['service'] !== 'enable'){
            return;
          }
          if(isset($ChatGPTScheduler_settings_CBF['key']) && trim($ChatGPTScheduler_settings_CBF['key']) !=''){
            $helper->log('Key Set');
            $curl = new Curl();
            $curl->disableTimeout();
            //$curl->setOpt(CURLOPT_TIMEOUT, 60); // Set a timeout of 30 seconds
            $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
            if($ChatGPTScheduler_settings_CBF['key'] == 'trial'){
              $helper->log('Trial');
              $curl->post(rapidtextai_chatgpt_scheduler_network.'trial?gigsixkey=trial',array("topic"=>$Primary_Keyword,"temperature"=>$Temperature));
            }
            else {
              $helper->log('Article');
              $prompt = 'Generate a detailed article on the topic of '.$Primary_Keyword.' with the following keywords:'. $Primary_Keyword2;
              $curl->post(rapidtextai_chatgpt_scheduler_network.'detailedarticle-v3?gigsixkey='.$ChatGPTScheduler_settings_CBF['key'],
                                array(  
                                        "type"=> "custom_prompt",
                                        "custom_prompt"=> $prompt,
                                        "temperature"=>$Temperature,
                                        'model'=>'gemini-1.5-flash'));
            }
            // Start output buffering
            $helper->log('Response:'.print_r($curl->response,true));
            if (isset($curl->response)){
                $content = $curl->response ? $helper->rapidtextai_simple_markdown_to_html($curl->response) : $curl->response;
                
                $helper->log(print_r($content,true));
                if($content == '' ){
                  $helper->log('No Content');
                  return;
                }
                $title = substr(strip_tags($content), 0, 50) . '...';
                $new_post_id = $helper->duplicate_post($Template_Post,$title,$content,$post_status);
                update_metadata('post',$new_post_id,'chatgpt_used_as_cgpt_templater','no');
            }
            
          }

      }
      
  } // class

  $ChatGPT_Cron_Schedules = new ChatGPT_Cron_Schedules;