angular
	.module('app')
	.factory('LocsService', ['$http','$q', function($http, $q){

		var self = {};

		// Get Locations
		self.getLocations = function(httpParams) {
/* 			console.log("HTTP PARAMS:");
			console.log(httpParams); */
			var defer = $q.defer();
			return $http.get(globalUrl + '/api/locations')
				.then(function successCallback(response) {
					//console.log(response)
					defer.resolve(response);

					// Must return the promise to the controller.
					return defer.promise;

			  }, function errorCallback(response) {
					defer.resolve(response);
					return defer.promise;
			  });
		}

		// Get Location by ID
		self.getLocation = function(id) {
			var defer = $q.defer();
			return $http.get(globalUrl + '/api/locations/'+id)
				.then(function successCallback(response) {
					defer.resolve(response);

					// Must return the promise to the controller.
					return defer.promise;

			  }, function errorCallback(response) {
					defer.resolve(response);
					return defer.promise;
			  });
		}

/* 		// Create Location
		self.createLocation = function(location) {
			return $http.post(globalUrl + '/api/locations',location);
		} */

/* 		// Update Location by ID
		self.updateLocation = function(id, update) {

			return $http.put(globalUrl + '/api/locations/'+id, update).then(function(response) {

				var data = response.data;
				return data;

			 }, function(error) {return false;});
		} */

/* 		// Delete Location by ID
		self.deleteLocation = function(id) {
			console.log('Service - Deleting ID: '+ id);
			return $http.delete(globalUrl + '/api/locations/'+id, id).then(function(response) {

				var data = response.data;
				return data;

			 });
		}
*/
		return self 

	}]);
