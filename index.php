<html>
  <head></head>
  <body>
    
	  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/require.js/2.3.6/require.js"></script> 
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	  
<?php
/********************************************************/
/*			Control the weather V1.0					*/
/*			By Albert Seuba	- 042319					*/
/********************************************************/
/* Consultamos el tiempo actual (ciudad=Barcelona), 
podemos variar la url pasando una variable desde inArguments y transformando country a su código */
/*$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "http://dataservice.accuweather.com/currentconditions/v1/307297?apikey=aE0Mu6wczdfgTIZacsEksP0KBDAUYZjr",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1
));
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
if ($err) {
  echo "cURL Error #:" . $err;
} else {
	$tempsbarcelona = json_decode($response);
	$accuweather_temps = $tempsbarcelona[0]->{'WeatherText'};
	echo $accuweather_temps;
};*/
	  echo 'sunny';
?>
<script type="text/javascript">
	/*
 * Copyright (c) 2018, salesforce.com, inc.
 * All rights reserved.
 * Licensed under the BSD 3-Clause license.
 * For full license text, see LICENSE.txt file in the repo root  or https://opensource.org/licenses/BSD-3-Clause
 */

var SDK = function (config, whitelistOverride, sslOverride) {
	// config has been added as the primary parameter
	// If it is provided ensure that the other paramaters are correctly assigned
	// for backwards compatibility
	if (Array.isArray(config)) {
		whitelistOverride = config;
		sslOverride = whitelistOverride;
		config = undefined;
	}

	this._whitelistOverride = whitelistOverride;
	this._sslOverride = sslOverride;
	this._messageId = 1;
	this._messages = {
		0: function () {}
	};
	this._readyToPost = false;
	this._pendingMessages = [];
	this._receiveMessage = this._receiveMessage.bind(this);

	window.addEventListener('message', this._receiveMessage, false);

	window.parent.postMessage({
		method: 'handShake',
		origin: window.location.origin,
		payload: config
	}, '*');
};

SDK.prototype.execute = function execute (method, options) {
	options = options || {};
	
	var self = this;
	var payload = options.data;
	var callback = options.success;

	if (!this._readyToPost) {
		this._pendingMessages.push({
			method: method,
			payload: payload,
			callback: callback
		});
	} else {
		this._post({
			method: method,
			payload: payload
		}, callback);
	}
};

SDK.prototype.getCentralData = function (cb) {
	this.execute('getCentralData', {
		success: cb
	});
};

SDK.prototype.getContent = function (cb) {
	this.execute('getContent', {
		success: cb
	});
};

SDK.prototype.getData = function (cb) {
	this.execute('getData', {
		success: cb
	});
};

SDK.prototype.getUserData = function (cb) {
	this.execute('getUserData', {
		success: cb
	});
};

SDK.prototype.getView = function (cb) {
	this.execute('getView', {
		success: cb
	});
};

SDK.prototype.setBlockEditorWidth = function (value, cb) {
	this.execute('setBlockEditorWidth', {
		data: value,
		success: cb
	});
};

SDK.prototype.setCentralData = function (dataObj, cb) {
	this.execute('setCentralData', {
		data: dataObj, 
		success: cb
	});
};

SDK.prototype.setContent = function (content, cb) {
	this.execute('setContent', {
		data: content, 
		success: cb});
};

SDK.prototype.setData = function (dataObj, cb) {
	this.execute('setData', {
		data: dataObj, 
		success: cb
	});
};

SDK.prototype.setSuperContent = function (content, cb) {
	this.execute('setSuperContent', {
		data: content, 
		success: cb
	});
};

SDK.prototype.triggerAuth = function (appID) {
	this.getUserData(function (userData) {
		var stack = userData.stack;
		if (stack.indexOf('qa') === 0) {
			stack = stack.substring(3,5) + '.' + stack.substring(0,3);
		}
		var iframe = document.createElement('IFRAME');
		iframe.src = 'https://mc.' + stack + '.exacttarget.com/cloud/tools/SSO.aspx?appId=' + appID + '&restToken=1&hub=1';
		iframe.style.width= '1px';
		iframe.style.height = '1px';
		iframe.style.position = 'absolute';
		iframe.style.top = '0';
		iframe.style.left = '0';
		iframe.style.visibility = 'hidden';
		iframe.className = 'authframe';
		document.body.appendChild(iframe);
	});
};

