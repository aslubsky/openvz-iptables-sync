'use strict';

/* App Module */


angular.module('listApp', ['ngResource', 'ui.sortable', 'ui.filters', 'ui.focusblur', 'oi.list'])

    //Создание ресурсов
    .factory('List1', function ($resource) {
        return $resource('action.php/list1/:itemId', {itemId:'@id'}, { add: {method: 'PUT'} })
    })
    
    .factory('List2', function ($resource) {
        return $resource('action.php/list2/:itemId', {itemId:'@id'}, { add: {method: 'PUT'} })
    })
    
    .factory('List3', function ($resource) {
        return $resource('action.php/list3/:itemId', {itemId:'@id'}, { add: {method: 'PUT'} })
    })
    
    .factory('List4', function ($resource) {
        return $resource('undefined')
    })
    
    .factory('ListLang', function ($resource) {
        return $resource('action.php/listlang/:itemId', {itemId:'@id'}, { add: {method: 'PUT'} })
    })
     

    //Контроллеры примеров     
    .controller('MyCtrl1', ['$scope', 'List1', 'oiList', function ($scope, List1, oiList) {

        oiList($scope, 'list1', List1);
    }])

    .controller('MyCtrl2', ['$scope', 'List2', 'oiList', function ($scope, List2, oiList) {

        oiList($scope, 'list2', List2, 'before', {fields: {
            name: ''
        }});
    }])
    
    .controller('MyCtrl3', ['$scope', 'List3', 'oiList', function ($scope, List3, oiList) {

        oiList($scope, 'list3', List3, 'after', {fields: {
            name: ''
        }});
    }])
    
    .controller('MyCtrlLang', ['$scope', 'ListLang', 'oiList', function ($scope, ListLang, oiList) {

        oiList($scope, 'listlang', ListLang, 'after', {fields: {
            name: [
                {lang: 'ru', text: ''},
                {lang: 'en', text: ''},
                {lang: 'de', text: ''}
            ]
        }});
    }])
    
    .controller('MyCtrl4', ['$scope', 'List4', 'oiList', function ($scope, List4, oiList) {

        oiList($scope, 'list4', List4);
    }])