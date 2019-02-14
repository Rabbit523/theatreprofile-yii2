var fileInput = document.getElementById('image');
var dropZone = document.getElementById('drop_zone');

var fileInput1 = document.getElementById('images');
var dropZone1 = document.getElementById('drop_zone1');

$( "#Production_firstPreviewDate" ).datepicker({dateFormat:"mm-dd-yy"});
$( "#Production_startDate" ).datepicker({dateFormat:"mm-dd-yy"});
$( "#Production_endDate" ).datepicker({dateFormat:"mm-dd-yy"});

$( "#Show_showName" ).autocomplete({
	source: cfg.showBaseUrl+'/ShowLists',
	minLength: 0,
	change: function( event, ui ) {
		$( "#Production_showID" ).val( ui.item? ui.item.id : 0 );
	}
})
.autocomplete( "instance" )._renderItem = function( ul, item ) {
	return $( "<li>" ).append( "<a>" + item.value + "<br><small class='muted'>" + item.desc + "</small></a>" ).appendTo( ul );
};


function link_adder(id)
{
	var random_hash_name='field_'+Math.floor((Math.random() * 10000) + 1);
	var html='<div class="well" id="'+random_hash_name+'"><a class="ui-icon ui-icon-close pull-right" onclick="$(this).parent().remove();"></a><label>Label</label><input type="text" class="input-block-level" name="links[new]['+cfg.link_counter+'][label]" required="required" value="Buy Tickets" /><label>URL</label><input type="text" class="input-block-level" name="links[new]['+cfg.link_counter+'][href]" required="required" rel="tooltip" data-placement="right" title="Input full URL including http:// or https://" /><input type="hidden" name="links[new]['+cfg.link_counter+'][productionVenueID]" value="'+id+'"/></div>';
	cfg.link_counter++;
	$("#ticketinglinks_"+id).append(html);
}

function venue_adder()
{
	var random_hash_name='field_'+Math.floor((Math.random() * 10000) + 1);
	var html='<div class="well" id="'+random_hash_name+'"><a class="ui-icon ui-icon-close pull-right" onclick="$(this).parent().remove();"></a><label>Enter Venue Name</label><input id="tb_'+random_hash_name+'" type="text" name="venue[new]['+cfg.venue_counter+'][venueName]" required="required" autocomplete="off" rel="tooltip" data-placement="right" title="Enter venue name only; Input a minimum of 3 characters to receive suggestions" /><label>Start date</label><input id="venue_start_'+random_hash_name+'" type="text" name="venue[new]['+cfg.venue_counter+'][startDate]" rel="tooltip" title="MM-DD-YYYY" data-placement="right" /><span class="help-block">Leave empty to use production start date.</span><label>End date</label><input id="venue_end_'+random_hash_name+'" type="text" name="venue[new]['+cfg.venue_counter+'][endDate]" rel="tooltip" title="MM-DD-YYYY" data-placement="right" /><span class="help-block">Leave empty to use production end date.</span><input id="venueID_'+random_hash_name+'" type="hidden" name="venue[new]['+cfg.venue_counter+'][venueID]" value="0" ></div>';
	cfg.venue_counter++;
	$('#venue_container').prepend(html);
	$( "#venue_start_"+random_hash_name ).datepicker({dateFormat:"mm-dd-yy"});
	$( "#venue_end_"+random_hash_name ).datepicker({dateFormat:"mm-dd-yy"});
	$( "#tb_"+random_hash_name ).autocomplete({
		source: cfg.productionBaseUrl+'/VenueLists',
		minLength: 3,
		change: function( event, ui ) {
			$( "#venueID_"+random_hash_name ).val( ui.item? ui.item.id : 0 );;
			setTimeout(function(){
			},1);
		}
	})
	.autocomplete( "instance" )._renderItem = function( ul, item ) {
		return $( "<li>" ).append( "<a>" + item.value + "<br><small class='muted'>" + item.desc + "</small></a>" ).appendTo( ul );
	};
}