/* Internal Methods */

SDK.prototype._executePendingMessages = function _executePendingMessages () {
	var self = this;

	this._pendingMessages.forEach(function (thisMessage) {
		self.execute(thisMessage.method, {
			data: thisMessage.payload, 
			success: thisMessage.callback
		});
	});

	this._pendingMessages = [];
};

SDK.prototype._post = function _post (payload, callback) {
	this._messages[this._messageId] = callback;
	payload.id = this._messageId;
	this._messageId += 1;
	// the actual postMessage always uses the validated origin
	window.parent.postMessage(payload, this._parentOrigin);
};

SDK.prototype._receiveMessage = function _receiveMessage (message) {
	message = message || {};
	var data = message.data || {};

	if (data.method === 'handShake') {
		if (this._validateOrigin(data.origin)) {
			this._parentOrigin = data.origin;
			this._readyToPost = true;
			this._executePendingMessages();
			return;
		}
	}

	// if the message is not from the validated origin it gets ignored
	if (!this._parentOrigin || this._parentOrigin !== message.origin) {
		return;
	}
	// when the message has been received, we execute its callback
	(this._messages[data.id || 0] || function () {})(data.payload);
	delete this._messages[data.id];
};

// the custom block should verify it is being called from the marketing cloud
SDK.prototype._validateOrigin = function _validateOrigin (origin) {
	// Make sure to escape periods since these strings are used in a regular expression
	var allowedDomains = this._whitelistOverride || ['exacttarget\\.com', 'marketingcloudapps\\.com', 'blocktester\\.herokuapp\\.com'];

	for (var i = 0; i < allowedDomains.length; i++) {
		// Makes the s optional in https
		var optionalSsl = this._sslOverride ? '?' : '';
		var mcSubdomain = allowedDomains[i] === 'exacttarget\\.com' ? 'mc\\.' : '';
		var whitelistRegex = new RegExp('^https' + optionalSsl + '://' + mcSubdomain + '([a-zA-Z0-9-]+\\.)*' + allowedDomains[i] + '(:[0-9]+)?$', 'i');

		if (whitelistRegex.test(origin)) {
			return true;
		}
	}

	return false;
};

if (typeof(window) === 'object') {
	window.sfdc = window.sfdc || {};
	window.sfdc.BlockSDK = SDK;
}
if (typeof(module) === 'object') {
	module.exports = SDK;
}

var SDK = function (config, whitelistOverride, sslOverride) {
	// config has been added as the primary parameter
	// If it is provided ensure that the other paramaters are correctly assigned
	// for backwards compatibility
	if (Array.isArray(config)) {
		whitelistOverride = config;
		sslOverride = whitelistOverride;
		config = undefined;
	}

	this._whitelistOverride = whitelistOverride;
	this._sslOverride = sslOverride;
	this._messageId = 1;
	this._messages = {
		0: function () {}
	};
	this._readyToPost = false;
	this._pendingMessages = [];
	this._receiveMessage = this._receiveMessage.bind(this);

	window.addEventListener('message', this._receiveMessage, false);

	window.parent.postMessage({
		method: 'handShake',
		origin: window.location.origin,
		payload: config
	}, '*');
};

SDK.prototype.execute = function execute (method, options) {
	options = options || {};
	
	var self = this;
	var payload = options.data;
	var callback = options.success;

	if (!this._readyToPost) {
		this._pendingMessages.push({
			method: method,
			payload: payload,
			callback: callback
		});
	} else {
		this._post({
			method: method,
			payload: payload
		}, callback);
	}
};

SDK.prototype.getCentralData = function (cb) {
	this.execute('getCentralData', {
		success: cb
	});
};

SDK.prototype.getContent = function (cb) {
	this.execute('getContent', {
		success: cb
	});
};

SDK.prototype.getData = function (cb) {
	this.execute('getData', {
		success: cb
	});
};

SDK.prototype.getUserData = function (cb) {
	this.execute('getUserData', {
		success: cb
	});
};

SDK.prototype.getView = function (cb) {
	this.execute('getView', {
		success: cb
	});
};

SDK.prototype.setBlockEditorWidth = function (value, cb) {
	this.execute('setBlockEditorWidth', {
		data: value,
		success: cb
	});
};

