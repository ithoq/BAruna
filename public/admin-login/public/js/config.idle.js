/**
 * 
 */
angular.module('app').config(function(IdleProvider) {
	IdleProvider.idle(60 * 10);
	IdleProvider.timeout(1);
});