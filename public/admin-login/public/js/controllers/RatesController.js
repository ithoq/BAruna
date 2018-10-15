/**
 *
 */
'use strict';
angular.module('app').controller(
		'RatesController',
		[ '$scope', '$state', '$stateParams', 'propertiesConstant', 'TokenStorage', 'masterDataServices','toaster','$filter','editableOptions', 'editableThemes',
				function($scope, $state, $stateParams, propertiesConstant, TokenStorage, masterDataServices,toaster,$filter,editableOptions, editableThemes) {
					var busy = false;

					var storageKey = TokenStorage.retrieve();
					var title = $state.current.data.title;
					var type = $state.current.data.type;
					var module = $state.current.data.module;
					var criteria_key_list = $state.current.data.criteria;
					$scope.company_id=storageKey[propertiesConstant.COMPANY_ID];

					editableThemes.bs3.inputClass = 'input-sm';
					editableThemes.bs3.buttonsClass = 'btn-sm';
					editableOptions.theme = 'bs3';

					if (type === 'list') {

						$scope.title = title + ' List';
						$scope.criteria_key_list = criteria_key_list;
						$scope.criteria= {};
						$scope.criteria.key = criteria_key_list[0].key;
						$scope.totalItems = 0;
						$scope.currentPage = 1;
						$scope.maxSize = 5;
						$scope.itemsPerPage = 10;


						$scope.pageChanged = function() {
							$scope.page = masterDataServices.queryPage({
								module : module,
								"page" : $scope.currentPage,
								"size" : $scope.itemsPerPage,
								"criteria_key" : $scope.criteria.key,
								"criteria_value" : $scope.criteria.value,
								"company_id":$scope.company_id
							}, function() {
								$scope.totalItems = $scope.page.total;

							});


						};

						$scope.pageChanged();

						$scope.create = function() {
							$state.go('app.' + module + '-new', {
								master : {}
							});
						};

						$scope.edit = function(master) {
							$state.go('app.' + module + '-edit', {
								id : master.id
							});
						};

						$scope.detail = function(master) {
							$state.go('app.rates-calender', {
								id : master.id
							});
						};

						$scope.remove = function(master) {
							masterDataServices.remove(module, master).success(function() {
								$scope.pageChanged();
							}).error(function() {
								toaster.pop('error','Information','unable to delete data ' + module + '!');
							});
						};

					} else if (type === 'form') {

						if ($stateParams.master==null){
							$state.go('app.' + module, {
								master : {}
							});
						}else{

							$scope.title = ($stateParams.master[0].id == null) ? 'New ' + title : 'Edit ' + title;
							$scope.buttonText = ($stateParams.master[0].id == null) ? 'Save' : 'Update';


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



							$scope.list_product = masterDataServices.query({module:'product',company_id:$scope.company_id});
								 $scope.list_rates = [];//masterDataServices.query({module:'rates_detail',id:$stateParams.id,company_id:storageKey[propertiesConstant.COMPANY_ID]});
								 $scope.list_rates_pos = [];//masterDataServices.query({module:'rates_pos',rates_id:$stateParams.id,company_id:storageKey[propertiesConstant.COMPANY_ID]});

							$scope.master = $stateParams.master[0];
							if  ($stateParams.master[0].id == null) {
								 $scope.master.use_end_date=1;
								 masterDataServices.query({module:'room_type',id:$stateParams.id,company_id:storageKey[propertiesConstant.COMPANY_ID]},function(room_type){
									 angular.forEach(room_type, function(value, key) {
											   var data_rates={
													        name:value.name,
																  room_type_id:value.id,
																	rates:0,
																   id:null
												         }
													$scope.list_rates.push(data_rates);
											});
								 });
							}

							if ($state.current.data.combo_box){


					            $scope.list_combo_box=[];
					            for (var i = 0; i < $state.current.data.combo_box.length; i++) {
					               $scope.list_combo_box[i] = masterDataServices.query({module:$state.current.data.combo_box[i],"company_id":storageKey[propertiesConstant.COMPANY_ID]});
					          };
					      }

					      	if ($state.current.data.use_date){


					      		if  ($stateParams.master[0].id == null) {
							      			masterDataServices.company({id:$scope.company_id},function(company){
				            					var date = new Date(company.app_date);
												      var firstDay = new Date(date.getFullYear(), date.getMonth() , 1);
												      $scope.master.start_date=$filter('date')(firstDay, 'yyyy-MM-dd');
												      var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
												      $scope.master.end_date=$filter('date')(lastDay,'yyyy-MM-dd');

							      		});
						      		}else{
						      			$scope.master.start_date=$filter('date')($stateParams.master[0].start_date, 'yyyy-MM-dd');
									      $scope.master.end_date=$filter('date')($stateParams.master[0].end_date,'yyyy-MM-dd');
						      		}




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
					        }

							$scope.save = function(master) {
								busy = true;

								if ($state.current.data.use_date){
									master.start_date = $filter('date')(master.start_date, 'yyyy-MM-dd');
                          			master.end_date = $filter('date')(master.end_date, 'yyyy-MM-dd');
								}
								master.company_id=$scope.company_id;
								masterDataServices.save(module, master).success(function(response) {
									busy = false;
									if (response.error==true){
						                toaster.pop('error','Information',response.message);
						              }else{

						              	if ($stateParams.master[0].id == null){
						              		console.log('rates_detail');
						              		$state.go('app.rates_detail', {id:response.id});
						              	}else{
						              		console.log('rates');
						              		$state.go('app.' + module);
						              	}

						              }

								}).error(function() {
									busy = false;
									toaster.pop('error','Information','unable to save data ' + module + '!');
								});
							};

							$scope.cancel = function() {
								$state.go('app.' + module, {
									master : {}
								});
							};

							$scope.isEdit = function() {
								return ($stateParams.master[0].id == null) ? false : true;
							};


						}


					}

					$scope.loading = function() {
						return busy;
					};





				} ]);
