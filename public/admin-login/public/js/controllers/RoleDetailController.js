/**
 * 
 */
'use strict';
angular.module('app').controller(
		'RoleDetailController',
		[ '$scope', '$state', '$stateParams', 'propertiesConstant', 'TokenStorage', 'masterDataServices',
				function($scope, $state, $stateParams, propertiesConstant, TokenStorage, masterDataServices) {
					var busy = false;
					var storageKey = TokenStorage.retrieve();

					$scope.title = $state.current.data.title;
					var type = $state.current.data.type;
					var module = $state.current.data.module;

					$scope.role = masterDataServices.get({id:$stateParams.id,
					                                      module:'role'}, function() {
							});
					

					 masterDataServices.query({
								module : 'menu',
								"role_id" : $stateParams.id
							},function(data) {
								
								$scope.menu_list = data ;
							});
					
					
					$scope.markAll=function(allChecked){
				       $scope.menu_list.forEach(function(menu){
				               menu.is_checked=allChecked;

				        });
				       //logger.logSuccess("Congrats! All done :)");
				  }

				  $scope.submit = function(){
				       busy=true;
				     
				     var master= {};
				      master.menu=$scope.menu_list;
				      master.role_id=$stateParams.id;
				      console.log(master);

						masterDataServices.save('role_list', master).success(function() {
							busy = false;
							$state.go('app.role');
						}).error(function() {
							busy = false;
							alert('unable to save data ' + module + '!');
						});
				   }		

				   	$scope.cancel = function() {
							$state.go('app.role' , {
								master : {}
							});
						};	
   
					$scope.loading = function() {
						return busy;
					};

				} ]);