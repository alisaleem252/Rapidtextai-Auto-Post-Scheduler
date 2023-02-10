<?php
class ChatGPT_scheduler_Main{
    function __construct(){
        add_action('wpschedule_singleevent_api_chatgpt_schedulerCBF',array($this,'api_chatgpt_schedulerCBF'),1,2 );
    }
    function schedule($time,$first,$second){
        wp_schedule_single_event( time() + 30, 'wpschedule_singleevent_api_chatgpt_schedulerCBF',array($first,$second) );
    }
    public function api_chatgpt_schedulerCBF($first,$second){

    }
    
}