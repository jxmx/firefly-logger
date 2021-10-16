// From https://stackoverflow.com/questions/19946680/parsing-multi-variable-cookies-into-web-form-values

//<!-- A script to set a cookie [Argument(s) accepted: Cookie Name, Cookie Value, etc.] [BEGIN] -->
function set_cookie ( name, value, path, domain, secure ) {
	var cookie_string = name + "=" + escape ( value );
	var cookie_date = new Date();  // current date & time ;

	var cookie_date_num = cookie_date.getTime(); // convert cookie_date to milliseconds ;
	cookie_date_num += 180 * 86400 * 60 * 1000;  // add 180 days in milliseconds ;
	cookie_date.setTime(cookie_date_num); // set my_date Date object forward ;
	cookie_string += "; expires=" + cookie_date.toGMTString();

	cookie_string += "; SameSite=None; Secure";

	document.cookie = cookie_string;
};
//<!-- A script to set a cookie [Argument(s) accepted: Cookie Name, Cookie Value, etc.] [END] -->

//<!-- A script to grab a cookies value by name [Argument(s) accepted: Cookies Name] [BEGIN] -->
function get_cookie ( cookie_name ) {
	var results = document.cookie.match ( '(^|;) ?' + cookie_name + '=([^;]*)(;|$)' );
	if ( results ) { 
	    return ( unescape ( results[2] ) ); 
	} else {
	    return null;
	};
};

//<!-- A script to grab a cookies value by name [Argument(s) accepted: Cookies Name] [END] -->


function populateCookieFromForm ( cookieName ) {

    var encodedCookie;
    var preCookieObj = '{';
    var allMainElements = $('stationset').find('input[type=text], select');
    for (var i=0; i < allMainElements.length; i++) 
	{
	    preCookieObj = preCookieObj + '"' + allMainElements[i].name +'":"'+ allMainElements[i].value +'",';     
	};

    preCookieObj = preCookieObj.substring(0, preCookieObj.length - 1);
    preCookieObj = preCookieObj + '}';

    //<!-- btoa() encodes 'string' argument into Base64 encoding --> 
    encodedCookie = btoa( preCookieObj );
    set_cookie(cookieName, encodedCookie);
};

function populateFormFromCookie (cookieName) {
    if ( ! get_cookie ( cookieName ) )
	{
		//<!-- Do Nothing - No Cookie For this form set. -->
	} else {
	    //<!-- atob() decodes 'string' argument from Base64 encoding --> 
	    jSONCookieObj = atob( get_cookie ( cookieName ) ) ;     
	    jSONObj = JSON.parse( jSONCookieObj );
	    var allMainElements = $('stationset').find('input[type=text], select');

	    for (var i=0; i < allMainElements.length; i++)  {
			var elementName = allMainElements[i].name;
			var elementValue = jSONObj[elementName];
			allMainElements[i].value = elementValue;
	    };
	};
};


