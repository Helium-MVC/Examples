
//Instantiate the app with modules
var AngularApp = angular.module('Site2App', ['ngResource', 'ngSanitize', 'ngRoute', 'wysiwyg.module', 'colorpicker.module',]);

//Set contants to be used across the app
AngularApp.value('api_domain', '/api');
AngularApp.value('messageTimeout', 20000);
AngularApp.value('api_public_key', angular.element(document.getElementById('api_public_key')).val());
AngularApp.value('api_signature', angular.element(document.getElementById('api_signature')).val());

//Make other js/node libraries as null
var exports = undefined;
var module = undefined;
var define = undefined;
var require = undefined;
var rangyAutoInitialize = undefined;

/**
 * Protect window.console method calls, e.g. console is not defined on IE
 * unless dev tools are open, and IE doesn't define console.debug
 */
(function() {
  if (!window.console) {
    window.console = {};
  }
  // union of Chrome, FF, IE, and Safari console methods
  var m = [
    "log", "info", "warn", "error", "debug", "trace", "dir", "group",
    "groupCollapsed", "groupEnd", "time", "timeEnd", "profile", "profileEnd",
    "dirxml", "assert", "count", "markTimeline", "timeStamp", "clear"
  ];
  // define undefined methods as noops to prevent errors
  for (var i = 0; i < m.length; i++) {
    if (!window.console[m[i]]) {
      window.console[m[i]] = function() {};
    }    
  } 
})();

var console = window.console;