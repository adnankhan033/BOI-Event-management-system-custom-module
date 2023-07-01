//alert("testing");

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
                for(view of Object.values(Drupal.views.instances)){
                    if(view.settings.view_name == "checkboxdata" && view.settings.view_display_id == "block_1")
                    {
                        view.$view.trigger("RefreshView");
                    }
                    else if(view.settings.view_name == "checkboxdata" && view.settings.view_display_id == "block_2")
                    {
                       
                        view.$view.trigger("RefreshView");
                    }
                }
            }
        }
    });

});