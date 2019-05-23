angular
	.module('app')
	.controller('Locations.Controller', ['LocationsService', '$location', '$state', '$scope', '$interval','$stateParams', function(LocationsService, $location, $state, $scope, $interval, $stateParams) {
		//console.log("Inside Locations Controller");
		var vm = this;

		// Match the window permission set in login.js and app.js - may want to user a service or just do an api call to get these. will decide later.
		vm.permissions = window.telecom_mgmt_permissions;

		vm.showaddrow = false;
		//initController();

		vm.locationsForm = {};


		vm.httpParams = {
			include: "part,vendor,warranty",
		};
		
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

		vm.loading = true;

		function isInArrayNgForeach(field, arr) {
			var result = false;
		
			angular.forEach(arr, function(value, key) {
				//console.log(value);
				if(field == value)
					result = true;
			});

			return result;
		}

		vm.getLocations = function() {
			LocationsService.getLocations(vm.httpParams)

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
						vm.locations = res.data.data;
						vm.loading = false;
					}

				}, function(err){
					alert(err);
				});
		}

				
		vm.getLocations();
		var pulllocations		= $interval(vm.getLocations,30000);

		$scope.$on('$destroy', function() {
			//console.log($scope);
            $interval.cancel(pulllocations);
		});

		var id = $stateParams.id;
		//console.log(id + " printing id here...")

		if(id != undefined){
			// Fix undefined site error on site list loading.
			vm.getLocation = LocationsService.getLocation(id)
			.then(function(res){
				//console.log(res)
				vm.locationForm = res.data.data;
			}, function(err){
							//Error
			});
		}

/* 		vm.submitLocation = function(form) {
			LocationsService.createLocation(angular.copy(form)).then(function(data) {
				//alert("site Added Succesfully" + data);
				$state.reload();
				//$state.go('locations');
			}, function(error) {
				//console.log(error)
				//console.log(error.data.message)
				alert('Error: ' + error.data.message + " | Status: " + error.status);
			});
		} */

		// Edit state for DID block Edit button.
		vm.edit = {};

		// Update DID Block service called by the save button.
/* 		vm.update = function(location) {

			LocationsService.updateLocation(location.id, location).then(function(data) {
			  //alert('Location Updated Successfully!')
			  //$location.path('/locations');
			}, function(error) {
				alert('An error occurred while updating the site')
			});
			//$state.reload();
		} */


		// Delete Location
/* 		vm.delete = function(location) {
			LocationsService.deleteLocation(location.id).then(function(data) {

				// jQuery Hack to fix body from the Model.
					$(".modal-backdrop").hide();
					$('body').removeClass("modal-open");
					$('body').removeClass("modal-open");
					$('body').removeAttr( 'style' );
				// End of Hack */
/*
				return $state.reload();
          }, function(error) {
				alert('An error occurred');
          });

		} */

	}])

	// Be nice to use a directive at some point to help template HTML
	.directive('trRow', function ($compile) {

		return {
			template: '<tr><td ng-bind="row.id"></td><td><strong ng-bind="row.name"></strong></td><td ng-bind="row.description"></td></tr>'
		};
	});
