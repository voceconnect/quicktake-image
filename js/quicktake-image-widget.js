/*global jQuery, MediaModal*/
'use strict';

function kill_imw_class(){
  jQuery('.widget').removeClass('current-image-modal-widget'); 
}

function attach_imw_click_events(){
  jQuery(".attach-image").on('click', function(){
   kill_imw_class();
   jQuery(this).closest('.widget').addClass('current-image-modal-widget');
  });
}

function attach_imw_loop(){
  jQuery('.attach-image').each(function(){
    new MediaModal({
      calling_selector : jQuery(this),
      cb : function(attachments){
        var attachment = attachments[0];
        var current_image_modal_widget = jQuery('.current-image-modal-widget');
        current_image_modal_widget.find('.attach-image').data('attachment_ids', attachment.id);
        current_image_modal_widget.find('.image img').attr('src', attachment.sizes.thumbnail.url);
        current_image_modal_widget.find('.attachment-id').val(attachment.id);
        current_image_modal_widget.find('.attachment-url').val(attachment.sizes.full.url);
        kill_imw_class();
      }
    },
    {
      title : 'Choose an Image',
      button : {
        text : 'Select Image'
      },
      library : {
        type : "image"
      }
    });
  }); 
}
jQuery(document).ready(function($) {
  attach_imw_loop();
  attach_imw_click_events();
  $('div.widgets-sortables').bind('sortstop',function(event,ui){
      setTimeout(function(){
        attach_imw_loop();
        attach_imw_click_events();
      }, 500);
  });

});