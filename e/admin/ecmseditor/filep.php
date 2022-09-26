<?php
define('EmpireCMSAdmin','1');
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
$link=db_connect();
$empire=new mysqlquery();
$editor=1;
//验证用户
$lur=is_login();
$logininid=$lur['userid'];
$loginin=$lur['username'];
$loginrnd=$lur['rnd'];
$loginlevel=$lur['groupid'];
$loginadminstyleid=$lur['adminstyleid'];
//ehash
$ecms_hashur=hReturnEcmsHashStrAll();

//返回按钮事件
function ToReturnDoFilepButton($doing,$tranfrom,$field,$file,$filename,$fileid,$filesize,$filetype,$no,$type){
	if($doing==1)//返回地址
	{
		$bturl="ChangeFile1(1,'".$file."');";
		$button="<input type=button name=button value='选择' onclick=\"javascript:".$bturl."\">";
	}
	elseif($doing==2)//返回地址
	{
		$bturl="ChangeFile1(2,'".$file."');";
		$button="<input type=button name=button value='选择' onclick=\"javascript:".$bturl."\">";
	}
	else
	{
		if($tranfrom==1)//编辑器选择
		{
			$bturl="EditorChangeFile('".$file."','".addslashes($filename)."','".$filetype."','".$filesize."','".addslashes($no)."');";
			$button="<input type=button name=button value='选择' onclick=\"javascript:".$bturl."\">";
		}
		elseif($tranfrom==2)//特殊字段选择
		{
			$bturl="SFormIdChangeFile('".addslashes($no)."','$file','$filesize','$filetype','$field');";
			$button="<input type=button name=button value='选择' onclick=\"javascript:".$bturl."\">";
		}
		else
		{
			$bturl="InsertFile('".$file."','".addslashes($filename)."','".$fileid."','".$filesize."','".$filetype."','','".$type."');";
			$button="<input type=button name=button value='插入' onclick=\"javascript:".$bturl."\">";
		}
	}
	$retr['button']=$button;
	$retr['bturl']=$bturl;
	return $retr;
}

$classid=(int)$_GET['classid'];
$infoid=(int)$_GET['infoid'];
$filepass=(int)$_GET['filepass'];
$type=(int)$_GET['type'];
$modtype=(int)$_GET['modtype'];
$fstb=(int)$_GET['fstb'];
$doing=(int)$_GET['doing'];
$field=RepPostVar($_GET['field']);
$tranfrom=ehtmlspecialchars($_GET['tranfrom']);
$fileno=ehtmlspecialchars($_GET['fileno']);
$doecmspage=RepPostStr($_GET['doecmspage'],1);
if(empty($field))
{
	$field="ecms";
}
include('eshoweditor.php');

$search="&classid=$classid&infoid=$infoid&filepass=$filepass&type=$type&modtype=$modtype&fstb=$fstb&doing=$doing&tranfrom=$tranfrom&field=$field&fileno=$fileno&doecmspage=$doecmspage".$ecms_hashur['ehref'];

//基目录
// $basepath=eReturnEcmsMainPortPath()."d/file/pic/shengzhiwuyuan23";//moreport
$basepath=eReturnEcmsMainPortPath()."d/file/pic/";//moreport
$filepath=ehtmlspecialchars($_GET['filepath']);
if(strstr($filepath,".."))
{
	$filepath="";
}
$filepath=eReturnCPath($filepath,'');
$openpath=$basepath."/".$filepath;
if(!file_exists($openpath))
{
	$openpath=$basepath;
}

