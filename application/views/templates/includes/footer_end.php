<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <script src="<?php echo base_url() ?>assets/js/loadingoverlay.min.js"></script>
    <script type="text/javascript">
		$('[data-toggle="tooltip"]').tooltip();
		$('body').tooltip({
		    selector: '[data-toggle="tooltip"]'
		});

		$(document).ajaxStart(function(){
			$.LoadingOverlaySetup({
			    background      : "rgba(0, 0, 0, 0.5)",
			    image           : "./logo.png",
			    imageAnimation  : "1.5s fadein",
			    imageColor      : "#ffcc00"
			});
		    $.LoadingOverlay("show");
		});
		$(document).ajaxStop(function(){
		    setTimeout(function(){$.LoadingOverlay("hide");}, 150);
		});

    	function goBack(){
    		window.history.back();
    	}
    	function capitalize(str) {
		    strVal = '';
		    str = str.split(' ');
		    for (var chr = 0; chr < str.length; chr++) {
		      strVal += str[chr].substring(0, 1).toUpperCase() + str[chr].substring(1, str[chr].length) + ' '
		    }
		    return strVal
		}
    </script>
</body>

</html>