function cast_adder()
{
	var random_hash_name='field_'+Math.floor((Math.random() * 10000) + 1);
	var html='<div class="well" id="'+random_hash_name+'"><a class="ui-icon ui-icon-close pull-right" onclick="$(this).parent().remove();"></a><label>Enter Cast Name</label><input id="tb_'+random_hash_name+'" type="text" name="cast[new]['+cfg.cast_counter+'][castName]" required="required" data-autocomplete="individual" autocomplete="off" rel="tooltip" title="Format: First Middle Last Suffix; Input a minimum of 3 characters to receive suggestions" data-placement="right"><label>Role Name</label><input id="cast_roleName_'+random_hash_name+'" type="text" name="cast[new]['+cfg.cast_counter+'][roleName]" maxlength="100"><label>Start date</label><input id="cast_start_'+random_hash_name+'" type="text" name="cast[new]['+cfg.cast_counter+'][startDate]" rel="tooltip" title="MM-DD-YYYY" data-placement="right" /><label>End Date</label><input id="cast_end_'+random_hash_name+'" type="text" name="cast[new]['+cfg.cast_counter+'][endDate]" rel="tooltip" title="MM-DD-YYYY" data-placement="right" /><input id="individualID_'+random_hash_name+'" type="hidden" name="cast[new]['+cfg.cast_counter+'][individualID]" value="0" /></div>';
	cfg.cast_counter++;
	$('#cast_container').prepend(html);
	$( "#cast_start_"+random_hash_name ).datepicker({dateFormat:"mm-dd-yy"});
	$( "#cast_end_"+random_hash_name ).datepicker({dateFormat:"mm-dd-yy"});
	//$( "#tb_"+random_hash_name ).autocomplete({
	//	source: "<?php echo yii::app()->createUrl('Show/CreatorLists') ?>",
	//	minLength: 3,
	//	change: function( event, ui ) {
	//		$( "#cast_id_"+random_hash_name ).val( ui.item? ui.item.id : 0 );;
	//		setTimeout(function(){},1);
	//	}
	//});
}

