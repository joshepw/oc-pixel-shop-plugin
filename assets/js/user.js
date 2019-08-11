function md5cycle(x, k) {
	var a = x[0], b = x[1], c = x[2], d = x[3];

	a = ff(a, b, c, d, k[0], 7, -680876936);
	d = ff(d, a, b, c, k[1], 12, -389564586);
	c = ff(c, d, a, b, k[2], 17,  606105819);
	b = ff(b, c, d, a, k[3], 22, -1044525330);
	a = ff(a, b, c, d, k[4], 7, -176418897);
	d = ff(d, a, b, c, k[5], 12,  1200080426);
	c = ff(c, d, a, b, k[6], 17, -1473231341);
	b = ff(b, c, d, a, k[7], 22, -45705983);
	a = ff(a, b, c, d, k[8], 7,  1770035416);
	d = ff(d, a, b, c, k[9], 12, -1958414417);
	c = ff(c, d, a, b, k[10], 17, -42063);
	b = ff(b, c, d, a, k[11], 22, -1990404162);
	a = ff(a, b, c, d, k[12], 7,  1804603682);
	d = ff(d, a, b, c, k[13], 12, -40341101);
	c = ff(c, d, a, b, k[14], 17, -1502002290);
	b = ff(b, c, d, a, k[15], 22,  1236535329);

	a = gg(a, b, c, d, k[1], 5, -165796510);
	d = gg(d, a, b, c, k[6], 9, -1069501632);
	c = gg(c, d, a, b, k[11], 14,  643717713);
	b = gg(b, c, d, a, k[0], 20, -373897302);
	a = gg(a, b, c, d, k[5], 5, -701558691);
	d = gg(d, a, b, c, k[10], 9,  38016083);
	c = gg(c, d, a, b, k[15], 14, -660478335);
	b = gg(b, c, d, a, k[4], 20, -405537848);
	a = gg(a, b, c, d, k[9], 5,  568446438);
	d = gg(d, a, b, c, k[14], 9, -1019803690);
	c = gg(c, d, a, b, k[3], 14, -187363961);
	b = gg(b, c, d, a, k[8], 20,  1163531501);
	a = gg(a, b, c, d, k[13], 5, -1444681467);
	d = gg(d, a, b, c, k[2], 9, -51403784);
	c = gg(c, d, a, b, k[7], 14,  1735328473);
	b = gg(b, c, d, a, k[12], 20, -1926607734);

	a = hh(a, b, c, d, k[5], 4, -378558);
	d = hh(d, a, b, c, k[8], 11, -2022574463);
	c = hh(c, d, a, b, k[11], 16,  1839030562);
	b = hh(b, c, d, a, k[14], 23, -35309556);
	a = hh(a, b, c, d, k[1], 4, -1530992060);
	d = hh(d, a, b, c, k[4], 11,  1272893353);
	c = hh(c, d, a, b, k[7], 16, -155497632);
	b = hh(b, c, d, a, k[10], 23, -1094730640);
	a = hh(a, b, c, d, k[13], 4,  681279174);
	d = hh(d, a, b, c, k[0], 11, -358537222);
	c = hh(c, d, a, b, k[3], 16, -722521979);
	b = hh(b, c, d, a, k[6], 23,  76029189);
	a = hh(a, b, c, d, k[9], 4, -640364487);
	d = hh(d, a, b, c, k[12], 11, -421815835);
	c = hh(c, d, a, b, k[15], 16,  530742520);
	b = hh(b, c, d, a, k[2], 23, -995338651);

	a = ii(a, b, c, d, k[0], 6, -198630844);
	d = ii(d, a, b, c, k[7], 10,  1126891415);
	c = ii(c, d, a, b, k[14], 15, -1416354905);
	b = ii(b, c, d, a, k[5], 21, -57434055);
	a = ii(a, b, c, d, k[12], 6,  1700485571);
	d = ii(d, a, b, c, k[3], 10, -1894986606);
	c = ii(c, d, a, b, k[10], 15, -1051523);
	b = ii(b, c, d, a, k[1], 21, -2054922799);
	a = ii(a, b, c, d, k[8], 6,  1873313359);
	d = ii(d, a, b, c, k[15], 10, -30611744);
	c = ii(c, d, a, b, k[6], 15, -1560198380);
	b = ii(b, c, d, a, k[13], 21,  1309151649);
	a = ii(a, b, c, d, k[4], 6, -145523070);
	d = ii(d, a, b, c, k[11], 10, -1120210379);
	c = ii(c, d, a, b, k[2], 15,  718787259);
	b = ii(b, c, d, a, k[9], 21, -343485551);

	x[0] = add32(a, x[0]);
	x[1] = add32(b, x[1]);
	x[2] = add32(c, x[2]);
	x[3] = add32(d, x[3]);

}

