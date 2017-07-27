<?php
			public function fmid(){
				global $_W;
				global $_GPC;
				$id=$_GPC['id'];	
				$number=$_GPC['number'];
				$FMID=$_FILES['FMID'];
				 $typeid=$_FILES['FMID'];
				 $ftype=$typeid['name'];
				$type=array('.jpg','.png','.jpeg','.bmp');
				$upFileType=strtolower(strrchr($ftype,'.'));
				$time="../uploadword/".date("Ymd")."/";
		
				if (!is_dir($time)) {
					 mkdir($time);
				}
		 
		 		if(is_uploaded_file($typeid['tmp_name'])){
		 
					if (in_array($upFileType,$type)) {
		
					 	 $pic_path=$time."/".date("Ymd").$typeid['name'];
					 	 
					 	   move_uploaded_file($typeid['tmp_name'],$pic_path);
					 	    
					 	    $zmid='fmid';
					 	   $sfz_data = array(
					 	   	 
					 	   $zmid=>$pic_path
					 	   	);
					 	   $result = pdo_update('ewei_shop_member_address', $sfz_data, array('id' => $id)); 
					 	    echo json_encode($pic_path); 	
					 }	
				}
			}
	 
		
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>AjAX+PHP+file</title>
		<script src="jquery-2.0.3.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="jquery.form.js" type="text/javascript" charset="utf-8"></script>
		
		 
	</head>
	<body>
		 <input type="file" id="FMID" name="FMID" class="filepath" value="" />
		 
	</body>
	<script>
		$(function(){
			   var url="{php echo mobileUrl('member/address/fmid',array('id'=>$address['id']))}";
			     $("#FMID").wrap("<form id='myuploads' action ="+url+" method='post' enctype='multipart/form-data'></form>"); 
			    $('#FMID').change(function(){
			    
			          $("#myuploads").ajaxSubmit({
			            dataType:'json',
			            success:function(data){
			               if(data==null){
			                 return;
			              }
			              $("#img2").hide();
			              $('#pic2').show();
			              $("#pic2").attr('src',data);
			               $("#pic2").attr('at',data);
			
			            },
			            error:function(xhr){
			           FoxUI.toast.show("照片不符合");
			          return
			            }
			          })
			        })
			})
		
	</script>
	
	
</html>
