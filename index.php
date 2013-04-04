<?php
/*
 * HTML Imagemap Generator
 * build with PHP, jQuery Maphighlight and CSS3
 * Since 	1/2013
 * Version	v1.2
 * by		Dario D. Müller
 * 			http://dariodomi.de
 * License	Distributed under the Lesser General Public License (LGPL)
 * 			http://www.gnu.org/copyleft/lesser.html
 */

session_start();

?><!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>Imagemap Generator | by Dario D. Müller</title>
	
	<!-- Dario D. Müller -->
	<!-- This is the domain of dariodomi.de -->
	<!-- All rights reserved.  -->
	<!-- Alle Rechte vorbehalten. -->
	
	<!-- Icon -->
	<link rel="shortcut icon" href="favicon.ico" />
	
	<!-- SEO -->
	<meta name="author" content="Dario D. Müller" />
	<meta name="publisher" content="http://dariodomi.de" />
	<meta name="copyright" content="Dario D. Müller" />
	<meta name="page-topic" content="imagemap generator, html imagemaps, html imgemap generator, html, html area generator, area imagemaps" />
	<meta name="page-type" content="html, imagemap, area, tags, coordinate, generator" />
	<meta name="description" content="After uploading an image, you can generate HTML area-Tags and imagemap-coordinates by clicking in the image." />
	<meta name="keywords" content="html, imagemap, generator, map, area, imagemap generator, creating map areas, html infos, newsletter" />
	
	<!-- Author -->
	<link rel="author" href="https://plus.google.com/113304109683958874741/" />
	<meta property="article:author" content="https://plus.google.com/113304109683958874741/" />
	<meta name="Author" content="Dario D. Müller, mailme@dariodomi.de">
	
	<!-- JS -->
	<script type="text/javascript" src="js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="js/jquery.maphilight.min.js"></script>
	<script type="text/javascript" src="js/jquery.uploadify-3.1.min.js"></script>
	<script type="text/javascript" src="js/script.js"></script>
	
	<!-- CSS -->
	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="css/snippet.css" type="text/css" media="screen" />
	
</head>

<?php

$uploaded = false;
if($_SESSION['image'] != null && !empty($_SESSION['image'][0]) && (substr_count($_SESSION['image'][0], '/') >= 1 || file_exists($_SESSION['image'][0]))) {
	$uploaded = true;
}

?>


