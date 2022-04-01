app.directive('canvasodontograma', function () {
    return {
        restrict: 'E',
        scope: {
            info: "=",
        },
        // templateUrl: 'canvas_odontograma'
        template: '<svg height="50" class="{{info.kindtooth}}" width="50" id="{{info.id}}">\n' +
            '\t\t  \t<polygon points="10,15 15,10 50,45 45,50" state="4" value="6" class="absent" />\n' +
            '  \t\t\t<polygon points="45,10 50,15 15,50 10,45" state="4" value="7" class="absent" />\n' +
            '  \t\t\t<circle cx="30" cy="30" r="16" state="8" value="8" class="crown"/>\n' +
            '  \t\t\t<circle cx="30" cy="30" r="16" state="11" value="11" class="impaction"/>\n' +
            '  \t\t\t<circle cx="30" cy="30" r="20" state="3" value="9" class="endodontics"/>\n' +
            '  \t\t\t<polygon points="50,10 40,10 10,26 10,32 46,32 10,50 20,50 50,36 50,28 14,28" state="6" value="10" class="implant"/>\n' +
            '  \t\t\t<polygon points="10,10 50,10 40,20 20,20" state="0" value="1" class="tooth" />\n' +
            '  \t\t\t<polygon points="50,10 50,50 40,40 40,20" state="0" value="2" class="tooth" />\n' +
            '  \t\t\t<polygon points="50,50 10,50 20,40 40,40" state="0" value="3" class="tooth" />\n' +
            '  \t\t\t<polygon points="10,50 20,40 20,20 10,10" state="0" value="4" class="tooth" />\n' +
            '  \t\t\t<polygon points="20,20 40,20 40,40 20,40" state="0" value="5" class="tooth" />\n' +
            '<text  x="25" y="9"  fill="navy" stroke="navy" stroke-width="0.1" style="font-size: 9pt;font-weight:normal">{{info.id}}</text>' +
            '</svg>'
    };
});
