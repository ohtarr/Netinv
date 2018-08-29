// This actually loads the app, called after the enterpriseauth generated preload.js preloads the app


document.write('<script src="https://code.jquery.com/jquery-1.12.4.js"   integrity="sha256-Qw82+bXyGq6MydymqBxNPYTaUXXq7c8v3CwiYwLLNXU="   crossorigin="anonymous"></script>');

var loadScripts = [
    // Dependancies
	'bower_components/bootstrap/dist/js/bootstrap.min.js',
    'bower_components/angular/angular.js',
    'bower_components/angular-messages/angular-messages.js',
    'bower_components/angular-ui-router/release/angular-ui-router.js',
    'bower_components/ngstorage/ngStorage.js',
    // The single page application
    'app.js',
    'app-services/parts.service.js',
    'app-services/assets.service.js',
    'app-services/partners.service.js',
    'app-services/locations.service.js',
    'home/index.controller.js',
	'parts/parts.controller.js',
	'assets/assets.controller.js',
	'partners/partners.controller.js',
];

// load up all the scripts
for (var i in loadScripts) {
    document.write('<script src="' + loadScripts[i] + '" type="text/javascript"></script>');
}
