$(window).load(function() {
	var max;
	$('.row-fluid,.row').each(function() {
		max = -1;
		$(this).find("[class$='thumbnail']").each(function() {
			var h = $(this).height(); 
			max = h > max ? h : max;
		});
		
		$(this).find("[class$='thumbnail']").each(function() {
			$(this).css({'min-height': max});
		});
	});
});