// Attach Fastclick JS to document body
$(function() {
    FastClick.attach(document.body);
});

$(document).ready( function() {

	// Responsive Navigation JS
	var $nav_main = $('.site-nav'),
		  $nav_trigger = $('.nav-trigger'),
      $subnav_trigger = $('.menu-item-has-children > a');

	$nav_trigger.on('click',function() {
		$nav_main.toggleClass('open');
		$nav_trigger.toggleClass('open');
		return false;
	});

	$subnav_trigger.on('click',function(e) {
		e.preventDefault();
    console.log(e);
		var $this = $(this);
		$this.toggleClass('open').next('ul').toggleClass('open');
	});

});

