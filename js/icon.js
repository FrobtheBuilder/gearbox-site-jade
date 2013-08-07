$(document).ready(function(){
	$( ".sponsor" ).each(function( index ) {
		var classes = this.className.split(/\s+/);
		$(this).css("background-image", "url(img/sponsors/" + classes[1] + ".png)")
	});
})