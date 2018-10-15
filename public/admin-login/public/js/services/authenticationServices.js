/**
 * 
 */
'use strict';
angular.module('app').factory(
		'authenticationServices',
		[
				'$state',
				'$http',
				'Idle',
				'TokenStorage',
				'propertiesConstant',
				function($state, $http, Idle, TokenStorage, propertiesConstant) {
					var service = {
						login : function(master) {
							return $http.post( propertiesConstant.API_URL + 'authenticate', master);
						},
						login_group : function(master) {
							return $http.post( propertiesConstant.API_URL + 'authenticate_group', master);
						},
						loginadmin : function(master) {
							return $http.post( propertiesConstant.API_URL + 'authenticate_admin', master);
						},
						logout : function(master) {
							TokenStorage.clear();
							Idle.unwatch();
							$state.go('access.signin');
						},
						change : function(passwd) {
							return $http.put( propertiesConstant.API_URL + 'user/passwd?currentPasswd=' + passwd.current + '&newPasswd='
									+ passwd.newx + '&renewPasswd=' + passwd.renew);
						}

					};
					return service;
				} ]);