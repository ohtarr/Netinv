angular
	.module('app')
	.controller('Parts.Controller', ['PartsService','PartnersService','$location', '$state', '$scope', '$interval','$stateParams', function(PartsService, PartnersService, $location, $state, $scope, $interval, $stateParams) {
		console.log("Inside Parts Controller");
		var vm = this;

		vm.newpart = {};

		vm.showaddrow = false;
		//initController();

		vm.partsForm = {};
		vm.model = {};
		vm.model.parts = [];
		vm.model.manufacturers = [];
		
		vm.clearAdd = function(){
			vm.newpart = null;
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
		vm.loading.parts = true;
		vm.loading.manufacturers = true;

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

		function renderParts(parts)
		{
			angular.forEach(parts, function(value, key) {
				vm.model.parts.push(value);
			});
			vm.loading.parts = false;
			console.log(vm.model.parts);
		}

		function renderManufacturers(manufacturers)
		{
			angular.forEach(manufacturers, function(value, key) {
				vm.model.manufacturers.push(value);
			});
			vm.model.manufacturers = sortByKey(vm.model.manufacturers, 'name');
			vm.loading.manufacturers = false;
			console.log(vm.model.manufacturers);

			angular.forEach(vm.model.parts, function(value, key) {
				manufacturer = findObjectByKey(vm.model.manufacturers, "id", value.manufacturer_id);
				vm.model.parts[key].manufacturer = manufacturer;
			});
		}

		function renderPartManufacturer(index){
			manufacturer = findObjectByKey(vm.model.manufacturers, "id", vm.model.parts[index].manufacturer_id);
			vm.model.parts[index].manufacturer = manufacturer;
		}

		function renderPartAll(index)
		{
			renderPartManufacturer(index)
		}

/* 		function renderPart(index, part)
		{
			console.log(part);
			vm.model.parts[index] = part;
		} */
		
		function getAll()
		{
			PartsService.getParts()
			.then(function(res){
				// Check for errors and if token has expired.
				if(res.data.message){
					vm.message = res.data.message;
					return vm.message;
				}else{
					renderParts(res.data.data);
					PartnersService.getPartners()
					.then(function(res){
						// Check for errors and if token has expired. 
						if(res.data.message){
							vm.message = res.data.message;
							return vm.message;
						}else{
							renderManufacturers(res.data.data);
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

		var id = $stateParams.id;

		if(id != undefined){
			// Fix undefined site error on site list loading.
			vm.getPart = PartsService.getPart(id)
			.then(function(res){
				//console.log(res)
				vm.partForm = res.data.data;
			}, function(err){
							//Error
			});
		}

		vm.submitPart = function(form) {
			console.log(form);
			PartsService.createPart(angular.copy(form)).then(function(data) {
				vm.model.parts.push(data.data);
				vm.clearAdd();
				partIndex = findObjectIndexByKey(vm.model.parts, "id", data.data.id);
				renderPartAll(partIndex);
			}, function(error) {
				alert('Error: ' + error.data.message + " | Status: " + error.status);
			});
		}

		// Edit state for DID block Edit button.
		vm.edit = {};

		// Update DID Block service called by the save button.
		vm.update = function(part) {
			console.log("SUBMITTED PART:");
			console.log(part);
			PartsService.updatePart(part.id, part).then(function(data) {
			  partIndex = findObjectIndexByKey(vm.model.parts, "id", part.id);
			  renderPartAll(partIndex);
			}, function(error) {
				alert('An error occurred while updating the site')
			});
			//$state.reload();
		}


		// Delete Part
		vm.delete = function(part) {
			PartsService.deletePart(part.id).then(function(data) {

				// jQuery Hack to fix body from the Model.
					$(".modal-backdrop").hide();
					$('body').removeClass("modal-open");
					$('body').removeClass("modal-open");
					$('body').removeAttr( 'style' );
				// End of Hack */
				console.log(vm.model.parts);
				partIndex = findObjectIndexByKey(vm.model.parts, "id", part.id);
				console.log(partIndex);
				//delete vm.model.parts[partIndex];
				vm.model.parts.splice(partIndex,1);
				console.log(vm.model.parts);
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
