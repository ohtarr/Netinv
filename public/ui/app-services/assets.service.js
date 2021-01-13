angular
	.module('app')
	.factory('AssetsService', ['$http','$q', function($http, $q){

		var self = {};

		// Get Assets
		self.getAssets = function(httpParams) {
			return $http.get(globalUrl + '/api/assets',{params: httpParams})
		}

		// Get Asset by ID
		self.getAsset = function(id) {
			return $http.get(globalUrl + '/api/assets/'+id)
		}

		// Create Asset
		self.createAsset = function(asset) {
			return $http.post(globalUrl + '/api/assets',asset);
		}

		// Update Asset by ID
		self.updateAsset = function(id, update) {
			return $http.put(globalUrl + '/api/assets/'+id, update);
		}

		// Delete Asset by ID
		self.deleteAsset = function(id) {
			return $http.delete(globalUrl + '/api/assets/'+id, id)
		}

		return self
	}]);
