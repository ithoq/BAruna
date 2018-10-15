/**
 * 
 */
'use strict';
angular.module('app').controller(
		'SliderController',
		[ '$scope', '$state', '$stateParams', 'propertiesConstant', 'TokenStorage', 'masterDataServices','toaster', 'FileUploader' ,'editableOptions', 'editableThemes',
				function($scope, $state, $stateParams, propertiesConstant, TokenStorage, masterDataServices,toaster , FileUploader,editableOptions, editableThemes) {
					var busy = false;

					editableThemes.bs3.inputClass = 'input-sm';
				    editableThemes.bs3.buttonsClass = 'btn-sm';
				    editableOptions.theme = 'bs3';						
					var storageKey = TokenStorage.retrieve();
					var title = $state.current.data.title;
					var link = $state.current.data.link;
					var type = $state.current.data.type;
					var module = $state.current.data.module;
					var criteria_key_list = $state.current.data.criteria;
					$scope.company_id=storageKey[propertiesConstant.COMPANY_ID];
					$scope.title="Slider";
					$scope.list_images=[];
					 var uploader = $scope.uploader = new FileUploader({
				        url: propertiesConstant.API_URL+'upload_slider?company_id='+$scope.company_id
				    });

					 uploader.filters.push({
				        name: 'customFilter',
				        fn: function(item /*{File|FileLikeObject}*/, options) {
				            return this.queue.length < 10;
				        }
				    });

					  
					  $scope.search=function(){
					  	$scope.list_images=[];
					  	masterDataServices.query({module:'slider'},function(response){
									$scope.list_images=response;
								});
					  }
					 
					$scope.search();

				    $scope.remove=function(master,index){
				    	masterDataServices.remove('slider', master).success(function() {
								$scope.list_images.splice(index, 1);
							}).error(function() {
								toaster.pop('error','Information','unable to delete data !');
							});
				    }


				    $scope.update=function(master){
				    	masterDataServices.save('slider', master).success(function(response) {
							busy = false;
						if (response.error==true){
				                toaster.pop('error','Information',response.message);
			              }
						}).error(function() {
							busy = false;
							toaster.pop('error','Information','unable to save data !');
							
						});
				    }

					uploader.onCompleteItem = function(fileItem, response, status, headers) {
				        $scope.list_images.push(response.data);
				    };

				    uploader.onCompleteAll = function() {
				    	// toaster.pop('success','Information','Process upload done!!');
				    	uploader.clearQueue();
				    	$scope.is_upload=false;
				    };

	

					$scope.loading = function() {
						return busy;
					};

					



				} ]);