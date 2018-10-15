/**
 * 
 */
'use strict';
angular.module('app').controller(
		'DashboardController',
		[ '$scope', '$state', '$stateParams', 'propertiesConstant', 'TokenStorage', 'masterDataServices',
				function($scope, $state, $stateParams, propertiesConstant, TokenStorage, masterDataServices) {
					var busy = false;
					
					var storageKey = TokenStorage.retrieve();

					var title = $state.current.data.title;
					var type = $state.current.data.type;
					var module = $state.current.data.module;

					 $scope.color= {
				          primary: '#7266ba',
				          info:    '#23b7e5',
				          success: '#27c24c',
				          warning: '#fad733',
				          danger:  '#f05050',
				          light:   '#e8eff0',
				          dark:    '#3a3f51',
				          black:   '#1c2b36'
				        };

				     if (type === 'list') {

						$scope.title = title + ' List';
						$scope.totalItems = 0;
						$scope.currentPage = 1;
						$scope.maxSize = 5;
						$scope.itemsPerPage = 10;

						$scope.pageChanged = function() {
							$scope.credit = masterDataServices.queryPage({
								module : "add-credit",
								"page" : $scope.currentPage,
								"size" : $scope.itemsPerPage,
							}, function() {
								$scope.totalItems = $scope.page.total;

							});

							$scope.product = masterDataServices.queryPage({
								module : "product",
								"page" : $scope.currentPage,
								"size" : $scope.itemsPerPage,
							}, function() {
								$scope.totalItems = $scope.page.total;

							});

							$scope.blog = masterDataServices.queryPage({
								module : "blog",
								"page" : $scope.currentPage,
								"size" : $scope.itemsPerPage,
							}, function() {
								$scope.totalItems = $scope.page.total;

							});

							$scope.rate = masterDataServices.queryPage({
								module : "add-interest_rate",
								"page" : $scope.currentPage,
								"size" : $scope.itemsPerPage,
							}, function() {
								$scope.totalItems = $scope.page.total;

							});
						};

						$scope.pageChanged();

						$scope.edit_credit = function(master) {
							$state.go('app.' + 'add-credit' + '-edit', {
								master : master
							});
						};

						$scope.remove_credit = function(master) {
							masterDataServices.remove('add-credit', master).success(function() {
								$scope.pageChanged();
							}).error(function() {
								toaster.pop('error','Information','unable to delete data !');
							});
						};

						$scope.edit_blog = function(master) {
							$state.go('app.' + 'blog' + '-edit', {
								master : master
							});
						};

					}
				} ]);