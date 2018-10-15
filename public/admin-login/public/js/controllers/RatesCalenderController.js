/**
*
*/
'use strict';
angular.module('app').controller(
   'RatesCalenderController',
   [ '$scope', '$state', '$stateParams', 'propertiesConstant', 'TokenStorage', 'masterDataServices','$filter','toaster','editableOptions', 'editableThemes','$modal',
       function($scope, $state, $stateParams, propertiesConstant, TokenStorage, masterDataServices,$filter,toaster,editableOptions, editableThemes,$modal) {
         var busy = false;
         var storageKey = TokenStorage.retrieve();

         var title = $state.current.data.title;
         var type = $state.current.data.type;
         var module = $state.current.data.module;
         $scope.title=title;
         $scope.rates={};

         editableThemes.bs3.inputClass = 'input-sm';
           editableThemes.bs3.buttonsClass = 'btn-sm';
           editableOptions.theme = 'bs3';

        $scope.company_id=storageKey[propertiesConstant.COMPANY_ID];
        $scope.criteria={};

        $scope.open_start = function($event) {
            $event.preventDefault();
            $event.stopPropagation();
            $scope.start_opened = true;
          };


          $scope.open_end = function($event) {
            $event.preventDefault();
            $event.stopPropagation();
            $scope.end_opened = true;
          };

          $scope.dateOptions = {
            formatYear: 'yy',
            startingDay: 1,
            class: 'datepicker'
          };

          $scope.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'dd.MM.yyyy', 'shortDate'];
          $scope.format = $scope.formats[0];


          $scope.list_dates=[];
          $scope.list_room_rates=[];
          $scope.company={};
          masterDataServices.company({
							id : $scope.company_id
						},function(response){
              $scope.company=response;
							$scope.use_app_date=response.use_app_date;
						 $scope.application_date=response.app_date;
					      var date = new Date(response.app_date);
					      var firstDay = new Date(response.app_date);
								firstDay.setDate(firstDay.getDate() -1);
					      $scope.criteria.start_date=$filter('date')(firstDay, 'yyyy-MM-dd');

					      var lastDay = new Date(response.app_date);
								lastDay.setDate(lastDay.getDate() + 30);
					      $scope.criteria.end_date=$filter('date')(lastDay,'yyyy-MM-dd');
                $scope.search();


					});

          $scope.opan_modal=function(){
            var items={
                company:$scope.company,
                rates_id:$stateParams.id
            };
            var modalInstance = $modal.open({
              templateUrl: 'modalBulkUpdate.html',
              controller: 'ModalBulkUpdateController',
              resolve: {
                items: function () {
                  return items;
                }
              }
            });

            modalInstance.result.then(function (status) {
              console.log(status);
              if (status=='refresh'){
                   $scope.search();
              }

              });

          }

          $scope.search=function(){
            var start_date=$filter('date')($scope.criteria.start_date,'yyyy-MM-dd');
            var end_date=$filter('date')($scope.criteria.end_date,'yyyy-MM-dd');
            masterDataServices.get({module:'rates_calender',
                                      id:$stateParams.id,
                                      company_id:$scope.company_id,
                                    start_date:start_date,
                                    end_date:end_date},function(response){

                    $scope.list_dates=angular.copy(response.dates);
                    $scope.list_room_rates=angular.copy(response.room_rates);


            });
          }


          $scope.save = function(master) {
            busy = true;
            master.company_id=$scope.company_id;
            masterDataServices.save('rates_room_detail', master).success(function(response) {
              busy = false;
              if (response.error==true){
                        toaster.pop('error','Information',response.message);
              }else{
                      toaster.pop('success','Information',response.message);
                }

            }).error(function() {
              busy = false;
              toaster.pop('error','Information','unable to save data ' + module + '!');
            });
          }





} ]);

angular.module('app')
.controller('ModalBulkUpdateController', ['$scope', '$modalInstance', 'items','$state','masterDataServices' ,'$filter','toaster',
function($scope, $modalInstance, items,$state,masterDataServices ,$filter,toaster) {


	$scope.popup_title="Update Bulk";
	$scope.company=items.company;
  $scope.rates_id=items.rates_id;
  var busy = false;
    $scope.master={};
  var date = new Date($scope.company.app_date);
  $scope.master.start_date=$filter('date')(date, 'yyyy-MM-dd');
  var lastDay =new Date($scope.company.app_date);
   lastDay.setDate(lastDay.getDate() +1);
  $scope.master.end_date=$filter('date')(lastDay,'yyyy-MM-dd');
  $scope.list_room_type = masterDataServices.query({module:'room_type',company_id:$scope.company.id});

          $scope.open_start = function($event) {
              $event.preventDefault();
              $event.stopPropagation();
              $scope.start_opened = true;
            };


            $scope.open_end = function($event) {
              $event.preventDefault();
              $event.stopPropagation();
              $scope.end_opened = true;
            };

            $scope.dateOptions = {
              formatYear: 'yy',
              startingDay: 1,
              class: 'datepicker'
            };

            $scope.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'dd.MM.yyyy', 'shortDate'];
            $scope.format = $scope.formats[0];


            $scope.save = function(master) {
              busy = true;
              master.rates_id=$scope.rates_id;
              master.company_id=$scope.company_id;
              master.start_date=$filter('date')(master.start_date, 'yyyy-MM-dd');
              master.end_date=$filter('date')(master.end_date, 'yyyy-MM-dd');
              masterDataServices.save('rates_bulk_update', master).success(function(response) {
                busy = false;
                if (response.error==true){
                          toaster.pop('error','Information',response.message);
                }else{
                        toaster.pop('success','Information','Update Rates Success!');
                        $modalInstance.close('refresh');

                  }

              }).error(function() {
                busy = false;
                toaster.pop('error','Information','unable to save data ' + module + '!');
              });
            }


  	$scope.loading = function() {
  		return busy;
  	};

    $scope.close = function () {
			$modalInstance.dismiss('cancel');
    };


  }]);
