app.controller('tooth', ['$scope', function ($scope) {
    var jsonArmed;

    var adultArrives_left = [];
    for (var i = 18; i >= 11; i--) {
        if (i > 3 && i < 14) {
            jsonArmed = {id: i, kindtooth: 'decidua Mixed'};
            adultArrives_left.push(jsonArmed);
        } else {
            jsonArmed = {id: i, kindtooth: 'decidua'};
            adultArrives_left.push(jsonArmed);
        }

    }
    $scope.adultArrives_left = adultArrives_left;


    var adultArrives_right = [];
    for (var i = 21; i <= 28; i++) {
        if (i > 3 && i < 14) {
            jsonArmed = {id: i, kindtooth: 'decidua Mixed'};
            adultArrives_right.push(jsonArmed);
        } else {
            jsonArmed = {id: i, kindtooth: 'decidua'};
            adultArrives_right.push(jsonArmed);
        }

    }
    $scope.adultArrives_right = adultArrives_right;

    var adult_down_left = [];
    for (var i = 48; i >= 41; i--) {
        if (i > 19 && i < 30) {
            jsonArmed = {id: i, kindtooth: 'decidua Mixed'};
            adult_down_left.push(jsonArmed);
        } else {
            jsonArmed = {id: i, kindtooth: 'decidua'};
            adult_down_left.push(jsonArmed);
        }
    }
    $scope.adult_down_left = adult_down_left;


    var adult_down_right = [];
    for (var i = 31; i <=38; i++) {
        if (i > 19 && i < 30) {
            jsonArmed = {id: i, kindtooth: 'decidua Mixed'};
            adult_down_right.push(jsonArmed);
        } else {
            jsonArmed = {id: i, kindtooth: 'decidua'};
            adult_down_right.push(jsonArmed);
        }
    }
    $scope.adult_down_right = adult_down_right;

    var ninoArrives_left = [];
    for (var i = 55; i >= 51; i--) {
        jsonArmed = {id: i, kindtooth: 'nino'};
        ninoArrives_left.push(jsonArmed);
    }
    $scope.ninoArrives_left = ninoArrives_left;


    var ninoArrives_right = [];
    for (var i = 61; i <= 65; i++) {
        jsonArmed = {id: i, kindtooth: 'nino'};
        ninoArrives_right.push(jsonArmed);
    }
    $scope.ninoArrives_right = ninoArrives_right;

    var ninoDown_left = [];
    for (var i = 85; i >= 81; i--) {
        jsonArmed = {id: i, kindtooth: 'nino'};
        ninoDown_left.push(jsonArmed);
    }
    $scope.ninoDown_left = ninoDown_left;


    var ninoDown_right = [];
    for (var i = 71; i <= 75; i++) {
        jsonArmed = {id: i, kindtooth: 'nino'};
        ninoDown_right.push(jsonArmed);
    }
    $scope.ninoDown_right = ninoDown_right;

}]);
