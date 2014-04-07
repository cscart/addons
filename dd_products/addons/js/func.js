var is_allowed = true;
var cart_already_displayed = false;
var cart_content_over = false;

$(function() {
	fn_dd_products_init();
	$(window).resize(function() {
		fn_dd_cart_products_init();
	});

});

function fn_dd_products_init()
{
	$('img[id^=det_img]').draggable({
		revert: true,
		helper: 'clone',
		opacity: 0.4,
		zIndex: 1000,
		cancel: ['input','button'],
		start: fn_dd_products_start,
		stop: fn_dd_products_stop,
		appendTo: 'body',
		containment: 'body',
		scroll: true,
		snapTolerance: 5
	});
	
	$('.cm-draggable-cart').droppable( {
			accept: 'img[id^=det_img]', 
			activeClass: 'cm-draggable-active-cart', 
			hoverClass: 'cm-draggable-hover-cart',
			tolerance: 'intersect',
			over: fn_dd_products_cart_hover,
			out: fn_dd_products_cart_out,
			drop: fn_dd_products_add_to_cart
	});
	
	fn_dd_cart_products_init();
}

function fn_dd_cart_products_init()
{
	$('#dd_cart_content').bind('mousemove', fn_dd_content_over).bind('mouseout', fn_dd_content_out).html($('#dd_cart_content_init').html());
	$('.cm-draggable-cart').css({right: 0, left: $('#container').offset().left + $('#container').width()}).bind('mousemove', fn_dd_show_cart_content).bind('mouseout', fn_dd_hide_cart_content);
	fn_dd_init_cart_content_position();
}

function fn_dd_init_cart_content_position()
{
	jcontent = $('#dd_cart_content');
	jcontainer = $('.cm-draggable-cart');
	window_sizes = $.get_window_sizes();
	
	top_position = parseInt(jcontainer.css('top')) + parseInt(jcontainer.css('height')) - 10;
	left_position = (parseInt(jcontent.css('width')) + parseInt(jcontainer.css('left')) > window_sizes.view_width) ? window_sizes.view_width - parseInt(jcontent.css('width')) - 30 : parseInt(jcontainer.css('left'));
	jcontent.css({top: top_position + 'px', left: left_position + 'px'});
	
	return {top: top_position, left: left_position};
}

function fn_dd_show_cart_content(e)
{
	if (cart_is_empty) {
		return false;
	}
	
	if (cart_already_displayed || cart_content_over) {
		return true;
		
	} else {
		var pos = fn_dd_init_cart_content_position();
		var jcontent = $('#dd_cart_content');
		jcontent.css({top: pos.top + 'px', left: pos.left + 'px'}).animate({opacity: 1});
		
		cart_already_displayed = true;
	}
}

function fn_dd_hide_cart_content()
{
	cart_already_displayed = false;
	fn_dd_check_cart_products_status();
}

function fn_dd_content_over()
{
	cart_content_over = true;
}

function fn_dd_content_out()
{
	cart_content_over = false;
	fn_dd_check_cart_products_status();
}

function fn_dd_check_cart_products_status(repeated)
{
	if (repeated != 'Y') {
		setTimeout('fn_dd_check_cart_products_status("Y")', 10);
		return;
	}
	
	if (!cart_content_over && !cart_already_displayed) {
		jcontent = $('#dd_cart_content');
		jcontent.animate({opacity: 0});
	}
}

function fn_dd_products_add_to_cart(e, ui)
{
	var frm = $(ui.draggable).parents('form');

	button = $('input[type=submit]:first', frm);
	result_ids = $('input[name=result_ids]', frm);

	value = result_ids.val();
	if (typeof(value) != 'undefined' && value.indexOf('draggable_cart') == -1) {
		form = $('form', button.parents());
		if (form.hasClass('cm-ajax')) {
			form.addClass('cm-ajax-force');
		}
		result_ids.val(result_ids.val() + ',draggable_cart');
	}
	
	button.click();
	
	fn_dd_init_cart_content_position();
	fn_dd_products_cart_out();
}

function fn_dd_products_start(e, ui)
{
	var frm = $(e.originalTarget).parents('form');

	if ($('input[type=submit]:first', frm).length) {
		is_allowed = true;
	} else {
		is_allowed = false;
	}

	$('.cm-draggable-cart').animate({opacity: 1});
}

function fn_dd_products_stop()
{
	$('.cm-draggable-cart').animate({opacity: 0.3});
}

function fn_dd_products_cart_hover()
{
	if (cart_is_empty) {
		if (is_allowed) {
			image = 'empty_add.gif';
		} else {
			image = 'empty_not_allowed.gif';
		}
	} else {
		if (is_allowed) {
			image = 'full_add.gif';
		} else {
			image = 'full_not_allowed.gif';
		}
	}
	$('.cm-draggable-cart img').attr('src', dd_images + image);
}

function fn_dd_check_cart_status()
{
	if ($('#cart_products_count').length) {
		cart_is_empty = false;
	} else {
		cart_is_empty = true;
	}
	
	fn_dd_cart_products_init();
}

function fn_dd_products_cart_out()
{
	fn_dd_check_cart_status();
	
	if (cart_is_empty) {
		image = 'empty.gif';
	} else {
		image = 'full.gif';
	}
	
	$('.cm-draggable-cart img').attr('src', dd_images + image);
}

function fn_dd_products_layout_callback(data)
{
	fn_dd_products_init();
}

function fn_dd_products_sorting_callback(data)
{
	fn_dd_products_init();
}

function fn_dd_products_empty_cart()
{
	$('.cm-draggable-cart img').attr('src', dd_images + 'empty.gif');
}

function fn_dd_products_full_cart()
{
	$('.cm-draggable-cart img').attr('src', dd_images + 'full.gif');
}

function fn_dd_init_delete_links()
{
	var delete_link = $('a[name=delete_cart_item]');
	delete_link.attr('rev', delete_link.attr('rev') + ',draggable_cart');
	if (delete_link.hasClass('cm-ajax')) {
		delete_link.addClass('cm-ajax-force');
	}
}