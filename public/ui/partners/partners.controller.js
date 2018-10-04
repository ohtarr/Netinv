angular
	.module('app')
	.controller('Partners.Controller', ['PartnersService','$location', '$state', '$scope', '$interval','$stateParams', function(PartnersService, $location, $state, $scope, $interval, $stateParams) {
		console.log("Inside Partners Controller");
		var vm = this;

		vm.newpartner = {};

		vm.showaddrow = false;
		//initController();

		vm.partnersForm = {};
		vm.model = {};
		vm.model.partners = [];
		
		vm.clearAdd = function(){
			vm.newpartner = null;
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
		vm.loading.partners = true;

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

		function renderPartners(partners)
		{
			angular.forEach(partners, function(value, key) {
				vm.model.partners.push(value);
			});
			vm.model.partners = sortByKey(vm.model.partners, 'name');
			vm.loading.partners = false;
			console.log(vm.model.partners);
		}

		function renderPartner(index, partner)
		{
			vm.model.partners[index] = partner;
		}

		function getPartners()
		{
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
		}

		getPartners();

		var id = $stateParams.id;

		if(id != undefined){
			// Fix undefined site error on site list loading.
			vm.getPartner = PartnersService.getPartner(id)
			.then(function(res){
				//console.log(res)
				vm.partnerForm = res.data.data;
			}, function(err){
							//Error
			});
		}

		vm.submitPartner = function(form) {
			console.log(form);
			PartnersService.createPartner(angular.copy(form)).then(function(data) {
				vm.model.partners.push(data.data);
				vm.clearAdd();
			}, function(error) {
				alert('Error: ' + error.data.message + " | Status: " + error.status);
			});
		}

		// Edit state for DID block Edit button.
		vm.edit = {};

		// Update DID Block service called by the save button.
		vm.update = function(partner) {

			PartnersService.updatePartner(partner.id, partner).then(function(data) {
			  partnerIndex = findObjectIndexByKey(vm.model.partners, "id", partner.id);
			  renderPartner(partnerIndex, partner);
			}, function(error) {
				alert('An error occurred while updating the site')
			});
			//$state.reload();
		}


		// Delete Partner
		vm.delete = function(partner) {
			PartnersService.deletePartner(partner.id).then(function(data) {

				// jQuery Hack to fix body from the Model.
					$(".modal-backdrop").hide();
					$('body').removeClass("modal-open");
					$('body').removeClass("modal-open");
					$('body').removeAttr( 'style' );
				// End of Hack */
				console.log(vm.model.partners);
				partnerIndex = findObjectIndexByKey(vm.model.partners, "id", partner.id);
				console.log(partnerIndex);
				//delete vm.model.partners[partnerIndex];
				vm.model.partners.splice(partnerIndex,1);
				console.log(vm.model.partners);
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
