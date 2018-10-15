/**
 * 
 */
'use strict';
angular.module('app').controller(
		'MenuNavigationHeaderController',
		[ '$scope', '$state', '$stateParams', 'propertiesConstant', 'TokenStorage', 'masterDataServices','toaster',
		function($scope, $state, $stateParams, propertiesConstant, TokenStorage, masterDataServices,toaster ) {
			var busy = false;

							
			var storageKey = TokenStorage.retrieve();
			var title = $state.current.data.title;
			var type = $state.current.data.type;
			var module = $state.current.data.module;
			var criteria_key_list = $state.current.data.criteria;
			$scope.company_id=storageKey[propertiesConstant.COMPANY_ID];
			$scope.title="Menu Navigation";
			$scope.list_menu_navigation_header=masterDataServices.query({module:'setting',group:'menu_navigation'});	



			$scope.detail=function(master){
				$state.go('app.menu_navigation',{id:master.id});

			}


		} ]);