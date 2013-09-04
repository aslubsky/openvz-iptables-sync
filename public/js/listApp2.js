'use strict';

/* App Module */


angular.module('listApp', ['ngResource', 'ui.filters', 'ui.focusblur', 'oi.list'])

    //Создание ресурсов
    
    .factory('ListLang', function ($resource) {
        return $resource('action.php?id=:itemId', {itemId:'@id'}, { add: {method: 'PUT'} })
    })
     

    //Контроллеры примеров     

    .controller('MyCtrlLang', ['$scope', 'ListLang', 'oiList', function ($scope, ListLang, oiList) {
        oiList($scope, 'listlang', ListLang);
    }])