function cmn(q, a, b, x, s, t) {
	a = add32(add32(a, q), add32(x, t));
	return add32((a << s) | (a >>> (32 - s)), b);
}

function ff(a, b, c, d, x, s, t) {
	return cmn((b & c) | ((~b) & d), a, b, x, s, t);
}

function gg(a, b, c, d, x, s, t) {
	return cmn((b & d) | (c & (~d)), a, b, x, s, t);
}

function hh(a, b, c, d, x, s, t) {
	return cmn(b ^ c ^ d, a, b, x, s, t);
}

function ii(a, b, c, d, x, s, t) {
	return cmn(c ^ (b | (~d)), a, b, x, s, t);
}

function md51(s) {
	txt = '';
	var n = s.length,
	state = [1732584193, -271733879, -1732584194, 271733878], i;
	for (i=64; i<=s.length; i+=64) {
		md5cycle(state, md5blk(s.substring(i-64, i)));
	}
	s = s.substring(i-64);
	var tail = [0,0,0,0, 0,0,0,0, 0,0,0,0, 0,0,0,0];
	for (i=0; i<s.length; i++)
		tail[i>>2] |= s.charCodeAt(i) << ((i%4) << 3);
	tail[i>>2] |= 0x80 << ((i%4) << 3);
	if (i > 55) {
		md5cycle(state, tail);
		for (i=0; i<16; i++) tail[i] = 0;
	}
tail[14] = n*8;
md5cycle(state, tail);
return state;
}

/* there needs to be support for Unicode here,
 * unless we pretend that we can redefine the MD-5
 * algorithm for multi-byte characters (perhaps
 * by adding every four 16-bit characters and
 * shortening the sum to 32 bits). Otherwise
 * I suggest performing MD-5 as if every character
 * was two bytes--e.g., 0040 0025 = @%--but then
 * how will an ordinary MD-5 sum be matched?
 * There is no way to standardize text to something
 * like UTF-8 before transformation; speed cost is
 * utterly prohibitive. The JavaScript standard
 * itself needs to look at this: it should start
 * providing access to strings as preformed UTF-8
 * 8-bit unsigned value arrays.
 */
 function md5blk(s) { /* I figured global was faster.   */
 	var md5blks = [], i; /* Andy King said do it this way. */
 	for (i=0; i<64; i+=4) {
 		md5blks[i>>2] = s.charCodeAt(i)
 		+ (s.charCodeAt(i+1) << 8)
 		+ (s.charCodeAt(i+2) << 16)
 		+ (s.charCodeAt(i+3) << 24);
 	}
 	return md5blks;
 }

 var hex_chr = '0123456789abcdef'.split('');

 function rhex(n)
 {
 	var s='', j=0;
 	for(; j<4; j++)
 		s += hex_chr[(n >> (j * 8 + 4)) & 0x0F]
 	+ hex_chr[(n >> (j * 8)) & 0x0F];
 	return s;
 }

 function hex(x) {
 	for (var i=0; i<x.length; i++)
 		x[i] = rhex(x[i]);
 	return x.join('');
 }

 function md5(s) {
 	return hex(md51(s));
 }

/* this function is much faster,
so if possible we use it. Some IEs
are the only ones I know of that
need the idiotic second function,
generated by an if clause.  */

function add32(a, b) {
	return (a + b) & 0xFFFFFFFF;
}

