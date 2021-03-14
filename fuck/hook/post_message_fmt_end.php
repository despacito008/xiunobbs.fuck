<?php exit; //assbbs.com
global $fuck;
if(!empty($fuck['newlinep']) && $arr['doctype']==1){$arr['message_fmt']='<p>'.str_replace(array('<br>','<p></p>'),array('</p><p>','<p>&nbsp;</p>'),$arr['message_fmt']).'</p>';}
?>