AngularApp.factory('Users', ['$resource', 'api_domain', function($resource, api_domain ) {
return $resource( api_domain, null,
    {
        'login': { method:'POST', url: '/api/login' },
        'register': { method:'POST', url: '/api/register' },
        'update': {method : 'POST', url : '/api/updateUser'},
        'email': {method : 'POST', url : '/api/updateEmail'},
        'password': {method : 'POST', url : '/api/updatePassword'},
        'get': {method : 'GET', url : '/api/findUser'},
        
    });
}]);