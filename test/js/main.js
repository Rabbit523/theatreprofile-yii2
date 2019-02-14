var isMobile = false; //initiate as false
// device detection
if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
    || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) isMobile = true;

if(cfg.displayCookieAlert==1)
{
	window.onload = function() {
		cookieAlert({
			element: 'cookie-alert-container',
			name: cfg.cookieName,
			close: 'accept-cookies'
		});
	}	
}

$('#term').typeahead({
'source':function(query, process) {
	var longEnough = query.length >= this.options.minLength;
	if (longEnough) {
		// remember the query so that you can compare it to the next one
		//this.search = query;
		if (cfg.timeout) {
			clearTimeout(cfg.timeout);
		}
		
		cfg.timeout = setTimeout(function() {
			if(this.ajax_call)
				this.ajax_call.abort();
			this.ajax_call =
			$.ajax({
			url:cfg.searchBaseUrl+'/Searchajax?term='+query,
			type: 'GET'
			})
			.done(function(msg){
				results = [];
				map = {};
				var data = $.parseJSON(msg);
				$.each(data, function (i, result) {
					map[result.label] = result;
					results.push(result.label);	
					process(results);
				});
			});
		}, 500);
	}
}
,'minLength':3
,'items':5
,'header':'<h3 class=\"suggest-elements\">Search Results<\/h3>'
,'matcher':function(item) {
	//return ~item.toLowerCase().indexOf(this.query.toLowerCase());
	return true;
}
,updater: function (item) {
	if(map[item].profileType==1)
		window.location.href = cfg.showBaseUrl+'/'+map[item].profileID;
	else if(map[item].profileType==2)
		window.location.href = cfg.productionBaseUrl+'/'+map[item].profileID;
	else if(map[item].profileType==3)
		window.location.href = cfg.peopleBaseUrl+'/'+map[item].profileID;
	else if(map[item].profileType==4)
		window.location.href = cfg.venueBaseUrl+'/'+map[item].profileID;
	else if(map[item].profileType==5)
		window.location.href = cfg.companyBaseUrl+'/'+map[item].profileID;
}
,highlighter: function(item){
	var s = map[item];
	if(s.profileType==1)
	{
		var imageURL = (s.imageURL==null)?cfg.baseUrl+'/images/default/default_28x44.gif':cfg.baseUrl+ '/images/uploads/'+s.imageURL;
		var itm = ''
			 + "<div class='typeahead_wrapper'>"
			 + "<img class='typeahead_photo' src='" + imageURL + "' height='44px' width='28px' />"
			 + "<span class='typeahead_primary'> " + s.label + "</span>"
	}
	else if(s.profileType==2)
	{
		var imageURL = (s.imageURL==null)?cfg.baseUrl+'/images/default/default_28x44.gif':cfg.baseUrl+'/images/uploads/'+s.imageURL;
		var itm = ''
			 + "<div class='typeahead_wrapper'>"
			 + "<img class='typeahead_photo' src='" + imageURL + "' height='44px' width='28px' />"
			 + "<span class='typeahead_primary'> " + s.label + "</span>"
	}
	else if(s.profileType==3)
	{
		var imageURL = (s.imageURL==null)?cfg.baseUrl+'/images/default/default_28x44.gif':cfg.baseUrl+'/images/uploads/'+s.imageURL;
		var itm = ''
			 + "<div class='typeahead_wrapper'>"
			 + "<img class='typeahead_photo' src='" + imageURL + "' height='44px' width='28px' />"
			 + "<span class='typeahead_primary'> " + s.label + "</span>"
	}
	else if(s.profileType==4)
	{
		var imageURL = (s.imageURL==null)?cfg.baseUrl+'/images/default/default_50x30.gif':cfg.baseUrl+'/images/uploads/'+s.imageURL;
		var itm = ''
		 + "<div class='typeahead_wrapper'>"
		 + "<img class='typeahead_photo' src='" + imageURL + "' height='30px' width='50px' />"
		 + "<span class='typeahead_primary'> " + s.label + "</span>"
	}
	else if(s.profileType==5)
	{
		var imageURL = (s.imageURL==null)?cfg.baseUrl+'/images/default/default_50x30.gif':cfg.baseUrl+'/images/uploads/'+s.imageURL;
		var itm = ''
		 + "<div class='typeahead_wrapper'>"
		 + "<img class='typeahead_photo' src='" + imageURL + "' height='30px' width='50px' />"
		 + "<span class='typeahead_primary'> " + s.label + "</span>"
	}
	return itm;
}
});

