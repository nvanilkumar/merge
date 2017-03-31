fixMozillaZIndex=true; //Fixes Z-Index problem  with Mozilla browsers but causes odd scrolling problem, toggle to see if it helps
_menuCloseDelay=500;
_menuOpenDelay=150;
_subOffsetTop=2;
_subOffsetLeft=-2;




with(menuStyle=new mm_style()){
bordercolor="#296488";
borderstyle="solid";
borderwidth=1;
fontfamily="Verdana, Tahoma, Arial";
fontsize="65%";
fontstyle="normal";
headerbgcolor="#ffffff";
headercolor="#000000";
offbgcolor="#DCE9F0";
offcolor="#515151";
onbgcolor="#4F8EB6";
oncolor="#ffffff";
outfilter="randomdissolve(duration=0.3)";
overfilter="Fade(duration=0.2);Alpha(opacity=90);Shadow(color=#777777', Direction=135, Strength=5)";
padding=5;
pagebgcolor="#82B6D7";
pagecolor="black";
separatorcolor="#2D729D";
separatorsize=3;
//subimage="http://www.milonic.com/menuimages/arrow.gif";
subimagepadding=2;
}

with(milonic=new menuname("Main Menu")){
alwaysvisible=1;
orientation="horizontal";
style=menuStyle;
aI("fontsize=11px;showmenu=Samples8;text=Home;url=http://www.trafficpresident.com/index.php");
aI("fontsize=11px;showmenu=Samples1;text=Kitchen");
aI("fontsize=11px;showmenu=Samples2;text=Laundry");
aI("fontsize=11px;showmenu=Samples3;text=Electronics");
aI("fontsize=11px;showmenu=Samples4;text=Video;url=http://www.trafficpresident.com/video.php");
aI("fontsize=11px;showmenu=Samples5;text=About Us;url=http://www.trafficpresident.com/aboutus.php");
aI("fontsize=11px;showmenu=Samples6;text=Store Locator;url=http://www.trafficpresident.com/store_locator.php");
}

with(milonic=new menuname("Samples1")){
overflow="scroll";
style=menuStyle;
aI("text=Refrigerators;url=http://www.trafficpresident.com/product_detail.php?prodcat=Refrigerators;")
aI("text=Freezers;url=http://www.trafficpresident.com/product_detail.php?prodcat=Freezers;")
aI("text=Dishwashers;url=http://www.trafficpresident.com/product_detail.php?prodcat=Dishwashers;")
aI("text=Ranges;url=http://www.trafficpresident.com/product_detail.php?prodcat=Ranges;")
aI("text=Cooktops;url=http://www.trafficpresident.com/product_detail.php?prodcat=Cooktops;")
aI("text=Ovens;url=http://www.trafficpresident.com/product_detail.php?prodcat=Ovens;")
aI("text=Microwaves;url=http://www.trafficpresident.com/product_detail.php?prodcat=Microwaves;")
aI("text=Wine Coolers;url=http://www.trafficpresident.com/product_detail.php?prodcat=Wine Coolers;")

}

with(milonic=new menuname("Samples2")){
overflow="scroll";
style=menuStyle;
aI("text=Washers;url=http://www.trafficpresident.com/product_detail.php?prodcat=Washers;")
aI("text=Dryers;url=http://www.trafficpresident.com/product_detail.php?prodcat=Dryers;")
}

with(milonic=new menuname("Samples3")){
overflow="scroll";
style=menuStyle;
aI("text=Televisions;url=http://www.trafficpresident.com/product_detail.php?prodcat=Televisions;")
aI("text=DVD;url=http://www.trafficpresident.com/product_detail.php?prodcat=DVD;")
aI("text=TV Combos;url=http://www.trafficpresident.com/product_detail.php?prodcat=TV Combos;")
aI("text=Projectors;url=http://www.trafficpresident.com/product_detail.php?prodcat=Projectors;")
aI("text=Flat Panel TV;url=http://www.trafficpresident.com/product_detail.php?prodcat=Flat Panel TV;")
aI("text=Projection TV;url=http://www.trafficpresident.com/product_detail.php?prodcat=Projection TV;")
aI("text=TV Furniture;url=http://www.trafficpresident.com/product_detail.php?prodcat=TV Furniture;")
aI("text=Video Recorders;url=http://www.trafficpresident.com/product_detail.php?prodcat=Video Recorders;")

}

with(milonic=new menuname("Samples5")){
overflow="scroll";
style=menuStyle;
aI("text=Our Partners;url=http://www.trafficpresident.com/partners.php;")
aI("text=Contact Us;url=http://www.trafficpresident.com/contactus.php;")
aI("text=Privacy Policy;url=http://www.trafficpresident.com/privacypolicy.php;")
aI("text=Sitemap;url=http://www.trafficpresident.com/sitemap.php;")
aI("text=FAQ;url=http://www.trafficpresident.com/faq.php;")
}

/*with(milonic=new menuname("Samples6")){
overflow="scroll";
style=menuStyle;
aI("text=Dealer location;showmenu=Samples7")}*/

with(milonic=new menuname("Samples7")){
overflow="scroll";
style=menuStyle;
aI("text=Product Search")
aI("text=Dealer All locations")
aI("text=Video")
aI("text=Dealer Map locations")
aI("text=Dealer Special Offers;url=http://www.trafficpresident.com/special_offers.php;")
}

drawMenus();