// $hand=@opendir($openpath);
db_close();
$empire=null;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>选择文件</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<script>
function InsertFile(filename,fname,fileid,filesize,filetype,fileno,dotype){
	var vstr="";
	if(dotype!=undefined)
	{
		vstr=showModalDialog("infoeditor/epage/insertfile.php?<?=$ecms_hashur['ehref']?>&ecms="+dotype+"&fname="+fname+"&fileid="+fileid+"&filesize="+filesize+"&filetype="+filetype+"&filename="+filename, "", "dialogWidth:45.5em; dialogHeight:27.5em; status:0");
		if(vstr==undefined)
		{
			return false;
		}
	}
	parent.opener.DoFile(vstr);
	parent.window.close();
}
function TInsertFile(vstr){
	parent.opener.DoFile(vstr);
	parent.window.close();
}
//选择字段
function ChangeFile1(obj,str){
<?php
if(strstr($field,'.'))
{
?>
	parent.<?=$field?>.value=str;
<?php
}
else
{
?>
	if(obj==1)
	{
		parent.opener.document.add.<?=$field?>.value=str;
	}
	else
	{
		parent.opener.document.form1.<?=$field?>.value=str;
	}
<?php
}
?>
	parent.window.close();
}
//编辑器选择
function EditorChangeFile(fileurl,filename,filetype,filesize,name){
	var returnstr;
	returnstr=fileurl;
	<?php
	$useeditor_r=ECMS_EditorReturnType('');
	if($useeditor_r['ftype']==0)
	{
	?>
	returnstr=fileurl+'##'+name+'##'+filesize;
	<?php
	}
	?>
	window.parent.opener.<?=$useeditor_r['jsfun']?>(returnstr);
	parent.window.close();
}
//变量层选择
function SFormIdChangeFile(name,url,filesize,filetype,idvar){
	parent.opener.doSpChangeFile(name,url,filesize,filetype,idvar);
	parent.window.close();
}
//全选
function CheckAll(form){
  for(var i=0;i<form.elements.length;i++)
  {
    var e = form.elements[i];
    if (e.name != 'chkall')
       e.checked = form.chkall.checked;
    }
}
//重新载入页面
function ReloadChangeFilePage(){
	self.location.reload();
}

</script>
</head>

<body>
<table width="100%" height="30" border="0" align="center" cellpadding="3" cellspacing="1" style="position:fixed;" bgcolor="c9f1fe">
  <tr> 
    <td> 当前目录：<strong>/ 
      <?=$filepath?>
      </strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[<a href="#ecms" onclick="javascript:history.go(-1);">返回上一页</a>]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="selall">全选</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="selzero">全不选</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="selInvert">反选</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="selmore">向下连选</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="sel">选择</button>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;<button class="picopti">压缩图片</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="picsmall">生成缩略图</button></td>
  </tr>
</table>
<br><br>
  
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <form name="dofile" method="post" action="../ecmsfile.php">
  <?=$ecms_hashur['form']?>
    <input name="enews" type="hidden" id="enews" value="DelPathFile">
	<input type=hidden name=doecmspage value="<?=$doecmspage?>">
    <tr class="header">
      <td><div align="center">选择</div></td>
	  <?php
	  if($filepath){
		  echo <<<GUIBOWEB
		  		<td><div align="center">多选</div></td>
		  		<td><div align="center">缩略图</div></td>		
GUIBOWEB;
	  }
	  ?>
      <?=$imgtb?>
      <td height="25"><div align="center">文件名</div></td>
      <td><div align="center">大小</div></td>
      <td><div align="center">类型</div></td>
      <td><div align="center">修改时间</div></td>
    </tr>
    <?php
	$gpath = preg_replace("/e\/.*/i","",dirname(__FILE__));
	$arr = scandir($gpath."d/file/pic/".$filepath);
	$efileurl=eReturnFileUrl(1)."pic/";  
	// while($file=@readdir($hand))
	foreach($arr as $key=>$val)
	{
		$file = $val;
		if(empty($filepath))
		{
			$truefile=$file;
		}
		else
		{
			$truefile=$filepath."/".$file;
			$thumbfile= $filepath."/thumb/".$file;
		}
		if($file=="."||$file=="..")
		{
			continue;
		}
		if($file=="thumb")
		{
			continue;
		}
		//目录
		$pathfile=$openpath."/".$file;
		if(is_dir($pathfile))
		{
			$filelink="'filep.php?filepath=".$truefile.$search."'";
			$filename=$file; 
			$img="../../data/images/dir/folder.gif";
			$target="";
			//发布时间
			$ftime=@filemtime($pathfile);
			$filetime=date("Y-m-d H:i:s",$ftime);
			$filesize='<目录>';
			$filetype='文件夹';
			$button="";
		}
		//文件
		else
		{
			$filelink="'".eReturnFileUrl()."pic/".$truefile."'";
			$thumb="'".eReturnFileUrl()."pic/".$thumbfile.".jpg'";
			$filename=$file; 
			$ftype=GetFiletype($file);
			$img='../../data/images/dir/'.substr($ftype,1,strlen($ftype))."_icon.gif";
			if(!file_exists($img))
			{
				$img='../../data/images/dir/unknown_icon.gif';
			}
			$target=" target='_blank'";
			//发布时间
			$ftime=@filemtime($pathfile);
			$filetime=date("Y-m-d H:i:s",$ftime);
			//文件大小
			$fsize=@filesize($pathfile);
			$filesize=ChTheFilesize($fsize);
			//文件类型
			if(strstr($ecms_config['sets']['tranpicturetype'],','.$ftype.','))
			{
				$filetype='图片';
				$imgs = <<<GUIBOWEB
				 <td  height="25">
					<a href={$filelink}{$target}> <img src={$thumb} width="130" height="120"></a>
					</td>
GUIBOWEB;

				$checkb = <<<GUIBOWEB
				 <td width="5%">
				  <div align="center">
					<input type="checkbox" name="check" class="check" value="<div class='pcimgs'><img data-original={$filelink} /></div><br/>" />
					</div></td>
GUIBOWEB;
			}
			elseif(strstr($ecms_config['sets']['tranflashtype'],','.$ftype.','))
			{
				$filetype='FLASH';
			}
			elseif(strstr($ecms_config['sets']['mediaplayertype'],','.$ftype.',')||strstr($ecms_config['sets']['realplayertype'],','.$ftype.','))
			{
				$filetype='视频';
			}
			else
			{
				$filetype='附件';
			}
			$furl=$efileurl.$truefile; 
			$buttonr=ToReturnDoFilepButton($doing,$tranfrom,$field,$furl,$file,0,$filesize,$ftype,'',$type);
			$button=$buttonr['button'];
			$buttonurl=$buttonr['bturl'];
		}
	 ?>
	
    <tr bgcolor="#FFFFFF"> 
      <td width="9%"> 
        <div align="center">
          <?=$button?>
        </div></td>
	   <?=$checkb?>
	   <?=$imgs?>
      <td width="39%" height="25"><a href=<?=$filelink?><?=$target?>> 
        <?=$filename?>
        </a></td>
      <td width="20%"> 
        <div align="center"><?=$filesize?></div></td>
      <td width="11%"> 
        <div align="center"><?=$filetype?></div></td>
      <td width="21%"> 
        <div align="center"><?=$filetime?></div></td>
    </tr>
    <?
	}
	@closedir($hand);
	?>
  </form>
