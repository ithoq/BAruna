'use strict';
angular.module('app', [
    'ngAnimate',
    'ngAria',
    'ngCookies',
    'ngMessages',
    'ngResource',
    'ngSanitize',
    'ngTouch',
    'ngStorage',
    'ui.router',
    'ui.bootstrap',
    'ui.utils',
    'ui.load',
    'ui.jq',
    'oc.lazyLoad',
    'pascalprecht.translate',
    'ngIdle',
    'angular-loading-bar',
    'daypilot'
]).constant('propertiesConstant', {
    BASE_URL : '/guestpro_cloud_pms',
    API_URL : 'http://demo.bpraruna.com/api/',
    STORAGE_KEY : 'guestpro_cloud_travel_auth_token',
    TOKEN_HEADER : 'Authorization',
    LOCAL_STORAGE : 'LOCAL_STORAGE',
    COMPANY_ID : 'COMPANY_ID'
})
