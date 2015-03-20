/**
* WPSearch Module
*
* Description
*/
angular.module('WPSearch', [], function($httpProvider) {
          // Use x-www-form-urlencoded Content-Type
          
          $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
          
           /**
           * The workhorse; converts an object to x-www-form-urlencoded serialization.
           * @param {Object} obj
           * @return {String}
           */ 
          var param = function(obj) {
            var query = '', name, value, fullSubName, subName, subValue, innerObj, i;
        
            for(name in obj) {
              value = obj[name];
        
              if(value instanceof Array) {
                for(i=0; i<value.length; ++i) {
                  subValue = value[i];
                  fullSubName = name + '[' + i + ']';
                  innerObj = {};
                  innerObj[fullSubName] = subValue;
                  query += param(innerObj) + '&';
                }
              }
              else if(value instanceof Object) {
                for(subName in value) {
                  subValue = value[subName];
                  fullSubName = name + '[' + subName + ']';
                  innerObj = {};
                  innerObj[fullSubName] = subValue;
                  query += param(innerObj) + '&';
                }
              }
              else if(value !== undefined && value !== null)
                query += encodeURIComponent(name) + '=' + encodeURIComponent(value) + '&';
            }
        
            return query.length ? query.substr(0, query.length - 1) : query;
          };
        
          // Override $http service's default transformRequest
          $httpProvider.defaults.transformRequest = [function(data) {
            return angular.isObject(data) && String(data) !== '[object File]' ? param(data) : data;
          }];
}).controller('searchCtrl', function($scope){

	$scope.allPosts = searchdata.posts;

	$scope.deletePost = function(id, title){
		$scope.deletingid = id;
		$scope.deletingtitle = title;		
	}

	$scope.deleteNow = function() {
		jQuery('#deletebtn').button('loading');
		var data = {
			action: 'wcp_delete_post',
			post_id: $scope.deletingid,
		}		
		jQuery.post(searchdata.url, data, function(resp) {
			jQuery('#deletebtn').button('reset');
			jQuery('.bs-example-modal-md').modal('toggle');
			location.reload();
		});
	}
});