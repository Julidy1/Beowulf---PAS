// JavaScript Document
var acOptions = {
    minChars: 3,
    max: 10,
    dataType: 'json', // this parameter is currently unused

    parse: function(data) {
        var parsed = [];
 
        for (var i = 0; i < data.length; i++) {
            parsed[parsed.length] = {
                data: data[i],
                value: data[i].id,
                result: data[i].term
            };
        }
 
        return parsed;
    },
    formatItem: function(item) {
        return item.term;
    }
};

jQuery(document).ready(function($) {


		$('#old_findID')
        .autocomplete('/ajax/relatedfind/', acOptions)
        .attr('name', 'old_findID')
        .result(function(e, data) {
		$('#old_findID').val(data.term);
		$('#find2ID').val(data.id);
		});
		
		
});