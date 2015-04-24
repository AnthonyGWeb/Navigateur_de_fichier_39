$(document).ready(function() {
	//linkArray = $('a[data-link-file]');

	$('a[data-link-file]').click(function() {
		
		var url = $(this).data('file'); //url pour

		/********************************************
			Test des fichiers pouvant être afficher directement
		********************************************/
		if ($(this).attr('title').match(/^image/)) {
			$('main').prepend('<div class="display-image"><img src="' + url + '"></div>');

			return false;
		}

		$.ajax({
			type: 'GET',
			url: url,
			complete: function(xhr, status) {
				var func = funcHeader(xhr.getResponseHeader("content-type"));
				handleResponse[func](xhr.responseText);
				//console.log(func);
				//console.log(xhr.responseText);
				// mettre les data dans une div
			}
		});
		return false;
	});

//	console.log(linkArray);
});

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
	}
};
