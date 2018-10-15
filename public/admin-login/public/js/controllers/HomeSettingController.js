/**
 *
 */
'use strict';
angular.module('app').controller(
		'HomeSettingController',
		[ '$scope', '$state', '$stateParams', 'propertiesConstant', 'TokenStorage', 'masterDataServices','toaster','$filter',
		function($scope, $state, $stateParams, propertiesConstant, TokenStorage, masterDataServices,toaster,$filter) {
			var busy = false;

			var storageKey = TokenStorage.retrieve();
			var title = $state.current.data.title;
			var type = $state.current.data.type;
			var module = $state.current.data.module;
			var criteria_key_list = $state.current.data.criteria;
			$scope.company_id=storageKey[propertiesConstant.COMPANY_ID];

			$scope.title="Home Setting";
			$scope.product_list={};
			$scope.gallery={};
			$scope.text_type={};
			$scope.blog={};
			$scope.list_room = masterDataServices.query({module:'room',"company_id":$scope.company_id});
			$scope.list_category = masterDataServices.query({module:'category',"company_id":$scope.company_id});
			$scope.list_post_category=masterDataServices.query({module:'post_category',"company_id":$scope.company_id});

			$scope.master={};
			$scope.list_gallery_category=masterDataServices.query({module:'gallery_category',"company_id":$scope.company_id});

			 masterDataServices.query({module:'setting',"company_id":$scope.company_id,"name":"home_setting"},function(response){
				$scope.master=response[0];


			 });

			 $scope.add_category=function(category,key){
			 		var category_selected=[];

			 		angular.forEach($scope.list_category, function(value, key) {
						if (value.is_checked==true){
								category_selected.push({category_id:value.id});
						}
					});
					$scope.master.value.data[key].data=category_selected;
					$scope.save($scope.master.value);

			 }


			 $scope.up_one=function(index){
			 	var oldnav = $scope.master.value.data[index];
			 	var newnav=  $scope.master.value.data[index-1];

			 	$scope.master.value.data[index] = newnav;
				$scope.master.value.data[index-1] = oldnav;
				$scope.save($scope.master.value);
			 }


			 $scope.down_one=function(index){
			 	var oldnav = $scope.master.value.data[index];
			 	var newnav = $scope.master.value.data[index+1];
			 	$scope.master.value.data[index] = newnav;
				$scope.master.value.data[index+1] = oldnav;
				$scope.save($scope.master.value);
			 }


			$scope.save_product_list=function(data){
				var product = {"name":"product","label":"Product List","status":true,"data":data};
				$scope.master.value.data.push(product);
				$scope.save($scope.master.value);
				$scope.product_list={};
			}


			$scope.save_text=function(data){
				var text = {"name":"text","label":"Text","status":true,"data":data};
				$scope.master.value.data.push(text);
				$scope.save($scope.master.value);
				$scope.text_type={};
			}




			$scope.save_room=function(data){
				var product = {"name":"room","label":"Room","status":true,"data":data};
				$scope.master.value.data.push(product);
				$scope.save($scope.master.value);

			}


			$scope.save_blog=function(data){
				var product = {"name":"blog","label":"Blog List","status":true,"data":data};
				$scope.master.value.data.push(product);
				$scope.save($scope.master.value);
				$scope.blog={};
			}

			$scope.save_gallery=function(data){
				var selected = $filter('filter')($scope.master.value.data, {name: 'gallery'},true);
				if (selected.length>0){
					toaster.pop('error','Information','This gallery already in home setting!');
				}else{
					var gallery = {"name":"gallery","label":"Gallery","status":true,"data":data};
					$scope.master.value.data.push(gallery);
					$scope.save($scope.master.value);
					$scope.gallery={};
				}
			}

			$scope.delete_product=function(index){
				$scope.master.value.data.splice(index, 1);
				$scope.save($scope.master.value);
			}

			$scope.update_product=function(data,index){
				$scope.master.value.data[index].data=data;
				$scope.save($scope.master.value);
			}

			$scope.update_text=function(data,index){
				$scope.master.value.data[index].data=data;
				$scope.save($scope.master.value);
			}

			$scope.update_room=function(data,index){
				$scope.master.value.data[index].data=data;
				$scope.save($scope.master.value);
			}


			$scope.delete_blog=function(index){
				$scope.master.value.data.splice(index, 1);
				$scope.save($scope.master.value);
			}

			$scope.update_blog=function(data,index){
				$scope.master.value.data[index].data=data;
				$scope.save($scope.master.value);
			}


			 $scope.save_list=function(){
			 	$scope.save($scope.master.value);
			 }


			 $scope.save=function(data){
			 	var master={};
			 	master.id=$scope.master.id;
			 	master.company_id=$scope.company_id;
			 	master.name='home_setting';
			 	master.value=angular.toJson(data);
			 	console.log(master);
				masterDataServices.save('setting', master).success(function(response) {
					busy = false;
					if (response.error==true){
		                toaster.pop('error','Information',response.message);
		              }else{
		                toaster.pop('success','Information',response.message);
		              }

				}).error(function() {
					busy = false;
					toaster.pop('error','Information','unable to save data ' + module + '!');
				});
			 }

		} ]);
