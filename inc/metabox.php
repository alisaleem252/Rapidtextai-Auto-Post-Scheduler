<?php
  class ChatGPT_Metabox{
      function __construct(){
        add_action('add_meta_boxes', array($this,'add_meta_boxes_CBF'));
        add_action('save_post',array(&$this,'save_post_CBF'));
      }

      function add_meta_boxes_CBF(){
         add_meta_box('chatgpt_cb_metabox_id', __('Used as ChatGPT Schedule Template ?','yesdevproductcustomizer'),array(&$this,'chatgpt_cb_metabox_idCBF'));

      }

      function chatgpt_cb_metabox_idCBF($cur_postObj){
        $cur_post_id = $cur_postObj->ID;
        $is_cgpt_tenp = get_metadata('post',$cur_post_id,'chatgpt_used_as_cgpt_templater',true);
        $is_cgpt_tenp = $is_cgpt_tenp == 'yes' ? 'yes' : 'no';

        echo '<select name="chatgpt_used_as_cgpt_templater">
                <option value="no">No</option>
                <option value="yes" '.($is_cgpt_tenp == 'yes' ? 'selected' : '').'>Yes</option>
              </select>';
      }

      function save_post_CBF($cur_post_id){
          foreach ($_POST as $key => $value) {
            if(strpos($key,'hatgpt_'))
              update_metadata('post',$cur_post_id,$key,$value);

          } // foreach ($_POST as $key => $v
      }
  }
  $ChatGPT_Metabox = new ChatGPT_Metabox;
