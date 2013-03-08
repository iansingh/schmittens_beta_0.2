// disable upload button if no file is selected

$(document).ready(
    function(){
        $('.imgupload').change(
            function(){
                if ($(this).val()) {
                    $('.disable').attr('disabled',false);
                    // or, as has been pointed out elsewhere:
                    // $('input:submit').removeAttr('disabled'); 
                } 
            }
            );
    });

// disable generic button until checkbox is triggered
$(document).ready(
    function(){
        $('.accept').change(
            function(){
                if ($(this).val()) {
                    $('.disable').attr('disabled',false);
                    // or, as has been pointed out elsewhere:
                    // $('input:submit').removeAttr('disabled'); 
                } 
            }
            );
    });
 
 
// toggle what to display on useradmin.php   
    
$(document).ready(
	function() {
		$('h1#account').click(function() {	
			$('div#account').toggleClass('hidden');
		});
	}

)

$(document).ready(
	function() {
		$('h1#events').click(function() {	
			$('div#events').toggleClass('hidden');
		});
	}

)

$(document).ready(
	function() {
		$('h1#locations').click(function() {	
			$('div#locations').toggleClass('hidden');
		});
	}

)

$(document).ready(
	function() {
		$('h1#export').click(function() {	
			$('div#export').toggleClass('hidden');
		});
	}

)

$(document).ready(
	function() {
		$('#fsecret:empty').change(function() {	
			$('p#exportlink').toggleClass('hidden');
		});
	}

)

$(function () {

    var counter = 0,
        spans = $('#s1, #s2, #s3');

    function showSpan () {
        spans.hide() // hide all divs
            .filter(function (index) { return index == counter % 3; }) // figure out correct div to show
            .show('slow'); // and show it

        counter++;
    }; // function to loop through divs and show correct div

    showSpan(); // show first div    

    setInterval(function () {
        showSpan(); // show next div
    }, 10 * 1000); // do this every 10 seconds    

});
