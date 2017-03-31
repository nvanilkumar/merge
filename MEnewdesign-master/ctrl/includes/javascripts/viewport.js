//http://msdn.microsoft.com/library/default.asp?url=/workshop/author/om/measuring.asp
function Viewport(){
	var _this = this;
	
	this.width;
	this.height;
	this.scrollLeft;
	this.scrollTop;
	
	function init(){
		setSize();
		setScroll();
	}

	function setSize(){
		if(document.documentElement.offsetWidth){
			//v-scroll always visible in ie
			_this.width = (navigator.userAgent.indexOf("MSIE") != -1 ? document.documentElement.offsetWidth - 22 : document.documentElement.offsetWidth);				
			_this.height = document.documentElement.offsetHeight;
		}else{
			_this.width = window.innerWidth;
			_this.height = window.innerHeight;
		}
	}
	
	function setScroll(){
		if(navigator.userAgent.indexOf("MSIE") != -1){
			if(document.compatMode.toLowerCase() != "css1compat"){
				_this.scrollLeft = document.body.scrollLeft;
				_this.scrollTop = document.body.scrollTop;
			}else{
				_this.scrollLeft = document.documentElement.scrollLeft;
				_this.scrollTop = document.documentElement.scrollTop;				
			}
		}else{
			_this.scrollLeft = window.pageXOffset;
			_this.scrollTop = window.pageYOffset;							
		}
	}
	
	init();
}