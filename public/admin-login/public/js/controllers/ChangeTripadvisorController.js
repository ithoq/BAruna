angular.module('app').controller(
    'ChangeTripadvisorController',
    [ '$scope', '$state', '$stateParams', 'propertiesConstant', 'TokenStorage', 'simpleDataServices', 'toaster',
        function($scope, $state, $stateParams, propertiesConstant, TokenStorage, simpleDataServices , toaster) {
    var busy = false;      
   var storageKey = TokenStorage.retrieve();
    $scope.myImage='';
    
    $scope.master={};
     simpleDataServices.get({
              module : 'company',
              company_id : storageKey[propertiesConstant.COMPANY_ID]
            }, function(data) {
              $scope.master = angular.copy(data);
              
            });

     $scope.Upload=function(){
                $scope.master.image=$scope.myImage;
                busy = true;
                simpleDataServices.save('change_tripadvisor', $scope.master).success(function(response) {
                  busy = false;
                  $state.go('app.company');
                }).error(function(error) {
                  busy = false;
                  toaster.pop('error','Information',error);
                });
              
     }

     $scope.loading = function() {
            return busy;
          };


    var handleFileSelect=function(evt) {
      
      
      var file=evt.currentTarget.files[0];
      var reader = new FileReader();
      reader.onload = function (evt) {
        $scope.$apply(function($scope){
          $scope.myImage=evt.target.result;
          console.log($scope.myImage);
        });
      };
      reader.readAsDataURL(file);
    };
    angular.element(document.querySelector('#fileInput')).on('change',handleFileSelect);
}]);