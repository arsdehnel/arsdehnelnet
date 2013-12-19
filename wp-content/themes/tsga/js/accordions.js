var Accordions = {

  init: function() { 
    console.log('test'); 
    this.bindUIfunctions();
    this.determineHeights();
  },

  bindUIfunctions: function() {

    // Delegation 
    $(document)
      .on("click", ".accordion-header a", function(event) {
        console.log('test');
        event.preventDefault();
        Accordions.toggle($(this).closest('.accordion-wrapper'));
      });

  },

  toggle: function(jqObj) { 
    
    console.log('test');
    
    if( jqObj.hasClass('open') ){
      $('.accordion-body',jqObj).animate({'height' : '0'}
                                         ,400
                                         ,'swing'
                                         ,jqObj.removeClass('open'));
    }else{
      var accordion_body = $('.accordion-body',jqObj);
      var open_height = accordion_body.attr('data-accordion-open-height');
      $('.accordion-body',jqObj).animate({'height' : open_height}
                                         ,400
                                         ,'swing'
                                         ,jqObj.addClass('open'));
    }    

  },
  
  determineHeights: function(){
    
    $('.accordion-body').each(function(){
      var $this  = $(this)
      var elem   = $this.clone().css({"height":"auto"}).appendTo("body")
      var height = elem.css("height");
        
      elem.remove();
      
      $this.attr('data-accordion-open-height',height.substring(0, height.length - 2));
        
    });
    
  }

}

Accordions.init();