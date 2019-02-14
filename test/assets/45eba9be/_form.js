var fileInput = document.getElementById('image');
var dropZone = document.getElementById('drop_zone');

var fileInput1 = document.getElementById('images');
var dropZone1 = document.getElementById('drop_zone1');

function role_adder(id)
{
	var random_hash_name='field_'+Math.floor((Math.random() * 1000) + 1);
	var html='<div class="well" id="'+random_hash_name+'"><a class="ui-icon ui-icon-close pull-right" onclick="$(this).parent().remove();return 0;"></a><label>Enter Name <span class="required">*</span></label><input type="text" id="tb_'+random_hash_name+'" name="new_creator['+cfg.creator_counter+'][name]" required="required" autocomplete="off" rel="tooltip" data-title="Format: First Middle Last Suffix; Input a minimum of 3 characters to receive suggestions"  data-placement="right"><input id="role_'+random_hash_name+'" type="hidden" name="new_creator['+cfg.creator_counter+'][role]" value="'+id+'" /><input id="individualID_'+random_hash_name+'" type="hidden" name="new_creator['+cfg.creator_counter+'][individualID]" value="0" /></div>';
	cfg.creator_counter++;
	$('#role_'+id).prepend(html);
	$( "#tb_"+random_hash_name ).autocomplete({
		source: cfg.showBaseUrl+'/'+'CreatorLists',
		minLength: 3,
		change: function( event, ui ) {
			$( "#individualID_"+random_hash_name ).val( ui.item? ui.item.id : 0 );
			setTimeout(function(){},1);
		}
	})
	.autocomplete( "instance" )._renderItem = function( ul, item ) {
		return $( "<li>" ).append( "<a>" + item.value + "<br><small class='muted'>" + item.desc + "</small></a>" ).appendTo( ul );
	};
}

function updateCoordinates(cropbox) {
	$("#crop_x").val(cropbox.x);
	$("#crop_y").val(cropbox.y);
	$("#width").val(cropbox.w);
	$("#height").val(cropbox.h);
}

function handleDragOver(evt) {
	evt.stopPropagation();
	evt.preventDefault();
	evt.dataTransfer.dropEffect = 'copy'; // Explicitly show this is a copy.
}

function handleClick(evt) {
	$("#image").click();
};

function handleClick1(evt) {
	$("#images").click();
};

function handleFileSelect(evt) {
	var files;
	if (typeof evt.target.files != 'undefined') {
		files = evt.target.files;
	} else {
		evt.stopPropagation();
		evt.preventDefault();
		files = evt.dataTransfer.files;
	}
	if (files.length != 0) {
		// Loop through the FileList and render image files as thumbnails.
		for (var i = 0, f; f = files[i]; i++) {
			// Only process image files.
			if (!f.type.match('image.*')) {
				continue;
			}

			var reader = new FileReader();
			// Closure to capture the file information.
			reader.onload = (function(theFile) {
				return function(e) {
					// Render thumbnail. 
					var image = new Image();
					image.src = e.target.result;
					image.onload = function() {           
						$('#btnImgUpd').prop('disabled', true);
						if (cfg.jCropRef) cfg.jCropRef.destroy();
						$("#preview").html("");
						$("#pnlPreview").hide();
						$(".img-upload.alert.alert-error").hide();
						if (image.width > 1000 || image.height > 800) {
							$(".img-upload.alert.alert-error").html("Picture too large and cannot be processed. Please try uploading a smaller picture").show();
							$("#image").val("");
							return false;
						} else {
							var maxWidth = 280;
							var maxHeight = 440;
							if (image.width < maxWidth)
							{
								maxWidth = image.width;
							}
							if (image.height < maxHeight)
							{
								maxHeight = image.height;
							}
							var x1 = image.width / 2 - maxWidth / 2;
							var x2 = image.width / 2 + maxWidth / 2;
							var y1 = image.height / 2 - maxHeight / 2;
							var y2 = image.height / 2 + maxHeight / 2;
							var img = $('<img id="imgSrc" src="' + e.target.result + '"></img>');
							$("#preview").append(img);
							img.Jcrop({
								//onSelect:    showCoords,
								onChange: updateCoordinates,
								bgColor: 'black',
								bgOpacity: .4,
								//maxSize: [maxWidth, maxHeight],
								//minSize: [maxWidth, maxHeight],
								aspectRatio: 7 / 11,
								setSelect: [x1, y1, x2, y2],
								boxWidth: maxWidth,
								boxHeight: maxHeight,
							}, function() {
								cfg.jCropRef = this;
							});
							$('#btnImgUpd').prop('disabled', false);
							$("#pnlPreview").show();
						}
					}
				};
			})(f);

			reader.readAsDataURL(f);
		}
	}
	fileInput.removeEventListener('change', handleFileSelect, false);
	fileInput.files = files;
	fileInput.addEventListener('change', handleFileSelect, false);
}

