var usbuilder = {
		_aAppInfo : null,
		_aBuilderUrlInfo : null,
        _setAppInfo: function(aAppInfo) {
            this._aAppInfo = aAppInfo;
        },
        getAppInfo: function(sKey) {
            if (sKey) {
                return this._aAppInfo[sKey];
            } else {
                return this._aAppInfo;
            }
        },        
        _setBuilderUrlInfo: function(aBuilderUrlInfo) {
            this._aBuilderUrlInfo = aBuilderUrlInfo;
        },		
        _replaceAllInfo: function(sClassName, sText) {
            aInfo = sClassName.match(/[A-Z][a-z]+/gm);
            pattAdmin = /^admin/;
            pattApi = /^api/;
            sPageExec = '';
            if (pattAdmin.test(sClassName)) {
                sPageExec = aInfo[0];
                delete aInfo[0];
            }
            sName = aInfo.join('');

            sText = sText.replace('|modulecode|', this.getAppInfo('app_id').toLowerCase());
            sText = sText.replace('|Modulecode|', this._ucfirst(this.getAppInfo('app_id')));
            sText = sText.replace('|pageexec|', sPageExec.toLowerCase());
            sText = sText.replace('|Pageexec|', this._ucfirst(sPageExec));
            sText = sText.replace('|name|', sName.toLowerCase());
            sText = sText.replace('|Name|', this._ucfirst(sName));
            return sText;
        },
		_getSpecifiedUrl : function(sClassName) {
            pattAdmin = /^admin/;
            pattFront = /^front/;
            pattApi = /^api/;
			if (pattAdmin.test(sClassName)) {
                aUrlInfo = this._aBuilderUrlInfo['admin'];
            } else if (pattFront.test(sClassName)) {
                aUrlInfo = this._aBuilderUrlInfo['front'];
            } else if (pattApi.test(sClassName)) {
                aUrlInfo = this._aBuilderUrlInfo['api'];
            }
            var aNewUrlInfo = {
            	'url' : null,
            	'param' : new Array()
			};
            aNewUrlInfo['url'] = this._replaceAllInfo(sClassName, aUrlInfo['url']);
            if (this._is_array(aUrlInfo['param'])) {
                for (var key in aUrlInfo['param']) {
                    sNewKey = this._replaceAllInfo(sClassName, key);
                    sNewVal = this._replaceAllInfo(sClassName, aUrlInfo['param'][key]);
                    aNewUrlInfo['param'][sNewKey] = sNewVal;
                }
            }
            return aNewUrlInfo;
		},
		getUrl : function(sClassName, mSeq) {
            aUrlInfo = this._getSpecifiedUrl(sClassName);
            sUrl = aUrlInfo['url'];
            i = 0;
            if (this._is_array(aUrlInfo['param'])) {
                for (var key in aUrlInfo['param']) {
                    if (i == 0) {
                        sGlue = '?';
                    } else {
                        sGlue = '&';
                    }
                    sUrl += sGlue + key + '=' + aUrlInfo['param'][key];
                    ++i;
                sGlue = '&';
                }
            } else {
                sGlue = '?';
            }
            if (mSeq == true) {
                if (aAppInfo['seq']) sUrl += sGlue + 'seq=' + aAppInfo['seq'];
            } else if (this._is_int(mSeq)) {
                $sUrl += $sGlue + 'seq=' + $mSeq;
            }            
            
            return sUrl;
		},
		_ucfirst : function(sText) {
			first = sText.charAt(0);
			rest = sText.substring(1,sText.length);
			first = first.toUpperCase();
			sText = first.concat(rest);

			return sText;
		},
		_is_array : function(input) {
			return typeof(input)=='object'||(input instanceof Array);
		},
		_is_int : function(input){
		    return typeof(input)=='number'&&parseInt(input)==input;
		}
};