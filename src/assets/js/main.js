$(document).ready(function() {

	$('a[data-link-file]').click(openFile);

	$('#upload').click(function() {
		var tree = document.location.search;

		tree = tree.replace('?', '&');

		$('main').prepend('<div class=""><form action="?action=upload' + tree + '" method="post" enctype="multipart/form-data"><input multiple type="file" name="filesUploaded[]"><input type="submit" value="upload"></form></div>');
	});

	$('#new-folder').click(function(){
		var nameFolder = window.prompt('Nom du dossier :');
		var url = document.location.href;
		var tree = document.location.search;

		tree = tree.replace('?', '&');

		if (nameFolder != null) {

			$.ajax({
				type: 'GET',
				url: '?new_folder=' + nameFolder + tree,
				success: function() {
					$(location).attr('href', url);
				}
			});
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
