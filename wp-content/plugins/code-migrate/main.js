//ajax form submission
jQuery('body').on('click','.ajax-form button[type=submit]',function(){
	var form   = jQuery(this).closest('.ajax-form')
	    submit = true;
	
	jQuery('.required',form).each(function(){
		if( jQuery(this).val() == '' ){
			submit = false;
			jQuery(this).after('<div class="error">'+jQuery('label[for='+jQuery(this).attr('name')+']').text()+' is required</div>');
		}
	});
	
	if( submit ){
		jQuery.ajax({
			data: form.serialize(),
			url: form.attr('action'),
			type: form.attr('method'),
			complete: function( data, textStatus, jqXHR ){
				if( textStatus == 'success' ){
					location.reload();
				}else{
					alert(textStatus);
				}
			}
		});
	}
	
	return false;
});

//toggle path subtable
jQuery('body').on('click','.inner-table-toggle',function(){
	jQuery(this).closest('tr').next().toggleClass('hide');
	return false;
});

//select files by type
jQuery('body').on('click','.file-options a',function(){
	var checkBoxes = jQuery('tr.'+jQuery(this).data('file-type')+' :checkbox');
	checkBoxes.prop("checked", !checkBoxes.prop("checked"));
	return false;
});

jQuery('body').on('click','.confirm-submit',function(){
	return confirm('Are you sure you want to begin the migration?');
});