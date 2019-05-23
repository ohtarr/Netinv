angular
	.module('app')
	.controller('Partners.Controller', ['PartnersService', '$location', '$state', '$scope', '$interval', '$stateParams', function (PartnersService, $location, $state, $scope, $interval, $stateParams) {
		//console.log("Inside Partners Controller");
		var vm = this;

		vm.newpartner = {};

		vm.showaddrow = false;
		vm.showfilterrow = false;
		//initController();

		// Edit state for DID block Edit button.
		vm.edit = {};

		vm.model = {};
		vm.model.partners = [];


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

		vm.eventEnter = function (event) {
			if (event.which === 13) {
				vm.getPartners();
			}
		}

		vm.pages = {};
		vm.pages.links = {};

		vm.httpParams = {};

		vm.clearAdd = function () {
			vm.newpartner = null;
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
		vm.loading.partners = true;

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

		function renderAllPartners() {
			angular.forEach(vm.model.partners, function (value, key) {
				renderPartnerAll(key);
			});
		}

		function renderPartnerAll(index) {}

		vm.getPartners = function () {
			vm.loading.partners = true;
			httpParams = {};
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
			PartnersService.getPartners(httpParams)
				.then(function (res) {
					// Check for errors and if token has expired.
					if (res.data.message) {
						vm.message = res.data.message;
						return vm.message;
					} else {
						partners = res.data.data;
						vm.pages.last_page = res.data.meta.last_page;
						vm.pages.current_page = res.data.meta.current_page;
						vm.pages.per_page = res.data.meta.per_page;
						vm.pages.total = res.data.meta.total;
						vm.pages.links.first = res.data.links.first;
						vm.pages.links.last = res.data.links.last;
						vm.pages.links.next = res.data.links.next;
						vm.pages.links.prev = res.data.links.prev;
						delete vm.model.partners;
						vm.model.partners = [];
						angular.forEach(partners, function (value, key) {
							//console.log(value);
							vm.model.partners.push(value);
						});
						vm.model.partners = sortByKey(vm.model.partners, 'id');
						//renderAllPartners();
						vm.loading.partners = false;
						//console.log(vm.model.partners);
					}
				})
		};

		vm.getPartners();

		vm.submitPartner = function (form) {
			//console.log(form);
			PartnersService.createPartner(angular.copy(form)).then(function (data) {
				vm.model.partners.push(data.data);
				partnerIndex = findObjectIndexByKey(vm.model.partners, "id", data.data.id);
				renderPartnerAll(partnerIndex);
				vm.clearAdd();
			}, function (error) {
				alert('Error: ' + error.data.message + " | Status: " + error.status);
			});
		}

		// Update DID Block service called by the save button.
		vm.update = function (partner) {

			PartnersService.updatePartner(partner.id, partner).then(function (data) {
				partnerIndex = findObjectIndexByKey(vm.model.partners, "id", partner.id);
				renderPartnerAll(partnerIndex);
			}, function (error) {
				alert('An error occurred while updating the site')
			});
			//$state.reload();
		}

		// Delete Partner
		vm.delete = function (partner) {
			PartnersService.deletePartner(partner.id).then(function (data) {

				// jQuery Hack to fix body from the Model.
				$(".modal-backdrop").hide();
				$('body').removeClass("modal-open");
				$('body').removeClass("modal-open");
				$('body').removeAttr('style');
				// End of Hack */
				//console.log(vm.model.partners);
				partnerIndex = findObjectIndexByKey(vm.model.partners, "id", partner.id);
				//console.log(partnerIndex);
				vm.model.partners.splice(partnerIndex, 1);
				//console.log(vm.model.partners);
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