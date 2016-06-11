jQuery(document).ready(function ($) {

	$('#di-insert-content').click( function() {
		var amount = $('#di-amount').val(),
			type = $('#di-type').val(),
			tag = $('#di-tag').val(),
			perPara = $('#di-perpara').val();

		var character = '';
		if ( $('#di-character').val().length ) {
			character = $('#di-character').val();
		}

		var args = {
			'type': type,
			'amount': amount,
			'tag': tag,
			'perPara': perPara,
		}

		if ( character.length ) { args['character'] = character; }
		var holder = document.createElement("span");
		$(holder).addClass('di-temp-holder');
		$(holder).delorean( args );
		// Send the shortcode to the editor
		window.send_to_editor(holder.innerHTML);
		$('.di-temp-holder').remove();
	});

});