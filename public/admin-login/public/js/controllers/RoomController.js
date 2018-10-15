/**
 *
 */
'use strict';
angular.module('app').controller(
		'RoomController',
		[ '$scope', '$state', '$stateParams', 'propertiesConstant', 'TokenStorage', 'masterDataServices','toaster', 'FileUploader' ,
				function($scope, $state, $stateParams, propertiesConstant, TokenStorage, masterDataServices,toaster , FileUploader) {
					var busy = false;


					var storageKey = TokenStorage.retrieve();
					var title = $state.current.data.title;
					var type = $state.current.data.type;
					var module = $state.current.data.module;
					var criteria_key_list = $state.current.data.criteria;
					$scope.company_id=storageKey[propertiesConstant.COMPANY_ID];
					$scope.list_images=[];
					$scope.company={};
					masterDataServices.company({
							id:$scope.company_id
						}, function(response) {
						  $scope.company = angular.copy(response);
					});


					 var uploader = $scope.uploader = new FileUploader({
				          url: propertiesConstant.API_URL+'room_gallery?company_id='+$scope.company_id
				    });


					  // FILTERS

				    uploader.filters.push({
				        name: 'customFilter',
				        fn: function(item /*{File|FileLikeObject}*/, options) {
				            return this.queue.length < 10;
				        }
				    });

				     uploader.onBeforeUploadItem = function(item) {
					  	   item.headers = {
							    'Authorization': 'Bearer '+storageKey[propertiesConstant.STORAGE_KEY]
							  };
							    item.formData.push({room_id: $scope.master.id});
				    };



					uploader.onCompleteItem = function(fileItem, response, status, headers) {
						if (response.error==false){
							$scope.list_images.push(response.data);

						}else{
							toaster.pop('error','Information',response.message);
						}


				    };

				    uploader.onCompleteAll = function(response) {

				    	// $state.go('app.' + module);
				    	// toaster.pop('success','Information','Process upload done!!');
				    	uploader.clearQueue();
				    	$scope.is_upload=false;
				    };




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
							}, function() {
								$scope.totalItems = $scope.page.total;

							});


						};

						$scope.pageChanged();

						$scope.create = function() {
							$state.go('app.' + module + '-edit', {
								master : {}
							});
						};

						$scope.edit = function(master) {
							$state.go('app.' + module + '-edit', {
								master : master
							});
						};


						$scope.view = function(master) {
							$state.go('app.room', {
								property_id :master.id
							});
						};



						$scope.remove = function(master) {
							masterDataServices.remove(module, master).success(function() {
								$scope.pageChanged();
							}).error(function() {
								toaster.pop('error','Information','unable to delete data !');
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
							$scope.list_facilities=masterDataServices.query({module:'room_facilities',company_id:$scope.company_id,type:'ROOM', room_id:$stateParams.master[0].id});
							$scope.list_tags=[];
							if ($stateParams.master[0].id){
								$scope.list_tags=masterDataServices.query({module:'room_tags',company_id:$scope.company_id,room_id:$stateParams.master[0].id});
							}
							$scope.master = $stateParams.master[0];

							if ($stateParams.master[0].id == null){
							}else{
								$scope.list_images=masterDataServices.query({module:'room_gallery',product_id:$stateParams.master[0].id});
							}

							$scope.setMainImages=function(id){
								var images = {id:$stateParams.master[0].id,image_id:id};
								masterDataServices.save('update_main_room_image', images).success(function(response) {
									busy = false;
									if (response.error==true){
						                toaster.pop('error','Information',response.message);
						              }else{
						              	toaster.pop('success','Information',response.message);
						              }
						         });

							}

							$scope.save_tag=function(data){
								data.room_id=$scope.master.id;
								data.company_id=$scope.company_id;
								masterDataServices.save('room_tags', data).success(function(response) {
									busy = false;
									if (response.error==true){
						                toaster.pop('error','Information',response.message);
						              }else{

						              	 angular.forEach(response.data, function(value, key){
												$scope.list_tags.push(value);
										   });


						              	$scope.tag={};
						              }

								}).error(function() {
									busy = false;
									toaster.pop('error','Information','unable to save data !');
								});
							}


							$scope.save = function(master) {
								busy = true;
								master.company_id=$scope.company_id;
								master.facilities=$scope.list_facilities;
								masterDataServices.save(module, master).success(function(response) {
									busy = false;
									if (response.error==true){
						                toaster.pop('error','Information',response.message);
						              }else{
						              	if (master.id==null){
						              		$scope.master.id=response.id;
						              		if (response.id){
						              			uploader.uploadAll();
						              		}

						              	}else{
						              		$state.go('app.' + module);
						              	}


						              }

								}).error(function() {
									busy = false;
									toaster.pop('error','Information','unable to save data !');
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



						$scope.remove_gallery=function(master,index){
						    	masterDataServices.remove('room_gallery', master).success(function() {
										$scope.list_images.splice(index, 1);
									}).error(function() {
										toaster.pop('error','Information','unable to delete data !');
									});
						}

						$scope.remove_tags=function(master,index){
						    	masterDataServices.remove('room_tags', master).success(function() {
										$scope.list_tags.splice(index, 1);
									}).error(function() {
										toaster.pop('error','Information','unable to delete data !');
									});
						}


					}
				}

					$scope.loading = function() {
						return busy;
					};





				} ]);