var newRender = function(items) {
 var that = this;
 items = $(items).map(function (i, item) {
   i = $(that.options.item).attr('data-value', item);
   i.find('a').html(that.highlighter(item));
   return i[0];
 })

 this.$menu.html(items);
 return this;
};
$.fn.typeahead.Constructor.prototype.render = newRender;


$.fn.typeahead.Constructor.prototype.select = function() {
	var val = this.$menu.find('.active').attr('data-value');
	if (val) {
	  this.$element
		.val(this.updater(val))
		.change();
	}
	else
	{
		$(".navbar-search").submit();
	}
	//return this.hide();
};


$("#launchReports").click(function(){
	$.ajax({
	  url: cfg.baseUrl+"/api/user/generatekey",
	  dataType: 'json'
	}).done(function(msg) {
	  window.open("http://analytics.theatreprofile.com?"+JSON.parse(msg).activeKey,"_blank");
	  //$('<form method="post" action="http://analytics.theatreprofile.com" target="_blank"><input type="hidden" name="key" value="'+key+'"></form>').appendTo('body').submit().remove();
	});
});

	
$("#btnSearch").click(function(){
	if($("#term").val()!="")
		$(".navbar-search").submit();
});

if(!isMobile)
{
	$('body').on('focus','input[rel=tooltip]', function(){
		$(this).tooltip({trigger:'manual'});
		$(this).tooltip('show');
	});

	$('body').on('focusout','input[rel=tooltip]', function(){
		$(this).tooltip('destroy');	
	});

	$('body').on('mouseover','a[rel=tooltip]', function(){
		$(this).tooltip({trigger:'manual'});
		$(this).tooltip('show');
	});

	$('body').on('mouseout','a[rel=tooltip]', function(){
		$(this).tooltip('destroy');	
	});

	$('body').on('mouseover','[rel=tooltip]:not(input,a)', function(){
		$(this).tooltip({trigger:'manual'});
		$(this).tooltip('show');
	});

	$('body').on('mouseout','[rel=tooltip]:not(input,a)', function(){
		$(this).tooltip('destroy');	
	});
}

$(".customCollapse").each(function(){
	maxHeight=($(this).attr("data-maxHeight")==undefined)?"400":$(this).attr("data-maxHeight");
	if($(this).height()>maxHeight)
	{
		$(this).css("max-height",maxHeight+"px");
		$(this).append("<button class='btnToggleCollapse'>Show all</button>");
	}
});

$('.btnToggleCollapse').on("click",function(){
	maxHeight=($(this).parent().attr("data-maxHeight")==undefined)?"400":$(this).parent().attr("data-maxHeight");
	if($(this).text()=="Show all")
	{
		$(this).parent().css("max-height","");
		$(this).text("Show less");
	}
	else
	{
		$(this).parent().css("max-height",maxHeight+"px");
		$(this).text("Show all");
	}
});

$('#term').on("keyup keypress", function(e) {
  var code = e.keyCode || e.which; 
  if (code  == 13) {    
      if($(this).val()==''){
          e.preventDefault();
          return false;
      }
  }
});
if(!cfg.DEBUG)
{
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	if(cfg.userID!=0)
		ga('create', 'UA-3986808-1', { 'userId': cfg.userID,'cookieDomain': 'www.theatreprofile.com'});
	else
		ga('create', 'UA-3986808-1', {'cookieDomain': 'www.theatreprofile.com'});
	ga('send', 'pageview');
}
/*
Copyright (c) 2012 We Make Media Ltd

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/function cookieAlert(a){var b={element:"cookie-alert-container",name:"allow-cookies",close:"accept-cookies",date:1e3};for(var c in a)b[c]=a[c];if(!document.getElementById(b.element))return!1;var d=document.getElementById(b.element);d.style.display="none";document.getElementById(b.close)||(d.innerHTML+="<a href='#' id='accept-cookies'>Close</a>");var e,f,g,h=document.cookie.split(";"),i=null;for(e=0;e<h.length;e++){f=h[e].substr(0,h[e].indexOf("="));g=h[e].substr(h[e].indexOf("=")+1);f=f.replace(/^\s+|\s+$/g,"");f===b.name&&(i=!0)}if(!i){d.style.display="block";document.getElementById(b.close).onclick=function(){var a=new Date;a.setDate(a.getDate()+b.date);document.cookie=b.name+"=1; expires="+a.toUTCString()+"; path=/";document.body.removeChild(d);return!1}}};