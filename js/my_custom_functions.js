function resetInput(mail) {
	mail.value = "";
}

var onloadCallbackGRecaptcha = function(){
	// alert('google recaptcha');
	grecaptcha.render('grecaptcha_elem', {
		'sitekey': '6LehGhsTAAAAAAtvnqPnp9emtruwKf-J9dDwXJJy'
	});
};


//jQuery
//edit-profile-form
$(document).ready(function(){
	$('.new_team_check').click(function(){
		$('#new_team').toggle();
	});
});