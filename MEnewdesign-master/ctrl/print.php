<html><head><title>Meraevent</title>
</head>
<body onLoad="printThis();">
<table cellpadding=0 cellspacing=0 ><tr><td class="normal">
<DIV id="PrintArea"></div>
</td></tr></table>
<script javascript>
function printThis()
{
document.getElementById('PrintArea').innerHTML = window.opener.document.getElementById('code').innerHTML;
	window.print();
}	
</script>
</body>
</html>