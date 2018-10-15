/**
 * 
 */
'use strict';
angular.module('app').directive('hasPermission', [ 'permissionServices', function(permissionServices) {
	return {
		link : function(scope, element, attrs) {
			if (!_.isString(attrs.hasPermission))
				throw 'hasPermission value must be a string';

			var value = attrs.hasPermission.trim();
			var notPermissionFlag = value[0] === '!';
			if (notPermissionFlag) {
				value = value.slice(1).trim();
			}

			function toggleVisibilityBasedOnPermission() {
				var hasPermission = permissionServices.hasPermission(value);
				if (hasPermission && !notPermissionFlag || !hasPermission && notPermissionFlag)
					element.show();
				else
					element.hide();
			}
			toggleVisibilityBasedOnPermission();
			scope.$on('permissionsChanged', toggleVisibilityBasedOnPermission);
		}
	};

} ]).directive('ngExportExcel', ['$timeout',
    function($timeout) {

      return {
        restrict: 'A',
        scope:{
          ngClick:"&",
          data:"="
        },
        link: function(scope, element, attrs) {
          element.bind('click', function() {
              console.log(scope.data);

            $timeout(function(){
               var link = document.createElement('a');
                if (scope.data){
                  link.href = scope.data; 
                }else{
                  link.href = attrs.data;
                }
               
                //Firefox requires the link to be in the body
                document.body.appendChild(link);
                //simulate click
                link.click();
                //remove the link when done
                document.body.removeChild(link);
            },100); // trigger download

            
          });

        }
      }
    }
  ]);