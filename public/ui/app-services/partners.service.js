angular
	.module('app')
	.factory('PartnersService', ['$http','$q', function($http, $q){
		
		var self = {};

		
		// Get Partners
		self.getPartners = function() {
			var defer = $q.defer();
			return $http.get(globalUrl + '/api/partners')
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

		// Get Partner by ID
		self.getPartner = function(id) {
			var defer = $q.defer();
			return $http.get(globalUrl + '/api/partners/'+id)
				.then(function successCallback(response) {
					defer.resolve(response);
					
					// Must return the promise to the controller. 
					return defer.promise;
					
			  }, function errorCallback(response) {
					defer.resolve(response);
					return defer.promise;
			  });
		}

		
		// Create Partner
		self.createPartner = function(partner) {
			return $http.post(globalUrl + '/api/partners',partner);
		}
		
		
		// Update Partner by ID
		self.updatePartner = function(id, update) {
        
			return $http.put(globalUrl + '/api/partners/'+id, update).then(function(response) {

				var data = response.data;
				return data;

			 }, function(error) {return false;});
		}

		
		// Delete Partner by ID
		self.deletePartner = function(id) {
			console.log('Service - Deleting ID: '+ id);
			return $http.delete(globalUrl + '/api/partners/'+id, id).then(function(response) {

				var data = response.data;
				return data;

			 });
		}


		return self

	}]);