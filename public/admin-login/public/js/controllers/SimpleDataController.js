/**
 * 
 */
'use strict';
angular.module('app').controller(
		'SimpleDataController',
		[ '$scope', '$state', '$stateParams', 'propertiesConstant', 'TokenStorage', 'simpleDataServices','$filter','toaster',
				function($scope, $state, $stateParams, propertiesConstant, TokenStorage, simpleDataServices,$filter,toaster) {
					var busy = false;
					var storageKey = TokenStorage.retrieve();

					var title = $state.current.data.title;
					var type = $state.current.data.type;
					var module = $state.current.data.module;
					$scope.images=propertiesConstant.API_URL+'images/no_images.jpg/100x100';

					if (type === 'list') {
						$scope.title = title;

						$scope.master = simpleDataServices.get({
							module : module,
							company_id : storageKey[propertiesConstant.COMPANY_ID]
						}, function(data) {
							$scope.current = angular.copy(data);
							$scope.images=propertiesConstant.API_URL+'images/'+$scope.current.logo+'/100x100';
						});

					} else if (type === 'form') {

							


						$scope.changePassword=function(){
							$scope.master.password_cm='';
							$scope.master.change_password=true;
						}

						if ($stateParams.master==null){
							$state.go('app.' + module, {
								master : {}
							});
						}else{

							$scope.title = ($stateParams.master[0].id == null) ? 'New ' + title : 'Edit ' + title;
							$scope.buttonText = ($stateParams.master[0].id == null) ? 'Save' : 'Update';
							$scope.master = $stateParams.master[0];

							if ($state.current.data.use_date){
					      		$scope.master.app_date = $filter('date')($scope.master.app_date, 'yyyy-MM-dd');
					        }

						    $scope.clear = function () {
						      $scope.dt = null;
						    };

						    
						    $scope.open_start = function($event) {
							      $event.preventDefault();
							      $event.stopPropagation();
							      $scope.start_opened = true;
							    };


							    $scope.open_end = function($event) {
							      $event.preventDefault();
							      $event.stopPropagation();
							      $scope.end_opened = true;
							    };

							    $scope.dateOptions = {
							      formatYear: 'yy',
							      startingDay: 1,
							      class: 'datepicker'
							    };

							    $scope.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'dd.MM.yyyy', 'shortDate'];
							    $scope.format = $scope.formats[0];


							$scope.save = function(master) {
								busy = true;
								if ($state.current.data.use_date){
						      		master.app_date = $filter('date')(master.app_date, 'yyyy-MM-dd');
						        }
								simpleDataServices.save(module, master).success(function(response) {
									busy = false;
									if (response.error==true){
										toaster.pop('error','Information',response.message);
									}else{
										$state.go('app.' + module);	
									}
									
								}).error(function() {
									busy = false;
									alert('unable to update data ' + module + '!');
								});
							};

							$scope.cancel = function() {
								$state.go('app.' + module, {
									master : {}
								});
							};
						}		
					}

					$scope.loading = function() {
						return busy;
					};

				} ]);