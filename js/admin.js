jQuery(document).ready(function($) {
    $('.chatGPT_schedule_settings_post_type').change(function() {
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
          console.log(response);
          $(thisvar).parent().parent().find('.ChatGPT_taxonomy').html('');
          $(thisvar).parent().parent().find('.ChatGPT_taxonomy_terms').html('');
          $(thisvar).parent().parent().find('.ChatGPT_taxonomy').html(response.tax_label);
          $(thisvar).parent().parent().find('.ChatGPT_taxonomy_terms').html(response.tax_terms_html);
        }
      });
    });


    $('.add_record').click(function() {
     var copy_content = $('#copy_content').html();
     console.log(copy_content);
     $('#wrapper_content').append(copy_content);
      
      
    });

  });

  
  jQuery(document).ready(function($) {
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
