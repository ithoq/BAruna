/**
 * 
 */
'use strict';
angular.module('app').factory('masterDataServices', [ '$resource', '$http', 'propertiesConstant', function($resource, $http, propertiesConstant) {
	var service = {
		master : $resource(  propertiesConstant.API_URL + ':module/:id', {}, {
			queryPage : {
				method : 'GET',
				isArray : false
			},
			save : {
				method : 'POST',
				isArray : false
			},
			update : {
				method : 'PUT',
				isArray : false
			}
		}),
		row : function(module) {
			return $http.get(propertiesConstant.API_URL + module );
		},
		get : function(params, callback) {
			return this.master.get(params, callback);
		},
		company : function(params, callback) {
				var company=$resource(  propertiesConstant.API_URL+'company/:id');
			   return company.get(params, callback);
		},
		group_company : function(params, callback) {
				var company=$resource(  propertiesConstant.API_URL + 'group_company/:id');
			   return company.get(params, callback);
		},
		user : function(params, callback) {
				var company=$resource(  propertiesConstant.API_URL + 'auth/user');
			   return company.get(params, callback);
		},
		queryPage : function(params, callback) {
			return this.master.queryPage(params, callback);
		},
		query : function(params, callback) {
			return this.master.query(params, callback);
		},
		save : function(module, master) {
			if (master.id == null) {
				return $http.post(propertiesConstant.API_URL + module , master);
			} else {
				return $http.put( propertiesConstant.API_URL + module + '/'+ master.id, master);
			}

		},
		remove : function(module, master) {
			if (master.id != null) {
				return $http.delete( propertiesConstant.API_URL + module  + '/' + master.id);
			}
		},
		savex : function(params, master, success, error) {
			if (master.id == null) {
				return this.master.save(params, master, success, error);
			} else {
				return this.master.update(params, master, success, error);
			}

		}
	};
	return service;
} ]);