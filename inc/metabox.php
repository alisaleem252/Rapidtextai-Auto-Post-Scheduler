<?php
  class ChatGPT_Metabox{
      function __construct(){
        add_action('add_meta_boxes', array($this,'add_meta_boxes_CBF'));
        add_action('save_post',array(&$this,'save_post_CBF'));
      }

      function add_meta_boxes_CBF(){
        $post_types = get_post_types(array('public' => true));
         add_meta_box('chatgpt_cb_metabox_id', __('ChatGPT Scheduler','chatgpt_cb_metabox_id_option'),array(&$this,'chatgpt_cb_metabox_idCBF'),$post_types,'side');

      }

      function chatgpt_cb_metabox_idCBF($cur_postObj){
        $cur_post_id = $cur_postObj->ID;
        $is_cgpt_tenp = get_metadata('post',$cur_post_id,'chatgpt_used_as_cgpt_templater',true);
        $is_cgpt_tenp = $is_cgpt_tenp == 'yes' ? 'yes' : 'no';
        if(!isset($_REQUEST['post'])){?>
        <div id="chgptpo_loading89789" style="display:none">
          <p>Generating Content, Please wait and do not close or refresh this page.</p>
          <img src="https://lukasznowicki.info/wp-includes/js/thickbox/loadingAnimation.gif" />
        </div>
        <div id="chgptpo_generated89789"></div>
        <div class="generate_ajax_chatgpt" id="chgtpt_disp889789">
          <label class="components-truncate components-text components-input-control__label em5sgkm4 css-1imalal e19lxcc00">Write Topic Below</label>
          <input type="text" id="chatgpt_topic_choose" placeholder="Write topic here"/>
          <div>
              <p><a id="anchor_cgpt_generate" class="button-primary">Generate</a></p>
          </div>
        </div>
        
        <?php
        }
        echo '<div>';
        echo '<label class="components-truncate components-text components-input-control__label em5sgkm4 css-1imalal e19lxcc00">Use this as ChatGPT Template</label>';
        echo '</div>';echo '<div>';
        echo '<select name="chatgpt_used_as_cgpt_templater">
                <option value="no">No</option>
                <option value="yes" '.($is_cgpt_tenp == 'yes' ? 'selected' : '').'>Yes</option>
              </select>';
        echo '</div>';
      }

      function save_post_CBF($cur_post_id){
          foreach ($_POST as $key => $value) {
            if(strpos($key,'hatgpt_'))
              update_metadata('post',$cur_post_id,$key,$value);

          } // foreach ($_POST as $key => $v
      }
  }
  $ChatGPT_Metabox = new ChatGPT_Metabox;
