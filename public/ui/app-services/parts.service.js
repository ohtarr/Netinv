angular
	.module('app')
	.factory('PartsService', ['$http','$q', function($http, $q){
		
		var self = {};

		
		// Get Parts
		self.getParts = function(httpParams) {
			return $http.get(globalUrl + '/api/parts',{params: httpParams})
		}

		// Get Part by ID
		self.getPart = function(id) {
			return $http.get(globalUrl + '/api/parts/'+id)
		}

		
		// Create Part
		self.createPart = function(part) {
			return $http.post(globalUrl + '/api/parts',part);
		}
		
		
		// Update Part by ID
		self.updatePart = function(id, update) {
			return $http.put(globalUrl + '/api/parts/'+id, update)
		}

		
		// Delete Part by ID
		self.deletePart = function(id) {
			return $http.delete(globalUrl + '/api/parts/'+id, id)
		}

		return self
	}]);