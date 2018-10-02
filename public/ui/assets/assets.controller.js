angular
	.module('app')
	.controller('Assets.Controller', ['AssetsService','PartsService','PartnersService','LocsService', '$location', '$state', '$scope', '$interval','$stateParams', function(AssetsService, PartsService, PartnersService, LocsService, $location, $state, $scope, $interval, $stateParams) {
		console.log("Inside Assets Controller");
		var vm = this;

		vm.newasset = {};
		// Match the window permission set in login.js and app.js - may want to user a service or just do an api call to get these. will decide later.
		vm.permissions = window.telecom_mgmt_permissions;

		vm.showaddrow = false;
		//initController();

		vm.assetsForm = {};
		vm.model = {};
		vm.model.assets = [];
		vm.model.parts = [];
		vm.model.partners = [];
		vm.model.locations = [];

		vm.httpParams = {};
		vm.httpParams["filter[id]"] = $location.search().id
		vm.httpParams["filter[serial]"] = $location.search().serial
		vm.httpParams["filter[part_id]"] = $location.search().part_id
		vm.httpParams["filter[vendor_id]"] = $location.search().vendor_id
		vm.httpParams["filter[warranty_id]"] = $location.search().warranty_id
		vm.httpParams["filter[location_id]"] = $location.search().location_id
		
		vm.test = function(){
			console.log(vm.newasset);
		}

		vm.clearAdd = function(){
			vm.newasset = null;
		}

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

		function sortByKey(array, key) {
			return array.sort(function(a, b) {
				var x = a[key]; var y = b[key];
				return ((x < y) ? -1 : ((x > y) ? 1 : 0));
			});
		}

		function findObjectByKey(array, key, value) {
			for (var i = 0; i < array.length; i++) {
				if (array[i][key] === value) {
					return array[i];
				}
			}
			return null;
		}

		function findObjectIndexByKey(array, key, value) {
			for (var i = 0; i < array.length; i++) {
				if (array[i][key] === value) {
					return i;
				}
			}
			return null;
		}

		function isInArrayNgForeach(field, arr) {
			var result = false;
			angular.forEach(arr, function(value, key) {
				if(field == value)
					result = true;
			});
			return result;
		}

		function renderAssets(assets)
		{
			//var vm.model = [];
			angular.forEach(assets, function(value, key) {
				//console.log(value);
				vm.model.assets.push(value);
			});
			vm.model.assets = sortByKey(vm.model.assets, 'id');
			vm.loading.assets = false;
			console.log(vm.model.assets);
		}

		function renderParts(parts)
		{
			angular.forEach(parts, function(value, key) {
				vm.model.parts.push(value);
			});
			vm.model.parts = sortByKey(vm.model.parts, 'part_number');
			vm.loading.parts = false;
			console.log(vm.model.parts);

			angular.forEach(vm.model.assets, function(value, key) {
				part = findObjectByKey(vm.model.parts, "id", value.part_id);
				//console.log(part);
				vm.model.assets[key].part = part;
				//value.part = value;

			});

		}

		function renderAssetAll(index){
			renderAssetPart(index);
			renderAssetPartner(index);
			renderAssetLocation(index);
		}

		function renderAssetPart(index){
			part = findObjectByKey(vm.model.parts, "id", vm.model.assets[index].part_id);
			vm.model.assets[index].part = part;
		}

		function renderAssetPartner(index){
			partner = findObjectByKey(vm.model.partners, "id", vm.model.assets[index].vendor_id);
			vm.model.assets[index].partner = partner;
		}

		function renderAssetLocation(index){
			loc = findObjectByKey(vm.model.locations, "sys_id", vm.model.assets[index].location_id);
			vm.model.assets[index].location = loc;
		}

		function renderPartners(partners)
		{
			angular.forEach(partners, function(value, key) {
				vm.model.partners.push(value);
			});
			vm.model.partners = sortByKey(vm.model.partners, 'name');
			vm.loading.partners = false;
			console.log(vm.model.partners);

			angular.forEach(vm.model.assets, function(value, key) {
				partner = findObjectByKey(vm.model.partners, "id", value.vendor_id);
				//console.log(partner);
				vm.model.assets[key].partner = partner;
			});
		}

		function renderLocations(locations)
		{
			angular.forEach(locations, function(value, key) {
				vm.model.locations.push(value);
			});
			vm.model.locations = sortByKey(vm.model.locations, 'name');
			vm.loading.locations = false;
			console.log(vm.model.locations);

 			angular.forEach(vm.model.assets, function(value, key) {
				loc = findObjectByKey(vm.model.locations, "sys_id", value.location_id);
				//console.log(loc);
				vm.model.assets[key].location = loc;
			});
		}

		function refreshAssetModel(id){
			assetIndex = findObjectIndexByKey(vm.model.assets, "id", id);
		}

		function getAll()
		{
			AssetsService.getAssets(vm.httpParams)
			.then(function(res){
				// Check for errors and if token has expired.
				if(res.data.message){
					vm.message = res.data.message;
					return vm.message;
				}else{
					renderAssets(res.data.data);
					PartsService.getParts()
					.then(function(res){
						// Check for errors and if token has expired.
						if(res.data.message){
							vm.message = res.data.message;
							return vm.message;
						}else{
							renderParts(res.data.data);
						}
					}, function(err){
						alert(err);
					});
					PartnersService.getPartners()
					.then(function(res){
						// Check for errors and if token has expired. 
						if(res.data.message){
							vm.message = res.data.message;
							return vm.message;
						}else{
							renderPartners(res.data.data);
						}
					}, function(err){
						alert(err);
					});
					LocsService.getLocations()
					.then(function(res){
						// Check for errors and if token has expired. 
						if(res.data.message){
							vm.message = res.data.message;
							return vm.message;
						}else{
							renderLocations(res.data.data);
						}
					}, function(err){
						alert(err);
					});
				}
			}, function(err){
				alert(err);
			});

		}

		getAll();

/* 		$scope.$on('$destroy', function() {
			//console.log($scope);
            $interval.cancel(pullassets);
		}); */

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
			console.log(form);
			AssetsService.createAsset(angular.copy(form)).then(function(data) {
				//alert("site Added Succesfully" + data);
				//$state.reload();
				//$state.go('assets');
				vm.model.assets.push(data.data);
				vm.clearAdd();
				assetIndex = findObjectIndexByKey(vm.model.assets, "id", data.data.id);
				renderAssetAll(assetIndex);
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
			  assetIndex = findObjectIndexByKey(vm.model.assets, "id", asset.id);
			  renderAssetAll(assetIndex);
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
				console.log(vm.model.assets);
				assetIndex = findObjectIndexByKey(vm.model.assets, "id", asset.id);
				console.log(assetIndex);
				//delete vm.model.assets[assetIndex];
				vm.model.assets.splice(assetIndex,1);
				console.log(vm.model.assets);
				//return $state.reload();
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
