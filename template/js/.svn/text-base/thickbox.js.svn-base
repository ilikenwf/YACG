$(document).ready(TB_init)
function TB_init(){
$("a.thickbox").click(function(event){
event.preventDefault()
this.blur()
var caption=this.title || this.name || ""
var group=this.rel || false
TB_show(caption,this.href,group)
})}
function TB_show(caption,url,rel){
if(!$("#TB_HideSelect").length){
$("body").append("<iframe id='TB_HideSelect'></iframe><div id='TB_overlay'></div><div id='TB_window'></div>")
$("#TB_overlay").click(TB_remove)}
$(window).scroll(TB_position)
TB_overlaySize()
$("body").append("<div id='TB_load'><img src='/template/loadingAnimation.gif' /></div>")
TB_load_position()
var baseURL=url.match(/(.+)?/)[1] || url
var imageURL=/\.(jpe?g|png|gif|bmp)/gi
if(baseURL.match(imageURL)){
var dummy={caption: "",url: "",html: ""}
var prev=dummy,
next=dummy,
imageCount=""
if(rel){
function getInfo(image,id,label){
return{
caption: image.title,
url: image.href,
html: "<span id='TB_"+id+"'>&nbsp;&nbsp;<a href='#'>"+label+"</a></span>"}}
var imageGroup=$("a[@rel="+rel+"]").get()
var foundSelf=false
for(var i=0;i<imageGroup.length;i++){
var image=imageGroup[i]
var urlTypeTemp=image.href.match(imageURL)
if(image.href==url){
foundSelf=true
imageCount="Image "+(i+1)+" of "+(imageGroup.length)
}else{
if(foundSelf){
next=getInfo(image,"next","Next &gt;")
break
}else{
prev=getInfo(image,"prev","&lt; Prev")}}}}
imgPreloader=new Image()
imgPreloader.onload=function(){
imgPreloader.onload=null
var pagesize=TB_getPageSize()
var x=pagesize[0]-150
var y=pagesize[1]-150
var imageWidth=imgPreloader.width
var imageHeight=imgPreloader.height
if(imageWidth>x){
imageHeight=imageHeight*(x/imageWidth)
imageWidth=x
if(imageHeight>y){
imageWidth=imageWidth*(y/imageHeight)
imageHeight=y}
}else if(imageHeight>y){
imageWidth=imageWidth*(y/imageHeight)
imageHeight=y
if(imageWidth>x){
imageHeight=imageHeight*(x/imageWidth)
imageWidth=x}}
TB_WIDTH=imageWidth+30
TB_HEIGHT=imageHeight+60
$("#TB_window").append("<a href='' id='TB_ImageOff' title='Close'><img id='TB_Image' src='"+url+"' width='"+imageWidth+"' height='"+imageHeight+"' alt='"+caption+"'/></a>"+"<div id='TB_caption'>"+caption+"<div id='TB_secondLine'>"+imageCount+prev.html+next.html+"</div></div><div id='TB_closeWindow'><a href='#' id='TB_closeWindowButton' title='Close'>close</a></div>")
$("#TB_closeWindowButton").click(TB_remove)
function buildClickHandler(image){
return function(){
$("#TB_window").remove()
$("body").append("<div id='TB_window'></div>")
TB_show(image.caption,image.url,rel)
return false}}
var goPrev=buildClickHandler(prev)
var goNext=buildClickHandler(next)
if(prev.html){
$("#TB_prev").click(goPrev)}
if(next.html){
$("#TB_next").click(goNext)}
document.onkeydown=function(e){
if(e==null){
keycode=event.keyCode
}else{
keycode=e.which}
switch(keycode){
case 27:
TB_remove()
break
case 190:
if(next.html){
document.onkeydown=null
goNext()}
break
case 188:
if(prev.html){
document.onkeydown=null
goPrev()}
break}}
TB_position()
$("#TB_load").remove()
$("#TB_ImageOff").click(TB_remove)
$("#TB_window").css({display:"block"})}
imgPreloader.src=url
}else{
var queryString=url.match(/\?(.+)/)[1]
var params=TB_parseQuery(queryString)
TB_WIDTH=(params['width']*1)+30
TB_HEIGHT=(params['height']*1)+40
var ajaxContentW=TB_WIDTH-30,
ajaxContentH=TB_HEIGHT-45
if(url.indexOf('TB_iframe')!=-1){
urlNoQuery=url.split('TB_')
$("#TB_window").append("<div id='TB_title'><div id='TB_ajaxWindowTitle'>"+caption+"</div><div id='TB_closeAjaxWindow'><a href='#' id='TB_closeWindowButton' title='Close'>close</a></div></div><iframe frameborder='0' hspace='0' src='"+urlNoQuery[0]+"' id='TB_iframeContent' name='TB_iframeContent' style='width:"+(ajaxContentW+29)+"px;height:"+(ajaxContentH+17)+"px;' onload='TB_showIframe()'> </iframe>")
}else{
$("#TB_window").append("<div id='TB_title'><div id='TB_ajaxWindowTitle'>"+caption+"</div><div id='TB_closeAjaxWindow'><a href='#' id='TB_closeWindowButton'>close</a></div></div><div id='TB_ajaxContent' style='width:"+ajaxContentW+"px;height:"+ajaxContentH+"px;'></div>")}
$("#TB_closeWindowButton").click(TB_remove)
if(url.indexOf('TB_inline')!=-1){
$("#TB_ajaxContent").html($('#'+params['inlineId']).html())
TB_position()
$("#TB_load").remove()
$("#TB_window").css({display:"block"})
}else if(url.indexOf('TB_iframe')!=-1){
TB_position()
if(frames['TB_iframeContent']==undefined){
$("#TB_load").remove()
$("#TB_window").css({display:"block"})
$(document).keyup(function(e){var key=e.keyCode;if(key==27){TB_remove()}})}
}else{
$("#TB_ajaxContent").load(url,function(){
TB_position()
$("#TB_load").remove()
$("#TB_window").css({display:"block"})
})}}
$(window).resize(TB_position)
document.onkeyup=function(e){
if(e==null){
keycode=event.keyCode
}else{
keycode=e.which}
if(keycode==27){
TB_remove()}}}
function TB_showIframe(){
$("#TB_load").remove()
$("#TB_window").css({display:"block"})}
function TB_remove(){
$("#TB_imageOff").unbind("click")
$("#TB_overlay").unbind("click")
$("#TB_closeWindowButton").unbind("click")
$("#TB_window").fadeOut("fast",function(){$('#TB_window,#TB_overlay,#TB_HideSelect').remove();})
$("#TB_load").remove()
return false}
function TB_position(){
var pagesize=TB_getPageSize()
var arrayPageScroll=TB_getPageScrollTop()
var style={width: TB_WIDTH,left:(arrayPageScroll[0]+(pagesize[0]-TB_WIDTH)/2),top:(arrayPageScroll[1]+(pagesize[1]-TB_HEIGHT)/2)}
$("#TB_window").css(style)}
function TB_overlaySize(){
if(window.innerHeight&&window.scrollMaxY || window.innerWidth&&window.scrollMaxX){
yScroll=window.innerHeight+window.scrollMaxY
xScroll=window.innerWidth+window.scrollMaxX
var deff=document.documentElement
var wff=(deff&&deff.clientWidth)|| document.body.clientWidth || window.innerWidth || self.innerWidth
var hff=(deff&&deff.clientHeight)|| document.body.clientHeight || window.innerHeight || self.innerHeight
xScroll-=(window.innerWidth-wff)
yScroll-=(window.innerHeight-hff)
}else if(document.body.scrollHeight>document.body.offsetHeight || document.body.scrollWidth>document.body.offsetWidth){
yScroll=document.body.scrollHeight
xScroll=document.body.scrollWidth
}else{
yScroll=document.body.offsetHeight
xScroll=document.body.offsetWidth}
$("#TB_overlay").css({"height": yScroll,"width": xScroll})
$("#TB_HideSelect").css({"height": yScroll,"width": xScroll})}
function TB_load_position(){
var pagesize=TB_getPageSize()
var arrayPageScroll=TB_getPageScrollTop()
$("#TB_load")
.css({left:(arrayPageScroll[0]+(pagesize[0]-100)/2),top:(arrayPageScroll[1]+((pagesize[1]-100)/2))})
.css({display:"block"})}
function TB_parseQuery(query){
if(!query)
return{}
var params={}
var pairs=query.split(/[;&]/)
for(var i=0;i<pairs.length;i++){
var pair=pairs[i].split('=')
if(!pair || pair.length !=2)
continue
params[unescape(pair[0])]=unescape(pair[1]).replace(/\+/g,' ')}
return params}
function TB_getPageScrollTop(){
var yScrolltop
var xScrollleft
if(self.pageYOffset || self.pageXOffset){
yScrolltop=self.pageYOffset
xScrollleft=self.pageXOffset
}else if(document.documentElement&&document.documentElement.scrollTop || document.documentElement.scrollLeft){
yScrolltop=document.documentElement.scrollTop
xScrollleft=document.documentElement.scrollLeft
}else if(document.body){
yScrolltop=document.body.scrollTop
xScrollleft=document.body.scrollLeft}
arrayPageScroll=new Array(xScrollleft,yScrolltop)
return arrayPageScroll}
function TB_getPageSize(){
var de=document.documentElement
var w=window.innerWidth || self.innerWidth ||(de&&de.clientWidth)|| document.body.clientWidth
var h=window.innerHeight || self.innerHeight ||(de&&de.clientHeight)|| document.body.clientHeight
arrayPageSize=new Array(w,h)
return arrayPageSize}