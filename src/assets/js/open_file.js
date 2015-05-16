/***********************************************
AFFICHAGE DES FICHIERS
****************************************************/
var funcHeader = function(header) {
	var funcArray = {
		'^text/': 'editor'
	};

	for(var i in funcArray) {
		if (header.match(i)) {
			return funcArray[i];
		}
	}
};  

var handleResponse = {
	editor: function(data) {
		console.log(data);
		$('main').prepend('<div class="write-file"><code>' + data + '</code></div>');
	}
};

var openFile = function() {
	var url = $(this).data('file'); //url pour

	/********************************************
		Test des fichiers pouvant être afficher directement
	********************************************/
	if ($(this).attr('title').match(/^image/)) {
		$('main').prepend('<div class="display-image"><img src="' + url + '"><div class="close-image"><i class="fa fa-times"></i></div></div>');


		$('.close-image').click(function() {
				$('.display-image').remove();
		});

		return false;
	}

	if ($(this).attr('title').match(/^audio/)) {

		$('main').prepend('<div class="audio-file"><audio src="' + url + '" controls>coucou</audio><div class="close-image"><i class="fa fa-times"></i></div></div>');


		$('.close-image').click(function() {
				$('.audio-file').remove();
		});

		return false;
	}

	if ($(this).attr('title').match(/^application\/ogg/)) {

		$('main').prepend('<div class="video-file"><video src="' + url + '" controls>coucou</video><div class="close-image"><i class="fa fa-times"></i></div></div>');


		$('.close-image').click(function() {
				$('.video-file').remove();
		});

		return false;
	}

	$.ajax({
		type: 'GET',
		url: url,
		complete: function(xhr, status) {
			var func = funcHeader(xhr.getResponseHeader("content-type"));
			handleResponse[func](xhr.responseText);

		}
	});
	return false;
}
/**************************************************
**
**
**
**
***************************************************/