SDK.prototype.setCentralData = function (dataObj, cb) {
	this.execute('setCentralData', {
		data: dataObj, 
		success: cb
	});
};

SDK.prototype.setContent = function (content, cb) {
	this.execute('setContent', {
		data: content, 
		success: cb});
};

SDK.prototype.setData = function (dataObj, cb) {
	this.execute('setData', {
		data: dataObj, 
		success: cb
	});
};

SDK.prototype.setSuperContent = function (content, cb) {
	this.execute('setSuperContent', {
		data: content, 
		success: cb
	});
};

SDK.prototype.triggerAuth = function (appID) {
	this.getUserData(function (userData) {
		var stack = userData.stack;
		if (stack.indexOf('qa') === 0) {
			stack = stack.substring(3,5) + '.' + stack.substring(0,3);
		}
		var iframe = document.createElement('IFRAME');
		iframe.src = 'https://mc.' + stack + '.exacttarget.com/cloud/tools/SSO.aspx?appId=' + appID + '&restToken=1&hub=1';
		iframe.style.width= '1px';
		iframe.style.height = '1px';
		iframe.style.position = 'absolute';
		iframe.style.top = '0';
		iframe.style.left = '0';
		iframe.style.visibility = 'hidden';
		iframe.className = 'authframe';
		document.body.appendChild(iframe);
	});
};

/* Internal Methods */

SDK.prototype._executePendingMessages = function _executePendingMessages () {
	var self = this;

	this._pendingMessages.forEach(function (thisMessage) {
		self.execute(thisMessage.method, {
			data: thisMessage.payload, 
			success: thisMessage.callback
		});
	});

	this._pendingMessages = [];
};

SDK.prototype._post = function _post (payload, callback) {
	this._messages[this._messageId] = callback;
	payload.id = this._messageId;
	this._messageId += 1;
	// the actual postMessage always uses the validated origin
	window.parent.postMessage(payload, this._parentOrigin);
};

SDK.prototype._receiveMessage = function _receiveMessage (message) {
	message = message || {};
	var data = message.data || {};

	if (data.method === 'handShake') {
		if (this._validateOrigin(data.origin)) {
			this._parentOrigin = data.origin;
			this._readyToPost = true;
			this._executePendingMessages();
			return;
		}
	}

	// if the message is not from the validated origin it gets ignored
	if (!this._parentOrigin || this._parentOrigin !== message.origin) {
		return;
	}
	// when the message has been received, we execute its callback
	(this._messages[data.id || 0] || function () {})(data.payload);
	delete this._messages[data.id];
};

// the custom block should verify it is being called from the marketing cloud
SDK.prototype._validateOrigin = function _validateOrigin (origin) {
	// Make sure to escape periods since these strings are used in a regular expression
	var allowedDomains = this._whitelistOverride || ['exacttarget\\.com', 'marketingcloudapps\\.com', 'blocktester\\.herokuapp\\.com'];

	for (var i = 0; i < allowedDomains.length; i++) {
		// Makes the s optional in https
		var optionalSsl = this._sslOverride ? '?' : '';
		var mcSubdomain = allowedDomains[i] === 'exacttarget\\.com' ? 'mc\\.' : '';
		var whitelistRegex = new RegExp('^https' + optionalSsl + '://' + mcSubdomain + '([a-zA-Z0-9-]+\\.)*' + allowedDomains[i] + '(:[0-9]+)?$', 'i');

		if (whitelistRegex.test(origin)) {
			return true;
		}
	}

	return false;
};

if (typeof(window) === 'object') {
	window.sfdc = window.sfdc || {};
	window.sfdc.BlockSDK = SDK;
}
if (typeof(module) === 'object') {
	module.exports = SDK;
}
if (window.self === window.top) {
	document.body.innerText = 'This application is for use in the Salesforce Marketing Cloud Content Builder Editor only.';
} else {
	var sdk = new SDK();
	sdk.getContent(function (content) {
		var albert = 'cloud';
		});
		
		function saveText() {
			var html = albert;
			sdk.setContent(html);
			sdk.setSuperContent('This is super content: ' + html);

			sdk.getData(function (data) {
				var numberOfEdits = data.numberOfEdits || 0;
				sdk.setData({
					numberOfEdits: numberOfEdits + 1
				});
			});
		}
		
	}

		</script>
  </body>
</html>
