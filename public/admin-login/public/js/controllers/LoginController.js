/**
 * 
 */
'use strict';
angular.module('app').controller(
		'LoginController',
		[ '$scope', '$state', 'authenticationServices', 'TokenStorage', 'propertiesConstant',
				function($scope, $state, authenticationService, TokenStorage, propertiesConstant) {
					var busy = false;
					$scope.login = function(user) {
						busy = true;
						authenticationService.login(user).success(function(data, status, headers, config) {
							busy = false;
							$scope.notAuthenticated = false;
							TokenStorage.store(data.token,data.company.id);
							$state.go('app.dashboard');
						}).error(function(data, status, headers, config) {
							busy = false;
							$scope.notAuthenticated = true;
							$scope.errorMessage = data.message;
							TokenStorage.clear();
						});

					};

					$scope.loading = function() {
						return busy;
					};
				} ]);