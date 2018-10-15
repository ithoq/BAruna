// config
angular.module('app').factory('TokenStorage', function(propertiesConstant) {
	var storageKey = {};
	return {
		store : function(token,company) {
			storageKey[propertiesConstant.STORAGE_KEY] = token;
			storageKey[propertiesConstant.COMPANY_ID] = company;
			return localStorage.setItem(propertiesConstant.LOCAL_STORAGE, JSON.stringify(storageKey));
		},
		retrieve : function() {
			return JSON.parse(localStorage.getItem(propertiesConstant.LOCAL_STORAGE));
		},
		clear : function() {
			return localStorage.removeItem(propertiesConstant.LOCAL_STORAGE);
		}
	};
}).factory('TokenAuthInterceptor', function($q, $location, $rootScope, TokenStorage, propertiesConstant) {
	return {
		request : function(config) {
			var storageKey = TokenStorage.retrieve();
			if (storageKey) {
				$rootScope.$broadcast('loadingBarShow');
				config.headers[propertiesConstant.TOKEN_HEADER] = 'Bearer '+storageKey[propertiesConstant.STORAGE_KEY];
			} else {
				$location.path('/access/signin');
			}
			return config;
		},
		responseError : function(error) {
			$rootScope.$broadcast('loadingBarHide');
			if (error.status === 401 || error.status === 403 || error.status === 400) {
				TokenStorage.clear();
				$location.path('/access/signin');
			}
			return $q.reject(error);
		},
		response : function(response) {
			$rootScope.$broadcast('loadingBarHide');
			return response;
		}
	};
}).config(function($httpProvider) {
	$httpProvider.interceptors.push('TokenAuthInterceptor');
});