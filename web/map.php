<?php

// This is the page title in <head>>. It's followed by "| Firefly"
$ff_page_title = "Map";

// This is centered content in the header
$ff_header_content = <<<EOT
	<h2>Map</h2>
	EOT;

$ff_additional_css = '<style>
    .ff-page-wrapper { height: 100%; }
</style>';

$ff_additional_scripts = <<<EOT
EOT;
include("header.php");
?>
<main>
  <iframe id="map-frame" src="map-contents.html" style="width: 100%; border: 0; display: block;"></iframe>
  <script>
    (function() {
      const frame = document.getElementById('map-frame');
      function resize() {
        const header = document.querySelector('header');
        const footer = document.querySelector('footer');
        const headerH = header ? header.offsetHeight : 0;
        const footerH = footer ? footer.offsetHeight : 0;
        frame.style.height = (window.innerHeight - headerH - footerH) + 'px';
      }
      resize();
      window.addEventListener('resize', resize);
      window.addEventListener('load', resize);
    })();
  </script>
</main>
<?php include("footer.php"); ?>