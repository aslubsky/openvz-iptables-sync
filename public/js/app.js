'use strict';

/* App Module */


angular.module('vpsApp', ['ngResource'])

    //Создание ресурсов
    
    .factory('PortsList', function ($resource) {
        return $resource('action.php?id=:itemId', {itemId:'@id'}, { add: {method: 'PUT'} })
    })
     

    //Контроллеры примеров     

    .controller('ListCtrl', ['$scope', 'PortsList', function ($scope, PortsList) {
        $scope.nodes = PortsList.query();
    }])


    .controller('VpsCtrl', ['$scope', 'PortsList', function ($scope, PortsList) {
        //oiList($scope, 'listlang', ListLang);
        $scope.save = function(port){
            var obj = new PortsList(port);
            $scope.loading = true;
            obj.$save(function(res) {
                $scope.loading = false;
                $scope.completed = true;
                //console.log(res);
            });
            //console.log(port);
        }
        $scope.add = function(nodeId){
            var obj = new PortsList();
            obj.node_id = nodeId;
            $scope.loading = true;
            obj.$save(function(res) {
                $scope.loading = false;
                $scope.completed = true;
                
                angular.forEach($scope.nodes, function(node){
                    if(node.id == nodeId) {
                        node.ports.push(res);
                    }
                });
            });
        }
        $scope.del = function(nodeId, id, idx){
            //console.log(idx);
            var obj = new PortsList();
            obj.$delete({itemId: id}, function(res){
                //console.log(res);
                angular.forEach($scope.nodes, function(node){
                    if(node.id == nodeId) {
                        node.ports.splice(idx, 1);
                    }
                });
            });
        }
    }])