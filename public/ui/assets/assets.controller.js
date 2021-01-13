angular
	.module('app')
	.controller('Assets.Controller', ['AssetsService', 'PartsService', 'PartnersService', 'LocsService', '$location', '$state', '$scope', '$interval', '$stateParams', function (AssetsService, PartsService, PartnersService, LocsService, $location, $state, $scope, $interval, $stateParams) {
		//console.log("Inside Assets Controller");
		var vm = this;

		vm.newasset = {};

		vm.showaddrow = false;
		vm.showfilterrow = false;
		//initController();

		vm.model = {};
		vm.model.assets = [];
		vm.model.parts = [];
		vm.model.partners = [];
		vm.model.locations = [];

		vm.selected = {};
		vm.selected.paginate = 100;
		
		vm.options = {};
		vm.options.paginate = [
			100,
			500,
			1000,
			10000,
			100000
		];

		vm.options.types = [
			"Router",
			"Switch",
			"Controller",
			"Access Point",
			"Firewall",
			"Server",
			"Bridge",
			"Accessory",
			"UPS",
			"PDU",
			"Console Server",
			"Rack",
		];

		vm.eventEnter = function (event) {
			if(event.which === 13)
			{
				vm.getAssets();
			}
		}

		vm.pages = {};
		vm.pages.links = {};

		vm.httpParams = {};

		vm.clearAdd = function () {
			vm.newasset = null;
		}

		vm.clearFilter = function () {
			vm.selected.filter = {};
			vm.selected.query = {};
		}

		vm.addtoggle = function () {
			if (vm.showaddrow == true) {
				vm.showaddrow = false;
			} else {
				if (vm.showaddrow == false) {
					vm.showaddrow = true;
					vm.showfilterrow = false;
				}
			}
		}

		vm.filtertoggle = function () {
			if (vm.showfilterrow == true) {
				vm.showfilterrow = false;
			} else {
				if (vm.showfilterrow == false) {
					vm.showfilterrow = true;
					vm.showaddrow = false;
				}
			}
		}

		vm.refresh = function () {
			// jQuery Hack to fix body from the Model.
			$(".modal-backdrop").hide();
			$('body').removeClass("modal-open");
			$('body').removeClass("modal-open");
			$('body').removeAttr('style');
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
			return array.sort(function (a, b) {
				var x = a[key];
				var y = b[key];
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

		function renderAllAssets()
		{
			angular.forEach(vm.model.assets, function (value, key) {
				renderAssetAll(key);
			});
		}
		
		function renderAssetAll(index) {
			renderAssetPart(index);
			renderAssetPartner(index);
			renderAssetLocation(index);
		}

		function renderAssetPart(index) {
			part = findObjectByKey(vm.model.parts, "id", vm.model.assets[index].part_id);
			vm.model.assets[index].part = part;
		}

		function renderAssetPartner(index) {
			partner = findObjectByKey(vm.model.partners, "id", vm.model.assets[index].vendor_id);
			vm.model.assets[index].partner = partner;
		}

		function renderAssetLocation(index) {
			loc = findObjectByKey(vm.model.locations, "sys_id", vm.model.assets[index].location_id);
			vm.model.assets[index].location = loc;
		}

/* 		function updateAssets() {
			angular.forEach(vm.model.assets, function (value, key) {
				part = findObjectByKey(vm.model.parts, "id", value.part_id);
				partner = findObjectByKey(vm.model.partners, "id", value.partner_id);
				loc = findObjectByKey(vm.model.locations, "sys_id", value.location_id);

				vm.model.assets[key].part = part;
				vm.model.assets[key].partner = partner;
				vm.model.assets[key].location = loc;
			})
		}; */

		vm.getAssets = function () {
			vm.loading.assets = true;
			var httpParams = {};
			httpParams.page = vm.pages.current_page;
			angular.forEach(vm.selected.filter, function (value, key) {
				//console.log(value);
				var filter = "filter["+key+"]";
				httpParams[filter] = value;
			});
			angular.forEach(vm.selected.query, function (value, key) {
				//console.log(value);
				httpParams[key] = value;
			});
			httpParams['paginate'] = vm.selected.paginate;
			//console.log("http params:");
			//console.log(httpParams);
			AssetsService.getAssets(httpParams)
				.then(function (res) {
					// Check for errors and if token has expired.
					if (res.data.message) {
						vm.message = res.data.message;
						return vm.message;
					} else {
						assets = res.data.data;
						vm.pages.last_page		= res.data.meta.last_page;
						vm.pages.current_page	= res.data.meta.current_page;
						vm.pages.per_page		= res.data.meta.per_page;
						vm.pages.total			= res.data.meta.total;
						vm.pages.links.first	= res.data.links.first; 
						vm.pages.links.last		= res.data.links.last;
						vm.pages.links.next		= res.data.links.next;
						vm.pages.links.prev		= res.data.links.prev;
						delete vm.model.assets;
						vm.model.assets = [];
						angular.forEach(assets, function (value, key) {
							//console.log(value);
							vm.model.assets.push(value);
						});
						vm.model.assets = sortByKey(vm.model.assets, 'id');
						//updateAssets();
						renderAllAssets();
						vm.loading.assets = false;
						//console.log(vm.model.assets);

					}
				}, function (error) {
					alert('An error occurred while getting Assets!')
				})
		};

		vm.getAsset = function (id) {
 			AssetsService.getAsset(id).then(
				function (response) {
					assetIndex = findObjectIndexByKey(vm.model.assets, "id", id);
					vm.model.assets[assetIndex] = response.data.data;
					renderAssetAll(assetIndex);
				}, function (error) {
					alert('An error occurred while getting the Asset with id ' + id)
			});
		}

		function getParts() {
			var httpParams = {};

			PartsService.getParts(httpParams)
				.then(function (res) {
					if (res.data.message) {
						vm.message = res.data.message;
						return vm.message;
					} else {
						parts = res.data.data;
						vm.model.parts = [];
						angular.forEach(parts, function (value, key) {
							vm.model.parts.push(value);
						});
						vm.model.parts = sortByKey(vm.model.parts, 'part_number');
						vm.loading.parts = false;
						renderAllAssets();
					}
				})
		};

		function getPartners() {
			PartnersService.getPartners()
				.then(function (res) {
					// Check for errors and if token has expired.
					if (res.data.message) {
						vm.message = res.data.message;
						return vm.message;
					} else {
						partners = res.data.data;
						vm.model.partners = [];
						angular.forEach(partners, function (value, key) {
							vm.model.partners.push(value);
						});
						vm.model.partners = sortByKey(vm.model.partners, 'name');
						vm.loading.partners = false;
						//updateAssets();
						renderAllAssets();
					}
				})
		};

		function getLocations() {
			LocsService.getLocations()
				.then(function (res) {
					// Check for errors and if token has expired.
					if (res.data.message) {
						vm.message = res.data.message;
						return vm.message;
					} else {
						locations = res.data.data;
						vm.model.locations = [];
						angular.forEach(locations, function (value, key) {
							vm.model.locations.push(value);
						});
						vm.model.locations = sortByKey(vm.model.locations, 'name');
						vm.loading.locations = false;
						//updateAssets();
						renderAllAssets();
					}
				})
		};

		vm.getAssets();
		getParts();
		getPartners();
		getLocations();


/* 		function getAll() {
			AssetsService.getAssets(vm.httpParams)
				.then(function (res) {
					// Check for errors and if token has expired.
					if (res.data.message) {
						vm.message = res.data.message;
						return vm.message;
					} else {
						renderAssets(res.data.data);
						PartsService.getParts()
							.then(function (res) {
								// Check for errors and if token has expired.
								if (res.data.message) {
									vm.message = res.data.message;
									return vm.message;
								} else {
									renderParts(res.data.data);
								}
							}, function (err) {
								alert(err);
							});
						PartnersService.getPartners()
							.then(function (res) {
								// Check for errors and if token has expired. 
								if (res.data.message) {
									vm.message = res.data.message;
									return vm.message;
								} else {
									renderPartners(res.data.data);
								}
							}, function (err) {
								alert(err);
							});
						LocsService.getLocations()
							.then(function (res) {
								// Check for errors and if token has expired. 
								if (res.data.message) {
									vm.message = res.data.message;
									return vm.message;
								} else {
									renderLocations(res.data.data);
								}
							}, function (err) {
								alert(err);
							});
					}
				}, function (err) {
					alert(err);
				});

		}

		getAll(); */

		/* 		$scope.$on('$destroy', function() {
					//console.log($scope);
		            $interval.cancel(pullassets);
				}); */

/* 		var id = $stateParams.id;
		//console.log(id + " printing id here...")

		if (id != undefined) {
			// Fix undefined site error on site list loading.
			vm.getAsset = AssetsService.getAsset(id)
				.then(function (res) {
					//console.log(res)
					vm.assetForm = res.data.data;
				}, function (err) {
					//Error
				});
		}
 */
		vm.submitAsset = function (form) {
			//console.log(form);
			AssetsService.createAsset(angular.copy(form)).then(function (data) {
				//alert("site Added Succesfully" + data);
				//$state.reload();
				//$state.go('assets');
 				vm.model.assets.push(data.data);
				vm.clearAdd();
 				assetIndex = findObjectIndexByKey(vm.model.assets, "id", data.data.id);
				renderAssetAll(assetIndex);
			}, function (error) {
				//console.log(error)
				//console.log(error.data.message)
				alert('Error: ' + error.data.message + " | Status: " + error.status);
			});
		}

		// Update Asset service called by the save button.
		vm.update = function (asset) {
 			AssetsService.updateAsset(asset.id, asset).then(
				function (response) {
					vm.getAsset(asset.id);
				}, function (error) {
					alert(error.data.message);
					vm.getAsset(asset.id);
			});
		}

		// Delete Asset
		vm.delete = function (asset) {
			AssetsService.deleteAsset(asset.id).then(function (data) {

				// jQuery Hack to fix body from the Model.
				$(".modal-backdrop").hide();
				$('body').removeClass("modal-open");
				$('body').removeClass("modal-open");
				$('body').removeAttr('style');
				// End of Hack */
				//console.log(vm.model.assets);
				assetIndex = findObjectIndexByKey(vm.model.assets, "id", asset.id);
				//console.log(assetIndex);
				//delete vm.model.assets[assetIndex];
				vm.model.assets.splice(assetIndex, 1);
				//console.log(vm.model.assets);
				//return $state.reload();
			}, function (error) {
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