function handleFileSelect1(evt) {
	if (typeof evt.target.files != 'undefined') {
		var files = evt.target.files;
	} else {
		evt.stopPropagation();
		evt.preventDefault();
		var files = evt.dataTransfer.files;
	}
	
	$('#btnImgUpd1').prop('disabled', true);
	$("#preview1").html("");
	$("#pnlPreview1").hide();
	$(".img-upload1.alert.alert-error").hide();
	
	if (files.length != 0) {
		// Loop through the FileList and render image files as thumbnails.
		for (var i = 0, f; f = files[i]; i++) {
			// Only process image files.
			if (!f.type.match('image.*')) {
				continue;
			}

			var reader = new FileReader();
			// Closure to capture the file information.
			reader.onload = (function(theFile) {
				return function(e) {
					// Render thumbnail. 
					var image = new Image();
					image.src = e.target.result;
					image.onload = function() {  
						$(".img-upload.alert.alert-error").hide();					
						if (image.width > 1280 || image.height > 1024) {
							$(".img-upload1.alert.alert-error").html("Some images were too large and could not be processed. The maximum allowed is 1280X1024 pixels.").show();
							$("#images").val("");
							$("#preview1").html("");
							$('#btnImgUpd1').prop('disabled', true);
							$("#pnlPreview1").hide();
							return false;
						} else {
							var img = $('<img class="thumbnail pull-left" src="' + e.target.result + '"></img>');
							$("#preview1").append(img);
							$('#btnImgUpd1').prop('disabled', false);
							$("#pnlPreview1").show();
						}
					}
				};
			})(f);
			reader.readAsDataURL(f);
		}
	}
	fileInput1.removeEventListener('change', handleFileSelect1, false)
	fileInput1.files = files;
	fileInput1.addEventListener('change', handleFileSelect1, false)
}

fileInput.addEventListener('change', handleFileSelect, false);
dropZone.addEventListener('dragover', handleDragOver, false);
dropZone.addEventListener('drop', handleFileSelect, false);
dropZone.addEventListener('click', handleClick, false);

if(fileInput1)
	fileInput1.addEventListener('change', handleFileSelect1, false);
if(dropZone1)
{
	dropZone1.addEventListener('dragover', handleDragOver, false);
	dropZone1.addEventListener('drop', handleFileSelect1, false);
	dropZone1.addEventListener('click', handleClick1, false);
}

$("#btnImgUpd").click(function() {
	if (cfg.isNewRecord== 1) {
        var rx = $("#crop_x").val();
		var ry = $("#crop_y").val();
		var width = $("#width").val();
		var height = $("#height").val();
		var img = new Image();
		img.src = $("#imgSrc").attr("src");
		canvas = $('<canvas width="'+ width +'" height="'+ height +'"/>').appendTo('body').hide(),
        ctx = canvas.get(0).getContext('2d'),
		ctx.drawImage(img, rx, ry, width, height, 0, 0, width, height);
		var base64ImageData = canvas.get(0).toDataURL();
		$("#imgProfilePic").prop("src", canvas.get(0).toDataURL());
		canvas.remove();
	} else {
		$("#btnSubmit").click();
	}
});

$("#btnImgUpd1").click(function() {
	$("#btnSubmit").click();
});

$('[data-target="#myModal"]').click(function() {
	$("#preview").html("");
	$("#pnlPreview").hide();
	$("#image").val("");
	if (cfg.jCropRef) cfg.jCropRef.destroy();
});

$('[data-target="#myModal1"]').click(function() {
	$("#preview1").html("");
	$("#pnlPreview1").hide();
	$("#images").val("");
});

$(document).on("click",".deleteConfirm",function(e){
	e.preventDefault();
    var location = $(this).attr('href');
	bootbox.confirm("Are you sure you want to delete this record?",function(result)
		{
			if(result)
				window.location.replace(location);
        });
});