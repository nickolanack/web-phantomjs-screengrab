/**
 * 
 */

console.log(JSON.stringify(phantom.args));

phantom.exit(0);
var page = require('webpage').create();
page.open('http://sitename', function(status) {
  console.log("Status: " + status);
  if(status === "success") {
 
    var render=function(size){
        
	page.viewportSize = size;
        page.render('page'+size.width+'x'+size.height+'.png');

	page.viewportSize = {width:size.height, height:size.width};
	page.render('page'+size.height+'x'+size.width+'.png');	

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
