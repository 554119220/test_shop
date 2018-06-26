+ function($) {
    "use strict";
    var t;
    var ids;
    var value;
    var defaults = {
        cssClass: "town-picker",
        rotateEffect: true,  //为了性能
        onChange : function (picker, values, displayValues) {
            var displayValue = displayValues.toString();
            $("#town-picker").html(displayValue);
            var index = $.inArray(displayValue, value);
            var town_id = ids[index];
            $("input[name='town_id']").val(town_id);
        },
        cols : [
            {
                textAlign: 'center',
                cssClass : 'col-town'
            }
        ],
    }
    $.fn.townPicker = function(params) {
        return this.each(function() {
            if(!this) return;
            value  = params.cols[0].values;
            ids  = params.cols[0].ids;
            var p = $.extend(defaults, params);
            if (p.value) {
                $(this).val(p.value.join(' '));
            } else {
                var val = $(this).val();
                val && (p.value = val.split(' '));
            }
            $(this).picker(p)
            var picker = $(this).data('picker');
            if (picker.cols[0] != undefined) {
                clearTimeout(t);
                t = setTimeout(function(){
                    picker.cols[0].replaceValues(value);
                    picker.updateValue();
                }, 200)
            }
            var now_value = $("#town-picker").html();
            if (params.value) $(this).val(p.formatValue(p, now_value, now_value));
        });
    }
}(Zepto);