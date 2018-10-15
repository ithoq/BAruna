/**
 * 
 */
'use strict';
angular.module('app').controller('PasswdController',
		[ '$scope', '$state', 'propertiesConstant', 'TokenStorage', 'authenticationServices', function($scope, $state, propertiesConstant, TokenStorage, authenticationServices) {
			var busy = false;
			var storageKey = TokenStorage.retrieve();

			$scope.passwd = {};
			$scope.showMessage = false;
			$scope.title = $state.current.data.title;

			$scope.change = function(passwd) {
				busy = true;
				authenticationServices.change(passwd).success(function() {
					busy = false;
					$scope.classAlert = 'alert alert-success';
					$scope.showMessage = true;
					$scope.message = 'password changed successfully, please relogin...';
				}).error(function() {
					busy = false;
					$scope.classAlert = 'alert alert-danger';
					$scope.showMessage = true;
					$scope.message = 'unable to change password!';
				});

			};

			$scope.isNotMatch = function() {
				if ($scope.passwd.newx !== $scope.passwd.renew) {
					return true;
				} else {
					return false;
				}
			};

			$scope.loading = function() {
				return busy;
			};
		} ]);