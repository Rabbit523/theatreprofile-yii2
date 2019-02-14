var fileInput = document.getElementById('image');
var dropZone = document.getElementById('drop_zone');

var fileInput1 = document.getElementById('images');
var dropZone1 = document.getElementById('drop_zone1');

function cast_adder()
{
	var random_hash_name='field_'+Math.floor((Math.random() * 10000) + 1);
	var html='<div class="well" id="'+random_hash_name+'"><a class="ui-icon ui-icon-close pull-right" onclick="$(this).parent().remove();"></a><label>Show</label><input id="showName_'+random_hash_name+'" type="text" name="cast[new]['+cfg.cast_counter+'][showName]" required="required"rel="tooltip" data-placement="right" title="Input a minimum of 3 characters to receive suggestions" autocomplete="off"/><label>Production</label><select id="productionID_'+random_hash_name+'" type="hidden" name="cast[new]['+cfg.cast_counter+'][productionID]"><option value="0">Select</option></select><label>Role Name</label><input type="text" name="cast[new]['+cfg.cast_counter+'][roleName]"  maxlength="100"><label>Start date</label><input id="cast_start_'+random_hash_name+'" type="text" name="cast[new]['+cfg.cast_counter+'][startDate]" rel="tooltip" title="MM-DD-YYYY" data-placement="right"><label>End date</label><input id="cast_end_'+random_hash_name+'" type="text" name="cast[new]['+cfg.cast_counter+'][endDate]" rel="tooltip" title="MM-DD-YYYY" data-placement="right"><input id="showID_'+random_hash_name+'" type="hidden" name="cast[new]['+cfg.cast_counter+'][showID]" value="0" /></div>';
	cfg.cast_counter++;
	$('#cast_container').prepend(html);
	$( "#cast_start_"+random_hash_name ).datepicker({dateFormat:"mm-dd-yy", changeMonth:true, changeYear:true});
	$( "#cast_end_"+random_hash_name ).datepicker({dateFormat:"mm-dd-yy", changeMonth:true, changeYear:true});
	$( "#showName_"+random_hash_name ).autocomplete({
		source: cfg.showBaseUrl+'/ShowLists',
		minLength: 3,
		select: function( event, ui ) {
			$( "#showID_"+random_hash_name ).val(ui.item.id);
			$.getJSON(cfg.venueBaseUrl+'/ProductionLists', {showID: ui.item.id},function(data)
			{
				$('#productionID_'+random_hash_name).empty();
				$('#productionID_'+random_hash_name).append(new Option("Select",0));				
				$('#productionID_'+random_hash_name).append(new Option("Create new production",0));
				$.each(data, function() {
					$('#productionID_'+random_hash_name).append(new Option(this.value, this.id));
				});
			});
		},
		change: function( event, ui ) {
			if(!ui.item)
			{
				$("#showID_"+random_hash_name).val(0);
				$('#productionID_'+random_hash_name).empty();
				$('#productionID_'+random_hash_name).append(new Option("Select",0));
				$('#productionID_'+random_hash_name).append(new Option("Create new production",0));
			}
		}
	})
	.autocomplete( "instance" )._renderItem = function( ul, item ) {
		return $( "<li>" ).append( "<a>" + item.value + "<br><small class='muted'>" + item.desc + "</small></a>" ).appendTo( ul );
	};
}

function crew_adder()
{
	var random_hash_name='field_'+Math.floor((Math.random() * 10000) + 1);
	//var role ="<select name='crew[new]["+cfg.crew_counter+"][roleID]'>"+$('#crew_role').html()+"</select>";
	var html='<div class="well" id="'+random_hash_name+'"><a class="ui-icon ui-icon-close pull-right" onclick="$(this).parent().remove();"></a><label>Show</label><input id="showName_'+random_hash_name+'" type="text" name="crew[new]['+cfg.crew_counter+'][showName]" required="required" rel="tooltip" data-placement="right" title="Input a minimum of 3 characters to receive suggestions" autocomplete="off"/><label>Production</label><select id="productionID_'+random_hash_name+'" type="hidden" name="crew[new]['+cfg.crew_counter+'][productionID]"><option value="0">Select</option></select><label>Role</label><input id="roleName_'+random_hash_name+'" type="text" autocomplete="off" name="crew[new]['+cfg.crew_counter+'][roleName]" required="required" maxlength="100" rel="tooltip" data-placement="right" title="Input a minimum of 3 characters to receive suggestions" autocomplete="off" /><label>Start date</label><input id="crew_start_'+random_hash_name+'" type="text" name="crew[new]['+cfg.crew_counter+'][startDate]" rel="tooltip" title="MM-DD-YYYY" data-placement="right" /><label>End date</label><input id="crew_end_'+random_hash_name+'" type="text" name="crew[new]['+cfg.crew_counter+'][endDate]" rel="tooltip" title="MM-DD-YYYY" data-placement="right" /><input id="showID_'+random_hash_name+'" type="hidden" name="crew[new]['+cfg.crew_counter+'][showID]" value="0" /><input id="roleID_'+random_hash_name+'" type="hidden" name="crew[new]['+cfg.crew_counter+'][roleID]" value="0" ></div>';
	cfg.crew_counter++;
	$('#crew_container').prepend(html);
	$( "#crew_start_"+random_hash_name ).datepicker({dateFormat:"mm-dd-yy", changeMonth:true, changeYear:true});
	$( "#crew_end_"+random_hash_name ).datepicker({dateFormat:"mm-dd-yy", changeMonth:true, changeYear:true});
	$( "#showName_"+random_hash_name ).autocomplete({
		source: cfg.showBaseUrl+'/ShowLists',
		minLength: 3,
		select: function( event, ui ) {
			$( "#showID_"+random_hash_name ).val(ui.item.id);
			$.getJSON(cfg.venueBaseUrl+'/ProductionLists', {showID: ui.item.id},function(data)
			{
				$('#productionID_'+random_hash_name).empty();
				$('#productionID_'+random_hash_name).append(new Option("Select",0));
				$('#productionID_'+random_hash_name).append(new Option("Create new production",0));					
				$.each(data, function() {
					$('#productionID_'+random_hash_name).append(new Option(this.value, this.id));
				});
			});
		},
		change: function( event, ui ) {
			if(!ui.item)
			{
				$("#showID_"+random_hash_name).val(0);
				$('#productionID_'+random_hash_name).empty();
				$('#productionID_'+random_hash_name).append(new Option("Select",0));
				$('#productionID_'+random_hash_name).append(new Option("Create new production",0));
			}
		}
	})
	.autocomplete( "instance" )._renderItem = function( ul, item ) {
		return $( "<li>" ).append( "<a>" + item.value + "<br><small class='muted'>" + item.desc + "</small></a>" ).appendTo( ul );
	};
	
	$( "#roleName_"+random_hash_name ).autocomplete({
		source: cfg.productionBaseUrl+'/CrewRoleList',
		minLength: 3,
		change: function( event, ui ) {
			$( "#roleID_"+random_hash_name ).val( ui.item? ui.item.id : 0 );
			setTimeout(function(){},1);
		}
	});
}

