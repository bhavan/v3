/*	$Id: popup-insert-uncompressed.js 989 2008-06-25 19:02:32Z Fritz Elfert $ */
function AvReloadedInsert(){this.init=function(D,B,A,E,C){o=this._getUriObject(window.self.location.href);q=$H(this._getQueryObject(o.query));this.editor=decodeURIComponent(q.get("e_name"));pl=q.get("playlist");this.playlist=pl?decodeURIComponent(pl):0;this.form=$("adminForm");this.fields=new Object();this.fields.url=$("url");this.fields.mloc=$("mloc");this.fields.provider=$("provider");this.fields.lprovider=$("lprovider");this.fields.tagid=$("tagid");this.fields.ltagid=$("ltagid");this.fields.width=$("width");this.fields.lwidth=$("lwidth");this.fields.height=$("height");this.fields.lheight=$("lheight");this.fields.mtag=$("mtag");this.fields.lmtag=$("lmtag");this.fields.local=$("local");this.buttons=[$("pv"),$("ins")];this.lbuttons=[$("lpv"),$("lins")];this.tags=D;this.ltags=B;this.dim=new Object();this.dim.w=A;this.dim.h=E;if(!C){$("preview").style.height=E+"px"}this.checkTag(0);this.checkTag(1)};this.buildTag=function(F,E){if(F){var A=this.fields.lwidth.getValue();var G=this.fields.lheight.getValue();var E=this.fields.ltagid.getValue();var H=this.fields.lprovider.getValue();for(var D=0;D<this.ltags.length;D++){if(this.ltags[D].val==H){var C=this.ltags[D].tag;var B="{"+C;if((A!="")&&(A!=this.dim.w)){B+=' width="'+A+'"'}if((G!="")&&(G!=this.dim.h)){B+=' height="'+G+'"'}B+="}"+E+"{/"+C+"}";this.fields.lmtag.value=B;this.checkTag(F);break}}}else{var A=this.fields.width.getValue();var G=this.fields.height.getValue();var E=this.fields.tagid.getValue();var H=this.fields.provider.getValue();for(var D=0;D<this.tags.length;D++){if(this.tags[D].val==H){var C=this.tags[D].tag;var B="{"+C;if((A!="")&&(A!=this.dim.w)){B+=' width="'+A+'"'}if((G!="")&&(G!=this.dim.h)){B+=' height="'+G+'"'}B+="}"+E+"{/"+C+"}";this.fields.mtag.value=B;this.checkTag(F);break}}}return false};this.matchLOC=function(C){for(var B=0;B<this.ltags.length;B++){if(this.ltags[B].rx!=null){var A;if(A=this.ltags[B].rx.exec(C)){this.fields.lprovider.value=this.ltags[B].val;this.fields.ltagid.value=A[1];this.buildTag(true);break}}}return false};this.matchURL=function(B){for(var C=0;C<this.tags.length;C++){if(this.tags[C].rx!=null){var A;if(A=this.tags[C].rx.exec(B)){this.fields.provider.value=this.tags[C].val;this.fields.tagid.value=A[1];this.buildTag(false);break}}}return false};this.enable=function(B,A){var C=B?this.lbuttons:this.buttons;C.each(function(D){D.disabled=!A})};this.checkTag=function(D){if(D){var A=this.fields.lmtag.getValue();for(var C=0;C<this.ltags.length;C++){var B=this.ltags[C].tag;var E=new RegExp("^{"+B+".*}.+{/"+B+"}$");if(E.test(A)){this.enable(D,true);return true}}}else{var A=this.fields.mtag.getValue();for(var C=0;C<this.tags.length;C++){var B=this.tags[C].tag;var E=new RegExp("^{"+B+".*}.+{/"+B+"}$");if(E.test(A)){this.enable(D,true);return true}}}this.enable(D,false);return false};this.onpreview=function(A){if(this.checkTag(A)){this.fields.local.value=A;this.form.submit()}};this.onok=function(B){if(this.playlist){var C=(B)?"local:"+this.fields.mloc.getValue():this.fields.url.getValue();window.parent.plinsert(C)}else{var A=(B)?this.fields.lmtag.getValue():this.fields.mtag.getValue();window.parent.jInsertEditorText(A,this.editor)}return false};this.showMessage=function(C){var B=$("message");var A=$("messages");if(B.firstChild){B.removeChild(B.firstChild)}B.appendChild(document.createTextNode(C));A.style.display="block"};this.parseQuery=function(D){var F=new Object();if(!D){return F}var C=D.split(/[;&]/);for(var B=0;B<C.length;B++){var G=C[B].split("=");if(!G||G.length!=2){continue}var A=unescape(G[0]);var E=unescape(G[1]).replace(/\+ /g," ");F[A]=E}return F};this._getQueryObject=function(B){var C=B.split(/[&;]/);var A={};if(C.length){C.each(function(E){var D=E.split("=");if(D.length&&D.length==2){A[encodeURIComponent(D[0])]=encodeURIComponent(D[1])}})}return A};this._getUriObject=function(A){var B=A.match(/^(?:([^:\/?#.]+):)?(?:\/\/)?(([^:\/?#]*)(?::(\d*))?)((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[\?#]|$)))*\/?)?([^?#\/]*))?(?:\?([^#]*))?(?:#(.*))?/);return(B)?B.associate(["uri","scheme","authority","domain","port","path","directory","file","query","fragment"]):null}};