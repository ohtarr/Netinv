angular
	.module('app')
	.controller('Parts.Controller', ['PartsService','PartnersService','$location', '$state', '$scope', '$interval','$stateParams', function(PartsService, PartnersService, $location, $state, $scope, $interval, $stateParams) {
		//console.log("Inside Parts Controller");
		var vm = this;

		vm.newpart = {};

		vm.showaddrow = false;
		vm.showfilterrow = false;
		//initController();

		vm.model = {};
		vm.model.parts = [];
		vm.model.manufacturers = [];

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
		];

		vm.eventEnter = function (event) {
			if(event.which === 13)
			{
				vm.getParts();
			}
		}

		vm.pages = {};
		vm.pages.links = {};

		vm.clearAdd = function () {
			vm.newpart = null;
		}

		vm.clearFilter = function () {
			vm.selected.filter = null;
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
		vm.loading.parts = true;
		vm.loading.manufacturers = true;

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

		function renderAllParts()
		{
			angular.forEach(vm.model.parts, function (value, key) {
				renderPartAll(key);
			});
		}
		
		function renderPartAll(index) {
			renderPartManufacturer(index);
		}

		function renderPartManufacturer(index) {
			manufacturer = findObjectByKey(vm.model.manufacturers, "id", vm.model.parts[index].manufacturer_id);
			vm.model.parts[index].manufacturer = manufacturer;
		}

/* 		function updateParts() {
			angular.forEach(vm.model.parts, function (value, key) {
				manufacturer = findObjectByKey(vm.model.manufacturers, "id", value.manufacturer_id);
				vm.model.parts[key].manufacturer = manufacturer;
			})
		}; */

		vm.getParts = function () {
			vm.loading.parts = true;
			var httpParams = {};
			httpParams.page = vm.pages.current_page;

			angular.forEach(vm.selected.filter, function (value, key) {
				//console.log(value);
				var filter = "filter["+key+"]";
				httpParams[filter] = value;
			});

/* 			if(vm.selected.part){
				httpParams["filter[id]"] = vm.selected.part.id;
			}
			if(vm.selected.manufacturer){
				httpParams["filter[manufacturer_id]"] = vm.selected.manufacturer.id;
			} */
			httpParams['paginate'] = vm.selected.paginate;
			PartsService.getParts(httpParams)
				.then(function (res) {
					// Check for errors and if token has expired.
					if (res.data.message) {
						vm.message = res.data.message;
						return vm.message;
					} else {
						parts = res.data.data;
						vm.pages.last_page		= res.data.meta.last_page;
						vm.pages.current_page	= res.data.meta.current_page;
						vm.pages.per_page		= res.data.meta.per_page;
						vm.pages.total			= res.data.meta.total;
						vm.pages.links.first	= res.data.links.first; 
						vm.pages.links.last		= res.data.links.last;
						vm.pages.links.next		= res.data.links.next;
						vm.pages.links.prev		= res.data.links.prev;
						delete vm.model.parts;
						vm.model.parts = [];
						angular.forEach(parts, function (value, key) {
							//console.log(value);
							vm.model.parts.push(value);
						});
						vm.model.parts = sortByKey(vm.model.parts, 'id');
						renderAllParts();
						vm.loading.parts = false;
						//console.log(vm.model.parts);
					}
				})
		};

		function getManufacturers() {
			vm.loading.manufacturers = true;
			PartnersService.getPartners()
				.then(function (res) {
					// Check for errors and if token has expired.
					if (res.data.message) {
						vm.message = res.data.message;
						return vm.message;
					} else {
						manufacturers = res.data.data;
						vm.model.manufacturers = [];
						angular.forEach(manufacturers, function (value, key) {
							vm.model.manufacturers.push(value);
						});
						vm.model.manufacturers = sortByKey(vm.model.manufacturers, 'name');
						vm.loading.manufacturers = false;
						renderAllParts();
						vm.loading.manufacturers = false;
						//console.log(vm.model.manufacturers);
					}
				})
		};

		getManufacturers();
		vm.getParts();


		vm.submitPart = function (form) {
			//console.log(form);
			PartsService.createPart(angular.copy(form)).then(function (data) {
				vm.model.parts.push(data.data);
				partIndex = findObjectIndexByKey(vm.model.parts, "id", data.data.id);
				renderPartAll(partIndex);
				vm.clearAdd();
			}, function (error) {
				alert('Error: ' + error.data.message + " | Status: " + error.status);
			});
		}

		// Edit state for DID block Edit button.
		vm.edit = {};

		// Update DID Block service called by the save button.
		vm.update = function (part) {

			PartsService.updatePart(part.id, part).then(function (data) {
				partIndex = findObjectIndexByKey(vm.model.parts, "id", part.id);
				renderPartAll(partIndex);
			}, function (error) {
				alert('An error occurred while updating the site')
			});
			//$state.reload();
		}

		// Delete Part
		vm.delete = function (part) {
			PartsService.deletePart(part.id).then(function (data) {

				// jQuery Hack to fix body from the Model.
				$(".modal-backdrop").hide();
				$('body').removeClass("modal-open");
				$('body').removeClass("modal-open");
				$('body').removeAttr('style');
				// End of Hack */
				//console.log(vm.model.parts);
				partIndex = findObjectIndexByKey(vm.model.parts, "id", part.id);
				//console.log(partIndex);
				//delete vm.model.parts[partIndex];
				vm.model.parts.splice(partIndex, 1);
				//console.log(vm.model.parts);
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