
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(typeof _ifwp_refresh_floating_labels !== 'function'){
    function _ifwp_refresh_floating_labels(){
        'use strict';
        jQuery('.form-label-group > select').each(function(){
            _ifwp_select_change(this);
        });
        jQuery('.form-label-group > textarea').each(function(){
            _ifwp_textarea_height(this);
        });
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(typeof _ifwp_select_change !== 'function'){
    function _ifwp_select_change(select){
        'use strict';
        if(jQuery(select).val() === ''){
            jQuery(select).removeClass('placeholder-hidden');
        } else {
            jQuery(select).addClass('placeholder-hidden');
        }
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(typeof _ifwp_textarea_height !== 'function'){
    function _ifwp_textarea_height(textarea){
        'use strict';
        var current_height = textarea.scrollHeight - parseInt(jQuery(textarea).data('ifwp-padding-height')),
            original_height = parseInt(jQuery(textarea).data('ifwp-element-height'));
        jQuery(textarea).height(original_height).height(current_height);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

jQuery(document).on('ready', function(){
    jQuery('.form-label-group > textarea').each(function(){
        var height = jQuery(this).height(), // margin: no, border: no, padding: no, element: yes
            innerHeight = jQuery(this).innerHeight(), // margin: no, border: no, padding: yes, element: yes
            outerHeight = jQuery(this).outerHeight(); // margin: no, border: yes, padding: yes, element: yes
        jQuery(this).data({
            'ifwp-border-height': outerHeight - innerHeight,
            'ifwp-element-height': height,
            'ifwp-padding-height': innerHeight - height,
        });
    });
    _ifwp_refresh_floating_labels();
    jQuery('.form-label-group > select').on('change', function(){
        _ifwp_select_change(this);
    });
    jQuery('.form-label-group > textarea').on('input propertychange', function(){
        _ifwp_textarea_height(this);
    });
});

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

jQuery(document).on(ifwp_page_visibility_event(), function(){
    _ifwp_refresh_floating_labels();
});

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
