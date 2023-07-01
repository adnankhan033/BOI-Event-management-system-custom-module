//alert("testing");
jQuery(function(){
	jQuery(document).ajaxSend(function() {
		jQuery("#overlay").fadeIn(300);　
	});
	jQuery(".send-checkbox-data").on('change', function(){
		var $checkbox = jQuery(this);
		var state = $checkbox.prop('checked');
		var nid = $checkbox.attr("data-nid");

		jQuery.ajax({
			type:'POST',
			url: drupalSettings.path.baseUrl+"custom_hooks/Checkbox",
			data:{state,nid,cid:$checkbox.val()},
			success:(d)=>{
				d = JSON.parse(d);
				if(d.status){
                    $checkbox.parent().children(".last-checked-view").trigger("RefreshView");
					for(view of Object.values(Drupal.views.instances)){
						if(view.settings.view_name == "checkboxdata" && view.settings.view_display_id == "block_1")
						{
							view.$view.trigger("RefreshView");
						}
						else if(view.settings.view_name == "asdf" && view.settings.view_display_id == "block_1")
						{

//							view.$view.trigger("RefreshView");
						}
					}
				}
			}
		}).done(function() {
			setTimeout(function(){
				jQuery("#overlay").fadeOut(300);
			},500);
		});
	});
});

