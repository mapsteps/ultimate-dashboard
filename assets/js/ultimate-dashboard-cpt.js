(function($) {

	// Metabox Tab Navigation
	$('#udb-metabox-tab-nav a').click(function(event) {
		event.preventDefault();
		$('#udb-metabox-tab-nav a').removeClass('nav-tab-active');
		$(this).addClass('nav-tab-active');
		$('.udb-metabox-wrapper').removeClass('active');
	});

	$('.udb-icon-tab').click(function() {
		$('.udb-icon-wrapper').addClass('active');
	});

	$('.udb-text-tab').click(function() {
		$('.udb-text-wrapper').addClass('active');
	});

	// Accordion
	$('.ca-accordion-title').click(function() {
		if($(this).hasClass('active')) {
			$('.ca-accordion-title').removeClass('active');
			$('.ca-accordion-content').stop().slideUp();
		} else {
			$('.ca-accordion-title').removeClass('active');
			$('.ca-accordion-content').stop().slideUp();
			$(this).next().stop().slideDown();
			$(this).addClass('active');
		}
	});

	// Search
	jQuery.expr[':'].Contains = function(a,i,m){
	  return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase())>=0;
	};

	function listFilter(header, list) {
	var form = $("<form>").attr({"action":"#"}),
		input = $("<input>").attr({"class":"udb-icon-search","type":"text"});
	$(form).append(input).appendTo(header);

	$(input)
	  .change( function () {
		var filter = $(this).val();
		if(filter) {

			// hide if no match
			$(list).find("label:not(:Contains(" + filter + "))").hide();
			// hide parent li if no match
			$(list).find("label:not(:Contains(" + filter + "))").parent().hide();

			// show if match
			$(list).find("label:Contains(" + filter + ")").show();
			// show parent li if match
			$(list).find("label:Contains(" + filter + ")").parent().show();

			// mark accordion title as inactive if no match
			$(list).find("label:not(:Contains(" + filter + "))").parent().parent().parent().prev().removeClass('active');
			// hide parent accordion content if match
			$(list).find("label:not(:Contains(" + filter + "))").parent().parent().parent().hide();

			// show parent accordion content if match
			$(list).find("label:Contains(" + filter + ")").parent().parent().parent().show();
			// mark accordion title as active if match
			$(list).find("label:Contains(" + filter + ")").parent().parent().parent().prev().addClass('active');

		} else { 

			// show all labels
			$(list).find("label").show();

			// show all li's
			$(list).find("label").parent().show();

			// hide accordion content
			$(list).find("label").parent().parent().parent().hide();

			// mark accordion title as inactive
			$(list).find("label").parent().parent().parent().prev().removeClass('active');

		}
		return false;
	  })
	.keyup( function () {
		$(this).change();
	});
	}

	$(function () {
		listFilter($("#udb-icon-header"), $(".udb-icon-list"));
	});

})( jQuery );