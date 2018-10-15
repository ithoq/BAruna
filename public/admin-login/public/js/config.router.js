
/**
 * Config for the router
 */
angular.module('app').run(
		[ '$rootScope', '$state', '$stateParams', 'TokenStorage', 'propertiesConstant', function($rootScope, $state, $stateParams, TokenStorage, propertiesConstant) {
			$rootScope.$state = $state;
			$rootScope.$stateParams = $stateParams;

			$rootScope.$on('$stateChangeStart', function(event, toState, toParams, fromState, fromParams) {
				var authenticated = toState.name === 'access.signin';
				if (authenticated) {
					return;
				}

				var storageKey = TokenStorage.retrieve();
				if (!storageKey) {
					event.preventDefault();
					$state.go('access.signin');
				}

			});
		} ]).config(
		[
				'$stateProvider',
				'$urlRouterProvider',
				'JQ_CONFIG',
				'MODULE_CONFIG',
				function($stateProvider, $urlRouterProvider, JQ_CONFIG, MODULE_CONFIG) {
					var layout = "public/tpl/app.html";
					$urlRouterProvider.otherwise('/access/signin');

					$stateProvider.state(
					'app',
					{
								abstract : true,
								url : '/app',
								controller : 'AppController',
								templateUrl : layout,
								resolve : load([ 'public/js/controllers/AppController.js', 'public/js/services/authenticationServices.js', 'public/js/services/permissionServices.js','public/js/services/masterDataServices.js',
										'public/js/directives/hasPermission.js' ])
					}).state('app.dashboard', {
						url : '/dashboard',
						templateUrl : 'public/tpl/app_dashboard_v1.html',
						controller : 'DashboardController',
						resolve: {
								     //  data_top_agent: function(chartService){
								     //    return chartService.getTopAgent();
								     //  },
								     //  data_forecast: function(chartService){
								     //    return chartService.getForeCast();
								     //  },
								     //  data_top_country: function(chartService){
								     //    return chartService.getTopCountry();
								     //  },
								     //  total_today_reservation:function(chartService){
								     //    return chartService.getTotalReservation();
								     //  },
								     //  data_occupancy:function(chartService){
								     //  	return chartService.getDataOccupancy();
								     //  },
								     // data_service:function(chartService){
								     //  	return chartService;
								     //  },
								     //  data_monthly_sales:function(chartService){
								     //  	return chartService.getWeeklyMonthly();;
								     //  },


								  },
						// resolve : load([ 'public/js/controllers/DashboardController.js', 'public/js/services/simpleDataServices.js' , 'public/js/services/masterDataServices.js', 'public/js/services/chartServices.js']),
						data : {
							title : 'Dashboard',
							type : 'list',
							module : 'dashboard',

						}
					}).state('app.company', {
						url : '/company',
						controller : 'SimpleDataController',
						templateUrl : 'public/views/company/list.html',
						resolve : load([ 'toaster', 'public/js/controllers/SimpleDataController.js', 'public/js/services/simpleDataServices.js' ]),
						data : {
							title : 'Company Profile',
							type : 'list',
							module : 'company'
						}
					}).state('app.company-edit', {
						url : '/company-edit',
						controller : 'SimpleDataController',
						templateUrl : 'public/views/company/form.html',
						resolve : load([ 'toaster', 'textAngular','public/js/controllers/SimpleDataController.js', 'public/js/services/simpleDataServices.js' ]),
						data : {
							title : 'Company Profile',
							type : 'form',
							module : 'company',
							use_date:true
						},
						params : {
							master : {
								array : true
							}
						}
					}).state('app.company-edit-logo', {
						url : '/company-edit-logo',
						controller : 'ChangeLogoController',
						templateUrl : 'public/views/company/change_logo.html',
						resolve : load([ 'toaster', 'ngImgCrop', 'public/js/controllers/ChangeLogoController.js', 'public/js/services/simpleDataServices.js' ]),
						data : {
							title : 'Change Logo',
							type : 'form',
							module : 'company'
						},
						params : {
							master : {
								array : true
							}
						}
					}).state('app.company-edit-image', {
						url : '/company-edit-image',
						controller : 'ChangeImageController',
						templateUrl : 'public/views/company/change_image.html',
						resolve : load([ 'toaster', 'ngImgCrop', 'public/js/controllers/ChangeImageController.js', 'public/js/services/simpleDataServices.js' ]),
						data : {
							title : 'Change Image',
							type : 'form',
							module : 'company'
						},
						params : {
							master : {
								array : true
							}
						}
					}).state('app.company-edit-tripadvisor', {
						url : '/company-edit-tripadvisor',
						controller : 'ChangeTripadvisorController',
						templateUrl : 'public/views/company/change_tripadvisor.html',
						resolve : load([ 'toaster', 'ngImgCrop', 'public/js/controllers/ChangeTripadvisorController.js', 'public/js/services/simpleDataServices.js' ]),
						data : {
							title : 'Change Tripadvisor',
							type : 'form',
							module : 'company'
						},
						params : {
							master : {
								array : true
							}
						}
					}).state('app.user', {
						url : '/user',
						controller : 'MasterDataController',
						templateUrl : 'public/views/user/list.html',
						resolve : load([ 'toaster', 'public/js/controllers/MasterDataController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'User',
							type : 'list',
							module : 'user',
							criteria : [ {key:'name',label:'Name'} , {key:'username',label:'Username'}]
						}
					}).state('app.user-edit', {
						url : '/user-edit',
						controller : 'MasterDataController',
						templateUrl : 'public/views/user/form.html',
						resolve : load([ 'toaster', 'public/js/controllers/MasterDataController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'User',
							type : 'form',
							module : 'user',
							combo_box : ['role']
						},
						params : {
							master : {
								array : true
							}

						}
					}).state('app.role', {
						url : '/role',
						controller : 'MasterDataController',
						templateUrl : 'public/views/role/list.html',
						resolve : load([ 'toaster' , 'public/js/controllers/MasterDataController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Role',
							type : 'list',
							module : 'role',
							criteria : [ {key:'name',label:'Name'}]
						}
					}).state('app.role-edit', {
						url : '/role-edit',
						controller : 'MasterDataController',
						templateUrl : 'public/views/role/form.html',
						resolve : load([ 'toaster' , 'public/js/controllers/MasterDataController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Role',
							type : 'form',
							module : 'role'
						},
						params : {
							master : {
								array : true
							}
						}
					}).state('app.role-detail', {
						url : '/role-detail/:id',
						controller : 'RoleDetailController',
						templateUrl : 'public/views/role/detail.html',
						resolve : load([ 'public/js/controllers/RoleDetailController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Role Detail',
							type : 'form',
							module : 'role_detail'
						}
					}).state('app.userprofile', {
						url : '/userprofile',
						controller : 'SimpleDataController',
						templateUrl : 'public/views/user/list_userprofile.html',
						resolve : load([ 'toaster', 'public/js/controllers/SimpleDataController.js', 'public/js/services/simpleDataServices.js' ]),
						data : {
							title : 'User Profile',
							type : 'list',
							module : 'userprofile'
						}
					}).state('app.userprofile-edit', {
						url : '/userprofile-edit',
						controller : 'SimpleDataController',
						templateUrl : 'public/views/user/form_userprofile.html',
						resolve : load([ 'toaster', 'public/js/controllers/SimpleDataController.js', 'public/js/services/simpleDataServices.js' ]),
						data : {
							title : 'User Profile',
							type : 'form',
							module : 'userprofile'
						},
						params : {
							master : {
								array : true
							}
						}
					}).state('app.passwd', {
						url : '/passwd',
						controller : 'PasswdController',
						templateUrl : 'public/views/user/change_passwd.html',
						resolve : load([ 'public/js/controllers/PasswdController.js', 'public/js/services/authenticationServices.js' ]),
						data : {
							title : 'Change User Password',
							type : 'form'
						}
					}).state('access', {
						url : '/access',
						template : '<div ui-view class="fade-in-right-big smooth"></div>'
					}).state('access.signin', {
						url : '/signin',
						controller : 'LoginController',
						templateUrl : 'public/tpl/page_signin.html',
						resolve : load([ 'public/js/controllers/LoginController.js', 'public/js/services/authenticationServices.js' ])
					}).state('access.forgotpwd', {
						url : '/forgotpwd',
						templateUrl : 'public/tpl/page_forgotpwd.html'
					}).state('access.404', {
						url : '/404',
						templateUrl : 'public/tpl/page_404.html'
					}).state('app.uom', {
						url : '/uom',
						controller : 'MasterDataController',
						templateUrl : 'public/views/uom/list.html',
						resolve : load([ 'toaster', 'public/js/controllers/MasterDataController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Units',
							type : 'list',
							module : 'uom',
							criteria : [ {key:'name',label:'Name'}]
						}
					}).state('app.category', {
						url : '/category',
						controller : 'MasterDataController',
						templateUrl : 'public/views/category/list.html',
						resolve : load([ 'toaster', 'public/js/controllers/MasterDataController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Category',
							type : 'list',
							module : 'category',
							criteria : [ {key:'name',label:'Name'}]
						}
					}).state('app.category-edit', {
						url : '/category-edit',
						controller : 'MasterDataController',
						templateUrl : 'public/views/category/form.html',
						resolve : load([ 'toaster', 'public/js/controllers/MasterDataController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Category',
							type : 'form',
							module : 'category',
							combo_box : ['category']
						},
						params : {
							master : {
								array : true
							}

						}

					}).state('app.blog', {
						url : '/blog',
						controller : 'BlogController',
						templateUrl : 'public/views/blog/list.html',
						resolve : load([  'angularFileUpload' , 'textAngular', 'toaster','public/js/controllers/BlogController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Blog',
							type : 'list',
							module : 'blog',
							criteria : [ {key:'name',label:'Name'}]
						}
					}).state('app.blog-edit', {
						url : '/blog-edit',
						controller : 'BlogController',
						templateUrl : 'public/views/blog/form.html',
						resolve : load([ 'angularFileUpload' , 'textAngular', 'toaster', 'public/js/controllers/BlogController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Post',
							type : 'form',
							module : 'blog',
						},
						params : {
							master : {
								array : true
							}

						}

					}).state('app.page', {
						url : '/page',
						controller : 'PageController',
						templateUrl : 'public/views/page/list.html',
						resolve : load([  'angularFileUpload' , 'textAngular', 'toaster','public/js/controllers/PageController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Page',
							type : 'list',
							module : 'page',
							module_type : 'PAGE',
							criteria : [ {key:'name',label:'Name'}]
						}
					}).state('app.page-edit', {
						url : '/page-edit',
						controller : 'PageController',
						templateUrl : 'public/views/page/form.html',
						resolve : load([ 'angularFileUpload' , 'textAngular', 'toaster', 'public/js/controllers/PageController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Page',
							type : 'form',
							module : 'page',
							module_type : 'PAGE'
						},
						params : {
							master : {
								array : true
							}
						}

					}).state('app.testimonial', {
						url : '/testimonial',
						controller : 'TestimonialController',
						templateUrl : 'public/views/testimonial/list.html',
						resolve : load([  'angularFileUpload' , 'textAngular', 'toaster','public/js/controllers/TestimonialController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Testimonial',
							type : 'list',
							module : 'testimonial',
							criteria : [ {key:'name',label:'Name'}]
						}
					}).state('app.testimonial-edit', {
						url : '/testimonial-edit',
						controller : 'TestimonialController',
						templateUrl : 'public/views/testimonial/form.html',
						resolve : load([ 'angularFileUpload' , 'textAngular', 'toaster', 'public/js/controllers/TestimonialController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Testimonial',
							type : 'form',
							module : 'testimonial',
						},
						params : {
							master : {
								array : true
							}

						}

					}).state('app.report', {
						url : '/report',
						controller : 'ReportController',
						templateUrl : 'public/views/report/list.html',
						resolve : load([  'angularFileUpload' , 'textAngular', 'toaster','public/js/controllers/ReportController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Report',
							type : 'list',
							module : 'report',
							criteria : [ {key:'name',label:'Name'} ]
						}
					}).state('app.report-edit', {
						url : '/report-edit',
						controller : 'ReportController',
						templateUrl : 'public/views/report/form.html',
						resolve : load([ 'angularFileUpload' , 'textAngular', 'toaster', 'public/js/controllers/ReportController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Report',
							type : 'form',
							module : 'report',
						},
						params : {
							master : {
								array : true
							}
						}
					}).state('app.report-thumb', {
						url : '/report-thumbnail',
						controller : 'ReportController',
						templateUrl : 'public/views/report/image.html',
						resolve : load([ 'angularFileUpload' , 'textAngular', 'toaster', 'public/js/controllers/ReportController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Report',
							type : 'image',
							module : 'report',
						},
						params : {
							master : {
								array : true
							}
						}
					}).state('app.report_category', {
						url : '/report-category',
						controller : 'MasterDataController',
						templateUrl : 'public/views/report_category/list.html',
						resolve : load([ 'toaster', 'public/js/controllers/MasterDataController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Report Category',
							type : 'list',
							module : 'report_category',
							criteria : [ {key:'name',label:'Name'}]
						}
					}).state('app.report_category-edit', {
						url : '/report-category-edit',
						controller : 'MasterDataController',
						templateUrl : 'public/views/report_category/form.html',
						resolve : load([ 'toaster', 'public/js/controllers/MasterDataController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Report Category',
							type : 'form',
							module : 'report_category',
							combo_box : ['report_category']
						},
						params : {
							master : {
								array : true
							}

						}
					}).state('app.add-credit', {
						url : '/add-credit',
						controller : 'AddCreditController',
						templateUrl : 'public/views/add_credit/list.html',
						resolve : load([  'angularFileUpload' , 'textAngular', 'toaster','public/js/controllers/AddCreditController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Add Credit',
							type : 'list',
							module : 'add-credit',
							criteria : [ {key:'name',label:'Name'} ]
						}
					}).state('app.add-credit-edit', {
						url : '/add-credit-edit',
						controller : 'AddCreditController',
						templateUrl : 'public/views/add_credit/form.html',
						resolve : load([ 'angularFileUpload' , 'textAngular', 'toaster', 'public/js/controllers/AddCreditController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Add Credit',
							type : 'form',
							module : 'add-credit',
						},
						params : {
							master : {
								array : true
							}
						}
					}).state('app.add-credit_category', {
						url : '/add-credit-category',
						controller : 'MasterDataController',
						templateUrl : 'public/views/add_credit_category/list.html',
						resolve : load([ 'toaster', 'public/js/controllers/MasterDataController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Add Credit Category',
							type : 'list',
							module : 'add-credit_category',
							criteria : [ {key:'name',label:'Name'}]
						}
					}).state('app.add-credit_category-edit', {
						url : '/add-credit-category-edit',
						controller : 'MasterDataController',
						templateUrl : 'public/views/add_credit_category/form.html',
						resolve : load([ 'toaster', 'public/js/controllers/MasterDataController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Add Credit Category',
							type : 'form',
							module : 'add-credit_category',
							combo_box : ['add-credit_category']
						},
						params : {
							master : {
								array : true
							}

						}
					}).state('app.add-interest_rate', {
						url : '/add-interest-rate',
						controller : 'MasterDataController',
						templateUrl : 'public/views/interest_rate/list.html',
						resolve : load([ 'toaster', 'public/js/controllers/MasterDataController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Add Interest Rate',
							type : 'list',
							module : 'add-interest_rate',
							criteria : [ {key:'name',label:'Name'}]
						}
					}).state('app.add-interest_rate-edit', {
						url : '/add-interest-rate-edit',
						controller : 'MasterDataController',
						templateUrl : 'public/views/interest_rate/form.html',
						resolve : load([ 'toaster', 'public/js/controllers/MasterDataController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Add Interest Rate',
							type : 'form',
							module : 'add-interest_rate',
							combo_box : ['add-interest_rate']
						},
						params : {
							master : {
								array : true
							}

						}
					}).state('app.post_category', {
						url : '/post-category',
						controller : 'MasterDataController',
						templateUrl : 'public/views/post_category/list.html',
						resolve : load([ 'toaster', 'public/js/controllers/MasterDataController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Blog Category',
							type : 'list',
							module : 'post_category',
							criteria : [ {key:'name',label:'Name'}]
						}
					}).state('app.post_category-edit', {
						url : '/post-category-edit',
						controller : 'MasterDataController',
						templateUrl : 'public/views/post_category/form.html',
						resolve : load([ 'toaster', 'public/js/controllers/MasterDataController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Blog Category',
							type : 'form',
							module : 'post_category',
							combo_box : ['post_category']
						},
						params : {
							master : {
								array : true
							}

						}

					}).state('app.facilities_PRODUCT', {
						url : '/facilities_PRODUCT',
						controller : 'MasterDataController',
						templateUrl : 'public/views/facilities/list.html',
						resolve : load([ 'toaster', 'public/js/controllers/MasterDataController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Facilities',
							type : 'list',
							module : 'facilities',
							param_type:'PRODUCT',
							criteria : [ {key:'name',label:'Name'}]
						}
					}).state('app.facilities_PRODUCT-edit', {
						url : '/facilities_PRODUCT-edit',
						controller : 'MasterDataController',
						templateUrl : 'public/views/facilities/form.html',
						resolve : load([ 'toaster', 'public/js/controllers/MasterDataController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Facilities',
							type : 'form',
							param_type:'PRODUCT',
							module : 'facilities'
						},
						params : {
							master : {
								array : true
							}

						}

					}).state('app.facilities_ROOM', {
						url : '/facilities_ROOM',
						controller : 'MasterDataController',
						templateUrl : 'public/views/facilities/list.html',
						resolve : load([ 'toaster', 'public/js/controllers/MasterDataController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Facilities',
							type : 'list',
							module : 'facilities',
							param_type:'ROOM',
							criteria : [ {key:'name',label:'Name'}]
						}
					}).state('app.facilities_ROOM-edit', {
						url : '/facilities_ROOM-edit',
						controller : 'MasterDataController',
						templateUrl : 'public/views/facilities/form.html',
						resolve : load([ 'toaster', 'public/js/controllers/MasterDataController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Facilities',
							type : 'form',
							param_type:'ROOM',
							module : 'facilities'
						},
						params : {
							master : {
								array : true
							}

						}

					}).state('app.gallery_category', {
						url : '/gallery_category',
						controller : 'MasterDataController',
						templateUrl : 'public/views/gallery_category/list.html',
						resolve : load([ 'toaster', 'public/js/controllers/MasterDataController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Gallery Category',
							type : 'list',
							module : 'gallery_category',
							criteria : [ {key:'name',label:'Name'}]
						}
					}).state('app.gallery_category-edit', {
						url : '/gallery_category-edit',
						controller : 'MasterDataController',
						templateUrl : 'public/views/gallery_category/form.html',
						resolve : load([ 'toaster', 'public/js/controllers/MasterDataController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Gallery Category',
							type : 'form',
							module : 'gallery_category'
						},
						params : {
							master : {
								array : true
							}

						}

					}).state('app.tags', {
						url : '/tags',
						controller : 'MasterDataController',
						templateUrl : 'public/views/tags/list.html',
						resolve : load([ 'toaster', 'public/js/controllers/MasterDataController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Tags',
							type : 'list',
							module : 'tags',
							criteria : [ {key:'name',label:'Name'}]
						}
					}).state('app.tags-edit', {
						url : '/tags-edit',
						controller : 'MasterDataController',
						templateUrl : 'public/views/tags/form.html',
						resolve : load([ 'toaster', 'public/js/controllers/MasterDataController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Tags',
							type : 'form',
							module : 'tags'
						},
						params : {
							master : {
								array : true
							}

						}

					}).state('app.product', {
						url : '/product',
						controller : 'ProductController',
						templateUrl : 'public/views/product/list.html',
						resolve : load([  'angularFileUpload' , 'textAngular', 'toaster','public/js/controllers/ProductController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Product',
							type : 'list',
							module : 'product',
							criteria : [ {key:'name',label:'Name'}]
						}
					}).state('app.product-edit', {
						url : '/product-edit',
						controller : 'ProductController',
						templateUrl : 'public/views/product/form.html',
						resolve : load([ 'angularFileUpload' , 'textAngular', 'toaster', 'public/js/controllers/ProductController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Product',
							type : 'form',
							module : 'product',
						},
						params : {
							master : {
								array : true
							}

						}

					}).state('app.product-category', {
						url : '/product-category',
						controller : 'ProductCategoryController',
						templateUrl : 'public/views/product_category/list.html',
						resolve : load([  'angularFileUpload' , 'textAngular', 'toaster','public/js/controllers/ProductCategoryController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Product Category',
							type : 'list',
							module : 'product-category',
							criteria : [ {key:'name',label:'Name'} ]
						}
					}).state('app.product-category-edit', {
						url : '/product-category-edit',
						controller : 'ProductCategoryController',
						templateUrl : 'public/views/product_category/form.html',
						resolve : load([ 'angularFileUpload' , 'textAngular', 'toaster', 'public/js/controllers/ProductCategoryController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Product Category',
							type : 'form',
							module : 'product-category',
						},
						params : {
							master : {
								array : true
							}
						}
					}).state('app.room', {
						url : '/room',
						controller : 'RoomController',
						templateUrl : 'public/views/room/list.html',
						resolve : load([  'angularFileUpload' , 'textAngular', 'toaster','public/js/controllers/RoomController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Room',
							type : 'list',
							module : 'room',
							criteria : [ {key:'name',label:'Name'}]
						}
					}).state('app.room-edit', {
						url : '/room-edit',
						controller : 'RoomController',
						templateUrl : 'public/views/room/form.html',
						resolve : load([ 'angularFileUpload' , 'textAngular', 'toaster', 'public/js/controllers/RoomController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Room',
							type : 'form',
							module : 'room',
						},
						params : {
							master : {
								array : true
							}

						}

					}).state('app.slider', {
						url : '/slider',
						controller : 'SliderController',
						templateUrl : 'public/views/slider/list.html',
						resolve : load([ 'angularFileUpload' , 'xeditable', 'toaster', 'public/js/controllers/SliderController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Slider',
							type : 'list',
							module : 'slider',
							criteria : [ {key:'name',label:'Name'}]
						}
					}).state('app.gallery', {
						url : '/gallery',
						controller : 'GalleryController',
						templateUrl : 'public/views/gallery/list.html',
						resolve : load([ 'angularFileUpload' , 'xeditable', 'toaster', 'public/js/controllers/GalleryController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Gallery',
							type : 'list',
							module : 'gallery',
							criteria : [ {key:'name',label:'Name'}]
						}
					}).state('app.menu_navigation_header', {
						url : '/menu_navigation_header',
						controller : 'MenuNavigationHeaderController',
						templateUrl : 'public/views/setting/menu_navigation_header.html',
						resolve : load([  'toaster', 'public/js/controllers/MenuNavigationHeaderController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Menu Navigation Header',
							type : 'list',
							module : 'menu_navigation_header',
							criteria : [ {key:'name',label:'Name'}]
						}
					}).state('app.menu_navigation', {
						url : '/menu_navigation/{id}',
						controller : 'MenuNavigationController',
						templateUrl : 'public/views/setting/menu_navigation_detail.html',
						resolve : load([ 'xeditable', 'toaster', 'public/js/controllers/MenuNavigationController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Menu Navigation',
							type : 'list',
							module : 'menu_navigation',
							criteria : [ {key:'name',label:'Name'}]
						}
					}).state('app.home_setting', {
						url : '/home_setting',
						controller : 'HomeSettingController',
						templateUrl : 'public/views/setting/home.html',
						resolve : load([ 'xeditable','textAngular', 'toaster', 'public/js/controllers/HomeSettingController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Home Setting',
							type : 'list',
							module : 'home_setting'
						}
					}).state('app.social_media', {
						url : '/social_media',
						controller : 'SocialMediaController',
						templateUrl : 'public/views/setting/social_media.html',
						resolve : load([ 'xeditable', 'toaster', 'public/js/controllers/SocialMediaController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Social Media',
							type : 'list',
							module : 'social_media'
						}
					}).state('app.theme', {
						url : '/theme',
						controller : 'ThemeSelectedController',
						templateUrl : 'public/views/theme/theme_setting.html',
						resolve : load([  'color.picker','toaster', 'public/js/controllers/ThemeSelectedController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Theme',
							type : 'list',
							module : 'theme'
						}
					}).state('app.rates', {
						url : '/rates',
						controller : 'RatesController',
						templateUrl : 'public/views/rates/list.html',
						resolve : load([ 'toaster','localytics.directives' , 'xeditable','public/js/controllers/RatesController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Rates',
							type : 'list',
							module : 'rates',
							criteria : [ {key:'name',label:'Name'}]
						}
					}).state('app.rates-new', {
						url : '/rates-new',
						controller : 'RatesNewController',
						templateUrl : 'public/views/rates/form.html',
						resolve : load([ 'xeditable','ui.select', 'localytics.directives','toaster','public/js/controllers/RatesNewController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Rates',
							type : 'form',
							module : 'rates',
							combo_box : ['currency']
						}
					}).state('app.rates-edit', {
						url : '/rates-edit/{id}',
						controller : 'RatesEditController',
						templateUrl : 'public/views/rates/edit.html',
						resolve : load([ 'xeditable', 'ui.select','localytics.directives','toaster','public/js/controllers/RatesEditController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Rates',
							type : 'form',
							module : 'rates',
							combo_box : ['currency'],
							use_date : true
						},
						params : {
							master : {
								array : true
							}

						}
					}).state('app.rates_detail', {
						url : '/rates_detail/{id}',
						controller : 'RatesDetailController',
						templateUrl : 'public/views/rates/detail.html',
						resolve : load([ 'toaster', 'ui.select', 'xeditable', 'public/js/controllers/RatesDetailController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Rates Detail'
						}
					}).state('app.rates-calender', {
						url : '/rates-calender/{id}',
						controller : 'RatesCalenderController',
						templateUrl : 'public/views/rates/calender.html',
						resolve : load([ 'toaster', 'ui.select', 'xeditable','public/js/controllers/RatesCalenderController.js', 'public/js/services/masterDataServices.js' ]),
						data : {
							title : 'Rates Detail',
							type : 'form',
							module : 'rates'
						}
					})





					;


					function load(srcs, callback) {
						return {
							deps : [ '$ocLazyLoad', '$q', function($ocLazyLoad, $q) {
								var deferred = $q.defer();
								var promise = false;
								srcs = angular.isArray(srcs) ? srcs : srcs.split(/\s+/);
								if (!promise) {
									promise = deferred.promise;
								}
								angular.forEach(srcs, function(src) {
									promise = promise.then(function() {
										if (JQ_CONFIG[src]) {
											return $ocLazyLoad.load(JQ_CONFIG[src]);
										}
										angular.forEach(MODULE_CONFIG, function(module) {
											if (module.name == src) {
												name = module.name;
											} else {
												name = src;
											}
										});
										return $ocLazyLoad.load(name);
									});
								});
								deferred.resolve();
								return callback ? promise.then(function() {
									return callback();
								}) : promise;
							} ]
						}
					}

				} ]);
