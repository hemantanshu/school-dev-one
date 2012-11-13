/*
 * File:        FixedColumns.min.js
 * Version:     2.0.3
 * Author:      Allan Jardine (www.sprymedia.co.uk)
 * 
 * Copyright 2010-2010 Allan Jardine, all rights reserved.
 *
 * This source file is free software, under either the GPL v2 license or a
 * BSD style license, available at:
 *   http://datatables.net/license_gpl2
 *   http://datatables.net/license_bsd
 * 
 * This source file is distributed in the hope that it will be useful, but 
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY 
 * or FITNESS FOR A PARTICULAR PURPOSE. See the license files for details.
 */
/*
     GPL v2 or BSD 3 point style
 @contact     www.sprymedia.co.uk/contact

 @copyright Copyright 2010-2011 Allan Jardine, all rights reserved.

 This source file is free software, under either the GPL v2 license or a
 BSD style license, available at:
 http://datatables.net/license_gpl2
 http://datatables.net/license_bsd
*/
var FixedColumns;
(function(b,q){FixedColumns=function(a,e){!this instanceof FixedColumns?alert("FixedColumns warning: FixedColumns must be initialised with the 'new' keyword."):("undefined"==typeof e&&(e={}),this.s={dt:a.fnSettings(),iTableColumns:a.fnSettings().aoColumns.length,aiWidths:[],bOldIE:b.browser.msie&&("6.0"==b.browser.version||"7.0"==b.browser.version)},this.dom={scroller:null,header:null,body:null,footer:null,grid:{wrapper:null,dt:null,left:{wrapper:null,head:null,body:null,foot:null},right:{wrapper:null,
head:null,body:null,foot:null}},clone:{left:{header:null,body:null,footer:null},right:{header:null,body:null,footer:null}}},this.s.dt.oFixedColumns=this,this._fnConstruct(e))};FixedColumns.prototype={fnUpdate:function(){this._fnDraw(!0)},fnRedrawLayout:function(){this._fnGridLayout()},fnRecalculateHeight:function(a){a._DTTC_iHeight=null;a.style.height="auto"},fnSetRowHeight:function(a,e){var c=b(a).children(":first"),c=c.outerHeight()-c.height();b.browser.mozilla||b.browser.opera?a.style.height=e+
"px":b(a).children().height(e-c)},_fnConstruct:function(a){var e,c=this;if("function"!=typeof this.s.dt.oInstance.fnVersionCheck||!0!==this.s.dt.oInstance.fnVersionCheck("1.8.0"))alert("FixedColumns "+FixedColumns.VERSION+" required DataTables 1.8.0 or later. Please upgrade your DataTables installation");else if(""===this.s.dt.oScroll.sX)this.s.dt.oInstance.oApi._fnLog(this.s.dt,1,"FixedColumns is not needed (no x-scrolling in DataTables enabled), so no action will be taken. Use 'FixedHeader' for column fixing when scrolling is not enabled");
else{this.s=b.extend(!0,this.s,FixedColumns.defaults,a);this.dom.grid.dt=b(this.s.dt.nTable).parents("div.dataTables_scroll")[0];this.dom.scroller=b("div.dataTables_scrollBody",this.dom.grid.dt)[0];var a=b(this.dom.grid.dt).width(),f=0,g=0;b("tbody>tr:eq(0)>td",this.s.dt.nTable).each(function(a){e=b(this).outerWidth();c.s.aiWidths.push(e);a<c.s.iLeftColumns&&(f+=e);c.s.iTableColumns-c.s.iRightColumns<=a&&(g+=e)});if(null===this.s.iLeftWidth)this.s.iLeftWidth="fixed"==this.s.sLeftWidth?f:100*(f/a);
if(null===this.s.iRightWidth)this.s.iRightWidth="fixed"==this.s.sRightWidth?g:100*(g/a);this._fnGridSetup();for(a=0;a<this.s.iLeftColumns;a++)this.s.dt.oInstance.fnSetColumnVis(a,!1);for(a=this.s.iTableColumns-this.s.iRightColumns;a<this.s.iTableColumns;a++)this.s.dt.oInstance.fnSetColumnVis(a,!1);b(this.dom.scroller).scroll(function(){c.dom.grid.left.body.scrollTop=c.dom.scroller.scrollTop;if(0<c.s.iRightColumns)c.dom.grid.right.body.scrollTop=c.dom.scroller.scrollTop});b(q).resize(function(){c._fnGridLayout.call(c)});
var d=!0;this.s.dt.aoDrawCallback=[{fn:function(){c._fnDraw.call(c,d);c._fnGridHeight(c);d=!1},sName:"FixedColumns"}].concat(this.s.dt.aoDrawCallback);this._fnGridLayout();this._fnGridHeight();this.s.dt.oInstance.fnDraw(!1)}},_fnGridSetup:function(){this.dom.body=this.s.dt.nTable;this.dom.header=this.s.dt.nTHead.parentNode;this.dom.header.parentNode.parentNode.style.position="relative";var a=b('<div class="DTFC_ScrollWrapper" style="position:relative; clear:both;"><div class="DTFC_LeftWrapper" style="position:absolute; top:0; left:0;"><div class="DTFC_LeftHeadWrapper" style="position:relative; top:0; left:0; overflow:hidden;"></div><div class="DTFC_LeftBodyWrapper" style="position:relative; top:0; left:0; overflow:hidden;"></div><div class="DTFC_LeftFootWrapper" style="position:relative; top:0; left:0; overflow:hidden;"></div></div><div class="DTFC_RightWrapper" style="position:absolute; top:0; left:0;"><div class="DTFC_RightHeadWrapper" style="position:relative; top:0; left:0; overflow:hidden;"></div><div class="DTFC_RightBodyWrapper" style="position:relative; top:0; left:0; overflow:hidden;"></div><div class="DTFC_RightFootWrapper" style="position:relative; top:0; left:0; overflow:hidden;"></div></div></div>')[0];
nLeft=a.childNodes[0];nRight=a.childNodes[1];this.dom.grid.wrapper=a;this.dom.grid.left.wrapper=nLeft;this.dom.grid.left.head=nLeft.childNodes[0];this.dom.grid.left.body=nLeft.childNodes[1];if(0<this.s.iRightColumns)this.dom.grid.right.wrapper=nRight,this.dom.grid.right.head=nRight.childNodes[0],this.dom.grid.right.body=nRight.childNodes[1];if(this.s.dt.nTFoot&&(this.dom.footer=this.s.dt.nTFoot.parentNode,this.dom.grid.left.foot=nLeft.childNodes[2],0<this.s.iRightColumns))this.dom.grid.right.foot=
nRight.childNodes[2];a.appendChild(nLeft);this.dom.grid.dt.parentNode.insertBefore(a,this.dom.grid.dt);a.appendChild(this.dom.grid.dt);this.dom.grid.dt.style.position="absolute";this.dom.grid.dt.style.top="0px";this.dom.grid.dt.style.left=this.s.iLeftWidth+"px";this.dom.grid.dt.style.width=b(this.dom.grid.dt).width()-this.s.iLeftWidth-this.s.iRightWidth+"px"},_fnGridLayout:function(){var a=this.dom.grid,e=b(a.wrapper).width(),c=0,f=0,c=0,c="fixed"==this.s.sLeftWidth?this.s.iLeftWidth:this.s.iLeftWidth/
100*e,f="fixed"==this.s.sRightWidth?this.s.iRightWidth:this.s.iRightWidth/100*e;a.left.wrapper.style.width=c+"px";a.dt.style.width=e-c-f+"px";a.dt.style.left=c+"px";if(0<this.s.iRightColumns)a.right.wrapper.style.width=f+"px",a.right.wrapper.style.left=e-f+"px"},_fnGridHeight:function(){var a=this.dom.grid,e=b(this.dom.grid.dt).height();a.wrapper.style.height=e+"px";a.left.body.style.height=b(this.dom.scroller).height()+"px";a.left.wrapper.style.height=e+"px";if(0<this.s.iRightColumns)a.right.wrapper.style.height=
e+"px",a.right.body.style.height=b(this.dom.scroller).height()+"px"},_fnDraw:function(a){this._fnCloneLeft(a);this._fnCloneRight(a);null!==this.s.fnDrawCallback&&this.s.fnDrawCallback.call(this,this.dom.clone.left,this.dom.clone.right);b(this).trigger("draw",{leftClone:this.dom.clone.left,rightClone:this.dom.clone.right})},_fnCloneRight:function(a){if(!(0>=this.s.iRightColumns)){var b,c=[];for(b=this.s.iTableColumns-this.s.iRightColumns;b<this.s.iTableColumns;b++)c.push(b);this._fnClone(this.dom.clone.right,
this.dom.grid.right,c,a)}},_fnCloneLeft:function(a){if(!(0>=this.s.iLeftColumns)){var b,c=[];for(b=0;b<this.s.iLeftColumns;b++)c.push(b);this._fnClone(this.dom.clone.left,this.dom.grid.left,c,a)}},_fnCopyLayout:function(a,e){for(var c=[],f=[],g=[],d=0,h=a.length;d<h;d++){var i=[];i.nTr=b(a[d].nTr).clone(!0)[0];for(var k=0,m=this.s.iTableColumns;k<m;k++)if(-1!==b.inArray(k,e)){var j=b.inArray(a[d][k].cell,g);-1===j?(j=b(a[d][k].cell).clone(!0)[0],f.push(j),g.push(a[d][k].cell),i.push({cell:j,unique:a[d][k].unique})):
i.push({cell:f[j],unique:a[d][k].unique})}c.push(i)}return c},_fnClone:function(a,e,c,f){var g=this,d,h,i,k,m,j,n;if(f){null!==a.header&&a.header.parentNode.removeChild(a.header);a.header=b(this.dom.header).clone(!0)[0];a.header.className+=" DTFC_Cloned";a.header.style.width="100%";e.head.appendChild(a.header);var l=this._fnCopyLayout(this.s.dt.aoHeader,c);k=b(">thead",a.header);k.empty();for(d=0,h=l.length;d<h;d++)k[0].appendChild(l[d].nTr);this.s.dt.oApi._fnDrawHead(this.s.dt,l,!0)}else{var l=this._fnCopyLayout(this.s.dt.aoHeader,
c),o=[];this.s.dt.oApi._fnDetectHeader(o,b(">thead",a.header)[0]);for(d=0,h=l.length;d<h;d++)for(i=0,k=l[d].length;i<k;i++)o[d][i].cell.className=l[d][i].cell.className,b("span.DataTables_sort_icon",o[d][i].cell).each(function(){this.className=b("span.DataTables_sort_icon",l[d][i].cell)[0].className})}this._fnEqualiseHeights("thead",this.dom.header,a.header);"auto"==this.s.sHeightMatch&&b(">tbody>tr",g.dom.body).css("height","auto");if(null!==a.body)a.body.parentNode.removeChild(a.body),a.body=null;
a.body=b(this.dom.body).clone(!0)[0];a.body.className+=" DTFC_Cloned";a.body.style.paddingBottom=this.s.dt.oScroll.iBarWidth+"px";a.body.style.marginBottom=2*this.s.dt.oScroll.iBarWidth+"px";null!==a.body.getAttribute("id")&&a.body.removeAttribute("id");b(">thead>tr",a.body).empty();b(">tfoot",a.body).remove();var p=b("tbody",a.body)[0];b(p).empty();if(0<this.s.dt.aiDisplay.length){h=b(">thead>tr",a.body)[0];for(n=0;n<c.length;n++)m=c[n],j=this.s.dt.aoColumns[m].nTh,j.innerHTML="",oStyle=j.style,
oStyle.paddingTop="0",oStyle.paddingBottom="0",oStyle.borderTopWidth="0",oStyle.borderBottomWidth="0",oStyle.height=0,oStyle.width=g.s.aiWidths[m]+"px",h.appendChild(j);b(">tbody>tr",g.dom.body).each(function(a){var d=this.cloneNode(!1),a=!1===g.s.dt.oFeatures.bServerSide?g.s.dt.aiDisplay[g.s.dt._iDisplayStart+a]:a;for(n=0;n<c.length;n++)m=c[n],"undefined"!=typeof g.s.dt.aoData[a]._anHidden[m]&&(j=b(g.s.dt.aoData[a]._anHidden[m]).clone(!0)[0],d.appendChild(j));p.appendChild(d)})}else b(">tbody>tr",
g.dom.body).each(function(){j=this.cloneNode(!0);j.className+=" DTFC_NoData";b("td",j).html("");p.appendChild(j)});a.body.style.width="100%";e.body.appendChild(a.body);this._fnEqualiseHeights("tbody",g.dom.body,a.body);if(null!==this.s.dt.nTFoot){if(f){null!==a.footer&&a.footer.parentNode.removeChild(a.footer);a.footer=b(this.dom.footer).clone(!0)[0];a.footer.className+=" DTFC_Cloned";a.footer.style.width="100%";e.foot.appendChild(a.footer);l=this._fnCopyLayout(this.s.dt.aoFooter,c);e=b(">tfoot",
a.footer);e.empty();for(d=0,h=l.length;d<h;d++)e[0].appendChild(l[d].nTr);this.s.dt.oApi._fnDrawHead(this.s.dt,l,!0)}else{l=this._fnCopyLayout(this.s.dt.aoFooter,c);e=[];this.s.dt.oApi._fnDetectHeader(e,b(">tfoot",a.footer)[0]);for(d=0,h=l.length;d<h;d++)for(i=0,k=l[d].length;i<k;i++)e[d][i].cell.className=l[d][i].cell.className}this._fnEqualiseHeights("tfoot",this.dom.footer,a.footer)}h=this.s.dt.oApi._fnGetUniqueThs(this.s.dt,b(">thead",a.header)[0]);b(h).each(function(a){m=c[a];this.style.width=
g.s.aiWidths[m]+"px"});null!==g.s.dt.nTFoot&&(h=this.s.dt.oApi._fnGetUniqueThs(this.s.dt,b(">tfoot",a.footer)[0]),b(h).each(function(a){m=c[a];this.style.width=g.s.aiWidths[m]+"px"}))},_fnGetTrNodes:function(a){for(var b=[],c=0,f=a.childNodes.length;c<f;c++)"TR"==a.childNodes[c].nodeName.toUpperCase()&&b.push(a.childNodes[c]);return b},_fnEqualiseHeights:function(a,e,c){if(!("none"==this.s.sHeightMatch&&"thead"!==a&&"tfoot"!==a)){var f,g,d=e.getElementsByTagName(a)[0],c=c.getElementsByTagName(a)[0],
a=b(">"+a+">tr:eq(0)",e).children(":first"),a=a.outerHeight()-a.height(),d=this._fnGetTrNodes(d),h=this._fnGetTrNodes(c);for(c=0,e=h.length;c<e;c++)if("semiauto"==this.s.sHeightMatch&&"undefined"!=typeof d[c]._DTTC_iHeight&&null!==d[c]._DTTC_iHeight)b.browser.msie&&b(h[c]).children().height(d[c]._DTTC_iHeight-a);else{f=d[c].offsetHeight;g=h[c].offsetHeight;f=g>f?g:f;if("semiauto"==this.s.sHeightMatch)d[c]._DTTC_iHeight=f;b.browser.msie&&8>b.browser.version?(b(h[c]).children().height(f-a),b(d[c]).children().height(f-
a)):(h[c].style.height=f+"px",d[c].style.height=f+"px")}}}};FixedColumns.defaults={iLeftColumns:1,iRightColumns:0,fnDrawCallback:null,sLeftWidth:"fixed",iLeftWidth:null,sRightWidth:"fixed",iRightWidth:null,sHeightMatch:"semiauto"};FixedColumns.prototype.CLASS="FixedColumns";FixedColumns.VERSION="2.0.3"})(jQuery,window,document);
