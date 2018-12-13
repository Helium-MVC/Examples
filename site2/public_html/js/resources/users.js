AngularApp.factory('Users', ['$resource', 'api_domain', 'api_public_key', 'api_signature',  function($resource, api_domain, api_public_key, api_signature ) {
return $resource( api_domain, null,
    {
        'login': { method:'POST', url: '/api/login?api_key='+ api_public_key +'&sig=' + api_signature },
        'register': { method:'POST', url: '/api/register?api_key='+ api_public_key +'&sig=' + api_signature },
        'update': {method : 'POST', url : '/api/updateUser?api_key='+ api_public_key +'&sig=' + api_signature},
        'email': {method : 'POST', url : '/api/updateEmail?api_key='+ api_public_key +'&sig=' + api_signature},
        'password': {method : 'POST', url : '/api/updatePassword?api_key='+ api_public_key +'&sig=' + api_signature},
        'get': {method : 'GET', url : '/api/findUser?api_key='+ api_public_key +'&sig=' + api_signature},
        
    });
}]);