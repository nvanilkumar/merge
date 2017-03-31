 <form name="newad" method="post" enctype="multipart/form-data"  action="">
 <table width="50%" align="center">
 	<tr><td colspan="2" class="headtitle"><div align="left">Edit Advertisement</div></td>
 	</tr>
	<tr><td colspan="2"><div align="left"></div></td></tr>
	<tr><td colspan="2"><div align="left"><img src="http://meraevents.com/bigbang/<?=$sql_file_row['filepath']?>"></div></td></tr>
 	<tr><td>
 	  
 	    <input type="file" name="image">
      </td></tr>
 	<tr><td>
 	    <input name="Submit" type="submit" value="Upload New Ad">
		<input name="filename" type="hidden" value="<?=$sql_file_row['filename']?>">
      </td></tr>
 </table>	
 </form>
