AngularApp.factory('Posts', ['$resource', 'api_domain', 'api_public_key', 'api_signature', function($resource, api_domain, api_public_key, api_signature) {
return $resource( api_domain + '/posts/:id', null,
    {
        'save': { method:'POST', url: '/api/createPost?api_key='+ api_public_key +'&sig=' + api_signature },
        'update': { method:'POST', url: '/api/updatePost?api_key='+ api_public_key +'&sig=' + api_signature },
        'get': {method : 'GET', url : '/api/findPost?api_key='+ api_public_key +'&sig=' + api_signature}
        
    });
}]);