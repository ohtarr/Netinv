angular
	.module('app')
	.factory('PartnersService', ['$http','$q', function($http, $q){
		
		var self = {};

		
		// Get Partners
		self.getPartners = function(httpParams) {
			return $http.get(globalUrl + '/api/partners',{params: httpParams})
		}

		// Get Partner by ID
		self.getPartner = function(id) {
			return $http.get(globalUrl + '/api/partners/'+id)
		}

		
		// Create Partner
		self.createPartner = function(partner) {
			return $http.post(globalUrl + '/api/partners',partner);
		}
		
		
		// Update Partner by ID
		self.updatePartner = function(id, update) {
			return $http.put(globalUrl + '/api/partners/'+id, update)
		}

		
		// Delete Partner by ID
		self.deletePartner = function(id) {
			return $http.delete(globalUrl + '/api/partners/'+id, id)
		}

		return self
	}]);