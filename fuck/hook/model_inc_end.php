<?php exit; //assbbs.com
$fuck=kv_get('fuck');
$fuck=array_merge(array(
'image'=>array(
'gif'=>'.gif,image/gif',
'jpg'=>'.jpg,.jpeg,image/jpeg,image/pjpeg',
'png'=>'.png,image/png,image/x-png',
'bmp'=>'.bmp,.dib,image/bmp,image/x-ms-bmp',
'ico'=>'.ico,.cur,image/icon,image/x-icon,image/vnd.microsoft.icon',
'webp'=>'.webp,image/webp',
),
'video'=>array(
'mp4'=>'.mp4,.m4v,.mp4v,video/mp4,video/m4v,video/x-m4v',
'mov'=>'.mov,.qtm,.qt,video/quicktime,video/x-quicktime',
'ogv'=>'.ogv,.ogg,.ogx,.ogm,video/ogg',
'webm'=>'.webm,video/webm',
),
'audio'=>array(
'm4a'=>'.m4a,.mp4a,audio/mp4,audio/m4a,audio/x-m4a',
'aac'=>'.aac,.adts,audio/aac,audio/x-aac,audio/hx-aac-adts,audio/x-hx-aac-adts',
'oga'=>'.oga,.spx,.opus,audio/ogg',
'weba'=>'.weba,audio/webm',
'wav'=>'.wav,audio/wav,audio/wave,audio/x-wav,audio/x-pn-wav',
'mp3'=>'.mp3,audio/mpeg,audio/x-mpeg',
'flac'=>'.flac,audio/flac,audio/x-flac',
),
),($fuck?$fuck:array()));
function fuck_mode($act){
switch($act){
case 'image':return 1;break;
case 'media':return 2;break;
//case 'file':return 3;break;
default:return 0;break;
}
};
function fuck_lash($var){ //正则需要转义，不知道干啥用的
return str_replace(array('\\','/','[',']','(',')','{','}','.','*','?','+','^','$','|'),array('\\\\','\\/','\\[','\\]','\\(','\\)','\\{','\\}','\\.','\\*','\\?','\\+','\\^','\\$','\\|'),htmlentities($var));
}
function fuck_sync($oldhtml,$newhtml){
global $db,$fuck;
$olditem=$oldhtml?(preg_match_all('/\"upload\/attach\/[\w\/]+\/([\d]+_[\d]{13})\./',$oldhtml,$olditem)?array_unique($olditem['1']):array()):array();
$newitem=$newhtml?(preg_match_all('/\"upload\/attach\/[\w\/]+\/([\d]+_[\d]{13})\./',$newhtml,$newitem)?array_unique($newitem['1']):array()):array();
$sql='';
foreach(array_diff($olditem,$newitem) as $row){$spl=explode('_',$row);$sql.='UPDATE `'.$db->tablepre.'fuck_file` SET `used`=`used`-1 WHERE `user`='.$spl['0'].' AND `mode` IN (1,2,3) AND `time`='.$spl['1'].';';}
foreach(array_diff($newitem,$olditem) as $row){$spl=explode('_',$row);$sql.='UPDATE `'.$db->tablepre.'fuck_file` SET `used`=`used`+1 WHERE `user`='.$spl['0'].' AND `mode` IN (1,2,3) AND `time`='.$spl['1'].';';}
if(!empty($sql)){db_exec($sql);}
}
function fuck_clean($days){
global $db;
foreach(db_sql_find('SELECT * FROM `'.$db->tablepre.'fuck_file` WHERE `used`=0 '.(empty($days)?'':'AND `time`<'.(sprintf('%.0f',microtime(true)*1000)-$days*86400000)).' ORDER BY `time` ASC LIMIT 100;') as $row){
$file=APP_PATH.'upload/attach/'.$row['date'].'/'.$row['user'].'_'.$row['time'].'.'.$row['type'];
if(is_file($file)){unlink($file);}
}
db_exec('DELETE FROM `'.$db->tablepre.'fuck_file` WHERE `used`=0 '.(empty($days)?'':'AND `time`<'.(sprintf('%.0f',microtime(true)*1000)-$days*86400000)).' ORDER BY `time` ASC LIMIT 100;');
}
$fuck['image_use']=array_intersect_key($fuck['image'],array_flip(empty($fuck['image_use'])?array():explode(',',$fuck['image_use'])));
$fuck['video_use']=array_intersect_key($fuck['video'],array_flip(empty($fuck['video_use'])?array():explode(',',$fuck['video_use'])));
$fuck['audio_use']=array_intersect_key($fuck['audio'],array_flip(empty($fuck['audio_use'])?array():explode(',',$fuck['audio_use'])));
?>