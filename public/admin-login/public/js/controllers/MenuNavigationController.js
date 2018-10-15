/**
 *
 */
'use strict';
angular.module('app').controller(
		'MenuNavigationController',
		[ '$scope', '$state', '$stateParams', 'propertiesConstant', 'TokenStorage', 'masterDataServices','toaster', '$filter',
		function($scope, $state, $stateParams, propertiesConstant, TokenStorage, masterDataServices,toaster ,$filter) {
			var busy = false;

			var storageKey = TokenStorage.retrieve();
			var title = $state.current.data.title;
			var type = $state.current.data.type;
			var module = $state.current.data.module;
			var criteria_key_list = $state.current.data.criteria;
			$scope.company_id=storageKey[propertiesConstant.COMPANY_ID];
			$scope.title="Menu Navigation";
			$scope.id=$stateParams.id;
			$scope.custom_link={};
			$scope.blog={label:'Blog'};
			$scope.text_type={};
			$scope.module={};
			$scope.gallery={};
			$scope.category={};

			$scope.list_category = masterDataServices.query({module:'category',"company_id":$scope.company_id});
			$scope.list_pages = masterDataServices.query({module:'page',"company_id":$scope.company_id,type:'PAGE',status:1});
			$scope.list_gallery_category = masterDataServices.query({module:'gallery_category',"company_id":$scope.company_id});
			$scope.list_module=[
									{id:'accommodation',name:"Accommodation"},
									{id:'facilities',name:"Facilities"},
									{id:'reservation',name:"Reservation"},
									{id:'contact',name:"Contact us"}
			];
			$scope.master={};
			 masterDataServices.get({module:'setting',id:$scope.id},function(response){
				$scope.master=response;

			 });


			 function slugify(text)
					{
					  return text.toString().toLowerCase()
					    .replace(/\s+/g, '-')           // Replace spaces with -
					    .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
					    .replace(/\-\-+/g, '-')         // Replace multiple - with single -
					    .replace(/^-+/, '')             // Trim - from start of text
					    .replace(/-+$/, '');            // Trim - from end of text
					}

			  $scope.save_blog=function(master){
			  	var selected = $filter('filter')($scope.master.value, {type:'BLOG'},true);
			  	if (selected.length>0){
					toaster.pop('error','Information','Blog already in menu!');
				}else{
					var blog = {label:master.label,type:'BLOG',data:{}}
				  	$scope.master.value.push(blog);
				  	$scope.save($scope.master.value);
				  	$scope.blog={};
				}

			 }


			 $scope.save_module=function(master){
				 var selected = $filter('filter')($scope.master.value, {type:'MODULE'},true);
				 var isValid=true;
				 angular.forEach(selected, function(value, key){
					 if (value.data.module==master.module){
						 	isValid=false;
					 }
				});
				 console.log(selected);
				 if (isValid==false){
						  toaster.pop('error','Information','Module '+master.module+' already in menu!');
					 }else{
						 var module = {label:master.label,type:'MODULE',data:master}
							 $scope.master.value.push(module);
							 $scope.save($scope.master.value);
							 $scope.module={};
					 }

			}


			  $scope.save_custom_link=function(master){
					var link={};
					if (master.parent_menu){
						angular.forEach($scope.master.value, function(value, key){
							if (value.type=='CUSTOM_LINK' && value.label==master.parent_menu){
								 link = {label:master.label,type:'CUSTOM_LINK',data:{url:master.url}}
								 value.data.sub_menu.push(link);
								 console.log($scope.master.value);
							}
						});
						$scope.save($scope.master.value);
					}else{
							link = {label:master.label,type:'CUSTOM_LINK',data:{url:master.url,sub_menu:[]}}
							$scope.master.value.push(link);
							$scope.save($scope.master.value);
					}
			  	$scope.custom_link={};
			 }


			  $scope.save_text=function(master){

			  	var text = {label:master.label,type:'TEXT',data:{description:master.description}}
			  	$scope.master.value.push(text);
			  	$scope.save($scope.master.value);
			  	$scope.text_type={};


			 }


			 $scope.update_module=function(master,index){
				 $scope.master.value[index]=master;
				 $scope.save($scope.master.value);
			}


			 $scope.update_text=function(master,index){

			  	var text = {label:master.label,type:'TEXT',data:{description:master.description}}
			  	angular.forEach($scope.master.value, function(value, key) {
					 		if (key==index){
					 			value=text;
					 		}
					 	});

			  	$scope.save($scope.master.value);
			  	$scope.text_type={};
			 }



			 $scope.changeGalleryName=function(){
			 	var slug = slugify($scope.gallery.label);
			 	$scope.gallery.slug=slug;

			 }

			 $scope.save_gallery=function(master){

			 	var selected = $filter('filter')($scope.master.value, {slug: master.slug},true);
				if (selected.length>0){
					toaster.pop('error','Information','This gallery already in menu!');
				}else{
					var datas=[]
					 	var datas_reference=[]
					 	angular.forEach($scope.list_gallery_category, function(value, key) {
					 		if (value.is_checked==true){
					 			datas_reference.push(value.id)	;
					 			datas.push(value.name);
					 		}
					 	});

					  	var gal = {label:master.label,type:'GALLERY',data:datas_reference,data_label:datas,slug:master.slug}
					  	$scope.master.value.push(gal);
					  	$scope.save($scope.master.value);
					  	$scope.gallery={};
				}


			 }


			 $scope.remove=function(index){
				$scope.master.value.splice(index, 1);
				$scope.save($scope.master.value);
			}

			$scope.remove_sub_menu=function(index,key_sub_menu){
			 $scope.master.value[index].data.sub_menu.splice(key_sub_menu, 1);
			 $scope.save($scope.master.value);
		 }

			 $scope.save_all=function(){
			 	$scope.save($scope.master.value);
			 }


			 $scope.save=function(data){

						 var master={};
						 master.id=$scope.master.id;
						 master.company_id=$scope.company_id;
						 master.name=$scope.master.name;
						 master.value=angular.toJson(data);
						 masterDataServices.save('setting', master).success(function(response) {
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

			 $scope.get_parent_menu=function(){
				 return  $filter('filter')($scope.master.value, {type: 'CUSTOM_LINK'},true);

			 }


			  $scope.up_one=function(index){
			 	var oldnav = $scope.master.value[index];
			 	var newnav=  $scope.master.value[index-1];

			 	$scope.master.value[index] = newnav;
				$scope.master.value[index-1] = oldnav;
				$scope.save($scope.master.value);
			 }


			 $scope.down_one=function(index){
			 	var oldnav = $scope.master.value[index];
			 	var newnav = $scope.master.value[index+1];
			 	$scope.master.value[index] = newnav;
				$scope.master.value[index+1] = oldnav;
				$scope.save($scope.master.value);
			 }


			 $scope.to_top=function(index){
			 	var oldTop = $scope.master.value[0];
			 	var newTop= $scope.master.value[index];
			 	$scope.master.value[0] = newTop;
				$scope.master.value[index] = oldTop;
				$scope.save($scope.master.value);
			 }


			  $scope.add_menu_by_category=function(category){
				var selected = $filter('filter')($scope.master.value, {reference_id: category.data.id},true);
				if (selected.length>0){
					toaster.pop('error','Information','This category already in menu!');
				}else{
					var cat = {label:category.label,type:'CATEGORIES', slug:category.data.slug, name: category.data.name, reference_id:category.data.id, data:{include_sub_menu:category.include_sub_menu} }
				  	$scope.master.value.push(cat);
				  	$scope.save($scope.master.value);
						$scope.category={};
				}
			 }


			 $scope.add_menu_by_page=function(page){
				var selected = $filter('filter')($scope.master.value, {reference_id: page.id},true);
				if (selected.length>0){
					toaster.pop('error','Information','This Page already in menu!');
				}else{
					var page = {label:page.name,type:'PAGES', slug:page.slug, name: page.name, reference_id:page.id, data:{}}
				  	$scope.master.value.push(page);
				  	$scope.save($scope.master.value);
				}
			 }



		} ]);
