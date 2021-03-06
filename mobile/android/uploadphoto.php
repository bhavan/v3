<!doctype html> 
<html> 
<head> 
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" /> 
<title>Android Fixed-Position Scrolling Demo</title> 
<style> 
body {
   -webkit-user-select:none;
   -webkit-touch-callout:none;
   -webkit-tap-highlight-color: rgba(0,0,0,0);
   font-family:helvetica neue;
	font-size:18px;
   margin:0;
   padding:0;
}
 
#hd, #ft {
    position:fixed;
    width:100%;
	text-align:center;
	font-weight:bold;
}
 
#hd { 
    top:0; 
    background:#050;
	line-height:44px;
	height:44px;
	color:#ff0;
	border-bottom:2px solid rgba(0, 0, 0, 0.2);
}
 
#ft {
    height:49px;
    bottom:0;
	line-height:48px;
	color:#fff;
	text-align:center;
    background:transparent url(UITabBarBkgd.png) 0 0 repeat-x;    
}
 
 
#bd {
    background:#fff;
    margin:44px 0 49px 0;
}
 
div, ul, li {
    margin:0;
    padding:0;
}
 
li {
    list-style:none;
	height:44px;
	line-height:44px;
	padding-left:10px;
	border-bottom:1px solid #eee;
}
 
li:nth-child(even) {
	background-color:#ffa;
}
 
 
</style> 
</head> 
<body> 
<div id="hd">Fixed Headers and Footers</div> 
<div id="bd"> 
<ul> 
<li>Look, Ma!</li> 
<li>No JavaScript!</li> 
<li>If you're</li> 
<li>looking at this</li> 
<li>with Froyo</li> 
<li>you should</li> 
<li>be seeing</li> 
<li>a fixed header</li> 
<li>a fixed footer</li> 
<li>and a </li> 
<li>lovely </li> 
<li>scrolling</li> 
<li>list!</li> 
<li>Sigh....</li> 
<li>if</li> 
<li>only</li> 
<li>this</li> 
<li>worked</li> 
<li>on an</li> 
<li>iPhone,</li> 
<li>life</li> 
<li>would</li> 
<li>be</li> 
<li>pretty</li> 
<li>much</li> 
<li>complete.</li> 
</ul> 
</div> 
<div id="ft">... and Scrolling Body</div> 
</body> 
</html>