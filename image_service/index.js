/**
 * Created by hocvt on 12/9/15.
 */
var IMGR = require('imgr').IMGR;
var express = require('express');

var express_app = express();

var imgr1 = new IMGR;

imgr1.serve('/Users/hocvt/Documents/webroot/unibee/public_html/images/users')
    .namespace('/users')
    .urlRewrite('/:size/:path/:file.:ext')
    .whitelist([ '200x300', '100x100', '1000x1000' ])
    .using(express_app);

var imgr2 = new IMGR;

imgr2.serve('/Users/hocvt/Documents/webroot/unibee/public_html/images/cou_avatar')
    .namespace('/course')
    .urlRewrite('/:size/:path/:file.:ext')
    .whitelist([ '200x300', '100x100', '1000x1000', '1000x' ])
    .using(express_app);



var server = express_app.listen(3000, function(){
    var host = server.address().address;
    var port = server.address().port;
    console.log('Example app listening at http://%s:%s', host, port);
});