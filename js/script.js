$(document).ready(function(){
	
	// Preloading Images
	$.preloadImages = function() {
		for(var i = 0; i<arguments.length; i++) {
			$('<img>').attr('src', arguments[i]);
		}
	}
	$.preloadImages('images/logo_active.png');
	
	// Top Navigation
	$('#navi a, a[rel^="#"]').click(function() {
		var current = $('#navi').attr('currentValue');
		var rel = $(this).attr('rel');
		$('#navi').attr('currentValue', rel);
		//if($(this).css('display') != 'block')
		$(current).slideUp(400, function() {
			$(rel).slideDown(400, function() {
				resizeHtml();
			});
			resizeHtml();
		});
		return false;
	});
	
	// Logo
	$('#logo').hover(function() {
		$(this).attr('src', 'images/logo_active.png');
	}, function() {
		$(this).attr('src', 'images/logo.png');
	});
	
	resizeUploadContainer();
	
	$('#mapContainer').click(function(e) {
		setCoordinates(e, 1);
		return false;
	});
	
	$('#newUpload span').click(function(e) {
		$('.dot').fadeOut(400);
		$('#navi').attr('currentValue', '#upload');
		$('#imagemap4posis').slideUp(400, function() {
			$('#upload').slideDown(400);
			resizeHtml();
		});
	});
	
	$('#urlMessage a').click(function(e) {
		$('.dot').remove();
		$('#imagemap4posis #mapContainer').find('img').attr('src', '#');
		$('#navi').attr('currentValue', '#upload');
		$('#imagemap4posis').slideUp(400, function() {
			$('#upload').slideDown(400);
			resizeHtml();
			removeErrorMessage();
		});
		return false;
	});
	
	$('#uploadUndo').click(function(e) {
		$('#upload').slideUp(400, function() {
			if($('#imagemap4posis #mapContainer').find('img').attr('src') == '#') {
				$('#upload').slideDown(400);
				$('#navi').attr('currentValue', '#upload');
			} else {
				$('.dot').fadeIn(400);
				$('#navi').attr('currentValue', '#imagemap4posis');
				$('#imagemap4posis').slideDown(400, function() {
					resizeHtml();
				});
			}
		});
	});
	
	// insert image path via url
	$('#linkform').submit(function() {
		enterImagelinkForm();
		return false;
	});
	$('.imageurl_submit').click(function() {
		enterImagelinkForm();
		return false;
	});
	
	$('.clearButton').click(function() {
		$('#coordsText').val('');
		$('#areaText').val('');
	});
	
	var timeoutIdShow = 0
	var timeoutIdHide = 0
	
	$('#info').mouseover(function() {
		clearTimeout(timeoutIdHide);
		timeoutIdShow = setTimeout(function() {
			$('#infotext').stop(true, true).show(200);
			$('#info').stop(true, true).animate({opacity: 1}, 200);
		}, 200);
	});
	$('#infotext').mouseover(function() {
		clearTimeout(timeoutIdHide);
	});
	$('#infotext').mouseleave(function() {
		clearTimeout(timeoutIdShow);
		timeoutIdHide = setTimeout(function() {
			$('#infotext').stop(true, true).hide(200);
			$('#info').stop(true, true).animate({opacity: 0.5}, 200);
		}, 200);
	});
	
	/*$('#mapContainer').mousemove(function(e) {
		setCoordinates(e, 0);
		return false;
	});*/
	
	var counter = 1;
	var coordsLength = 0;
	function setCoordinates(e, status) {
		var x = e.pageX;
		var y = e.pageY;
		
		//$('body').append('<img class="dot" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAMAAAADCAYAAABWKLW/AAAABGdBTUEAALGPC/xhBQAAABh0RVh0U29mdHdhcmUAUGFpbnQuTkVUIHYzLjM2qefiJQAAACFJREFUGFdj9J/6KomBgUEYiN8yADmlQPwfRIM4SVCBJAAiRREoec4ImAAAAABJRU5ErkJggg==" style="left: '+ (x-1) +'px; top: '+ (y-1) +'px;" />');
		
		var offset = $('#imagemap4posis img').offset();
		x -= parseInt(offset.left);
		y -= parseInt(offset.top);
		if(x < 0) { x = 0; }
		if(y < 0) { y = 0; }
		
		var value = $('#coordsText').val();
		if(value == '') {
			value = x+','+y;
			coordsLength = value.length;
			counter++;
		} else {
			value = value+','+x+','+y;
			coordsLength = value.length;
		}
		if(status)
			$('#coordsText').val(value);
		
		if($('#area'+counter).length != 0)
			$('#area'+counter).remove();
		var countKomma = value.split(',').length;
		var shape = (countKomma <= 4) ? 'rect' : 'poly';
		if(countKomma >= 4) {
			var html = '<area shape="'+shape+'" id="area'+counter+'" class="area" coords="'+value+'" href="#" alt="" title="">';
			$('map').append(html);
		}
		
		$('#mapContainer').append($('.imgmapMainImage'));
		$('#mapContainer').children('div').remove();
		$('.imgmapMainImage').removeClass('maphilighted');
		//$('canvas').remove();
		
		
		hightlight();
		
		var text = '<area alt="" title="" href="#" shape="'+shape+'" coords="'+value+'" />';
		$('#areaText').val(text);
	}
	
	
	function hightlight() {
		$('.imgmapMainImage').maphilight({
			strokeColor: '4F95EA',
			alwaysOn: true,
			fillColor: '365E71',
			fillOpacity: 0.2,
			shadow: true,
			shadowColor: '000000',
			shadowRadius: 5,
			shadowOpacity: 0.6,
			shadowPosition: 'outside'
		});
	}
});

