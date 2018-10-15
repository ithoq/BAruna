<!DOCTYPE html>
<html lang="en" ng-app="app">
<head>
  <meta charset="utf-8" />
  <title>Think BALI | App</title>
  <meta name="description" content="" />
  <meta name="keywords" content="" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
   
   	{!! HTML::style('libs/assets/animate.css/animate.css') !!}
   	{!! HTML::style('libs/assets/font-awesome/css/font-awesome.min.css') !!}
   	{!! HTML::style('libs/assets/simple-line-icons/css/simple-line-icons.css') !!}
   	{!! HTML::style('css/app.min.css') !!}
   	{!! HTML::style('libs/angular/angular-loading-bar/build/loading-bar.min.css') !!}
  
   

  
</head>

<body ng-controller="AppCtrl">
  <div class="app" id="app" ng-class="{'app-header-fixed':app.settings.headerFixed, 'app-aside-fixed':app.settings.asideFixed, 'app-aside-folded':app.settings.asideFolded, 'app-aside-dock':app.settings.asideDock, 'container':app.settings.container}" ui-view></div>

  
  {!! HTML::script('libs/jquery/jquery/dist/jquery.min.js') !!}



  <!-- Bootstrap -->
  {!! HTML::script('libs/jquery/bootstrap/dist/js/bootstrap.min.js'); !!}

  <!-- Angular -->
  {!! HTML::script('libs/angular/angular/angular.min.js'); !!}
    
  {!! HTML::script('js/accounting.js'); !!} 
  {!! HTML::script('libs/angular/angular-animate/angular-animate.min.js'); !!}
  {!! HTML::script('libs/angular/angular-aria/angular-aria.min.js'); !!}
  {!! HTML::script('libs/angular/angular-cookies/angular-cookies.min.js'); !!}

  {!! HTML::script('libs/angular/angular-messages/angular-messages.min.js'); !!}
  {!! HTML::script('libs/angular/angular-resource/angular-resource.min.js'); !!}
  {!! HTML::script('libs/angular/angular-sanitize/angular-sanitize.min.js'); !!}
  {!! HTML::script('libs/angular/angular-touch/angular-touch.min.js'); !!}

  {!! HTML::script('libs/angular/angular-ui-router/release/angular-ui-router.min.js'); !!}
  {!! HTML::script('libs/angular/ngstorage/ngStorage.min.js'); !!}
  {!! HTML::script('libs/angular/angular-ui-utils/ui-utils.min.js'); !!} 
  {!! HTML::script('libs/angular/ng-idle/angular-idle.min.js'); !!}  
  {!! HTML::script('libs/angular/angular-loading-bar/build/loading-bar.min.js'); !!}  
 

  <!-- bootstrap -->
    {!! HTML::script('libs/angular/angular-bootstrap/ui-bootstrap-tpls.min.js'); !!}  
  
  <!-- lazyload -->
  {!! HTML::script('libs/angular/oclazyload/dist/ocLazyLoad.min.js'); !!}  
  
  <!-- translate -->
  {!! HTML::script('libs/angular/angular-translate/angular-translate.min.js'); !!}
  {!! HTML::script('libs/angular/angular-translate-loader-static-files/angular-translate-loader-static-files.min.js'); !!}
  
  {!! HTML::script('libs/angular/angular-translate-storage-cookie/angular-translate-storage-cookie.min.js'); !!}
  {!! HTML::script('libs/angular/angular-translate-storage-local/angular-translate-storage-local.min.js'); !!}


  <!-- underscore -->
  {!! HTML::script('libs/underscore/underscore-min.js'); !!}  
  

  <!-- App -->  
  {!! HTML::script('js/app.js'); !!}  
  {!! HTML::script('js/config.js'); !!}  
  {!! HTML::script('js/config.idle.js'); !!} 
  {!! HTML::script('js/config.rest.js'); !!} 
  {!! HTML::script('js/config.lazyload.js'); !!}  
  {!! HTML::script('js/config.adminrouter.js'); !!}  
  {!! HTML::script('js/main.js'); !!}  
  
  {!! HTML::script('js/services/ui-load.js'); !!}  
  {!! HTML::script('js/filters/fromNow.js'); !!} 
  {!! HTML::script('js/filters/formatNumber.js'); !!}  
  {!! HTML::script('js/directives/setnganimate.js'); !!}  
  {!! HTML::script('js/directives/ui-butterbar.js'); !!}  
  {!! HTML::script('js/directives/ui-focus.js'); !!}  
  {!! HTML::script('js/directives/ui-fullscreen.js'); !!}  
  {!! HTML::script('js/directives/ui-jq.js'); !!}  
  {!! HTML::script('js/directives/ui-module.js'); !!}  
  {!! HTML::script('js/directives/ui-nav.js'); !!}  
  {!! HTML::script('js/directives/ui-scroll.js'); !!}  
  {!! HTML::script('js/directives/ui-shift.js'); !!}  
  {!! HTML::script('js/directives/ui-toggleclass.js'); !!}  
  {!! HTML::script('js/directives/ui-delete.js'); !!}  
  {!! HTML::script('js/directives/ui-modal.js'); !!}  
  
  {!! HTML::script('js/services/chartServices.js'); !!}  
  {!! HTML::script('js/controllers/DashboardController.js'); !!} 


</body>
</html>
