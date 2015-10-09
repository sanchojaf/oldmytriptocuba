$(function() {
	var sl_slide = $("#slslide").slippry({
		transition: 'fade',
		useCSS: true,
		speed: 1000,
		pause: 3000,
		auto: true,
		preload: 'visible'
	});

	$('.stop').click(function () {
		sl_slide.stopAuto();
	});

	$('.start').click(function () {
		sl_slide.startAuto();
	});

	$('.prev').click(function () {
		sl_slide.goToPrevSlide();
		return false;
	});
	$('.next').click(function () {
		sl_slide.goToNextSlide();
		return false;
	});
	$('.reset').click(function () {
		sl_slide.destroySlider();
		return false;
	});
	$('.reload').click(function () {
		sl_slide.reloadSlider();
		return false;
	});
	$('.init').click(function () {
		sl_slide = $("#slslide").slippry();
		return false;
	});
});
