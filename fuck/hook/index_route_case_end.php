<?php exit; //assbbs.com
case 'fuck_save':
//sleep(6); //测试用模拟慢速上传
$fuck_file=array('user'=>G('uid'),'mode'=>fuck_mode(empty($_GET['act'])?'':$_GET['act']),'time'=>sprintf('%.0f',microtime(true)*1000));
if(empty($fuck['tinymce']) || empty($fuck_file['mode']) || empty($fuck[G('gid').'_'.$_GET['act'].'_size'])){die('{"shit":"auth"}');}
if(!empty($fuck[G('gid').'_'.$_GET['act'].'_amount'])){
$fuck_file_head=db_sql_find_one('SELECT `time` FROM `'.$db->tablepre.'fuck_file` WHERE `user`='.$fuck_file['user'].' AND `mode`='.$fuck_file['mode'].' ORDER BY `time` DESC LIMIT '.($fuck[G('gid').'_'.$_GET['act'].'_amount']-1).',1');
if($fuck_file_head && (empty($fuck[G('gid').'_'.$_GET['act'].'_interval']) || ($fuck_file['time']-$fuck_file_head['time'])<$fuck[G('gid').'_'.$_GET['act'].'_interval']*60000)){die('{"shit":"over"}');}
}
if(empty($_FILES['file']) || !is_uploaded_file($_FILES['file']['tmp_name']) || $_FILES['file']['error']>0 || $_FILES['file']['size']<12){die('{"shit":"file"}');}
$fuck_file['size']=$_FILES['file']['size'];
if($fuck_file['size']>$fuck[G('gid').'_'.$_GET['act'].'_size']*1048576){die('{"shit":"size"}');}
$fuck_file['type']=null;
if($_GET['act']=='image'){$type=getimagesize($_FILES['file']['tmp_name']);$type=$type['mime'];}
elseif($_GET['act']=='media' && extension_loaded('fileinfo')){$type=mime_content_type($_FILES['file']['tmp_name']);}
else{$type=strrchr($_FILES['file']['name'],'.');}
foreach((($_GET['act']=='media')?array_merge($fuck['video_use'],$fuck['audio_use']):$fuck[$_GET['act'].'_use']) as $key=>$row){
if(in_array($type,explode(',',$row))){$fuck_file['type']=$key;}
}
if(!$fuck_file['type']){die('{"shit":"type"}');}
$fuck_file['zone']=0; //上传到本地
$fuck_file['date']=date('Ym');
if(db_exec('CREATE TABLE IF NOT EXISTS `'.$db->tablepre.'fuck_file` ( `user` int(11) unsigned NOT NULL, `time` bigint(13) unsigned NOT NULL, `date` varchar(10) COLLATE utf8_unicode_ci NOT NULL, `zone` tinyint(3) unsigned NOT NULL, `mode` tinyint(1) unsigned NOT NULL, `type` varchar(10) COLLATE utf8_unicode_ci NOT NULL, `size` bigint(13) unsigned NOT NULL, `used` int(11) unsigned NOT NULL, UNIQUE KEY `user_mode_time` (`user`,`mode`,`time`), KEY `used_time` (`used`,`time`) ) ENGINE='.$db->rconf['engine'].' DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; INSERT INTO `'.$db->tablepre.'fuck_file` SET `user`='.$fuck_file['user'].',`date`="'.$fuck_file['date'].'",`time`='.$fuck_file['time'].',`zone`='.$fuck_file['zone'].',`mode`='.$fuck_file['mode'].',`type`="'.$fuck_file['type'].'",`size`='.$fuck_file['size'].',`used`=0;')===false){die('{"shit":"data"}');}
if(!is_dir('upload/attach/'.$fuck_file['date']) && !mkdir('upload/attach/'.$fuck_file['date'],0755,true)){die('{"shit":"path"}');}
if(!file_exists($_FILES['file']['tmp_name']) || !move_uploaded_file($_FILES['file']['tmp_name'],'upload/attach/'.$fuck_file['date'].'/'.$fuck_file['user'].'_'.$fuck_file['time'].'.'.$fuck_file['type'])){die('{"shit":"move"}');}
die('{"shit":"done","zone":"'.$fuck_file['zone'].'","date":"'.$fuck_file['date'].'","user":"'.$fuck_file['user'].'","time":"'.$fuck_file['time'].'","type":"'.$fuck_file['type'].'"}');
break;
case 'fuck_auth':
$fuck_file=array('user'=>G('uid'),'mode'=>fuck_mode(empty($_GET['act'])?'':$_GET['act']),'time'=>sprintf('%.0f',microtime(true)*1000));
if(empty($fuck['tinymce']) || empty($fuck_file['mode']) || empty($fuck[G('gid').'_'.$_GET['act'].'_size'])){die('{"shit":"auth"}');}
if(!empty($fuck[G('gid').'_'.$_GET['act'].'_amount'])){
$fuck_file_head=db_sql_find_one('SELECT `time` FROM `'.$db->tablepre.'fuck_file` WHERE `user`='.$fuck_file['user'].' AND `mode`='.$fuck_file['mode'].' ORDER BY `time` DESC LIMIT '.($fuck[G('gid').'_'.$_GET['act'].'_amount']-1).',1');
if($fuck_file_head && (empty($fuck[G('gid').'_'.$_GET['act'].'_interval']) || ($fuck_file['time']-$fuck_file_head['time'])<$fuck[G('gid').'_'.$_GET['act'].'_interval']*60000)){die('{"shit":"over"}');}
}
die('{"shit":"done"}');
break;
case 'fuck_jump':
if(!$fuck['jumplast'] || empty($_GET['shit'])){die;}
$shit=thread__read(intval($_GET['shit']));
if(!$shit){die;}
$last=ceil(($shit['posts']+1)/$conf['postlist_pagesize']);
if(isset($_GET['suck'])){header('Location:'.url('thread-'.$shit['tid'].'-'.$last).'#jumplast');}
else{echo $last;}
break;
?>