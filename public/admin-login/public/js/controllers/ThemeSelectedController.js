/**
 *
 */
'use strict';
angular.module('app').controller(
		'ThemeSelectedController',
		[ '$scope', '$state', '$stateParams', 'propertiesConstant', 'TokenStorage', 'masterDataServices','toaster', 
				function($scope, $state, $stateParams, propertiesConstant, TokenStorage, masterDataServices,toaster ) {
					var busy = false;

					$scope.options = {
						 format: 'hex'
					};

					var storageKey = TokenStorage.retrieve();
					var title = $state.current.data.title;
					var type = $state.current.data.type;
					var module = $state.current.data.module;
					$scope.title=title;
					$scope.company_id=storageKey[propertiesConstant.COMPANY_ID];
					$scope.company={};
					$scope.master={};
					$scope.list_setting=[];
					$scope.search=function(){
						$scope.company=masterDataServices.get({
								module : 'company',
								id: $scope.company_id
							},function(response){
								$scope.company=response;
								
								 $scope.list_setting=JSON.parse(response.theme_setting);

							masterDataServices.get({
								module : 'theme',
								id: response.theme_id
							},function(response_setting){
								$scope.master=response_setting;
								
								
							});

					});
					}
					

					$scope.search();
					

					$scope.list_theme =masterDataServices.query({
								module : 'theme',
								type: "BASIC",
								company_id:$scope.company_id
							});

					$scope.save=function(config){
						var master = {id:$scope.company_id,
						             theme_setting:angular.toJson(config)
						         }
						masterDataServices.save('change_theme_setting', master).success(function(response) {
									busy = false;
									if (response.error==true){
						                toaster.pop('error','Information',response.message);
						              }else{
						              	$scope.search();
						                toaster.pop('success','Information',response.message);
						              }
									
								}).error(function() {
									busy = false;
									toaster.pop('error','Information','unable to save data ' + module + '!');
								});
					}

					$scope.select=function(id){
						var master = {id:$scope.company_id,
						             theme_id:id}
						masterDataServices.save('change_theme', master).success(function(response) {
									busy = false;
									if (response.error==true){
						                toaster.pop('error','Information',response.message);
						              }else{
						              	$scope.search();
						                toaster.pop('success','Information',response.message);
						              }
									
								}).error(function() {
									busy = false;
									toaster.pop('error','Information','unable to save data ' + module + '!');
								});
					}


				} ]);
