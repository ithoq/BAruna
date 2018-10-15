angular.module('app')
.directive('ngDeleteClick', ['$modal',
    function($modal) {

      var ModalDeleteInstanceCtrl = function($scope, $modalInstance) {
        $scope.ok = function() {
          $modalInstance.close();
        };

        $scope.cancel = function() {
          $modalInstance.dismiss('cancel');
        };
      };

      return {
        restrict: 'A',
        scope:{
          ngDeleteClick:"&",
          item:"="
        },
        link: function(scope, element, attrs) {
          element.bind('click', function() {
            var message = attrs.ngReallyMessage || "Are you sure delete this data?";
            var modalHtml = '<div class="modal-body">' + message + '</div>';
            modalHtml += '<div class="modal-footer"><button class="btn btn-primary" ng-click="ok()">OK</button><button class="btn btn-warning" ng-click="cancel()">Cancel</button></div>';

            var modalInstance = $modal.open({
              template: modalHtml,
              controller: ModalDeleteInstanceCtrl
            });

            modalInstance.result.then(function() {
              scope.ngDeleteClick({item:scope.item}); 
              
            }, function() {
              //Modal dismissed
            });
            //*/
            
          });

        }
      }
    }
  ])