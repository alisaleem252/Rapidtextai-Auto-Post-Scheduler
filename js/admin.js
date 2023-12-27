jQuery(document).ready(function($) {
  $(document.body).off('change', '.chatGPT_schedule_settings_post_type').on('change', '.chatGPT_schedule_settings_post_type',function(e) {
      var post_type = $(this).val();
      var thisvar = $(this);
      $.ajax({
        type: 'post',
        dataType:"json",
        url: ajaxurl,
        data: {
          action: 'chatGPT_schedule_get_taxonomy',
          post_type: post_type
        },
        beforeSend: function() {
          $(thisvar).parent().parent().find('.ChatGPT_taxonomy').html('loading. . . .');
          $(thisvar).parent().parent().find('.ChatGPT_taxonomy_terms').html('loading. . . .');
        },
        success: function(response) {
          //console.log(response);
          $(thisvar).parent().parent().find('.ChatGPT_taxonomy').html('');
          $(thisvar).parent().parent().find('.ChatGPT_taxonomy_terms').html('');
          $(thisvar).parent().parent().find('.ChatGPT_taxonomy').html(response.tax_label);
          $(thisvar).parent().parent().find('.ChatGPT_taxonomy_terms').html(response.tax_terms_html);
        }
      });
    });

    $(document.body).off('click', '.add_record').on('click', '.add_record',function(e) {     
      var copy_content = $('#copy_content').html();
     $('#wrapper_content').append('<tr>'+copy_content+'</tr>');
    });

    $(document.body).off('click', '.remove_record').on('click', '.remove_record',function(e) {     
      $(this).parent().parent().remove();
    });

    $(document.body).off('mousemove', '.range-slider__range').on('mousemove', '.range-slider__range',function(e) {     
      var slider = $(this);
      var output = $(this).next();

      $(output).text(slider.val());
      
      //output.innerHTML = slider.value;
      
      // This function input current value in span and add progress colour in range
      slider.oninput = function() {
      
        output.innerHTML = this.value;
      
        var value = (this.value-this.min)/(this.max-this.min)*100;
        
        this.style.background = 'linear-gradient(to right, #82CFD0 0%, #82CFD0 ' + value + '%, #d7dcdf ' + value + '%, #d7dcdf 100%)'
      }
    });

  


  });

  
  jQuery(document).ready(function($) {
    jQuery(document.body).on('click','#anchor_cgpt_generate',function(e){
      e.preventDefault();
      jQuery('#content-html').click();
      var topic = jQuery('#chatgpt_topic_choose').val();
      var lang = jQuery('#chatgpt_m_lang :selected').val();
      var temp = jQuery('#chatgpt_m_temp').val();
      var tone = jQuery('#chatgpt_m_tone :selected').val();
      if(topic == ''){
        alert('Topic Cannot be empty');
        return;
      }
      $.ajax({
          type:'post',
          url:rapidtextaiURL+'article/?gigsixkey='+gigsixkey,
          dataType:'json',
          data:{
            
            "topic": topic,"lang":lang,"temp":temp,"tone":tone
          },
          beforeSend:function(){ jQuery('#chgtpt_disp889789').hide();jQuery('#chgptpo_loading89789').show();},
          complete:function(response){ 
                              jQuery('#chgptpo_loading89789').hide();
                            },
          success:function(response){  
            console.log(response);
              if(response.error){
                  jQuery('#chgtpt_disp889789').show();
                  jQuery('#chgptpo_loading89789').hide();
              }
              else {
                let content = response.content;
                // Replace \n\n# with <h2>
                content = content.replace(/\n\n#/g, "<h2>");

                // Replace next \n\n with closing </h2>
                content = content.replace(/\n\n/g, "</h2>");

                // Replace remaining \n with <p> and </p>
                content = content.replace(/\n/g, "</p><p>");

                // Add opening <p> at the start and closing </p> at the end
                content = "<p>" + content + "</p>";

                if (typeof wp !== 'undefined' && typeof wp.data !== 'undefined' && typeof wp.blocks !== 'undefined' && content.length) {
                  const { dispatch } = wp.data;
                  // Insert a new paragraph block with custom text
                  const newText = content;
                  const newBlock = wp.blocks.createBlock('core/paragraph', {
                    content: newText,
                  });
                  //dispatch('core/editor').editPost({ title: response.title });
                  // Add the new block to the editor
                  dispatch('core/editor').insertBlock(newBlock);
                }
                else {
                      jQuery('#content-html').click();
                       //jQuery('#title').val(response.title);
                       jQuery('#title-prompt-text').text('');
                      var currentContent = jQuery('.wp-editor-area').val();
                      jQuery('.wp-editor-area').val(currentContent + content);
                }
                
                //Cgpt_typeWriter('.editor-post-title',response.title);
                //jQuery('.block-editor-default-block-appender__content').html(response.content);
                // Insert text into the block's content
                // Get the current block editor instance
                jQuery('#chgptpo_generated89789').html(content.length+' Characters Generated');
            }
          },
          error:function(){ jQuery('#chgtpt_disp889789').show();jQuery('#chgptpo_loading89789').hide();}
      }); 
      

    })
    $('#ChatGPT_taxonomies_select').change(function() {
      var taxonomy = $(this).val();
      $.ajax({
        type: 'post',
        url: ajaxurl,
        data: {
          action: 'wp_ajax_chatGPT_schedule_get_taxonomy_terms',
          taxonomy: taxonomy
        },
        success: function(response) {
          $('#ChatGPT_taxonomy_terms').html(response);
        }
      });
    });
  });
  

jQuery('#ChatGPT_scheduler_copy').on('click',function(){
    jQuery(this).parent().clone().appendTo('.ChatGPT_scheduler_Table');
});
jQuery('#ChatGPT_scheduler_remove').on('click',function(){
    jQuery(this).parent().remove();
});
function Cgpt_typeWriter(elmelm,txt) {
  //setTimeout(function() {
    jQuery(elmelm).typed({
      strings: [txt],
      typeSpeed: 1,
      contentType: 'html'
    });
  //}, 500);
}