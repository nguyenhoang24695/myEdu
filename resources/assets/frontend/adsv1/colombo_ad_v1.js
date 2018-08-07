/**
 * Created by hocvt on 5/4/16.
 * Colombo ad client v1
 */

if(String.prototype.replaceArray == undefined){
    String.prototype.replaceArray = function(find, replace) {
        var replaceString = this;
        var regex;
        if(arguments.length == 2){
            for (var i = 0; i < find.length; i++) {
                regex = new RegExp(find[i], "g");
                replaceString = replaceString.replace(regex, replace[i]);
            }

        }else if(arguments.length == 1){
            for (var i in find) {
                regex = new RegExp(i, "g");
                replaceString = replaceString.replace(regex, find[i]);
            }
        }

        return replaceString;
    };
}

if(undefined === window.Cookies){
    /*! js-cookie v2.1.1 | MIT */
    !function(a){if("function"==typeof define&&define.amd)define(a);else if("object"==typeof exports)module.exports=a();else{var b=window.Cookies,c=window.Cookies=a();c.noConflict=function(){return window.Cookies=b,c}}}(function(){function a(){for(var a=0,b={};a<arguments.length;a++){var c=arguments[a];for(var d in c)b[d]=c[d]}return b}function b(c){function d(b,e,f){var g;if("undefined"!=typeof document){if(arguments.length>1){if(f=a({path:"/"},d.defaults,f),"number"==typeof f.expires){var h=new Date;h.setMilliseconds(h.getMilliseconds()+864e5*f.expires),f.expires=h}try{g=JSON.stringify(e),/^[\{\[]/.test(g)&&(e=g)}catch(i){}return e=c.write?c.write(e,b):encodeURIComponent(String(e)).replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g,decodeURIComponent),b=encodeURIComponent(String(b)),b=b.replace(/%(23|24|26|2B|5E|60|7C)/g,decodeURIComponent),b=b.replace(/[\(\)]/g,escape),document.cookie=[b,"=",e,f.expires&&"; expires="+f.expires.toUTCString(),f.path&&"; path="+f.path,f.domain&&"; domain="+f.domain,f.secure?"; secure":""].join("")}b||(g={});for(var j=document.cookie?document.cookie.split("; "):[],k=/(%[0-9A-Z]{2})+/g,l=0;l<j.length;l++){var m=j[l].split("="),n=m[0].replace(k,decodeURIComponent),o=m.slice(1).join("=");'"'===o.charAt(0)&&(o=o.slice(1,-1));try{if(o=c.read?c.read(o,n):c(o,n)||o.replace(k,decodeURIComponent),this.json)try{o=JSON.parse(o)}catch(i){}if(b===n){g=o;break}b||(g[n]=o)}catch(i){}}return g}}return d.set=d,d.get=function(a){return d(a)},d.getJSON=function(){return d.apply({json:!0},[].slice.call(arguments))},d.defaults={},d.remove=function(b,c){d(b,"",a(c,{expires:-1}))},d.withConverter=b,d}return b(function(){})});
}


var colombo_ads = function(){
    var clientId = window._caOptions.clientId;
    var wheight = window._caOptions.height || 'auto';
    var wwidth = window._caOptions.width || 'auto';
    var version = '1.0';
    var apiEndPoint = 'http://unibee.dev/api/ad/v1';

    var getTitle = function(){
        return document.title;
    };

    var getKeyword = function(){
        var kw = window._caOptions.keywords || 'title';
        console.log(kw);
        if(kw == 'title'){
            return getTitle();
        }else{
            return $(kw).text();
        }

    }

    var getContainer = function(){
        var container = window._caOptions.container;
        if(typeof container != jQuery){
            container = $(container);
        }
        if(container.length != 1){
            console.log('Không tìm thấy container hoặc container nhiều hơn 1');
        }
        return container;
    }

    var showBestCourse = function(){
        var url = apiEndPoint + "?keyword=" + encodeURIComponent(getKeyword()) + '&version=' + version;
        var container = getContainer();
        container.append('<iframe style="border: none;" width="100%" height="350px"></iframe>');
        var _iframe = container.find('iframe:first');
        _iframe.attr('src', url);

    }

    var init = function(){
        showBestCourse();
    }

    this.getClientId = function(){
        return clientId;
    }

    this.getContainer = function(){
        return getContainer();
    }

    init();

    return this;
}();
