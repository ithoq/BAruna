angular.module('app').directive('uiLoadingbar', [ '$rootScope', '$anchorScroll', function($rootScope, $anchorScroll) {
	return {
		restrict : 'AC',
		template : '<span class="bar"></span>',
		link : function(scope, el, attrs) {
			el.addClass('butterbar hide');

			scope.$on('loadingBarShow', function() {
				$anchorScroll();
				el.removeClass('hide').addClass('active');
			});

			scope.$on('loadingBarHide', function() {
				el.addClass('hide').removeClass('active');
			});

			scope.$on('$destroy', function() {
				console.log('destroy');
			});

		}
	};
} ]);