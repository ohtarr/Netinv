angular
	.module('app')
	.controller('Parts.Controller', ['PartsService', '$location', '$state', '$scope', '$interval','$stateParams', function(PartsService, $location, $state, $scope, $interval, $stateParams) {

		var vm = this;

		// Match the window permission set in login.js and app.js - may want to user a service or just do an api call to get these. will decide later.
		vm.permissions = window.telecom_mgmt_permissions;


		//initController();

		vm.partsForm = {};

		vm.refresh = function (){
			// jQuery Hack to fix body from the Model.
					$(".modal-backdrop").hide();
					$('body').removeClass("modal-open");
					$('body').removeClass("modal-open");
					$('body').removeAttr( 'style' );
				// End of Hack */
			$state.reload();
		};

		vm.messages = 'Loading stuff and things...';

		vm.loading = true;


		// Page Request
		//vm.getpage = PageService.getpage('infrastructure')

		/*if(!vm.permissions.read.Site){
			$location.path('/accessdenied');
		}*/


		function isInArrayNgForeach(field, arr) {
			var result = false;
			//console.log("HERRE")
			//console.log(field);
			//console.log(arr);

			angular.forEach(arr, function(value, key) {
				//console.log(value);
				if(field == value)
					result = true;
			});

			return result;
		}

		function getParts() {
			PartsService.getParts()

				.then(function(res){

					console.log(res)
					// Check for errors and if token has expired.
					if(res.data.message){
						//console.log(res);
						vm.message = res.data.message;
						console.log(vm.message);

						if(vm.message == "Token has expired"){
							// Send user to login page if token expired.
							//alert("Token has expired, Please relogin");
							$state.go('logout');
						}

						return vm.message;
					}else{

						vm.parts = res.data.data;
						console.log(vm.parts);

						vm.loading = false;

					}

				}, function(err){
					console.log(err)
					alert(err);
				});
		}

		getParts();

		var pullparts		= $interval(getParts,30000);

		$scope.$on('$destroy', function() {
			//console.log($scope);
            $interval.cancel(pullparts);
		});

		var id = $stateParams.id;
		//console.log(id + " printing id here...")

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

			PartsService.createPart(angular.copy(form)).then(function(data) {
				//alert("site Added Succesfully" + data);
				$state.go('parts');
			}, function(error) {
				console.log(error)
				console.log(error.data.message)
				alert('Error: ' + error.data.message + " | Status: " + error.status);
			});

		}

		// Edit state for DID block Edit button.
		vm.edit = {};

		// Update DID Block service called by the save button.
		vm.update = function(part) {

			PartsService.updatePart(part.id, part).then(function(data) {
			  //alert('Part Updated Successfully!')
			  $location.path('/parts');
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
