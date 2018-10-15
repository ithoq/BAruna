/**
 * 
 */
'use strict';
angular.module('app').controller(
		'AppController',
		[ '$scope', '$rootScope', 'Idle', 'authenticationServices', 'TokenStorage', 'propertiesConstant', 'permissionServices','masterDataServices','$filter',
				function($scope, $rootScope,Idle, authenticationService, TokenStorage, propertiesConstant, permissionServices,masterDataServices,$filter) {
					var storageKey = TokenStorage.retrieve();

					$scope.logout = function() {
						authenticationService.logout();
						$scope.notAuthenticated = true;

					};

					masterDataServices.company({
							id : storageKey[propertiesConstant.COMPANY_ID]
						},function(response){
							$scope.company=response;
					      $rootScope.application_date=$filter('date')(response.app_date, 'dd-MM-yyyy');
					});

					

					
					permissionServices.get({},function(data) {
						permissionServices.setPermissions(data);
					});

					Idle.watch();
					$scope.$on('IdleTimeout', function() {
						$scope.logout();
					});

} ])
  ; 