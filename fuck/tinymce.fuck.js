function fuck_mediahtml(data){
var noaudio=(data.source.lastIndexOf('.')<0||Object.keys(fuck_audio_use).indexOf(data.source.substring(data.source.lastIndexOf('.')+1))<0)?true:false;
return '<'+(noaudio?'video':'audio')+' controls="controls" '+(noaudio?((data.poster?'poster="'+data.poster+'" ':'')+(data.width?'width="'+data.width+'" ':'')+(data.height?'height="'+data.height+'" ':'')):'')+'><source src="'+data.source+'" /></'+(noaudio?'video':'audio')+'>';
};
function fuck_clip(blob,call){
for(var obj of tinymce.activeEditor.dom.select('img[src="'+blob.blobUri()+'"]')){obj.style.opacity='0.5';};
fuck_pool.push({act:"image",now:"wait",src:blob.blobUri()});
document.querySelector('#fuck_list').insertAdjacentHTML('afterbegin','<div style="max-width:100%;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><a id="fuck_file_'+(fuck_pool.length-1)+'" href="javascript:;" onclick="fuck_stop('+(fuck_pool.length-1)+');">[等待]</a> [图片] 粘贴</div>');
fuck_info('rest',1);
fuck_deal(null,call);
};
function fuck_file(call,what,meta){
switch(meta.filetype){
case 'image':var show='图片',accept='image/*,'+fuck_image_arr.join(',');break;
case 'media':var show='媒体',accept=fuck_media_arr.join(',');break;
default:console.log('SHIT');return;break;
};
fuck_pick.setAttribute('accept',accept);
fuck_pick.setAttribute('type','file');
fuck_pick.setAttribute('multiple','multiple');
fuck_pick.onchange=function(){
for(var obj of fuck_pick.files){
fuck_pool.push({act:meta.filetype,now:"wait",src:obj});
document.querySelector('#fuck_list').insertAdjacentHTML('afterbegin','<div style="max-width:100%;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><a id="fuck_file_'+(fuck_pool.length-1)+'" href="javascript:;" onclick="fuck_stop('+(fuck_pool.length-1)+');">[等待]</a> ['+show+'] '+obj.name+'</div>');
};
if(fuck_pick.files.length==1){fuck_deal(fuck_pool.length-1,call);}
else{tinymce.activeEditor.windowManager.close();fuck_deal(null);};
fuck_info('rest',fuck_pick.files.length);
fuck_pick.value=null;
};
if(!document.querySelector('#fuck_solo')){document.querySelector('.tox-dialog__title').insertAdjacentHTML('beforeend',' <a id="fuck_solo" href="javascript:;" style="cursor:pointer;"></a>');};
fuck_pick.click();
};
function fuck_deal(ikey,call){
var solo=false;
if(ikey===null){
for(var key in fuck_pool){
if(!fuck_pool[key]){continue;};
if(fuck_pool[key].now=='proc'){return;};
if(fuck_pool[key].now=='wait'){ikey=key;fuck_pool[ikey].now='proc';break;};
};
}
else{fuck_pool[ikey].now='solo';solo=true;};
if(ikey===null){console.log(fuck_pool);return;};
if(fuck_rape&&fuck_pool[ikey].act=='image'){
new Rape(fuck_pool[ikey].src,fuck_rape).conv().then((res)=>{if(fuck_image_arr.indexOf('.'+res.format)<0){fuck_fail({"shit":"type"},ikey,solo,call);return;};fuck_auth(res.canvas,ikey,call);}).catch((err)=>{fuck_fail({"shit":"rape"},ikey,solo,call);console.log('RAPE:'+err);});
}
else if(typeof(fuck_pool[ikey].src)=='string'&&fuck_pool[ikey].src.match(/^blob:/)){
var suck=new XMLHttpRequest;
suck.responseType='blob';
suck.onerror=function(e){fuck_fail({"shit":"suck"},ikey,solo,call);};
suck.onload=function(e){fuck_auth(suck.response,ikey,call);};
suck.open('GET',fuck_pool[ikey].src);
suck.send(null);
}
else{
fuck_auth(fuck_pool[ikey].src,ikey,call);
};
};
function fuck_auth(file,ikey,call){
var solo=(fuck_pool[ikey].now=='solo');
switch(fuck_pool[ikey].act){
case 'image':var type=fuck_image_arr,size=fuck_image_size,test=fuck_image_test;break;
case 'media':var type=fuck_media_arr,size=fuck_media_size,test=fuck_media_test;break;
default:console.log('SHIT');return;break;
};
if(!size){fuck_fail({"shit":"auth"},ikey,solo,call);return;};
if(file.size<12){fuck_fail({"shit":"file"},ikey,solo,call);return;};
if(file.size>size){fuck_fail({"shit":"size"},ikey,solo,call);return;};
if(test){
var suck=new XMLHttpRequest();
suck.open('POST','./?fuck_auth&act='+fuck_pool[ikey].act,true);
suck.onerror=function(e){fuck_fail({"shit":"suck"},ikey,solo,call);};
suck.onreadystatechange=function(e){
if(this.readyState===4&&this.status===200){
var cunt=JSON.parse(this.responseText);
if(cunt['shit']=='done'){fuck_save(file,ikey,call);}
else{fuck_fail(cunt,ikey,solo,call);};
};
};
suck.send(null);
}
else{fuck_save(file,ikey,call);};
};
function fuck_save(file,ikey,call){
var solo=(fuck_pool[ikey].now=='solo');
var suck=new XMLHttpRequest();
fuck_pool[ikey].xhr=suck;
suck.open('POST','./?fuck_save&act='+fuck_pool[ikey].act,true);
suck.onerror=function(e){fuck_fail({"shit":"suck"},ikey,solo,call);};
suck.upload.onprogress=function(e){
if(!e.lengthComputable){return;};
var prog=parseInt(99*e.loaded/e.total)+'%';
if(solo&&document.querySelector('#fuck_solo')){
document.querySelector('#fuck_solo').innerHTML='<span style="color:darkorange;">['+prog+']</span>';
document.querySelector('#fuck_solo').setAttribute('onclick','fuck_stop('+ikey+');');
};
document.querySelector('#fuck_file_'+ikey).innerHTML='<span style="color:darkorange;">['+prog+']</span>';
fuck_proc(false);
};
suck.onreadystatechange=function(e){
if(this.readyState===4&&this.status===200){
delete fuck_pool[ikey].xhr;
var cunt=JSON.parse(this.responseText);
if(cunt['shit']=='done'){fuck_done(cunt,ikey,solo,call);}
else{fuck_fail(cunt,ikey,solo,call);};
};
};
var dick=new FormData();
dick.append('file',file);
suck.send(dick);
};
function fuck_done(cunt,ikey,solo,call){
fuck_pool[ikey].now='done';
switch(cunt['zone']){
default:
cunt['file']='upload/attach/'+cunt['date']+'/'+cunt['user']+'_'+cunt['time']+'.'+cunt['type'];
break;
};
if(typeof(fuck_pool[ikey].src)=='string'&&fuck_pool[ikey].src.match(/^blob:/)){
for(var obj of tinymce.activeEditor.dom.select('img[src="'+fuck_pool[ikey].src+'"]')){obj.style.opacity='';};
fuck_pool[ikey].src=cunt['file'];
if(call){call(fuck_pool[ikey].src);};
}
else if(solo&&document.querySelector('#fuck_solo')){
document.querySelector('#fuck_solo').innerHTML='<span style="color:green;">[完成]</span>';
document.querySelector('#fuck_solo').removeAttribute('onclick');
fuck_pool[ikey].src=cunt['file'];
if(call){call(fuck_pool[ikey].src);};
}
else{
fuck_pool[ikey].src=cunt['file'];
fuck_exec(ikey);
};
document.querySelector('#fuck_file_'+ikey).innerHTML='<span style="color:green;">[完成]</span>';
document.querySelector('#fuck_file_'+ikey).setAttribute('onclick','fuck_exec('+ikey+');');
fuck_info('done',1);
fuck_proc(true);
if(!solo){fuck_deal(null);};
};
function fuck_fail(cunt,ikey,solo,call){
fuck_pool[ikey].now=cunt['shit'];
if(typeof(fuck_pool[ikey].src)=='string'&&fuck_pool[ikey].src.match(/^blob:/)){
if(call){call(fuck_pool[ikey].src);};
}
else if(solo&&document.querySelector('#fuck_solo')){
document.querySelector('#fuck_solo').innerHTML='<span style="color:red;">[错误]</span>';
document.querySelector('#fuck_solo').setAttribute('onclick','fuck_exec('+ikey+');');
};
delete fuck_pool[ikey].src;
document.querySelector('#fuck_file_'+ikey).innerHTML='<span style="color:red;">[错误]</span>';
document.querySelector('#fuck_file_'+ikey).setAttribute('onclick','fuck_exec('+ikey+');');
fuck_info('fail',1);
fuck_proc(true);
if(!solo){fuck_deal(null);};
};
function fuck_stop(ikey){
tinymce.activeEditor.windowManager.confirm((ikey===null)?'停止队列？':'删除任务？',function(yes){
if(!yes){return;};
if(ikey===null){
for(var key in fuck_pool){
if(!fuck_pool[key].xhr){continue;};
fuck_pool[key].xhr.abort();
delete fuck_pool[key].xhr;
fuck_pool[key].now='wait';
document.querySelector('#fuck_file_'+key).innerHTML='<span style="color:blue;">[等待]</span>';
};
fuck_proc(true);
}
else{
var proc=false;
if(fuck_pool[ikey].xhr){
if(fuck_pool[ikey].now=='proc'){proc=true;};
fuck_pool[ikey].xhr.abort();
};
delete fuck_pool[ikey];
fuck_info('rest',-1);
document.querySelector('#fuck_file_'+ikey).parentNode.innerHTML='';
if(document.querySelector('#fuck_solo')){
document.querySelector('#fuck_solo').innerHTML='';
document.querySelector('#fuck_solo').removeAttribute('onclick');
};
fuck_proc(true);
if(proc){
fuck_deal(null);
};
};
});
};
function fuck_exec(ikey){
switch(fuck_pool[ikey].now){
case 'done':
tinymce.activeEditor.selection.collapse();
switch(fuck_pool[ikey].act){
case 'image':tinymce.activeEditor.insertContent('<p><img src="'+fuck_pool[ikey].src+'" alt="" /></p>');break;
case 'media':tinymce.activeEditor.insertContent('<p>'+fuck_mediahtml({source:fuck_pool[ikey].src})+'</p>');break;
case 'file':break;
default:tinymce.activeEditor.windowManager.alert('上传成功');break;
};
break;
case 'auth':tinymce.activeEditor.windowManager.alert('无权使用插件');break;
case 'over':tinymce.activeEditor.windowManager.alert('周期配额用尽');break;
case 'file':tinymce.activeEditor.windowManager.alert('文件上传出错');break;
case 'size':tinymce.activeEditor.windowManager.alert('文件大小超限');break;
case 'type':tinymce.activeEditor.windowManager.alert('文件类型禁止');break;
case 'data':tinymce.activeEditor.windowManager.alert('数据存取失败');break;
case 'path':tinymce.activeEditor.windowManager.alert('目录创建失败');break;
case 'move':tinymce.activeEditor.windowManager.alert('文件存储失败');break;
case 'suck':tinymce.activeEditor.windowManager.alert('网络请求失败');break;
case 'rape':tinymce.activeEditor.windowManager.alert('前端处理失败');break;
default:tinymce.activeEditor.windowManager.alert('未知错误');break;
};
};
function fuck_info(mode,qnty){
document.querySelector('#fuck_info_'+mode).innerHTML=parseInt(document.querySelector('#fuck_info_'+mode).innerHTML)+qnty;
if(mode!='rest'){fuck_info('rest',-1);};
};
function fuck_proc(stop){
document.querySelector('#fuck_proc').innerHTML=stop?'开始':'停止';
document.querySelector('#fuck_proc').setAttribute('onclick',stop?'fuck_deal(null);':'fuck_stop(null);');
};
function fuck_list(){
document.querySelector('#fuck_list').style.display=(document.querySelector('#fuck_list').style.display=='none')?'':'none';
};
var fuck_image_arr=[];
for(var key in fuck_image_use){fuck_image_arr=fuck_image_arr.concat(fuck_image_use[key].split(','));};
var fuck_media_arr=[];
for(var key in fuck_video_use){fuck_media_arr=fuck_media_arr.concat(fuck_video_use[key].split(','));};
for(var key in fuck_audio_use){fuck_media_arr=fuck_media_arr.concat(fuck_audio_use[key].split(','));};
var fuck_pick=document.createElement('input');
var fuck_pool=[];
var UM={getEditor:function(func){if(func!='message'){return;};return {setContent:function(html){tinymce.activeEditor.insertContent(html);},execCommand:function(cmd,html){if(cmd!='insertHtml'){return;};tinymce.activeEditor.insertContent(html);}};}};