angular
	.module('app')
	.factory('LocsService', ['$http','$q', function($http, $q){

		var self = {};

		// Get Locations
		self.getLocations = function(httpParams) {
			return $http.get(globalUrl + '/api/locations')
		}

		// Get Location by ID
		self.getLocation = function(id) {
			return $http.get(globalUrl + '/api/locations/'+id)
		}

		return self 
	}]);
