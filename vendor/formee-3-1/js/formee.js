function equalHeight(group) {
    var tallest = 0;
    group.each(function() { 
        var thisHeight = $(this).height();
        if(thisHeight > tallest) {
            tallest = thisHeight;
        }
    });
    group.height(tallest);
}
jQuery(document).ready(function() {
    equalHeight(jQuery(".formee-equal"));
});
jQuery(window).resize(function() {
    equalHeight(jQuery(".formee-equal"));
});


addFile = function() {
    var $this = $(this);
    $obj = $this.parent().prev('input');
    $id =  $obj.attr('id');
    
    $('#' + $id).val($this.val());
};

$(function() {
    $('input:file').bind('change', addFile);
});