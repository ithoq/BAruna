/**
 * 
 */
'use strict';
angular.module('app').factory('permissionServices', [ '$rootScope', '$resource', 'propertiesConstant', function($rootScope, $resource, propertiesConstant) {
	var permissionList = {};
	var service = {
		master : $resource( propertiesConstant.API_URL + 'menu_permission', {}, {
			query : {
				method : 'GET',
				isArray : true
			}
		}),
		get : function(params, callback) {
			return this.master.query(params, callback);
		},
		setPermissions : function(permissions) {
			permissionList = permissions;
			$rootScope.$broadcast('permissionsChanged');
		},
		hasPermission : function(permission) {
			permission = permission.trim();

			return _.some(permissionList, function(item) {
				return item.code.trim() === permission;
			});

		}
	};
	return service;

} ]);