<body>
	<header>
		<div id="header">
			<p><a href="http://imagemap-generator.dariodomi.de/"><img src="images/logo.png" alt="Imagemap Generator" title="" id="logo" /></a></p>
			<p class="author">HTML Imagemap Generator by <a rel="author" href="https://plus.google.com/113304109683958874741/">Dario D. Müller</a></p>
		</div>
	</header>
	
	<?php
		if($uploaded)
			echo '<div id="navi" currentValue="#imagemap4posis">';
		else
			echo '<div id="navi" currentValue="#upload">';
	?>
		<ul>
			<li><a href="#" rel="#upload" class="blue">Imagemap Generator</a></li>
			<li><a href="#" rel="#htmlinfos" class="red">HTML Infos</a></li>
			<li><a href="#" rel="#htmlinfos2" class="yellow">Applications</a></li>
			<li><a href="#" rel="#aboutinfos" class="green">About</a></li>
		</ul>
	</div>
	
	<div id="upload" class="effect infobox">
		<article>
			<div class="uploadContainer infobox2">
				<div id="uploadUndo"<?php if(!$uploaded) echo ' style="display: none;"'; ?>></div>
				<h1>Imagemap Generator</h1>
				<p>Select an image. Choose one of your choice, for example a map. You have the possibility to upload a new file or specify an URL link.</p>
				<p>For getting help, check out our <a href="#" rel="#htmlinfos">HTML Infos</a> and <a href="#" rel="#aboutinfos">About Page</a>.</p>
				<p class="headline">Select a local file</p>
				<form action="#" method="post">
					<input type="file" name="image" id="uploadify" />
					<script type="text/javascript">
						$(function() {
							<?php $timestamp = time(); ?>
							$('#uploadify').uploadify({
								'formData'      : {
									'timestamp' : '<?php echo $timestamp; ?>',
									'<?php echo session_name();?>' : '<?php echo session_id();?>'
								},
								'fileObjName'   : 'image',
								'fileTypeExts'  : '*.jpg; *.jpeg; *.gif; *.png', 
								'buttonText'	: 'upload image',
								'swf'           : 'uploadify.swf',
								'uploader'      : 'uploadify.php',
								width: 200,
								height: 35,
								onUploadSuccess : function(file, dataAuth, response) {
									// identify upload 2 user session
									data = dataAuth.split(',\"');
									dataAuth = data[0]+',\"uploads/'+data[1];
									jQuery.ajax({
										type: 'POST',
										url: 'upload_ident.php',
										data: {'data': dataAuth},
										dataType : 'json'
									});
									
									// hide upload area and show imagemap generator
									var returnValue = $.parseJSON(dataAuth);
									if(returnValue[0] == true) {
										$('#imagemap4posis #mapContainer').find('img').attr('src', returnValue[1]);
										
										removeErrorMessage();
										removeOldMapAndValues();
										$('#navi').attr('currentValue', '#imagemap4posis');
										
										$('#upload').slideUp(400, function() {
											//$('.dot').fadeIn(400);
											$('#uploadUndo').show();
											$('#imagemap4posis').slideDown(400, function() {
												resizeHtml();
											});
											loadImagemapGenerator(returnValue[2], returnValue[3]);
										});
									} else {
										$('#upload .uploadContainer').append('<p>'+returnValue[1]+'</p>');
									}
								}
							});
						});
					</script>
				</form>
				<p class="headline">Or insert an image link</p>
				<form action="#" id="linkform">
					<input type="text" name="fileurl" value="" placeholder="http://www.." id="imageurl" class="insetEffect" />
					<a href="#" class="imageurl_submit"></a>
				</form>
				<p>After choosing a file, you can generate HTML &lt;area&gt;-coordinates and imagemap-source and by clicking in the image.</p>
			</div>
		</article>
	</div>
	
	<div id="htmlinfos" class="effect infobox">
		<article>
			<div class="infobox2">
				<h2>HTML Infos: &lt;map&gt; &amp; &lt;area&gt;</h2>
				<p>An Imagemap is a HTML element, which can be used with an image to integrate links. On the contrary to an &lt;a&gt;-tag, it allows to set several areas of this image with links.</p>
				<p>The clickable area can be a rectangle (shape="rect"), an polygon (shape="poly") or an circle (shape="circle").</p>
				<p>It's very useful while creating banners, mails and landingpages.</p>
				<div class="sh_peachpuff snippet-wrap">
					<div style="display: none;" class="snippet-menu sh_sourceCode">
						<div class="snippet-clipboard" style="position: absolute; left: 0px; top: 0px; width: 0px; height: 0px; z-index: 99;">
							<embed width="0" height="0" align="middle" wmode="transparent" flashvars="id=1&amp;width=0&amp;height=0" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" allowfullscreen="false" allowscriptaccess="always" name="ZeroClipboardMovie_1" bgcolor="#ffffff" quality="best" menu="false" loop="false" src="snippet-highlighter/ZeroClipboard.swf" id="ZeroClipboardMovie_1">
						</div>
					</div>
					<pre class="context sh_html snippet-formatted sh_sourceCode"><ol class="snippet-num"><li><span class="sh_keyword">&lt;img</span> <span class="sh_type">src</span><span class="sh_symbol">=</span><span class="sh_string">"teaser.jpg"</span> <span class="sh_type">usemap</span><span class="sh_symbol">=</span><span class="sh_string">"#Teaser"</span> <span class="sh_type">alt</span><span class="sh_symbol">=</span><span class="sh_string">""</span> <span class="sh_keyword">/&gt;</span></li><li><span class="sh_keyword">&lt;map</span> <span class="sh_type">name</span><span class="sh_symbol">=</span><span class="sh_string">"Teaser"</span> <span class="sh_type">id</span><span class="sh_symbol">=</span><span class="sh_string">"Teaser"</span><span class="sh_keyword">&gt;</span></li><li><span class="sh_keyword">&lt;area</span> <span class="sh_type">alt</span><span class="sh_symbol">=</span><span class="sh_string">""</span> <span class="sh_type">href</span><span class="sh_symbol">=</span><span class="sh_string">""</span> <span class="sh_type">coords</span><span class="sh_symbol">=</span><span class="sh_string">"10,10,20,20"</span> <span class="sh_type">shape</span><span class="sh_symbol">=</span><span class="sh_string">"rect"</span> <span class="sh_keyword">/&gt;</span></li><li><span class="sh_keyword">&lt;area</span> <span class="sh_type">alt</span><span class="sh_symbol">=</span><span class="sh_string">""</span> <span class="sh_type">href</span><span class="sh_symbol">=</span><span class="sh_string">""</span> <span class="sh_type">coords</span><span class="sh_symbol">=</span><span class="sh_string">"10,10,20,20,30,30 [...] "</span> <span class="sh_type">shape</span><span class="sh_symbol">=</span><span class="sh_string">"poly"</span> <span class="sh_keyword">/&gt;</span></li><li><span class="sh_keyword">&lt;area</span> <span class="sh_type">alt</span><span class="sh_symbol">=</span><span class="sh_string">""</span> <span class="sh_type">href</span><span class="sh_symbol">=</span><span class="sh_string">""</span> <span class="sh_type">coords</span><span class="sh_symbol">=</span><span class="sh_string">"16,619,259"</span> <span class="sh_type">shape</span><span class="sh_symbol">=</span><span class="sh_string">"circle"</span> <span class="sh_keyword">/&gt;</span></li><li><span class="sh_keyword">&lt;/map&gt;</span></li></ol></pre>
				</div>
				<img src="images/html_imagemap_example.png" alt="" />
				<h3>How to use Shape-Values</h3>
				<p>Shape-Values are coordiate-pairs. Every pair have a X and a Y value (from left/top of an image), separated with a comma. Every pair is as well separated with a comma.</p>
				<p><strong>Rect</strong> Allows two coordinate-pairs.</p>
				<p><strong>Poly</strong> Allows any number of coordinate-pairs you want.</p>
				<p><strong>Circle</strong> One coordinate-pair and secound value the radius.</p>
			</div>
		</article>
	</div>
	
	<div id="htmlinfos2" class="effect infobox">
		<article>
			<div class="infobox2">
				<h2>Maps &amp; Newsletter with Imagemaps</h2>
				<p>Imagemap's are defined with HTML 3.2. Every Web-Browser and Mail-Client supports Imagemaps without having problems.</p>
				<p>Popular applications are Newsletter with large Teaser and Landingpages or Banner on Websites. Mostly World-Maps or Country-Maps are build with these technology.</p>
				<h3>Browser-Support</h3>
				<ul>
					<li>Firefox</li>
					<li>Internet Explorer 6+</li>
					<li>Chrome, Safari (WebKit)</li>
				</ul>
				<h3>Mail-Clients</h3>
				<ul>
					<li>Outlook</li>
					<li>Thunderbird</li>
					<li>Apple Mail</li>
				</ul>
				<p>You see, there aren't probleme using this Maps. For questions, write an email to <a href="mailto:mailme@dariodomi.de">mailme@dariodomi.de</a> or click <a href="#" rel="#htmlinfos">HTML Infos</a>.</p>
			</div>
		</article>
	</div>
	
	<div id="aboutinfos" class="effect infobox">
		<article>
			<div class="infobox2">
				<h2>About this Imagemap Tool</h2>
				<img src="images/generator-html-thumb.gif" alt="" title="" />
				<p>This Software generates HTML Imagemaps and &lt;area&gt;-tags by clicking in an uploaded image.</p>
				<p>Usage:</p>
				<ul>
					<li>Upload an image</li>
					<li>Click the image to change coordinates</li>
					<li>Copy the values or the Map-Area HTML-Code</li>
				</ul>
				<h3>Applications</h3>
				<p>I personally use this software, while developing Newsletter and Landingpages.</p>
				<p>In some kinds of HTML, for example mail, you have not the opportunity to use extreme CSS-Skills and there are many images with a lot of links.</p>
				<p>With this software, you have the possibility to create every map-area you want in only a few seconds. <a href="#" rel="#htmlinfos2">more</a></p>
			</div>
		</article>
	</div>
	
	<div id="imagemap4posis">
		<div id="newUpload"><span></span></div>
		<div id="urlMessage"><p class="effect">You can't see an image?<br /><a href="#">Please upload a new one &raquo;</a></p></div>
		<div id="mapContainer" class="effect">
			<img src="<?php echo ($uploaded) ? $_SESSION['image'][0] : '#'; ?>" id="main" class="imgmapMainImage" alt="" usemap="#map" />
			<map name="map" id="map"></map>
		</div>
		<div class="form">
			<input id="coordsText" class="effect" name="" type="text" value="" placeholder="&laquo; Coordinates &raquo;" />
			<div class="clearButton"></div>
			<textarea name="" id="areaText" class="effect" placeholder="&laquo; HTML-Code &raquo;"></textarea>
		</div>
	</div>
	
	<div id="infotext">
		<address class="author">Design &amp; Code &copy; 2013 by <a rel="author" href="https://plus.google.com/113304109683958874741/" title="Dario D. Müller">Dario D. Müller</a><br />Feedback: <a href="mailto:mailme@dariodomi.de">mailme@dariodomi.de</a></address>
		<p class="infolast">This Software uses Google Analytics.<br />It's build with jQuery Framework and CSS3.</p>
	</div>
	<div id="info"></div>
	
	<footer>
		<div id="about">
			<p><a href="http://imagemap-generator.dariodomi.de/">Software</a> by <a href="http://dariodomi.de" target="_blank">Dario D. M&uuml;ller</a><span></span>Design &amp; Code &copy; 2013<span></span><a href="http://dariodomi.de/kontakt" target="_blank">Feedback &amp; Contact</a></p>
		</div>
	</footer>
	
	<script type="text/javascript">
		/* init */
		$(function() {
			<?php if($uploaded) { ?>
				setTimeout(function() {
					$('#imagemap4posis').slideDown(400, function() {
						resizeHtml();
					});
					loadImagemapGenerator(0,0);
				}, 600);
			<?php } else { ?>
				$('#upload').delay(600).slideDown(400, function() {
					resizeHtml();
				});
				resizeHtml();
			<?php } ?>
		});
		
		/* Google Analytics */
		
		var url=document.URL.split('/')[2];
		if(url != 'linuxdev1') {
			var _gaq = _gaq || [];
			var pluginUrl = '//www.google-analytics.com/plugins/ga/inpage_linkid.js';
			_gaq.push(['_require', 'inpage_linkid', pluginUrl]);
			_gaq.push(['_setAccount', 'UA-38069110-1']);
			_gaq.push (['_gat._anonymizeIp']);
			_gaq.push(['_trackPageview']);
			(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		}
	</script>
</body>
</html>