</table> 
</body>
<script src="/template/pc/skin/js/jquery-1.7.2.min.js" type="text/javascript"></script>
<script type="text/javascript">
	$(function(){
		$(".selall").click(function(){
			$(".check").prop({checked:true});
		})
		$(".selzero").click(function(){
			$(".check").prop({checked:false}); 
		})
		$(".selInvert").click(function(){
			$(".check").each(function(){ //反选 
			         if($(this).prop("checked")){ 
			             $(this).prop("checked",false); 
			          } else{ 
			             $(this).prop("checked",true); 
			         } 
			    });    
		})
		$selstart = 0;
		$(".selmore").click(function(){
			$(".check").each(function(){ 
				if($(this).prop("checked")){
					if($selstart == 0){
						$selstart =1;
					}else{
						$selstart = 0;
					}
					
				} 
				if($selstart ==1){
					$(this).prop("checked",true); 
				}
			});   
		})
		$(".sel").click(function(){
			$images = "";
			$i=0;
			$(".check").each(function(){
				if($(this).prop("checked")){
					$file = $(this).val();
					$i++;
					if($i <4){
						$images += $(this).val().replace("data-original","src");
					}else{
						$images += $(this).val();
					}
					
				} 
			}); 
			copyToClipboard($images);
			alert("复制图片代码成功，现在可粘贴到内容页面中!");
			parent.window.close();
		})
		
		$(".picopti").click(function(){
			$(this).text("请稍候，正在优化图片大小,完成后会自动刷新页面...");
			$url = window.location.href.split("&");
			$param = $url[$url.length-1];
			$.ajax({
				type:"post",
				url:"thumb.php?"+$param,
				data:{"filepath":"/d/file/pic/<?=$filepath?>/","method":"optimize"},
				success:function(txt){
					window.location.reload();
				}
			})
		})
		
		$(".picsmall").click(function(){ 
			$(this).text("请稍候，正在生成缩略图片,完成后会自动刷新页面...");
			$url = window.location.href.split("&");
			$param = $url[$url.length-1];
			$.ajax({
				type:"post",
				url:"thumb.php?"+$param,
				data:{"filepath":"/d/file/pic/<?=$filepath?>/","method":"thumb"},
				success:function(txt){
					window.location.reload();
				}
			})
		})
		
	})
	function copyToClipboard(text) {
	    var sampleTextarea = document.createElement("textarea");
	    document.body.appendChild(sampleTextarea);
	    sampleTextarea.value = text; //save main text in it
	    sampleTextarea.select(); //select textarea contenrs
	    document.execCommand("copy");
	    document.body.removeChild(sampleTextarea);
	}
	
</script>
</html>