if (md5('hello') != '5d41402abc4b2a76b9719d911017c592') {
	function add32(x, y) {
		var lsw = (x & 0xFFFF) + (y & 0xFFFF),
		msw = (x >> 16) + (y >> 16) + (lsw >> 16);
		return (msw << 16) | (lsw & 0xFFFF);
	}
}

var Profile = {};

Profile.zipCodes = {"AD":"AA999","AE":"","AF":"9999","AG":"99999","AI":"AA-9999","AL":"9999","AM":"9999","AN":"","AO":"","AQ":"9999","AR":"A9999AAA","AS":"99999","AT":"9999","AU":"9999","AW":"9999 AA","AX":"99999","AZ":"AA 9999","BA":"99999","BB":"AA99999","BD":"9999","BE":"9999","BF":"","BG":"9999","BH":"9999","BI":"","BJ":"","BL":"99999","BM":"AA 99","BN":"AA9999","BO":"","BQ":"9999 AA","BR":"99999-999","BS":"","BT":"99999","BV":"","BW":"","BY":"999999","BZ":"","CA":"A9A 9A9","CC":"9999 A9","CD":"","CF":"","CG":"","CH":"9999","CI":"","CK":"","CL":"9999999","CM":"","CN":"999999","CO":"999999","CR":"99999","CU":"99999","CV":"9999","CW":"9999 AA","CX":"9999","CY":"9999","CZ":"999 99","DE":"99999","DJ":"","DK":"9999","DM":"","DO":"99999","DZ":"99999","EC":"999999","EE":"99999","EG":"99999","EH":"99999","ER":"","ES":"99999","ET":"9999","FI":"99999","FJ":"","FK":"AAAA 9AA","FM":"99999","FO":"999","FR":"99999","GA":"","GB":"AA9 9AA","GD":"","GE":"9999","GF":"99999","GG":"AA9 9AA","GH":"","GI":"AA99 9AA","GL":"9999","GM":"","GN":"999","GP":"99999","GQ":"","GR":"999 99","GS":"AAAA 9AA","GT":"99999","GU":"99999","GW":"9999","GY":"","HK":"999999","HM":"9999","HN":"","HR":"99999","HT":"9999","HU":"9999","ID":"99999","IE":"","IL":"9999999","IM":"AA99 9AA","IN":"999999","IO":"AA9A 9AA","IQ":"99999","IR":"99999","IS":"999","IT":"99999","JE":"AA9 9AA","JM":"AAAAA99","JO":"99999","JP":"999-9999","KE":"99999","KG":"999999","KH":"99999","KI":"","KM":"","KN":"","KP":"","KR":"99999","KW":"99999","KY":"AA9-9999","KZ":"999999","LA":"99999","LB":"9999","LC":"","LI":"9999","LK":"99999","LR":"9999","LS":"999","LT":"99999","LU":"9999","LV":"AA-9999","LY":"99999","MA":"99999","MC":"99999","MD":"9999","ME":"99999","MF":"99999","MG":"999","MH":"99999","MK":"9999","ML":"","MM":"99999","MN":"99999","MO":"999999","MP":"99999","MQ":"99999","MR":"","MS":"AAA9999","MT":"AAA 9999","MU":"A9999","MV":"99999","MW":"","MX":"99999","MY":"99999","MZ":"9999","NA":"","NC":"99999","NE":"9999","NF":"9999","NG":"999999","NI":"99999","NL":"9999 AA","NO":"9999","NP":"99999","NR":"","NU":"","NZ":"9999","OM":"999","PA":"","PE":"99999","PF":"99999","PG":"999","PH":"9999","PK":"99999","PL":"99-999","PM":"99999","PN":"AAA9 9AA","PR":"99999","PS":"999-999","PT":"9999-999","PW":"99999","PY":"9999","QA":"","RE":"99999","RO":"999999","RS":"99999","RU":"999999","RW":"","SA":"99999-9999","SB":"","SC":"","SD":"99999","SE":"999 99","SG":"999999","SH":"AAAA 9AA","SI":"9999","SJ":"9999","SK":"999 99","SL":"","SM":"99999","SN":"99999","SO":"","SR":"","SS":"99999","ST":"","SV":"9999","SX":"9999 AA","SY":"","SZ":"A999","TC":"AAAA 9AA","TD":"99999","TF":"99999","TG":"","TH":"99999","TJ":"999","TK":"","TL":"","TM":"999999","TN":"9999","TO":"","TR":"99999","TT":"","TV":"","TW":"99999","TZ":"","UA":"99999","UG":"","US":"99999-9999","UY":"99999","UZ":"999999","VA":"999","VC":"AA9999","VE":"9999","VG":"AA9999","VI":"99999","VN":"999999","VU":"","WF":"99999","WS":"99999","XK":"99999","YE":"","YT":"99999","ZA":"9999","ZM":"99999","ZW":""};

