<?php //assbbs.com
!defined('DEBUG') AND exit('Forbidden');
$fuck=kv_get('fucked');
$json=json_decode(file_get_contents(APP_PATH.'plugin/fuck/conf.json'),true);
kv_set('fucked',str_replace('.','',$json['version']));
//【FUCK】2020.05.22 改变fuck_file索引
if($fuck && $fuck<20200522){
db_exec('ALTER TABLE `'.$db->tablepre.'fuck_file` DROP INDEX `used`, ADD INDEX `used_time` (`used`,`time`) USING BTREE;');
}
//【FUCK】2020.04.05 改变fuck_file结构
if(kv_get('fuck_xiuno')){
db_exec('ALTER TABLE `'.$db->tablepre.'fuck_file` DROP `name`;');
db_exec('ALTER TABLE `'.$db->tablepre.'fuck_file` ADD `mode` TINYINT(1) unsigned NOT NULL AFTER `zone`;');
db_exec('ALTER TABLE `'.$db->tablepre.'fuck_file` MODIFY COLUMN `type` VARCHAR(10) COLLATE utf8_unicode_ci NOT NULL;');
db_exec('ALTER TABLE `'.$db->tablepre.'fuck_file` DROP INDEX `user_time`, ADD UNIQUE `user_mode_time` (`user`,`mode`,`time`) USING BTREE;');
db_exec('UPDATE `'.$db->tablepre.'fuck_file` SET `mode`=1 WHERE `mode`=0;');
db_exec('UPDATE `'.$db->tablepre.'fuck_file` SET `type`="gif" WHERE `type`="101";');
db_exec('UPDATE `'.$db->tablepre.'fuck_file` SET `type`="jpg" WHERE `type`="102";');
db_exec('UPDATE `'.$db->tablepre.'fuck_file` SET `type`="png" WHERE `type`="103";');
db_exec('UPDATE `'.$db->tablepre.'fuck_file` SET `type`="bmp" WHERE `type`="106";');
db_exec('UPDATE `'.$db->tablepre.'fuck_file` SET `type`="ico" WHERE `type`="117";');
db_exec('UPDATE `'.$db->tablepre.'fuck_file` SET `type`="webp" WHERE `type`="118";');
kv_delete('fuck_xiuno');
plugin_lock_end();
}
?>