AngularApp.factory('Posts', ['$resource', 'api_domain', function($resource, api_domain ) {
return $resource( api_domain + '/gs/:id', null,
    {
        'save': { method:'POST', url: '/api/createPost' },
        'update': { method:'POST', url: '/api/updatePost' },
        'get': {method : 'GET', url : '/api/findPost'}
        
    });
}]);