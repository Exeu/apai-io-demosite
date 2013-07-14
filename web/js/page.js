$(document).ready(function() {

	$('button#search-action').on('click', function(e) {
		e.preventDefault();

		$.post($(this).data('uri'), $("#search-form").serialize()).done(function(data) {
			for (var x in data) {

				if (!data[x].price)
					data[x].price = 'kA';

				if (!data[x].img)
					data[x].img = 'assets/images/no.gif';

				var html = '<div class="item well">';
				html += '<h3>'+data[x].title+'</h2>';
				html += '<img src="'+data[x].img+'" width="100%" class="img-polaroid">';
				html += '<a class="various fancybox.ajax" href="'+endpoint+'/api/lookup/'+data[x].asin+'">Ajax</a>'
				html += '</div>';

				$('#iso').isotope( 'insert', $(html));
			}

			$(".various").fancybox({
				maxWidth	: 800,
				maxHeight	: 600,
				fitToView	: false,
				width		: '70%',
				height		: '70%',
				autoSize	: false,
				closeClick	: false,
				openEffect	: 'fade',
				closeEffect	: 'fade',
				dataType : 'html'
			});
		});
	});


	$('#iso').isotope({
		itemSelector : '.item',
		layoutMode : 'fitRows'
	});
});