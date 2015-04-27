$(document).ready(function() {

	$('a[data-link-file]').click(openFile);

	$('#new-folder').click(function(){
		var nameFolder = window.prompt('Nom du dossier :');
		var url = document.location.href;

		if (url.match(/\?/)) {
			$(location).attr('href', url + '&new_folder=' + nameFolder);
		}
		else {

			$(location).attr('href', url + '?new_folder=' + nameFolder);
		}
	});

	dragAndDrop();

});

var dragAndDrop = function() {
		/******************************************************
		DRAG AND DROP
	********************************************************/
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
