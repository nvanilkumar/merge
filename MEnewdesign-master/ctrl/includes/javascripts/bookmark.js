
function openBookmarkSite(intBookmarkSite, strSourceUrl, strTitle, strDescription){
	var strTargetUrl = "";
	
	switch(intBookmarkSite){
		case 1:
			strTargetUrl = "http://www.backflip.com/add_page_pop.ihtml?url=" + strSourceUrl + "&title=" + strTitle;
			break;
		case 2:
			strTargetUrl = "http://www.blinklist.com/index.php?Action=Blink/addblink.php&Url=" + strSourceUrl + "&Title=" + strTitle + "&Description=" + strDescription;
			break;
		case 3:
			strTargetUrl = "http://del.icio.us/post?url=" + strSourceUrl + "&title=" + strTitle;
			break;
		case 4:
			strTargetUrl = "http://digg.com/submit?phase=2&url=" + strSourceUrl + "&title=" + strTitle + "&bodytext=" + strDescription + "&topic=world_news";
			break;
		case 5:
			strTargetUrl = "http://www.feedmarker.com/admin.php?url=" + strSourceUrl + "&title=" + strTitle + "&description=" + strDescription;
			break;
		case 6:
			strTargetUrl = "http://www.furl.net/storeIt.jsp?u=" + strSourceUrl + "&t=" + strTitle + "&c=" + strDescription;
			break;
		case 7:
			strTargetUrl = "http://www.google.com/bookmarks/mark?op=edit&output=popup&bkmk=" + strSourceUrl + "&title=" + strTitle;
			break;			
		case 8:
			strTargetUrl = "http://jots.com/?cmd=do_post&show_post=true&url=" + strSourceUrl + "&title=" + strTitle + "&description=" + strDescription;
			break;
		case 9:
			strTargetUrl = "http://www.linkroll.com/index.php?action=insertLink&url=" + strSourceUrl + "&title=" + strTitle;
			break;
		case 10:
			strTargetUrl = "http://favorites.live.com/quickadd.aspx?url=" + strSourceUrl + "&title=" + strTitle;
			break;
		case 11:
			strTargetUrl = "http://ma.gnolia.com/bookmarklet/popup/add?url=" + strSourceUrl + "&title=" + strTitle + "&description=" + strDescription;
			break;
		case 12:
			strTargetUrl = "http://www.rawsugar.com/tagger/?turl=" + strSourceUrl + "&tttl=" + strTitle + "&note=" + strDescription;
			break;
		case 13:
			strTargetUrl = "http://reddit.com/submit?url=" + strSourceUrl + "&title=" + strTitle;
			break;
		case 14:
			strTargetUrl = "http://www.shadows.com/shadow/?page=" + strSourceUrl;
			break;
		case 15:
			strTargetUrl = "http://www.simpy.com/simpy/LinkAdd.do?href=" + strSourceUrl + "&title=" + strTitle + "&note=" + strDescription;
			break;
		case 16:
			strTargetUrl = "http://www.spurl.net/spurl.php?v=3&url=" + strSourceUrl + "&title=" + strTitle + "&blocked=" + strDescription;
			break;
		case 17:
			strTargetUrl = "http://www.stumbleupon.com/submit?url=" + strSourceUrl + "&title=" + strTitle;
			break;			
		case 18:
			strTargetUrl = "http://wink.com/_/tag?url=" + strSourceUrl + "&ttl=" + strTitle + "&d=" + strDescription;
			break;
		case 19:
			strTargetUrl = "http://myweb.search.yahoo.com/myresults/bookmarklet?u=" + strSourceUrl + "&t=" + strTitle + "&d=" + strDescription;
			break;
	}
	
	if(strTargetUrl.length > 0)
		window.open(strTargetUrl, null, "top=50, left=50, status=yes, toolbar=yes, menubar=yes, location=yes, resizable=yes, scrollbars=yes");
}

function simplesearch()
{
	var txtSearch = document.frmSearch.txtSearch.value;
	var selSearchType = document.frmSearch.selSearchType.value;
	if(txtSearch.length > 0)
	{
		if(selSearchType==3)
		{
		   document.frmSearch.method="post";
		   document.frmSearch.action="search_portfolios.php?txtKeywords=" + txtSearch + "&process=search";
		   document.frmSearch.submit();
			//window.location= "search_portfolios.php?txtKeywords=" + txtSearch + "&process=search";
		}
		else if(selSearchType==2)
		{
			document.frmSearch.method="post";
			document.frmSearch.action="search_providers.php?txtKeywords=" + txtSearch + "&process=search";
			document.frmSearch.submit();
			//window.location= "search_providers.php?txtKeywords=" + txtSearch + "&process=search";
		}
		else if(selSearchType==1)
		{
			document.frmSearch.method="post";
			document.frmSearch.action="search_projects.php?txtKeywords=" + txtSearch + "&process=results";
			document.frmSearch.submit();
			//location.href= "search_projects.php?txtKeywords=" + txtSearch + "&process=search";
		}
	}
}

function bookmarkPage()
{
	var intBookmarkSite = parseInt(document.getElementById('selBookmark').value);
	var strSourceUrl = "http%3a%2f%2fwww.ifreelance.com%2ffind%2fportfolios%2fbrowse.aspx%3fba%3d2%26sc%3d57";
	var strTitle = "Find+Freelance+3D+Animator+Portfolios+-+iFreelance.com";
	var strDescription = "Brochure+Design+Freelance+Jobs.+Freelance+Audio+Video+Editors.+Presentation+Design+Freelance+Jobs.+Freelance+Business+Card+Designers.+Freelance+Illustrators.+Flash+Design+Freelance+Jobs.+Freelance+User+Interface+Designers.+Freelance+Poster+Designers.+Album+CD+DVD+Design+Freelance+Jobs.+Business+Document+Design+Freelance+Jobs.+";
	openBookmarkSite(intBookmarkSite, strSourceUrl, strTitle, strDescription);
}