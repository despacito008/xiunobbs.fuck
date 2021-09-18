<?php exit; //assbbs.com
global $fuck;
fuck_sync($post['message'],$arr['message'],$pid,$isfirst?$tid:0);
if(!empty($fuck['auto_clean'])){fuck_clean($fuck['auto_clean']);}
?>