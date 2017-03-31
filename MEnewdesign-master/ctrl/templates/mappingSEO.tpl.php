<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title>MeraEvents - Admin Panel - Manage SEO Data</title>
    <link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
    <link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
    <script language="javascript" src="<?=_HTTP_SITE_ROOT;?>/js/public/jQuery.js"></script>    
    <script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script> 
<script>
function validateEventUrlForm(form)
{
            if(form=="url")
            {
               
                var url =document.getElementById('url').value;
                
                if(Trim(url).length==0)
                {
		alert("Please enter a URL");
		document.getElementById('url').focus();
		return false;
                 }
	else
	{
		$.get('includes/ajaxSeoTags.php',{eventIDChk:1,url:url,devChk:1}, function(data){
			window.location=data;
			
		});
	}
            }
	return false;
}


function Trim(str)
{   
      while(str.charAt(0) == (" ") )
      {  
           str = str.substring(1);
      }
      while(str.charAt(str.length-1) == " " )
      {  
           str = str.substring(0,str.length-1);
      }
      return str;
}

function validateEventAddForm(f)
{
	var mtitle=f.mtitle.value;
	var mkeywords=f.mkeywords.value;
	var mdescription=f.mdescription.value;
        var URL=f.sURL.value;
        var maptype=f.maptype.value;
        var pageType=f.pageType.value;
        var murl=f.murl.value;
        var params=f.params.value;
        
                if(mtitle == '')
		{
			alert('Please Enter Title');
			document.getElementById('mtitle').focus();
			return false;
		}
                if(mkeywords == '')
		{
			alert('Please Enter keywords');
			document.getElementById('mkeywords').focus();
			return false;
		}
                if(mdescription == '')
		{
			alert('Please Enter description');
			document.getElementById('mdescription').focus();
			return false;
		}
                if(maptype == '')
		{
			alert('Please Enter maptype (include/redirect)');
			document.getElementById('maptype').focus();
			return false;
		}
                if(maptype != 'redirect'){
                    if(pageType == '')
                    {
			alert('Please Enter controller');
			document.getElementById('pageType').focus();
			return false;
                    }
                    if(params == '')
                    {
			alert('Please Enter Parameters');
			document.getElementById('params').focus();
			return false;
                    }
                }
                if(murl == '')
		{
			alert('Please Enter mapping method/redirection url');
			document.getElementById('murl').focus();
			return false;
		}
  
	return true;
	
}

</script>   
</head>    
<body style="background-image: url(images/background.gif); background-repeat:repeat-x; margin-top: 0px; margin-left: 0px; margin-right:0px; padding:0px">
    <?php include('templates/header.tpl.php'); ?>                
    </div>
    <table style="width:100%; height:495px;" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width:150px; vertical-align:top; background-image:url(images/menugradient.jpg); background-repeat:repeat-x">
                <?php include('templates/left.tpl.php'); ?>
            </td>
            <td style="vertical-align:top">
                <div  id="divMainPage" style="margin-left: 10px; margin-right:5px">
<!-------------------------------SEO types PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
      document.getElementById('ans21').style.display='block';
</script>

<div align="center" style="width:100%">&nbsp;</div>
<div align="center" style="width:100%" class="headtitle">SEO Data</div>
<p align="center" style="width:100%"><?php if($msg!='') echo $msg; ?></p>

<div>

<form action="#"  onsubmit="return validateEventUrlForm('url')">
	<table>
        <td>URL : <?php echo $_SERVER["HTTP_HOST"].'/'; ?></td><td><input type="text" name="url" id="url" value="<?php echo $URL; ?>" /></td>
        <td><input type="submit" name="Sub" value="Submit" /></td>
    </table>
</form>
</div>


<div id="seodataform" style="display:<?php if(isset($_REQUEST['edit']) || strlen($URL)>0 )echo 'block';else echo 'none'; ?>">
<form method="post" action="" onsubmit="return validateEventAddForm(this)">
<table>
    <tr>
    <td>Meta Title</td><td><input type="text" name="mtitle" id="mtitle" size="80" value="<?php echo stripslashes($edidSEOdata[0]['seotitle']); ?>" /></td>
    </tr>
    <tr>
    	<td>Meta Keywords</td><td><textarea name="mkeywords" id="mkeywords" cols="60"><?php echo stripslashes($edidSEOdata[0]['seokeywords']); ?></textarea><br /><span style="color:#000;">Enter List of Keywords seperated by a comma (,)</span></td>
    </tr>
    <tr>
    	<td>Meta Description</td><td><textarea name="mdescription" id="mdescription" cols="60"><?php echo stripslashes($edidSEOdata[0]['seodescription']); ?></textarea></td>
    </tr>
    <tr>
    	<td>Canonical URL </td><td><input type="text" name="sURL" id="sURL" size="80" value="<?php echo stripslashes(isset($edidSEOdata[0]['conanicalurl'])?$edidSEOdata[0]['conanicalurl']:$edidSEOdata[0]['canonicalurl']); ?>" /></td>
    </tr>
     <tr>
    	<td>Mapping Type</td><td><input type="text" name="maptype" id="maptype" size="80" value="<?php echo stripslashes($edidSEOdata[0]['mappingtype']); ?>" /></td>
    </tr>
    <tr>
    	<td>Controller Name</td><td><input type="text" name="pageType" id="pageType" size="80" value="<?php echo stripslashes($edidSEOdata[0]['pagetype']); ?>" /></td>
    </tr>
    <tr>
    	<td>Mapping Method/URL</td><td><input type="text" name="murl" id="murl" size="80" value="<?php echo stripslashes($edidSEOdata[0]['mappingurl']); ?>" /></td>
    </tr>
    <tr>
        <td>Parameters</td><td><textarea name="params" id="params" cols="60"><?php echo stripslashes($edidSEOdata[0]['params']); ?></textarea></td>
    </tr>

    <tr>
    	<td colspan="2">
            <input type="hidden" name="addSEO" value="1" />
            <input type="hidden" name="editid" value="<?php if($_REQUEST['edit'])echo $_REQUEST['edit']; ?>" />
            <input type="hidden" name="seomapid" value="<?php echo $edidSEOdata[0]['id']; ?>" />
            <input type="submit" value="<?php  if($_REQUEST['edit'] || strlen($edidSEOdata[0]['seokeywords']) > 0)echo 'MODIFY'; else echo 'ADD'; ?>" />
        </td>
    </tr>
</table>
</form>

</div>

<div align="center" style="width:100%">&nbsp;</div>

<!-------------------------------SEO types PAGE ENDS HERE--------------------------------------------------------------->
                </div>
            </td>
        </tr>
    </table>
</div>    
</body>
</html>
