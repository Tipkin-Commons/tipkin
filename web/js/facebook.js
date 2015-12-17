if(typeof console !== 'object')
	console = {};
if((typeof console.debug) !== 'function'){
	if(typeof opera === 'object'){ //redirige les appels vers opera.postError();
		console = {
			debug : function(){return opera.postError(arguments);},
			info : function(){this.debug('[INFO] ',arguments);},
			log : function(){this.debug('[LOG] ',arguments);}
		};
	}
	else{ //Ne rien afficher sur les autres navigateurs
		console = {
			debug : function(){return true;},
			info : function(){return true;},
			log : function(){return true;}
		};
	}
}

handleFacebook();
$('#fb-button').click(function(){
	fbGetLoginStatus();
});

/**
 * 
 */
function handleFacebook() {
	if(!window.fbApiInit) {
		FB.init({appId: '393954967300921', status: false, xfbml: true, cookie: true});
		fbApiInit = true;
		FB.Event.subscribe('auth.authResponseChange', onStatus);
	}
}

function fbGetLoginStatus() {
	
	FB.getLoginStatus(function(response) {
		
		 // every status change
		onStatus(response); // once on page load
	});
}

/**
* This will be called once on page load, and every time the status changes.
*/
function onStatus(response) {
	console.info('onStatus', response);
	if (response.status.indexOf('connected') != -1) {
		console.info('User logged in');
		if (response.perms) {
			console.info('User granted permissions');
		}else{
			console.info('User has not granted permissions');
		}
		showAccountInfo();
	} else {
		console.info('User is logged out');
	}
}

/**
* This assumes the user is logged out, and renders a login button.
*/
function showLoginButton() {
	var button = '<fb:login-button perms="email" />';
	$('#fb-login-button').html(button);
	FB.XFBML.parse(document.getElementById('fb-login-button'));
}

function showAccountInfo() {
	FB.api(
		{
		method: 'fql.query',
		query: 'SELECT username, first_name, last_name, uid, email, sex  FROM user WHERE uid='+FB.getUserID()
		},
		function(response) {
			console.info('API Callback', response);
			var user = response[0];

			$('#usernamefb').val(user.username);
			$('#mailfb').val(user.email);
			
			$('#facebook-connect-form').submit();
		}
	);
}