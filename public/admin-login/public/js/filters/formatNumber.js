'use strict';

/* Filters */
// need load the moment.js to use this filter. 
angular.module('app')
  .filter('currencycode',
    [ '$filter', '$locale', function(filter, locale) {
      return function(amount, currencySymbol) {
              var options = {};


          if (currencySymbol){
              if (currencySymbol.toUpperCase() == 'USD'){
               options = {
                                          symbol : "$",
                                          decimal : ".",
                                          thousand: ",",
                                          precision : 2,
                                          format: "%s %v"
                              };
          }else  if (currencySymbol.toUpperCase() == 'IDR'){
               options = {
                                          symbol : "",
                                          decimal : ",",
                                          thousand: ".",
                                          precision : 0,
                                          format: "%s %v"
                              };

          }
          }


   return accounting.formatMoney(amount, options) ;
      };
}]);
