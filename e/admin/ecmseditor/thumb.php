<?php
define('EmpireCMSAdmin','1');
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
require("../../class/t_functions.php");
$link=db_connect();
$empire=new mysqlquery();
$editor=1;
//验证
$lur=is_login(); 
$logininid=$lur['userid'];
$loginin=$lur['username'];
$loginrnd=$lur['rnd'];
$loginlevel=$lur['groupid'];
$loginadminstyleid=$lur['adminstyleid'];
//ehash
$ecms_hashur=hReturnEcmsHashStrAll();

$filepath=$_POST['filepath'];
$method=$_POST['method'];
$gpath = preg_replace("/e\/.*/i","",dirname(__FILE__));

if($method == "optimize"){
	shell_exec("jpegoptim -m32 {$gpath}{$filepath}*.jpg");
	exit();
}
if($method == "thumb" and !is_dir("{$gpath}{$filepath}thumb")){
	mkdir("{$gpath}{$filepath}thumb");
}
$hand=@opendir($gpath.$filepath);
while($file=@readdir($hand))
{
		if($file=="."||$file=="..")
		{
			continue;
		}
		if($method == "thumb"){
			sys_ResizeImg($filepath."$file",130,120,0,"$file",$filepath."thumb/");
		}
}

?>

