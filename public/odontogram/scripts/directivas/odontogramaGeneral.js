app.directive('odontogramageneral', function () {
    return {
        restrict: 'E',
        scope: {},
        // templateUrl:'odontograma'
        template: '<center ng-controller="tooth">\n' +
            '    <div>\n' +
            '        <div class="row">\n' +
            '            <div class="col-md-6">\n' +
            '                <canvasodontograma info="i" ng-repeat="i in adultArrives_left"></canvasodontograma>\n' +
            '            </div>\n' +
            '            <div class="col-md-6">\n' +
            '                <canvasodontograma info="i" ng-repeat="i in adultArrives_right"></canvasodontograma>\n' +
            '            </div>\n' +
            '        </div>\n' +
            '    </div>\n' +
            '    <div>\n' +
            '        <div class="row">\n' +
            '            <div class="col-md-6">\n' +
            '                <canvasodontograma info="i" ng-repeat="i in ninoArrives_left"></canvasodontograma>\n' +
            '            </div>\n' +
            '            <div class="col-md-6">\n' +
            '                <canvasodontograma info="i" ng-repeat="i in ninoArrives_right"></canvasodontograma>\n' +
            '            </div>\n' +
            '        </div>\n' +
            '    </div>\n' +
            '    <div>\n' +
            '        <div class="row">\n' +
            '            <div class="col-md-6">\n' +
            '                <canvasodontograma info="i" ng-repeat="i in ninoDown_left"></canvasodontograma>\n' +
            '            </div>\n' +
            '            <div class="col-md-6">\n' +
            '                <canvasodontograma info="i" ng-repeat="i in ninoDown_right"></canvasodontograma>\n' +
            '            </div>\n' +
            '        </div>\n' +
            '    </div>\n' +
            '    <div>\n' +
            '        <div class="row">\n' +
            '            <div class="col-md-6">\n' +
            '                <canvasodontograma info="i" ng-repeat="i in adult_down_left"></canvasodontograma>\n' +
            '            </div>\n' +
            '            <div class="col-md-6">\n' +
            '                <canvasodontograma info="i" ng-repeat="i in adult_down_right"></canvasodontograma>\n' +
            '            </div>\n' +
            '        </div>\n' +
            '\n' +
            '    </div>\n' +
            '    <opcionescanvas></opcionescanvas>\n' +
            '</center>\n'
    };

});
