(function () {
    'use strict';

    angular
        .module('app')
        .controller('Home.IndexController', Controller);
    function Controller($location, PartsService) {
        var vm = this;
		console.log('inside home controller')
        getParts();

        vm.messages = 'Loading...';
        vm.accounts = {};

        function getParts() {
			PartsService.getParts()
			
				.then(function(res){
					
					console.log(res.data.data)

					// Check for errors and if token has expired. 
					if(res.data.message){
						console.log(res);
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
						console.log(vm.parts)
										
						vm.loading = false;
					}
					
				}, function(err){
					console.log(err)
					alert(err);
				});
		}
    }

})();