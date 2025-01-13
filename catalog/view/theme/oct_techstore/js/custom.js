$( document ).ready(function() {
	$('#form-language').submit(function() {
		$('#form-language [name="redirect"]').val(window.location.href);
		console.log(window.location.href);
	});
});