/**
 * 
 */
'use strict';
angular.module('app').factory('simpleDataServices', [ '$resource', '$http', 'propertiesConstant', function($resource, $http, propertiesConstant) {
	var service = {
		master : $resource( propertiesConstant.API_URL +  ':module'),
		get : function(params, callback) {
			return this.master.get(params, callback);
		},
		save : function(module, master) {
			if (master.id == null) {
				return $http.post( propertiesConstant.API_URL + module , master);
			} else {
				return $http.put( propertiesConstant.API_URL + module +'/' + master.id , master);
			}

		}
	};
	return service;
} ]);