<?php exit; //assbbs.com
global $fuck;
$fuck_media_src=empty($fuck['media_src'])?array():explode(' ',str_replace(array("\r\n","\r","\n"),' ',$fuck['media_src']));
foreach($fuck_media_src as $fuck_src_key=>$fuck_src_row){
$doc=str_replace('src="'.$fuck_src_row,'&fuck_src_'.$fuck_src_key.'="'.$fuck_src_row,$doc);
$white_value['&fuck_src_'.$fuck_src_key]=array('pcre','',array('#^'.$fuck_src_row.'#is'));
}
$white_tag[]='source';
$white_tag[]='video';
$white_tag[]='audio';
$white_value['rel']=array('list','noopener',array('noopener'));
$white_value['allowfullscreen']=array('list','allowfullscreen',array('allowfullscreen'));
$white_value['controls']=array('list','controls',array('controls'));
$white_value['autoplay']=array('list','autoplay',array('autoplay'));
$white_value['preload']=array('list','preload',array('preload'));
$white_value['muted']=array('list','muted',array('muted'));
$white_value['loop']=array('list','loop',array('loop'));
$white_value['poster']=array('pcre','',array($pattern['img_url']));
$white_value['scrolling']=array('list','auto',array('auto','yes','no'));
$white_value['marginwidth']=array('range',0,array(0,10));
$white_value['marginheight']=array('range',0,array(0,10));
$white_value['framespacing']=array('range',0,array(0,10));
$white_css['text-decoration']=array('pcre','none',array($pattern['safe']));
$white_css['border-collapse']=array('pcre','separate',array($pattern['safe']));
$white_css['border-style']=array('pcre','',array($pattern['safe']));
$white_css['border-color']=array('pcre','',array($pattern['css']));
?>