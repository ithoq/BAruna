/**
 *
 */
'use strict';
angular.module('app').controller(
		'ProductCategoryController',
		[ '$scope', '$state', '$stateParams', 'propertiesConstant', 'TokenStorage', 'masterDataServices','toaster', 'FileUploader' ,
				function($scope, $state, $stateParams, propertiesConstant, TokenStorage, masterDataServices,toaster , FileUploader) {
					var busy = false;

					var storageKey = TokenStorage.retrieve();
					var title = $state.current.data.title;
					var type = $state.current.data.type;
					var module = $state.current.data.module;
					var criteria_key_list = $state.current.data.criteria;
					$scope.company_id=storageKey[propertiesConstant.COMPANY_ID];
					$scope.myImag='';

					$scope.company={};
					$scope.master={};
					masterDataServices.company({
							id:$scope.company_id
						}, function(response) {
						  $scope.company = angular.copy(response);
					});


					 var uploader = $scope.uploader = new FileUploader({
				          url: propertiesConstant.API_URL+'product_upload_image?company_id='+$scope.company_id
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
							  
						item.formData.push({post_id: $scope.master.id});
						
				    };



					uploader.onCompleteItem = function(fileItem, response, status, headers) {

						 busy = false;
						if (response.error==true){
							console.log(response.message);
						
						    toaster.pop('error','Information',response.message);
						}else{
							$state.go('app.' + module);
						}


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
							$scope.master = $stateParams.master[0];

							$scope.title = ($stateParams.master[0].id == null) ? 'New ' + title : 'Edit ' + title;
							$scope.buttonText = ($stateParams.master[0].id == null) ? 'Save' : 'Update';
							$scope.master = $stateParams.master[0];


							$scope.save = function(master) {
								busy = true;
								master.company_id=$scope.company_id;

								masterDataServices.save(module, master).success(function(response) {
									

									if (response.error==true){
										toaster.pop('error','Information',response.message);
									}else{
										if (master.id==null){
											$scope.master.id=response.id;
											if (response.id){
												 if (uploader.queue.length==1){
													uploader.uploadAll();	
												 }else{
												 	$state.go('app.' + module);
												 }
											}

										}else{
											
											 if (uploader.queue.length==1){
												uploader.uploadAll();	
											 }else{
											 	$state.go('app.' + module);
											 }
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

					}
				}

					$scope.loading = function() {
						return busy;
					};

					 var handleFileSelect=function(evt) {
					  	console.log('test');
						      var file=evt.currentTarget.files[0];
						      var reader = new FileReader();
						      reader.onload = function (evt) {
						        $scope.$apply(function($scope){
						          $scope.myImage=evt.target.result;
						          console.log($scope.myImage);
						        });
						      };
						      console.log(file);
						      reader.readAsDataURL(file);
						    };
						    angular.element(document.querySelector('#fileInput')).on('change',handleFileSelect);



				} ]);
