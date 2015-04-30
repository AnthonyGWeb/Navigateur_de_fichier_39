$(document).ready(function() {

	$('a[data-link-file]').click(openFile);

	$('#new-folder').click(function(){
		var nameFolder = window.prompt('Nom du dossier :');
		var url = document.location.href;

// console.log(typeof nameFolder);

		if (nameFolder != null) {
			if (url.match(/\?/)) {
				$(location).attr('href', url + '&new_folder=' + nameFolder);
			}
			else {
				$(location).attr('href', url + '?new_folder=' + nameFolder);
			}
		}
	});

	$('.delete-file').click(function() {
		
		var link = $(this).data('url');
		var url = $(location).attr('href');

		var reponse = window.confirm('Supprimer : ' + link + ' ?');

		if (reponse) {
			$.ajax({
				type: 'GET',
				url: '?delete=' + link,
				success: function() {
					$(location).attr('href', url);
				}
			});
		}
	});
	

	dragAndDrop();

});


/******************************************************
	DRAG AND DROP
********************************************************/
var dragAndDrop = function() {

	$( ".draggable" ).draggable({
		revert: "invalid",
		helper: 'clone'
	});

    $( ".droppable" ).droppable({
    	activeClass: "drop-state-active",
    	hoverClass: "drop-state-hover",
    	drop: function( event, ui ) {
			
    		var fileDrag = ui.draggable[0].getAttribute('data-file-name');

    		var destination = this.getAttribute('data-url') + '/' + fileDrag;

    		var oldPath = ui.draggable[0].getAttribute('data-url');

    		var url = this.getAttribute('href');

			$.ajax({
				type: 'POST',
				url: '?moveFile=true',
				data: {
					drag: true,
					destination: destination,
					oldPath: oldPath
				},
				success: function(){
					$(location).attr('href', url);
				}
			});
		}
    });
}
