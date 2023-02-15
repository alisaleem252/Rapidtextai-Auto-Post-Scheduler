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
        
      }
      function cgpt_everytwelve_hour_cron_schedule_eventCBF(){
        
      }
      function cgpt_everyday_cron_schedule_eventCBF(){
        
      }
      function cgpt_everyweek_cron_schedule_eventCBF(){
        
      }
      function cgpt_everymonth_cron_schedule_eventCBF(){
        
      }
      function cgpt_everyyear_cron_schedule_eventCBF(){
        
      }


      
  } // class
  $ChatGPT_Cron_Schedules = new ChatGPT_Cron_Schedules;