$(window).resize(function() {
	resizeUploadContainer();
});

function resizeUploadContainer() {
	if($('#upload').length) {
		var val = (($(window).height() - $('#logo').outerHeight() - $('#upload').outerHeight()) / 2) * 0.6;
		if(val < 100) { val = 100; }
		$('.infobox').css('margin-top', val+'px');
	}
	resizeHtml();
}
	
function loadImagemapGenerator(width, height) {
	if(width == 0)
		width = $('#mapContainer img').width();
	if(height == 0)
		height = $('#mapContainer img').height();
	$('#newUpload').width(width-8);
	$('#mapContainer').width(width);
	widthTmp = (width < 350) ? 364 : width;
	widthTmp2 = (width < 350) ? 350 : width;
	$('div.form').width(widthTmp+4);
	$('div.form input').width(widthTmp2-34);
	width = (width < 363) ? 363 : width;
	$('div.form textarea').css({'width': width-10, 'min-width': width-10, 'max-width': width-10});
	$('#mapContainer').height(height);
	
	resizeHtml();
}

function resizeHtml() {
	var current, height;
	$.each($('.infobox'), function() {
		if($(this).css('display') != 'none')
		current = $(this);
	});
	
	if(typeof(current) == 'undefined') {
		height = $('#imagemap4posis').outerHeight() + $('#header').outerHeight();
	} else {
		height = $(current).outerHeight(true) + $('#header').outerHeight();
	}
	
	if((typeof(current) == 'undefined' && height > $(window).height())
		|| (typeof(current) != 'undefined' && height > $(window).height())) {
		$('html').height(height);
		$('#about').css('top', height - 30 +'px');
	} else if(typeof(current) != 'undefined') {
		$('html').height('100%');
		$('#about').css('top', $(window).height() - 30 +'px');
	}
}
		
function removeOldMapAndValues() {
	if($('.imgmapMainImage').hasClass('maphilighted')) {
		$('#mapContainer').append($('.imgmapMainImage'));
		$('#mapContainer').children('div').remove();
		$('.imgmapMainImage').removeClass('maphilighted').css('opacity', 1);
		$('.dot').remove();
		$('#coordsText').val('');
		$('#areaText').val('');
		$('#map').children('area').remove();
	}
}
function removeErrorMessage() {
	$('#urlMessage').hide();
}
function enterImagelinkForm() {
	removeErrorMessage();
	var url = $('#imageurl').val();
	var parts = url.split('.');
	var ext = parts[parts.length-1];
	if(ext == 'gif' || ext == 'jpg' || ext == 'jpeg' || ext == 'png') {
		$('#imagemap4posis #mapContainer').find('img').attr('src', url);
		removeOldMapAndValues();
		jQuery.ajax({
			type: 'POST',
			url: 'upload_ident.php',
			data: {'data': '[true, "'+url+'", 0, 0]'},
			dataType : 'json'
		});
		$('#navi').attr('currentValue', '#imagemap4posis');
		$('#upload').slideUp(400, function() {
			$('#uploadUndo').show();
			$('#imagemap4posis').slideDown(400, function() {
				resizeHtml();
				
				// not correct loaded yet?
				setTimeout(function() {
					loadImagemapGenerator();
				}, 300);
				
			});
			loadImagemapGenerator(0, 0);
		});
		setTimeout(function() {
			if($('#main').width() == 0)
				$('#urlMessage').slideDown(600);
		}, 1000);
	} else {
		$('a.imageurl_submit').parent().find('span').hide();
		$('a.imageurl_submit').after('<span class="error">Incorrect input</span>');
	}
}