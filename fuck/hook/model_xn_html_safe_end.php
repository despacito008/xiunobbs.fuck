<?php exit; //assbbs.com
foreach($fuck_media_src as $fuck_src_key=>$fuck_src_row){
$result=str_replace('&fuck_src_'.$fuck_src_key.'="'.$fuck_src_row,'src="'.$fuck_src_row,$result);
}
?>