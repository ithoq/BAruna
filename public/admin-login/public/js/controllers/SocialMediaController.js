/**
 * 
 */
'use strict';
angular.module('app').controller(
		'SocialMediaController',
		[ '$scope', '$state', '$stateParams', 'propertiesConstant', 'TokenStorage', 'masterDataServices','toaster','$filter',
		function($scope, $state, $stateParams, propertiesConstant, TokenStorage, masterDataServices,toaster,$filter) {
			var busy = false;

			var storageKey = TokenStorage.retrieve();
			var title = $state.current.data.title;
			var type = $state.current.data.type;
			var module = $state.current.data.module;
			var criteria_key_list = $state.current.data.criteria;
			$scope.company_id=storageKey[propertiesConstant.COMPANY_ID];
			$scope.title="Social Media";
			$scope.master={};
			 masterDataServices.query({module:'setting',"company_id":$scope.company_id,"name":"social_media"},function(response){
				$scope.master=response[0];	
			 });
			


			 
			 $scope.up_one=function(index){
			 	var oldnav = $scope.master.value[index];
			 	var newnav=  $scope.master.value[index-1];

			 	$scope.master.value[index] = newnav;
				$scope.master.value[index-1] = oldnav;
				$scope.save($scope.master.value);
			 }

			
			 $scope.down_one=function(index){
			 	var oldnav = $scope.master.value[index];
			 	var newnav = $scope.master.value[index+1];
			 	$scope.master.value[index] = newnav;
				$scope.master.value[index+1] = oldnav;
				$scope.save($scope.master.value);
			 }



			 $scope.save_list=function(){
			 	$scope.save($scope.master.value);
			 }


			 $scope.save=function(data){
			 	var master={};
			 	master.id=$scope.master.id;
			 	master.company_id=$scope.company_id;
			 	master.name='social_media';
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