 /**
 *
 */
'use strict';

angular.module('app').controller(
		'RatesDetailController',
		[ '$scope', '$state', '$stateParams', 'propertiesConstant', 'TokenStorage', 'masterDataServices','$filter','toaster','editableOptions', 'editableThemes',
				function($scope, $state, $stateParams, propertiesConstant, TokenStorage, masterDataServices,$filter,toaster,editableOptions, editableThemes) {
					var busy = false;
					var storageKey = TokenStorage.retrieve();

					var title = $state.current.data.title;
					var type = $state.current.data.type;
					var module = $state.current.data.module;
					$scope.title=title;
					$scope.rates={};

					editableThemes.bs3.inputClass = 'input-sm';
				    editableThemes.bs3.buttonsClass = 'btn-sm';
				    editableOptions.theme = 'bs3';


					masterDataServices.get({module:'rates',id:$stateParams.id},function(data){
						$scope.rates =data;
					})	;
					$scope.list_product = masterDataServices.query({module:'product',company_id:storageKey[propertiesConstant.COMPANY_ID]});

					 $scope.list_rates = masterDataServices.query({module:'rates_detail',id:$stateParams.id,company_id:storageKey[propertiesConstant.COMPANY_ID]});

					 $scope.list_rates_pos = masterDataServices.query({module:'rates_pos',rates_id:$stateParams.id,company_id:storageKey[propertiesConstant.COMPANY_ID]});


					$scope.newProductPos=function(){
						$scope.inserted = {
								        id: null,
								        rates_id:$stateParams.id,
								        product:null,
								        price:null,
								        taxes_pct:0,
								        pax:1,
								        service_pct:0,
								        index: $scope.list_rates_pos.length

								      };
								$scope.list_rates_pos.push($scope.inserted);
					}


					$scope.save=function(data){
						data.rates_id=$stateParams.id;
						data.company_id=storageKey[propertiesConstant.COMPANY_ID];
						masterDataServices.save('rates_room', data ).success(function(response) {
							busy = false;
							data.id=response.id;
							console.log(data.id);
							if (response.error==true){
				                toaster.pop('error','Information',response.message);
				              }

						}).error(function() {
							busy = false;

							toaster.pop('error','Information','unable to save data ' + module + '!');
							});
					}

					$scope.removeInTable = function(index) {
				      $scope.list_rates_pos.splice(index, 1);
				    };

					$scope.remove=function(index,master){
						if (master.id){
				      		masterDataServices.remove('rates_pos', master).success(function() {
							 $scope.removeInTable(index);
							}).error(function() {
								toaster.pop('error','Information','unable to delete data ' + module + '!');
							});
				      	}else{
				      		$scope.removeInTable(index);
				      	}
					}


					$scope.save_rate_pos=function(data){
						console.log(data.product);
						if (data.product && data.price){
								data.rates_id=$stateParams.id;
								data.product_id=data.product.id;
								data.company_id=storageKey[propertiesConstant.COMPANY_ID];
								masterDataServices.save('rates_pos', data ).success(function(response) {
									busy = false;
									data.id=response.id;
									if (response.error==true){
						                toaster.pop('error','Information',response.message);
						              }else{
						              	toaster.pop('success','Information',"Proses Save is success!");
						              }

								}).error(function() {
									busy = false;
									toaster.pop('error','Information','unable to save data ' + module + '!');
								});
								return false;
						}else{
							toaster.pop('error','Information','Data is Invalid!');
							return true;
						}

					}






} ]);