function crew_adder()
{
	var random_hash_name='field_'+Math.floor((Math.random() * 10000) + 1);
	//var role ="<select name='crew["+cfg.crew_counter+"][roleID]'>"+$('#role').html()+"</select>";
	var html='<div class="well" id="'+random_hash_name+'"><a class="ui-icon ui-icon-close pull-right" onclick="$(this).parent().remove();"></a><strong><label class="radio inline"><input type="radio" class="crewType" name="crew[new]['+cfg.crew_counter+'][crewType]" id="crewType1_'+random_hash_name+'" value="1" checked>Individual</label><label class="radio inline"><input type="radio" class="crewType" name="crew[new]['+cfg.crew_counter+'][crewType]" id="crewType2_'+random_hash_name+'" value="2">Company</label><label>Enter Crew Name</label><input data-autocomplete="individual" type="text" name="crew[new]['+cfg.crew_counter+'][crewName]" required="required" autocomplete="off" rel="tooltip" title="Format: First Middle Last Suffix; Input a minimum of 3 characters to receive suggestions" data-placement="right"><label>Role</label><input id="tbrole_'+random_hash_name+'" type="text" autocomplete="off" name="crew[new]['+cfg.crew_counter+'][roleName]" required="required"  maxlength="100" rel="tooltip" data-placement="right" title="Input a minimum of 3 characters to receive suggestions" /><label>Start Date</label><input id="crew_start_'+random_hash_name+'" type="text" name="crew[new]['+cfg.crew_counter+'][startDate]" rel="tooltip" title="MM-DD-YYYY" data-placement="right"><label>End Date</label><input id="crew_end_'+random_hash_name+'" type="text" name="crew[new]['+cfg.crew_counter+'][endDate]" rel="tooltip" title="MM-DD-YYYY" data-placement="right" /><input id="individualID_'+random_hash_name+'" type="hidden" name="crew[new]['+cfg.crew_counter+'][profileID]" value="0" /><input id="crew_roleID_'+random_hash_name+'" type="hidden" name="crew[new]['+cfg.crew_counter+'][roleID]" value="0" /></strong></div>';
	cfg.crew_counter++;
	$('#crew_container').prepend(html);
	$( "#crew_start_"+random_hash_name ).datepicker({dateFormat:"mm-dd-yy"});
	$( "#crew_end_"+random_hash_name ).datepicker({dateFormat:"mm-dd-yy"});
	//$( "#individualName_"+random_hash_name ).autocomplete({
	//	source: "<?php echo yii::app()->createUrl('Show/CreatorLists') ?>",
	//	minLength: 3,
	//	change: function( event, ui ) {
	//		$( "#crew_id_"+random_hash_name ).val( ui.item? ui.item.id : 0 );
	//		setTimeout(function(){},1);
	//	}
	//});
	
	
	$( "#tbrole_"+random_hash_name ).autocomplete({
		source: cfg.productionBaseUrl+'/CrewRoleList',
		minLength: 3,
		change: function( event, ui ) {
			$( "#crew_roleID_"+random_hash_name ).val( ui.item? ui.item.id : 0 );
			setTimeout(function(){},1);
		}
	});
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
	if (typeof evt.target.files != 'undefined') {
		var files = evt.target.files;
	} else {
		evt.stopPropagation();
		evt.preventDefault();
		var files = evt.dataTransfer.files;
		fileInput.files = files;
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
							//setTimeout(function() {
							//	$(".img-upload.alert.alert-error").hide('fadeout', {}, 500)
							//}, 3000);
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
}

function handleFileSelect1(evt) {
	if (typeof evt.target.files != 'undefined') {
		var files = evt.target.files;
	} else {
		evt.stopPropagation();
		evt.preventDefault();
		var files = evt.dataTransfer.files;
		fileInput.files = files;
	}
	
	$('#btnImgUpd1').prop('disabled', true);
	$("#preview1").html("");
	$("#pnlPreview1").hide();
	$(".img-upload.alert.alert-error").hide();
	
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
						if (image.width > 1280 || image.height > 1024) {
							$(".img-upload.alert.alert-error").html("Some images were too large and could not be processed. The maximum allowed is 1280X1024 pixels.").show();
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
	if (cfg.jcropRef) cfg.jcropRef.destroy();
});

$('[data-target="#myModal1"]').click(function() {
	$("#preview1").html("");
	$("#pnlPreview1").hide();
	$("#images").val("");
});

$(".ui-icon.edit").click(function(){
	$(this).parent().addClass("hide");
	$(this).parent().next().removeClass("hide");
});

$(".ui-icon.editCancel").click(function(){
	$(this).parent().addClass("hide");
	$(this).parent().prev().removeClass("hide");
});
$(document).on('focus', "[data-exttype='date']", function() {
	$(this).datepicker({dateFormat:"mm-dd-yy"});
});

$(document).on("click","input:radio[class=crewType]",function() {
	if($(this).filter(':checked').val()=="1")
	{
		$(this).parent().siblings("input[data-autocomplete='company']").val("");
		$(this).parent().siblings("input[data-autocomplete='company']").attr("data-original-title","Format: First Middle Last Suffix; Input a minimum of 3 characters to receive suggestions");
		$(this).parent().siblings("input[data-autocomplete='company']").attr("data-autocomplete","individual");
		$(this).parent().siblings("input[id^='individualID_']").val("");
	}
	else
	{
		$(this).parent().siblings("input[data-autocomplete='individual']").val("");
		$(this).parent().siblings("input[data-autocomplete='individual']").attr("data-original-title","Company name; Input a minimum of 3 characters to receive suggestions");
		$(this).parent().siblings("input[data-autocomplete='individual']").attr("data-autocomplete","company");
		$(this).parent().siblings("input[id^='individualID_']").val("");
	}
});

$(document).on('focus', "[data-autocomplete='individual']", function() {
	$(this).autocomplete({
		source: cfg.showBaseUrl+'/CreatorLists',
		minLength: 3,
		change: function( event, ui ) {
			$(this).parent().find("input[id^='individualID_']").val( ui.item? ui.item.id : 0 );;
			setTimeout(function(){},1);
		}
	});
});

$(document).on('focus', "[data-autocomplete='company']", function() {
	$(this).autocomplete({
		source: cfg.companyBaseUrl+'/CompanyLists',
		minLength: 3,
		change: function( event, ui ) {
			$(this).parent().find("input[id^='individualID_']").val( ui.item? ui.item.id : 0 );;
			setTimeout(function(){},1);
		}
	});
});

$(document).on('focus', "[data-autocomplete='role']", function() {
	$(this).autocomplete({
		source: cfg.productionBaseUrl+'/CrewRoleList',
		minLength: 3,
		change: function( event, ui ) {
			$(this).parent().find("input[id^='roleID_']").val( ui.item? ui.item.id : 0 );
			setTimeout(function(){},1);
		}
	});
});


$(document).on('focus', "[data-autocomplete='venue']", function() {
	$(this).autocomplete({
		source: cfg.productionBaseUrl+'/VenueLists',
		minLength: 3,
		change: function( event, ui ) {
			$(this).parent().find("input[id^='venueID_']").val( ui.item? ui.item.id : 0 );;
			setTimeout(function(){},1);
		}
	})
	.autocomplete( "instance" )._renderItem = function( ul, item ) {
		return $( "<li>" ).append( "<a>" + item.value + "<br><small class='muted'>" + item.desc + "</small></a>" ).appendTo( ul );
	};
});

$(document).on("click",".deleteVenueConfirm",function(e){
	e.preventDefault();
    var location = $(this).attr('href');
	bootbox.confirm("Are you sure you want to delete this venue? We like to keep a record of past venues so only delete if this record is a mistake.",function(result)
		{
			if(result)
				window.location.replace(location);
        });
});


$(document).on("click",".deleteCastConfirm",function(e){
	e.preventDefault();
    var location = $(this).attr('href');
	bootbox.confirm("Are you sure you want to delete this person? We like to keep a record of past casts members so only delete if this record is a mistake and this person was never connected to the production.",function(result)
		{
			if(result)
				window.location.replace(location);
        });
});

$(document).on("click",".deleteCrewConfirm",function(e){
	e.preventDefault();
    var location = $(this).attr('href');
	bootbox.confirm("Are you sure you want to delete this record? We like to keep a record of past creative, crew and staff so only delete if this record if it is a mistake.",function(result)
		{
			if(result)
				window.location.replace(location);
        });
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