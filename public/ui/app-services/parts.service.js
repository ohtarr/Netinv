angular
	.module('app')
	.factory('PartsService', ['$http','$q', function($http, $q){
		
		var self = {};

		
		// Get Parts
		self.getParts = function(httpParams) {
			var defer = $q.defer();
			return $http.get(globalUrl + '/api/parts',{params: httpParams})
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

		// Get Part by ID
		self.getPart = function(id) {
			var defer = $q.defer();
			return $http.get(globalUrl + '/api/parts/'+id)
				.then(function successCallback(response) {
					defer.resolve(response);
					
					// Must return the promise to the controller. 
					return defer.promise;
					
			  }, function errorCallback(response) {
					defer.resolve(response);
					return defer.promise;
			  });
		}

		
		// Create Part
		self.createPart = function(part) {
			return $http.post(globalUrl + '/api/parts',part);
		}
		
		
		// Update Part by ID
		self.updatePart = function(id, update) {
        
			return $http.put(globalUrl + '/api/parts/'+id, update).then(function(response) {

				var data = response.data;
				return data;

			 }, function(error) {return false;});
		}

		
		// Delete Part by ID
		self.deletePart = function(id) {
			console.log('Service - Deleting ID: '+ id);
			return $http.delete(globalUrl + '/api/parts/'+id, id).then(function(response) {

				var data = response.data;
				return data;

			 });
		}


		return self

	}]);