function creator_adder()
{
	var random_hash_name='field_'+Math.floor((Math.random() * 10000) + 1);
	var role ="<select name='creator[new]["+cfg.counter_counter+"][roleID]'>"+$('#creator_role').html()+"</select>";
	var html='<div class="well" id="'+random_hash_name+'"><a class="ui-icon ui-icon-close pull-right" onclick="$(this).parent().remove();"></a><label>Enter show name</label><input id="tb_'+random_hash_name+'" type="text" name="creator[new]['+cfg.counter_counter+'][showName]" required="required" rel="tooltip" data-placement="right" title="Input a minimum of 3 characters to receive suggestions" autocomplete="off"/><label>Role</label>'+role+'<input id="showID_'+random_hash_name+'" type="hidden" name="creator[new]['+cfg.counter_counter+'][showID]" value="0" /></div>';
	cfg.counter_counter++;
	$('#creator_container').prepend(html);
	$( "#tb_"+random_hash_name ).autocomplete({
		source: cfg.showBaseUrl+'/ShowLists',
		minLength: 3,
		change: function( event, ui ) {
			$( "#showID_"+random_hash_name ).val( ui.item? ui.item.id : 0 );
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

$(".ui-icon.edit").click(function(){
	$(this).parent().addClass("hide");
	$(this).parent().next().removeClass("hide");
});

$(".ui-icon.editCancel").click(function(){
	$(this).parent().addClass("hide");
	$(this).parent().prev().removeClass("hide");
});

$(document).on('focus', "[data-exttype='date']", function() {
	$(this).datepicker({dateFormat:"mm-dd-yy", changeMonth:true, changeYear:true});
});

$(document).on('focus', "[data-autocomplete='role']", function() {
	$(this).autocomplete({
		source: cfg.productionBaseUrl+'/CrewRoleList',
		minLength: 3,
		change: function( event, ui ) {
			$(this).siblings("input[id^='roleID_']").val( ui.item? ui.item.id : 0 );
		}
	});
});

$(document).on('focus', "[data-autocomplete='show']", function(){
	$(this).autocomplete({
		source: cfg.showBaseUrl+'/ShowLists',
		minLength: 3,
		select: function( event, ui ) {
			element = $(this);
			element.siblings("input[id^='showID_']").val(ui.item.id);
			if(element.siblings("select[id^='productionID_']").length>0)
			{
				$.getJSON(cfg.venueBaseUrl+'/ProductionLists', {showID: ui.item.id},function(data)
				{	
					element.siblings("select[id^='productionID_']").empty();
					element.siblings("select[id^='productionID_']").append(new Option("Select",0));
					element.siblings("select[id^='productionID_']").append(new Option("Create new production",0));
					$.each(data, function() {
						element.siblings("select[id^='productionID_']").append(new Option(this.value, this.id));
					});
				});
			}
		},
		change: function( event, ui ) {
			if(!ui.item)
			{
				element.siblings("input[id^='showID_']").val(0);
				if(element.siblings("select[id^='productionID_']").length>0)
				{
					element.siblings("select[id^='productionID_']").empty();
					element.siblings("select[id^='productionID_']").append(new Option("Select",0));
					element.siblings("select[id^='productionID_']").append(new Option("Create new production",0));
				}
			}
		}
	})
	.autocomplete( "instance" )._renderItem = function( ul, item ) {
		return $( "<li>" ).append( "<a>" + item.value + "<br><small class='muted'>" + item.desc + "</small></a>" ).appendTo( ul );
	};
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