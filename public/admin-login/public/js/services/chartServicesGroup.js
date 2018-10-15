/**
 * 
 */
'use strict';
angular.module('app').service('chartService', function($http, $q,propertiesConstant, TokenStorage) {

    
    this.getTotalReservation = function() {
       var storageKey = TokenStorage.retrieve(); 
       var deferred = $q.defer();
       $http.get(propertiesConstant.API_URL +'today_reservation_group/'+storageKey[propertiesConstant.COMPANY_ID]).success(function(data) {
          deferred.resolve(data);
       }).error(function(error){
          deferred.reject(error);
       });
       return deferred.promise;
    }

    
    

     this.postCheckIn = function(id) {
       return $http.put(propertiesConstant.API_URL + 'checkin/'+id)
    }

    this.getReservation = function(callback) {
        var storageKey = TokenStorage.retrieve(); 
       return $http.get( propertiesConstant.API_URL + 'today_reservation_group/'+storageKey[propertiesConstant.COMPANY_ID],callback);
    }

});


