angular
	.module('app')
	.factory('AssetsService', ['$http','$q', function($http, $q){

		var self = {};

		// Get Assets
		self.getAssets = function(httpParams) {
/* 			console.log("HTTP PARAMS:");
			console.log(httpParams); */
			var defer = $q.defer();
			return $http.get(globalUrl + '/api/assets',{params: httpParams})
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

		// Get Asset by ID
		self.getAsset = function(id) {
			var defer = $q.defer();
			return $http.get(globalUrl + '/api/assets/'+id)
				.then(function successCallback(response) {
					defer.resolve(response);

					// Must return the promise to the controller.
					return defer.promise;

			  }, function errorCallback(response) {
					defer.resolve(response);
					return defer.promise;
			  });
		}

		// Create Asset
		self.createAsset = function(asset) {
			return $http.post(globalUrl + '/api/assets',asset);
		}

		// Update Asset by ID
		self.updateAsset = function(id, update) {

			return $http.put(globalUrl + '/api/assets/'+id, update).then(function(response) {

				var data = response.data;
				return data;

			 }, function(error) {return false;});
		}

		// Delete Asset by ID
		self.deleteAsset = function(id) {
			console.log('Service - Deleting ID: '+ id);
			return $http.delete(globalUrl + '/api/assets/'+id, id).then(function(response) {

				var data = response.data;
				return data;

			 });
		}

		return self

	}]);
