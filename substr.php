<?php
// 截取字符无乱码
	function subs ($str,$len){
		for ($i=0; $i <$len ; $i++) { 
			//UTF-8 	一个中文汉字需要三个字节
			//GB2312	正常 一个中文汉字需要2个字节
			//
			// ord字符ASCII >0xa0 160  
			if(ord(substr($str, $i,1)>0xa0)){
			$srting .=substr($str,$i,2);
			$i++;
			}else{
			$string .=substr($str,$i,1);
			}
		}
		return $srting;

	}
// 
	// UTF-8截取字符无乱码
function sub($str,$len){
	for ($i=0; $i < $len ; $i++) { 
		//ASCII
	 	if (ord(substr($str,$i,1))>0xa0) {
	 		$string .=substr($str,$i,3);
	 		$i++;
	 	}else{
	 		$string .=substr($str,$i,1);

	 	}

	}
	return $string;

}

?>