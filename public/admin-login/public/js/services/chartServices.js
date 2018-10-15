/**
 * 
 */
'use strict';
angular.module('app').service('chartService', function($http, $q,propertiesConstant,TokenStorage) {

    this.getTopAgent = function() {
       var deferred = $q.defer();
       var storageKey = TokenStorage.retrieve();
       $http.get(propertiesConstant.API_URL +'top_5_agent?company_id='+storageKey[propertiesConstant.COMPANY_ID]).success(function(data) {
          deferred.resolve(data);
       }).error(function(error){
          deferred.reject(error);
       });
       return deferred.promise;
    }

    this.getTopCountry = function() {
       var deferred = $q.defer();
       var storageKey = TokenStorage.retrieve();
       $http.get(propertiesConstant.API_URL+'top_5_country?company_id='+storageKey[propertiesConstant.COMPANY_ID]).success(function(data) {
          deferred.resolve(data);
       }).error(function(error){
          deferred.reject(error);
       });
       return deferred.promise;
    }

     this.getForeCast = function() {
       var deferred = $q.defer();
       var storageKey = TokenStorage.retrieve();
       $http.get(propertiesConstant.API_URL+'report_forecast?company_id='+storageKey[propertiesConstant.COMPANY_ID]).success(function(data) {
          deferred.resolve(data);
       }).error(function(error){
          deferred.reject(error);
       });
       return deferred.promise;
    }

    this.getTotalReservation = function() {
       var deferred = $q.defer();
       var storageKey = TokenStorage.retrieve();
       $http.get(propertiesConstant.API_URL+'today_reservation?company_id='+storageKey[propertiesConstant.COMPANY_ID]).success(function(data) {
          deferred.resolve(data);
       }).error(function(error){
          deferred.reject(error);
       });
       return deferred.promise;
    }

     this.getDataOccupancy = function() {
       var deferred = $q.defer();
       var storageKey = TokenStorage.retrieve();
       $http.get(propertiesConstant.API_URL + 'get_occupancy?company_id='+storageKey[propertiesConstant.COMPANY_ID]).success(function(data) {
          deferred.resolve(data);
       }).error(function(error){
          deferred.reject(error);
       });
       return deferred.promise;
    }

    this.getWeeklyMonthly = function() {
       var deferred = $q.defer();
       var storageKey = TokenStorage.retrieve();
       $http.get(propertiesConstant.API_URL + 'monthly_sales?company_id='+storageKey[propertiesConstant.COMPANY_ID]).success(function(data) {
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
       return $http.get(propertiesConstant.API_URL + 'today_reservation?company_id='+storageKey[propertiesConstant.COMPANY_ID],callback);
    }

});