Profile.setShippingCountryOnSelect = function(data){
	if(!data.code)
		return;

	Profile.evalAddressSelect(data.code, 'shipping');
};

Profile.setBillingCountryOnSelect = function(data){
	if(!data.code)
		return;

	Profile.evalAddressSelect(data.code, 'billing');
};

Profile.evalAddressSelect = function(countryCode, type){
    console.log(countryCode, type)
	if(countryCode && Profile.zipCodes[countryCode]){
		$('.col-'+type).removeClass('col-md-4').addClass('col-md-3');
		$('.col-'+type+'-zip').fadeIn(300);
		$('.col-'+type+'-zip').find('input').mask(Profile.zipCodes[countryCode]);
		$('[name="'+type+'_zip_required"]').val('required');
	}else{
		$('.col-'+type).removeClass('col-md-3').addClass('col-md-4');
		$('.col-'+type+'-zip').hide();
		$('[name="'+type+'_zip_required"]').val('');
	}
}

Profile.checkSameAddress = function(el){
	if(el.prop('checked'))
		$('.shop__billing-elements').hide();
	else
		$('.shop__billing-elements').fadeIn();
}

Profile.onFinishLogin = function(result){
    if(result.action == 'refresh')
        location.reload();

    if(result.action == 'redirect')
        location.replace(result.url);
}

jQuery(document).ready(function($) {
	var hash = window.location.hash;
  		hash && $('ul.nav a[href="' + hash + '"]').tab('show');

	if($('[name="shipping_address[country]"]').val())
		Profile.evalAddressSelect($('[name="shipping_address[country]"]').val(), 'shipping');

	if($('[name="billing_address[country]"]').val())
		Profile.evalAddressSelect($('[name="billing_address[country]"]').val(), 'billing');

	if($('#same-address').prop('checked'))
		Profile.checkSameAddress($('#same-address'));

	$('#same-address').change(function(event) {
		Profile.checkSameAddress($(this));
	});

	if($('.user-avatar-image').attr('data-src'))
		$('.user-avatar-image').css('background-image', 'url('+$('.user-avatar-image').attr('data-src')+')');

	$('.new-account').click(function(event) {
		$('.account-signin-container').hide();
		$('.account-register-container').fadeIn();
	});

	$('.returning-account').click(function(event) {
		$('.account-signin-container').fadeIn();
		$('.account-register-container').hide();
	});

	$('.account-wall [name="username"]').keyup(function(event) {
		if(validateEmail($(this).val()))
			$('.profile-img').attr('src', get_gravatar_image_url($(this).val(), 192));
	});

	if(validateEmail($('.account-wall [name="username"]').val()))
		$('.profile-img').attr('src', get_gravatar_image_url($(this).val(), 192));

	function validateEmail(email) {
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	}

	function get_gravatar_image_url (email, size, default_image, allowed_rating, force_default){
		email = typeof email !== 'undefined' ? email : 'john.doe@example.com';
		size = (size >= 1 && size <= 2048) ? size : 80;
		default_image = typeof default_image !== 'undefined' ? default_image : 'mm';
		allowed_rating = typeof allowed_rating !== 'undefined' ? allowed_rating : 'x';
		force_default = force_default === true ? 'y' : 'n';

		return ("https://secure.gravatar.com/avatar/" + md5(email.toLowerCase().trim()) + "?size=" + size + "&default=" + encodeURIComponent(default_image) + "&rating=" + allowed_rating + (force_default === 'y' ? "&forcedefault=" + force_default : ''));
	}
});