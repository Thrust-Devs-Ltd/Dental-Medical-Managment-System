app.directive('opcionescanvas', function () {
    return {
        restrict: 'E',
        scope: {},
        // templateUrl: 'opciones_canvas'
        template: '<br><input type="button" class="hidden" value="ver" id="ver"/>\n' +
            '<input type="button" value="save changes" class="btn green-meadow" id="add"/>\n' +
            '<input type="button" class="hidden" value="clean" id="clean"/>\n' +
            '\n' +
            '<input type="radio" class="hidden" id="Decidua" name="kind" value="1" checked/>' +
            '<input type="radio" class="hidden" id="Children" name="kind" value="2"/>' +
            '<input type="radio" class="hidden" id="Mixed" name="kind" value="3"/> <br><br>' +
            '<table border="1" align="center" width="600px">\n' +
            '    <tr>\n' +
            '        <th> Filling </th>' +
            '        <th> Carious </th>' +
            '        <th> RCT </th>' +
            '        <th> Extraction </th>' +
            '        <th class="hidden">Resin</th>' +
            '        <th> Implant </th>\n' +
            '        <th class="hidden">Sealant</th>' +
            '        <th> Crown </th>' +
            '        <th> Impaction </th>' +
            '        <th class="text-danger"> Unmark Tooth </th>' +
            '    </tr>\n' +
            '    <td>\n' +
            '        <center>\n' +
            '            <div class="color" value="1" style="background-color:red;width:20px;height:20px"></div>\n' +
            '        </center>\n' +
            '    </td>\n' +
            '    <td>\n' +
            '        <center>\n' +
            '            <div class="color" value="2" style="background-color:yellow;width:20px;height:20px"></div>\n' +
            '        </center>\n' +
            '    </td>\n' +
            '    <td>\n' +
            '        <center>\n' +
            '            <div class="color" value="3" style="background-color:orange;width:20px;height:20px"></div>\n' +
            '        </center>\n' +
            '    </td>\n' +
            '    <td>\n' +
            '        <center>\n' +
            '            <div class="color" value="4" style="background-color:tomato;width:20px;height:20px"></div>\n' +
            '        </center>\n' +
            '    </td>\n' +
            '    <td class="hidden">\n' +
            '        <center>\n' +
            '            <div class="color" value="5" style="background-color:#CC6600;width:20px;height:20px"></div>\n' +
            '        </center>\n' +
            '    </td>\n' +
            '    <td>\n' +
            '        <center>\n' +
            '            <div class="color" value="6" style="background-color:#CC66CC;width:20px;height:20px"></div>\n' +
            '        </center>\n' +
            '    </td>\n' +
            '    <td class="hidden">\n' +
            '        <center>\n' +
            '            <div class="color" value="7" style="background-color:green;width:20px;height:20px"></div>\n' +
            '        </center>\n' +
            '    </td>\n' +
            '    <td>\n' +
            '        <center>\n' +
            '            <div class="color" value="8" style="background-color:blue;width:20px;height:20px"></div>\n' +
            '        </center>\n' +
            '    </td>\n' +
            '    <td>\n' +
            '        <center>\n' +
            '            <div class="color" value="11" style="background-color:#29e1b1;width:20px;height:20px"></div>\n' +
            '        </center>\n' +
            '    </td>\n' +
            '    <td>\n' +
            '        <center>\n' +
            '            <div class="color" value="9" style="background-color:black;width:20px;height:20px"></div>\n' +
            '        </center>\n' +
            '    </td>\n' +

            '    <tr>\n' +
            '</table>\n'
    }
});
