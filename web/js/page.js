$(document).ready(function() {

	$('button#search-action').on('click', function(e) {
		e.preventDefault();

		ga('send', {
			'hitType': 'event',
			'eventCategory': 'search button',
			'eventAction': 'click',
			'eventLabel': $('input#searchform_search').val(),
			'eventValue': 1
		});

		$.post($(this).data('uri'), $("#search-form").serialize()).done(function(data) {
			var container = $(data);

			container.imgLiquid({
				fill: true,
				verticalAlign: 'top',
				horizontalAlign: '50%'
			});

			$('#iso').prepend(container).isotope( 'reloadItems' ).isotope({ sortBy: 'original-order' });
		});
	});

	$(document).on('click', 'a.various', function (e) {
		e.preventDefault();

		$.fancybox.open($(this), {
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

	$('#iso').isotope({
		itemSelector : '.item',
		layoutMode : 'fitRows'
	});
});