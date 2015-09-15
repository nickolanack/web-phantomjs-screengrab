/**
 * 
 */
var system = require('system');
var args = system.args;
//console.log(JSON.stringify(args));
//phantom.exit(0);

if(args.length!=2){
	console.error("requires url argument")
	phantom.exit(1);
}

siteurl=args[1];

folder=siteurl.replace('://','.');

var page = require('webpage').create();
page.open(siteurl, function(status) {
	console.log("Status: " + status);
	if(status === "success") {

		page.zoomFactor = 0.25;

		var render=function(dim){
			var size={width:Math.round(dim.width*page.zoomFactor), height:Math.round(dim.height*page.zoomFactor)}

			page.viewportSize = size;
			page.render(folder+'/page'+size.width+'x'+size.height+'.png');

			page.viewportSize = {width:size.height, height:size.width};
			page.render(folder+'/page'+size.height+'x'+size.width+'.png');	

		}

		setTimeout(function(){

			render({ width: 768, height: 1024 });
			render({ width: 640, height: 980 });

			phantom.exit();



		},5000);

	}else{
		phantom.exit();
	}

});
