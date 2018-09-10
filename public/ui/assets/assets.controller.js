angular
	.module('app')
	.controller('Assets.Controller', ['AssetsService','PartsService','PartnersService','LocsService', '$location', '$state', '$scope', '$interval','$stateParams', function(AssetsService, PartsService, PartnersService, LocsService, $location, $state, $scope, $interval, $stateParams) {
		console.log("Inside Assets Controller");
		var vm = this;

		// Match the window permission set in login.js and app.js - may want to user a service or just do an api call to get these. will decide later.
		vm.permissions = window.telecom_mgmt_permissions;

		vm.showaddrow = false;
		//initController();

		vm.assetsForm = {};
		vm.model = {};
		vm.model.assets = {};
		vm.model.parts = {};
		vm.model.partners = {};
		vm.model.locations = {};

		vm.httpParams = {};
		vm.httpParams["filter[id]"] = $location.search().id
		vm.httpParams["filter[serial]"] = $location.search().serial
		vm.httpParams["filter[part_id]"] = $location.search().part_id
		vm.httpParams["filter[vendor_id]"] = $location.search().vendor_id
		vm.httpParams["filter[warranty_id]"] = $location.search().warranty_id
		vm.httpParams["filter[location_id]"] = $location.search().location_id
		
		vm.clearFilter = function(){
			vm.httpParams["filter[id]"] = ""
			vm.httpParams["filter[serial]"] = ""
			vm.httpParams["filter[part_id]"] = ""
			vm.httpParams["filter[vendor_id]"] = ""
			vm.httpParams["filter[warranty_id]"] = ""
			vm.httpParams["filter[location_id]"] = ""
		}

		vm.addtoggle = function(){
			if(vm.showaddrow == true){
				vm.showaddrow = false;
			}else{
				if(vm.showaddrow == false){
					vm.showaddrow = true;
				}
			}
		}

		vm.refresh = function (){
			// jQuery Hack to fix body from the Model.
					$(".modal-backdrop").hide();
					$('body').removeClass("modal-open");
					$('body').removeClass("modal-open");
					$('body').removeAttr( 'style' );
				// End of Hack */
			//console.log(vm.httpParams);
			$state.reload();
		};

		vm.messages = 'Loading stuff and things...';

		vm.loading = {};
		vm.loading.assets = true;
		vm.loading.parts = true;
		vm.loading.partners = true;
		vm.loading.locations = true;

		function isInArrayNgForeach(field, arr) {
			var result = false;
			angular.forEach(arr, function(value, key) {
				if(field == value)
					result = true;
			});
			return result;
		}

		function getAssets() {
		// vm.getAssets = function() {
			AssetsService.getAssets(vm.httpParams)
				.then(function(res){
					// Check for errors and if token has expired.
					if(res.data.message){
						vm.message = res.data.message;
						if(vm.message == "Token has expired"){
							// Send user to login page if token expired.
							//alert("Token has expired, Please relogin");
							$state.go('logout');
						}
						return vm.message;
					}else{
						assets = res.data.data;
						renderAssets(assets);
					}
				}, function(err){
					alert(err);
				});
		}

		function getParts() {
		//vm.getParts = function() {
			PartsService.getParts()
				.then(function(res){
					// Check for errors and if token has expired.
					if(res.data.message){
						vm.message = res.data.message;
						if(vm.message == "Token has expired"){
							// Send user to login page if token expired.
							//alert("Token has expired, Please relogin");
							$state.go('logout');
						}
						return vm.message;
					}else{
						parts = res.data.data;
						renderParts(parts);
					}
				}, function(err){
					alert(err);
				});
		}

		function getPartners() {
		//vm.getPartners = function() {
			PartnersService.getPartners()
				.then(function(res){
					// Check for errors and if token has expired. 
					if(res.data.message){
						vm.message = res.data.message;
						if(vm.message == "Token has expired"){
							// Send user to login page if token expired. 
							//alert("Token has expired, Please relogin");
							$state.go('logout');
						}
						return vm.message;
					}else{
						partners = res.data.data;
						renderPartners(partners);
					}
				}, function(err){
					alert(err);
				});
		}

		function getLocations() {
		//vm.getLocations = function() {
			LocsService.getLocations()
				.then(function(res){
					// Check for errors and if token has expired. 
					if(res.data.message){
						vm.message = res.data.message;
						if(vm.message == "Token has expired"){
							// Send user to login page if token expired. 
							//alert("Token has expired, Please relogin");
							$state.go('logout');
						}
						return vm.message;
					}else{
						locations = res.data.data;
						renderLocations(locations);
					}
				}, function(err){
					alert(err);
				});
		}

		function renderAssets(assets)
		{
			//var vm.model = [];
			angular.forEach(assets, function(value, key) {
				//console.log(value);
				vm.model.assets[value.id] = value;

				//vm.model[vm.asset[id]][part] = vm.getPart(vm.asset[id].part_id);
				//vm.model[vm.asset[id]][partner] = vm.getPartner(vm.asset[id].partner_id);
				//vm.model[vm.asset[id]][location] = vm.getLocation(vm.asset[id].location_id);
			});
			vm.loading.assets = false;
			console.log(vm.model.assets);
		}

		function renderParts(parts)
		{
			angular.forEach(parts, function(value, key) {
				vm.model.parts[value.id] = value;
			});
			vm.loading.parts = false;
			console.log(vm.model.parts);
		}

		function renderPartners(partners)
		{
			angular.forEach(partners, function(value, key) {
				vm.model.partners[value.id] = value;
			});
			vm.loading.partners = false;
			console.log(vm.model.partners);
		}

		function renderLocations(locations)
		{
			angular.forEach(locations, function(value, key) {
				vm.model.locations[value.sys_id] = value;
			});
			vm.loading.locations = false;
			console.log(vm.model.locations);
		}

		//vm.combined = vm.refreshCombined();
		getAssets();
		getParts();
		getPartners();
		getLocations();
		//var pullassets		= $interval(vm.getAssets,30000);

		$scope.$on('$destroy', function() {
			//console.log($scope);
            $interval.cancel(pullassets);
		});

		var id = $stateParams.id;
		//console.log(id + " printing id here...")

		if(id != undefined){
			// Fix undefined site error on site list loading.
			vm.getAsset = AssetsService.getAsset(id)
			.then(function(res){
				//console.log(res)
				vm.assetForm = res.data.data;
			}, function(err){
							//Error
			});
		}

		vm.submitAsset = function(form) {
			AssetsService.createAsset(angular.copy(form)).then(function(data) {
				//alert("site Added Succesfully" + data);
				$state.reload();
				//$state.go('assets');
			}, function(error) {
				//console.log(error)
				//console.log(error.data.message)
				alert('Error: ' + error.data.message + " | Status: " + error.status);
			});
		}

		// Edit state for DID block Edit button.
		vm.edit = {};

		// Update DID Block service called by the save button.
		vm.update = function(asset) {

			AssetsService.updateAsset(asset.id, asset).then(function(data) {
			  //alert('Asset Updated Successfully!')
			  /* $state.reload(); */
			  //$location.path('/assets');
			  console.log(vm.assets);
			  console.log(asset);
			}, function(error) {
				alert('An error occurred while updating the site')
			});
			//$state.reload();
		}


		// Delete Asset
		vm.delete = function(asset) {
			AssetsService.deleteAsset(asset.id).then(function(data) {

				// jQuery Hack to fix body from the Model.
					$(".modal-backdrop").hide();
					$('body').removeClass("modal-open");
					$('body').removeClass("modal-open");
					$('body').removeAttr( 'style' );
				// End of Hack */

				return $state.reload();
          }, function(error) {
				alert('An error occurred');
          });

		}

	}])

	// Be nice to use a directive at some point to help template HTML
	.directive('trRow', function ($compile) {

		return {
			template: '<tr><td ng-bind="row.id"></td><td><strong ng-bind="row.name"></strong></td><td ng-bind="row.description"></td></tr>'
		};
	});
