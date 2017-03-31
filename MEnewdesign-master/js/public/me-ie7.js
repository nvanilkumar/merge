/* To avoid CSS expressions while still supporting IE 7 and IE 6, use this script */
/* The script tag referencing this file must be placed before the ending body tag. */

/* Use conditional comments in order to target IE 7 and older:
	<!--[if lt IE 8]><!-->
	<script src="ie7/ie7.js"></script>
	<!--<![endif]-->
*/

(function() {
	function addIcon(el, entity) {
		var html = el.innerHTML;
		el.innerHTML = '<span style="font-family: \'mediface-iconfont\'">' + entity + '</span>' + html;
	}
	var icons = {
		'mficon-access-control': '&#xe600;',
		'mficon-chat': '&#xe601;',
		'mficon-doctor-hover': '&#xe602;',
		'mficon-doctor-normal': '&#xe603;',
		'mficon-dropdown': '&#xe604;',
		'mficon-fb': '&#xe605;',
		'mficon-find-doctor': '&#xe606;',
		'mficon-find-facility': '&#xe607;',
		'mficon-health-record': '&#xe608;',
		'mficon-info': '&#xe609;',
		'mficon-insurance-details': '&#xe60a;',
		'mficon-login': '&#xe60b;',
		'mficon-medical-info': '&#xe60c;',
		'mficon-medical-record': '&#xe60d;',
		'mficon-mediface': '&#xe60e;',
		'mficon-news': '&#xe60f;',
		'mficon-next': '&#xe610;',
		'mficon-patient-hover': '&#xe611;',
		'mficon-patient-normal': '&#xe612;',
		'mficon-personal-detail': '&#xe613;',
		'mficon-pwd': '&#xe614;',
		'mficon-request': '&#xe615;',
		'mficon-searchbylocation': '&#xe616;',
		'mficon-settings': '&#xe617;',
		'mficon-symptom': '&#xe618;',
		'mficon-twitter': '&#xe619;',
		'mficon-user': '&#xe61a;',
		'0': 0
		},
		els = document.getElementsByTagName('*'),
		i, c, el;
	for (i = 0; ; i += 1) {
		el = els[i];
		if(!el) {
			break;
		}
		c = el.className;
		c = c.match(/mficon-[^\s'"]+/);
		if (c && icons[c[0]]) {
			addIcon(el, icons[c[0]]);
		}
	}
}());
