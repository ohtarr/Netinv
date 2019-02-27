(function () {
    'use strict';

    angular
        .module('app', ['ui.router', 'ngMessages', 'ngStorage','ui.select','ngSanitize','ui.bootstrap'])
        .config(config)
        .run(run);

    function config($stateProvider, $urlRouterProvider) {
        // default route
        $urlRouterProvider.otherwise("/");

        // app routes
        $stateProvider
            .state('home', {
                url: '/',
                templateUrl: 'assets/assets.html',
                controller: 'Assets.Controller',
                controllerAs: 'vm'
            })
            .state('assets', {
                url: '/assets',
                templateUrl: 'assets/assets.html',
                controller: 'Assets.Controller',
                controllerAs: 'vm'
            })
            .state('createasset', {
                url: '/assets/create',
                templateUrl: 'assets/createasset.html',
                controller: 'Assets.Controller',
                controllerAs: 'vm'
            })
            .state('editasset', {
                url: '/assets/edit/{id}',
                templateUrl: 'assets/editasset.html',
                controller: 'Assets.Controller',
                controllerAs: 'vm'
            })
            .state('parts', {
                url: '/parts',
                templateUrl: 'parts/parts.html',
                controller: 'Parts.Controller',
                controllerAs: 'vm'
            })
            .state('createpart', {
                url: '/parts/create',
                templateUrl: 'parts/createpart.html',
                controller: 'Parts.Controller',
                controllerAs: 'vm'
            })
            .state('editpart', {
                url: '/parts/edit/{id}',
                templateUrl: 'parts/editpart.html',
                controller: 'Parts.Controller',
                controllerAs: 'vm'
            })
            .state('partners', {
                url: '/partners',
                templateUrl: 'partners/partners.html',
                controller: 'Partners.Controller',
                controllerAs: 'vm'
            })
            .state('createpartner', {
                url: '/partners/create',
                templateUrl: 'partners/createpartner.html',
                controller: 'Partners.Controller',
                controllerAs: 'vm'
            })
            .state('editpartner', {
                url: '/partners/edit/{id}',
                templateUrl: 'partners/editpartner.html',
                controller: 'Partners.Controller',
                controllerAs: 'vm'
            });
    }

    function run($rootScope, $http, $location, $localStorage) {
        // require an authenticated user before continuing.
        var user = userAgentApplication.getUser();
        if (!user) {
            console.log('angular run does not have valid user, i should abort');
            throw 'kaboom';
        } else {
            console.log('angular run DOES have a dalid user, proceeding to token request');
            // Try to acquire the token used to query Graph API silently first:
            userAgentApplication.acquireTokenSilent(APIScopes)
                .then(function (token) {
                    console.log('acquiretokensilent got token ' + token);
                    // save the cancerous token so we know we are logged in
                    localStorage.currentUser = { token: token };
                    window.token = token;
                    $http.defaults.headers.common.Authorization = 'Bearer ' + token;
                    //console.log('http.defaults.headers.common.Authorization = ' + $http.defaults.headers.common.Authorization);

                }, function (error) {
                    console.log('acquiretokenssilent failed, attempting acquiretokenredirect');
                    // If the acquireTokenSilent() method fails, then acquire the token interactively via acquireTokenRedirect().
                    if (error) {
                        userAgentApplication.acquireTokenRedirect(APIScopes);
                    }
                });
        }

    }
})();
