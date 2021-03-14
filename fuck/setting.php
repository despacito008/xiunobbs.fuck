<?php !defined('DEBUG') AND exit('Access Denied.'); //assbbs.com
if($method=='POST'){
if(param('csrf')!=session_id()){message(0,'<a href="'.http_referer().'">滚你妈的</a>');}
$post=array();
$post['tinymce']=trim(param('tinymce'),'/ ');
$post['tidyicon']=param('tidyicon',0);
$post['noautowh']=param('noautowh',0);
$post['inner_style']=trim(preg_replace('/\s(?=\s)/','\\1',param('inner_style','',false)));
$post['outer_style']=trim(preg_replace('/\s(?=\s)/','\\1',param('outer_style','',false)));
$post['prism']=trim(param('prism'),'/ ');
$post['plugins']=trim(param('plugins'));
$post['toolbar']=trim(param('toolbar'));
$post['moreset']=trim(param('moreset'));
$post['keeptext']=param('keeptext',0);
$post['newlinep']=param('newlinep',0);
$post['jumplast']=param('jumplast',0);
$post['jumpedit']=param('jumpedit',0);
foreach($grouplist as $row){
$post[$row['gid'].'_image_interval']=intval(param($row['gid'].'_image_interval',0));
$post[$row['gid'].'_image_amount']=intval(param($row['gid'].'_image_amount',0));
$post[$row['gid'].'_image_size']=intval(param($row['gid'].'_image_size',0));
$post[$row['gid'].'_media_interval']=intval(param($row['gid'].'_media_interval',0));
$post[$row['gid'].'_media_amount']=intval(param($row['gid'].'_media_amount',0));
$post[$row['gid'].'_media_size']=intval(param($row['gid'].'_media_size',0));
}
$post['image_use']=array();
foreach($fuck['image'] as $key=>$row){if(param('image_'.$key,0)){$post['image_use'][]=$key;}}
$post['image_use']=implode(',',$post['image_use']);
$post['rape']=trim(preg_replace('/\s(?=\s)/','\\1',param('rape','',false)));
$post['video_use']=array();
foreach($fuck['video'] as $key=>$row){if(param('video_'.$key,0)){$post['video_use'][]=$key;}}
$post['video_use']=implode(',',$post['video_use']);
$post['audio_use']=array();
foreach($fuck['audio'] as $key=>$row){if(param('audio_'.$key,0)){$post['audio_use'][]=$key;}}
$post['audio_use']=implode(',',$post['audio_use']);
$post['media_src']=trim(preg_replace('/\s(?=\s)/','\\1',param('media_src','',false)));
$post['auto_clean']=intval(param('auto_clean',0));
kv_set('fuck',$post);
message(0,'<a href="'.http_referer().'">修改成功</a>');
}
elseif(isset($_GET['trash'])){
error_reporting(0);
echo json_encode(db_sql_find('SELECT * FROM `'.$db->tablepre.'fuck_file` WHERE `used`=0 ORDER BY `time` ASC LIMIT 100;'));
}
elseif(isset($_GET['clean']) && $_GET['csrf']==session_id()){
fuck_clean(0);
}
else{
$json=json_decode(file_get_contents(APP_PATH.'plugin/fuck/conf.json'),true);
include _include(ADMIN_PATH.'view/htm/header.inc.htm');
echo '
<a id="fuck-index" href="javascript:;" onclick="fuck(\'index\');" style="font-weight:bold;">[FUCK]</a>
<a id="fuck-basic" href="javascript:;" onclick="fuck(\'basic\');">[基础]</a>
<a id="fuck-extra" href="javascript:;" onclick="fuck(\'extra\');">[扩展]</a>
<a id="fuck-group" href="javascript:;" onclick="fuck(\'group\');">[权限]</a>
<a id="fuck-image" href="javascript:;" onclick="fuck(\'image\');">[图片]</a>
<a id="fuck-media" href="javascript:;" onclick="fuck(\'media\');">[媒体]</a>
<a id="fuck-clean" href="javascript:;" onclick="fuck(\'clean\');">[清理]</a>
<hr />
<form action="" method="post" id="form">
<span id="fuck_index">
<div><b>当前版本</b>：'.$json['version'].'</div>
<div><b>最新版本</b>：<a href="https://github.com/netcrashed/xiunobbs.fuck" target="_blank">https://github.com/netcrashed/xiunobbs.fuck</a></div>
<div><b>温馨提示</b>：如需开启上传请先设置 [权限] [图片] [媒体]</div>
<div><b>屌丝论坛</b>：<a href="https://assbbs.com" target="_blank">https://assbbs.com</a></div>
</span>
<span id="fuck_basic" style="display:none;">
<div><b>TinyMCE</b>（编辑器调用，<a href="https://github.com/tinymce/tinymce/releases" target="_blank">最新版本号</a>）</div>
<div>'.form_text('tinymce',empty($fuck['tinymce'])?'':$fuck['tinymce']).'</div>
<div>https://cdn.jsdelivr.net/npm/tinymce</div>
<div>https://cdn.staticfile.org/tinymce/5.7.0</div>
<div>https://cdn.bootcss.com/tinymce/5.7.0</div>
<hr />
<div><b>缩进工具栏</b>（编辑器样式更协调）</div>
<div>'.form_radio_yes_no('tidyicon',empty($fuck['tidyicon'])?0:1).'</div>
<hr />
<div><b>禁止自动宽高</b>（防止图片尺寸缩小）</div>
<div>'.form_radio_yes_no('noautowh',empty($fuck['noautowh'])?0:1).'</div>
<hr />
<div><b>内部样式</b>（编辑器内CSS）</div>
<div>'.form_textarea('inner_style',empty($fuck['inner_style'])?'':$fuck['inner_style']).'</div>
<div>*{max-width:100% !important;}/*元素不超出编辑器宽度*/</div>
<div>body{color:#333 !important;font-size:15px !important;}/*字色字号*/</div>
<div>img{height:auto !important;}/*图片高度跟随比例*/</div>
<hr />
<div><b>外部样式</b>（论坛全局CSS）</div>
<div>'.form_textarea('outer_style',empty($fuck['outer_style'])?'':$fuck['outer_style']).'</div>
<div>.message *{max-width:100% !important;}/*元素不超出内容页宽度*/</div>
<div>.message a{text-decoration:underline !important;}/*链接下划线*/</div>
<div>.message img{height:auto !important;}/*图片高度跟随比例*/</div>
<div>@media(min-width:1000px){.message img{max-width:720px !important;}}/*图片在大屏幕中最宽720px*/</div>
<div>.message img,.message audio,.message video{margin-top:8px !important;}/*媒体顶部间距*/</div>
<div>.message table,.message th,.message td{border:1px solid #999 !important;}/*表格简易样式*/</div>
<div>.message pre[class^=language-]{padding:8px 12px !important;background:#DDD !important;}/*代码示例简易样式*/</div>
<div>.message iframe[src^="//music.163.com/"]{height:86px !important;}.message iframe[src^="https://h5.xiami.com/"]{height:110px !important;}/*音频嵌入高度修正*/</div>
<div>@media(min-width:1000px){.message iframe[src^="//player.bilibili.com/"],.message iframe[src^="https://v.qq.com/"],.message iframe[src^="https://tv.sohu.com/"],.message iframe[src^="https://player.youku.com/"]{width:600px !important;height:400px !important;}}@media(max-width:1000px){.message iframe[src^="//player.bilibili.com/"],.message iframe[src^="https://v.qq.com/"],.message iframe[src^="https://tv.sohu.com/"],.message iframe[src^="https://player.youku.com/"]{width:100% !important;height:50vw !important;}}/*视频嵌入宽高统一*/</div>
<hr />
<div><b>Prism代码高亮</b>（留空不启用，<a href="https://github.com/PrismJS/prism/releases" target="_blank">最新版本号</a>）</div>
<div>'.form_text('prism',empty($fuck['prism'])?'':$fuck['prism']).'</div>
<div>https://cdn.jsdelivr.net/npm/prismjs</div>
<div>https://cdn.staticfile.org/prism/1.23.0</div>
<div>https://cdn.bootcss.com/prism/1.23.0</div>
<hr />
<div><b>自定义插件</b>（不懂留空别乱改）</div>
<div>'.form_text('plugins',empty($fuck['plugins'])?'':$fuck['plugins']).'</div>
<div>code codesample hr image link media paste table wordcount</div>
<hr />
<div><b>自定义工具</b>（不懂留空别乱改）</div>
<div>'.form_text('toolbar',empty($fuck['toolbar'])?'':$fuck['toolbar']).'</div>
<div>undo redo code removeformat align bold italic underline strikethrough forecolor backcolor fontsizeselect image media link unlink hr codesample table</div>
<hr />
<div><b>自定义配置</b>（不懂留空别乱改）</div>
<div>'.form_text('moreset',empty($fuck['moreset'])?'':$fuck['moreset']).'</div>
<div>menubar:true,height:400</div>
</span>
<span id="fuck_extra" style="display:none;">
<div><b>高级回复继承</b>（切换编辑器保留内容）</div>
<div>'.form_radio_yes_no('keeptext',empty($fuck['keeptext'])?0:1).'</div>
<hr />
<div><b>统一文本换行</b>（快捷回复使用P换行符）</div>
<div>'.form_radio_yes_no('newlinep',empty($fuck['newlinep'])?0:1).'</div>
<hr />
<div><b>回复后跳转最后一页</b>（需禁用回复排序类插件）</div>
<div>'.form_radio_yes_no('jumplast',empty($fuck['jumplast'])?0:1).'</div>
<hr />
<div><b>编辑后跳转至来源页</b>（不开启跳转帖子第一页）</div>
<div>'.form_radio_yes_no('jumpedit',empty($fuck['jumpedit'])?0:1).'</div>
</span>
<span id="fuck_group" style="display:none;">
<table>
<tbody>
<tr>
<th></th>
<th><a href="javascript:;" onclick="alert(\'填写“0”为永久\r\n强烈不建议设置为永久\r\n推荐值：1440\');">配额周期（分）</a></th>
<th><a href="javascript:;" onclick="alert(\'填写“0”不限制\r\n数值越大主机压力越大\r\n推荐值：100\');">最多上传（张）</a></th>
<th><a href="javascript:;" onclick="alert(\'填写“0”关闭该组上传\r\n建议不要太大以免中断\r\n推荐值：5\');">最大尺寸（MB）</a></th>
</tr>
';
foreach($grouplist as $row){
echo '
<tr>
<td><b>'.$row['gid'].':'.$row['name'].'</b></td>
</tr>
<tr>
<td>图片</td>
<td>'.form_text($row['gid'].'_image_interval',empty($fuck[$row['gid'].'_image_interval'])?0:$fuck[$row['gid'].'_image_interval']).'</td>
<td>'.form_text($row['gid'].'_image_amount',empty($fuck[$row['gid'].'_image_amount'])?0:$fuck[$row['gid'].'_image_amount']).'</td>
<td>'.form_text($row['gid'].'_image_size',empty($fuck[$row['gid'].'_image_size'])?0:$fuck[$row['gid'].'_image_size']).'</td>
</tr>
<tr>
<td>媒体</td>
<td>'.form_text($row['gid'].'_media_interval',empty($fuck[$row['gid'].'_media_interval'])?0:$fuck[$row['gid'].'_media_interval']).'</td>
<td>'.form_text($row['gid'].'_media_amount',empty($fuck[$row['gid'].'_media_amount'])?0:$fuck[$row['gid'].'_media_amount']).'</td>
<td>'.form_text($row['gid'].'_media_size',empty($fuck[$row['gid'].'_media_size'])?0:$fuck[$row['gid'].'_media_size']).'</td>
</tr>
';
}
echo '
</tbody>
</table>
<hr />
<div><b>以下设置必须高于最大尺寸</b>（需要修改服务器参数）</div>
<div>[php.ini] post_max_size (当前:'.ini_get('post_max_size').')</div>
<div>[php.ini] upload_max_filesize (当前:'.ini_get('upload_max_filesize').')</div>
<div>[nginx.conf] client_max_body_size (<a href="https://blog.csdn.net/webnoties/article/details/17266651" target="_blank">了解详情</a>)</div>
</span>
<span id="fuck_image" style="display:none;">
<div><b>可上传格式</b>（WEBP格式不兼容：PHP&lt;7.1）</div>
<div>
';
foreach($fuck['image'] as $key=>$row){echo form_checkbox('image_'.$key,empty($fuck['image_use'][$key])?0:1,$key);}
echo '
</div>
<hr />
<div><b>Rape前端处理</b>（留空不启用，<a href="https://github.com/netcrashed/rape.js" target="_blank">配置说明书</a>）</div>
<div>'.form_textarea('rape',empty($fuck['rape'])?'':$fuck['rape']).'</div>
<div>_png:{width:2048,height:2048,fill:null,format:"image/png",quality:0.9},</div>
<div>_jpg:{width:2048,height:2048,fill:"#FFF",format:"image/jpeg",quality:0.9},</div>
<div>_webp:{width:2048,height:2048,fill:null,format:"image/webp",quality:0.9},</div>
<div>png:{normal:"_webp",nowebp:"_png",animate:false},</div>
<div>jpg:{normal:"_webp",nowebp:"_jpg"},</div>
<div>bmp:{normal:"_webp",nowebp:"_jpg"},</div>
<div>ico:null,</div>
<div>gif:{normal:"_webp",nowebp:"_jpg",animate:false},</div>
<div>webp:{normal:"_webp",nowebp:"_png",animate:false},</div>
</span>
<span id="fuck_media" style="display:none;">
<div><b>可上传格式</b>（<a href="https://developer.mozilla.org/zh-TW/docs/Web/Media/Formats/Containers" target="_blank">跨平台兼容</a>：MP4/MOV[AVC]，M4A[AAC]，WEBM，WEBA，WAV，MP3，FLAC）</div>
<div>
';
foreach($fuck['video'] as $key=>$row){echo form_checkbox('video_'.$key,empty($fuck['video_use'][$key])?0:1,$key);}
echo '
</div>
<div>
';
foreach($fuck['audio'] as $key=>$row){echo form_checkbox('audio_'.$key,empty($fuck['audio_use'][$key])?0:1,$key);}
echo '
</div>
'.(extension_loaded('fileinfo')?'':'<div>建议安装<a href="https://www.php.net/manual/zh/book.fileinfo.php" target="_blank">PHP-Fileinfo</a>扩展实现<a href="https://developer.mozilla.org/zh-CN/docs/Web/HTTP/Basics_of_HTTP/MIME_types" target="_blank">MIME</a>精准检测。</div>').'
<hr />
<div><b>可嵌入地址</b>（iframe可以调用的网站，头部匹配，每行一个）</div>
<div>'.form_textarea('media_src',empty($fuck['media_src'])?'':$fuck['media_src']).'</div>
<div>//music.163.com/</div>
<div>//player.bilibili.com/</div>
<div>https://v.qq.com/</div>
<div>https://tv.sohu.com/</div>
<div>https://player.youku.com/</div>
</span>
<span id="fuck_clean" style="display:none;">
<div><b>自动清理</b>（上传N天后未使用则删除，填写“0”不启用）</div>
<div>'.form_text('auto_clean',empty($fuck['auto_clean'])?0:$fuck['auto_clean']).'</div>
<hr />
<div><b>手动清理</b>（列举100条未使用文件，不要误删编辑中内容）</div>
<div><a href="javascript:;" onclick="fuck_clean();">&#9760;&nbsp;一键删除本页文件&nbsp;&#9760;</a></div>
<span id="fuck_trash"></span>
</span>
<hr />
<input name="csrf" value="'.session_id().'" style="display:none;" />
<div><a href="javascript:this.form.submit();" id="submit" data-loading-text="'.lang('submiting').'..."><b>['.lang('confirm').']</b></a></div>
</form>
<script>
function fuck(page){
document.querySelector(\'#fuck-index\').style.fontWeight=(page==\'index\')?\'bold\':\'\';
document.querySelector(\'#fuck-basic\').style.fontWeight=(page==\'basic\')?\'bold\':\'\';
document.querySelector(\'#fuck-extra\').style.fontWeight=(page==\'extra\')?\'bold\':\'\';
document.querySelector(\'#fuck-group\').style.fontWeight=(page==\'group\')?\'bold\':\'\';
document.querySelector(\'#fuck-image\').style.fontWeight=(page==\'image\')?\'bold\':\'\';
document.querySelector(\'#fuck-media\').style.fontWeight=(page==\'media\')?\'bold\':\'\';
document.querySelector(\'#fuck-clean\').style.fontWeight=(page==\'clean\')?\'bold\':\'\';
document.querySelector(\'#fuck_index\').style.display=(page==\'index\')?\'\':\'none\';
document.querySelector(\'#fuck_basic\').style.display=(page==\'basic\')?\'\':\'none\';
document.querySelector(\'#fuck_extra\').style.display=(page==\'extra\')?\'\':\'none\';
document.querySelector(\'#fuck_group\').style.display=(page==\'group\')?\'\':\'none\';
document.querySelector(\'#fuck_image\').style.display=(page==\'image\')?\'\':\'none\';
document.querySelector(\'#fuck_media\').style.display=(page==\'media\')?\'\':\'none\';
document.querySelector(\'#fuck_clean\').style.display=(page==\'clean\')?\'\':\'none\';
};
function fuck_filter(text){
var map={\'&\':\'&amp;\',\'<\':\'&lt;\',\'>\':\'&gt;\',\'"\':\'&quot;\',"\'":\'&#039;\'};
return text.replace(/[&<>"\']/g,function(m){return map[m];});
};
function fuck_trash(){
document.querySelector(\'#fuck_trash\').innerHTML=\'\';
var xhr=new XMLHttpRequest;
xhr.open(\'GET\',\''.url('plugin-setting-fuck').'?&trash\');
xhr.onreadystatechange=function(){
if(this.readyState===4 && this.status===200){
var json=JSON.parse(this.responseText);
for(var i=0;i<json.length;i++){
var tobj=new Date(parseInt(json[i].time));
document.querySelector(\'#fuck_trash\').innerHTML+=\'<div><a href="../?user-\'+json[i].user+\'.htm" target="_blank">[\'+tobj.getFullYear()+\'-\'+(tobj.getMonth()+1)+\'-\'+tobj.getDate()+\']</a> <a href="../upload/attach/\'+json[i].date+\'/\'+json[i].user+\'_\'+json[i].time+\'.\'+json[i].type+\'" target="_blank">\'+(json[i].size/1048576).toFixed(2)+\' MB (\'+json[i].type+\')</a></div>\';
}
};
};
xhr.send(null);
};
function fuck_clean(){
if(!confirm(\'确认清理？\')){return;};
var xhr=new XMLHttpRequest;
xhr.open(\'GET\',\''.url('plugin-setting-fuck').'?&clean&csrf=\'+document.querySelector(\'input[name="csrf"]\').value);
xhr.onreadystatechange=function(){
if(this.readyState===4 && this.status===200){
fuck_trash();
};
};
xhr.send(null);
};
fuck_trash();
</script>
';
include _include(ADMIN_PATH.'view/htm/footer.inc.htm');
}
?>