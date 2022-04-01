//sample data
var color = 1;
var data = [{tooth: 1, position: 1, color: 8, kind: 1},
    {tooth: 2, position: 2, color: 2, kind: 1},
    {tooth: 2, position: 5, color: 1, kind: 1},
    {tooth: 3, position: 2, color: 6, kind: 1},
    // {tooth: 4, position: 2, color: 4, kind: 1}
];


$(document).ready(function () {
    show_dental_chart();
    $('polygon').on('click', function () {
        var class_disabled = $(this).attr('class');
        if (class_disabled != 'class_disabled') {
            smart(color, this)
        }

    });
    $('input:radio').change(function () {
        var kind = $(this).attr('value');

        // change_odontogram(kind);

    });

    $('.color').click(function () {
        color = $(this).attr('value');
    });

    $('#ver').click(function () {


    });

    function show_dental_chart() {
        var skipEachCircle = null;
        var jumpEachPolygon = null;
        clean();
        // now get data from the database

        //draw the chart

        $.ajax({
            type: 'get',
            url: "/dental-charting/" + $('#global_appointment_id').val(),
            success: function (data_process) {
                console.log(data_process);
                // change_odontogram(data_process[0].kind)


                for (var i = 0; i < data_process.length; i++) {
                    if (data_process[0].kind == 1) {
                        $('#Decidua').prop('checked', true);
                    } else if (data_process[0].kind == 2) {
                        $('#Children').prop('checked', true);
                    } else {
                        $('#Mixed').prop('checked', true);
                    }

                    skipEachCircle = false;
                    jumpEachPolygon = false;
                    if (data_process[i].color == 3 || data_process[i].color == 8 || data_process[i].color == 11) {
                        $('#' + data_process[i].tooth + ' circle').each(function () {
                            if (skipEachCircle == false) {
                                smart(data_process[i].color, this);
                                skipEachCircle = true;
                            }
                        });
                    } else if (data_process[i].color == 4 || data_process[i].color == 6) {
                        $('#' + data_process[i].tooth + ' polygon').each(function () {
                            if (jumpEachPolygon == false) {
                                smart(data_process[i].color, this);
                                jumpEachPolygon = true;
                            }
                        });
                    } else {
                        $('#' + data_process[i].tooth + ' polygon').each(function () {

                            if (data_process[i].position == $(this).attr('value')) {
                                smart(data_process[i].color, this);
                            }
                        });
                    }

                }


            },
            error: function (request, status, error) {
                $('.loading').hide();
            }
        });

    }


    $('#add').click(function () {
        swal({
                title: "Are you sure, You want to save the changes in the chart?",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, save the Chart!",
                closeOnConfirm: false
            },
            function () {
                data = [];
                $('svg').each(function () {
                    var toothD = $(this).attr('id');
                    entrarEach = false;
                    $(this).find('.marked').each(function () {
                        var positionD = $(this).attr('value');
                        var colorD = $(this).attr('color');
                        var kindD = $('input:radio[name=kind]:checked').val();

                        //push all the values to the array
                        data.push({tooth: toothD, position: positionD, color: colorD, kind: kindD});
                    });
                });
                //check if the array is empty
                if (data != "") {
                    console.log(data);
                    //now send array to the server
                    insert_selection(data);
                } else {
                    alert('please first do the charting before saving empty values');
                }

            });
    });


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // function insert_selection(Tooth, position, color, kind) {
    function insert_selection(data) {
        var values = {
            'appointment_id': $('#global_appointment_id').val(),
            'data': data
        };
        $.ajax({
            type: 'POST',
            url: "/dental-charting",
            data: values,
            success: function (data) {
                swal("Alert!", data.message, "success");

                setTimeout(function () {
                    location.reload();
                }, 1900);

            },
            error: function (request, status, error) {
                console.log(error);
            }
        });
    }


    $('#clean').click(function () {
        clean();
    });
});


function clean() {
    $('svg').each(function () {
        $(this).find('.marked').each(function () {
            var color = $(this).attr('color');

            if (color != 3 && color != 4 && color != 6 && color != 8 && color != 11) {
                $(this).attr('class', 'tooth');
            } else if (color == 3) {
                $(this).attr('class', 'endodontics');
            } else if (color == 4) {
                $(this).attr('class', 'absent');
            } else if (color == 6) {
                $(this).attr('class', 'implant');
            } else if (color == 8) {
                $(this).attr('class', 'crown');
            } else if (color == 11) {
                $(this).attr('class', 'impaction');
            }

        });
    });
}

function removeSpecial(object) {
    $(object).parent().find('polygon').each(function () {
        if ($(this).attr('value') >= 6 && $(this).attr('value') <= 7) {
            $(this).attr('class', 'absent');
        } else if ($(this).attr('value') == 10) {
            $(this).attr('class', 'implant');
        }
    });
    $(object).parent().find('circle').each(function () {
        if ($(this).attr('value') == 8) {
            $(this).attr('class', 'crown');
        } else if ($(this).attr('value') == 9) {
            $(this).attr('class', 'endodontics');
        } else if ($(this).attr('value') == 11) {
            $(this).attr('class', 'impaction');
        }
    });
}

function clean_sides(object) {
    $(object).parent().find('polygon').each(function () {
        if ($(this).attr('value') < 6) {
            $(this).attr({
                class: 'tooth',
                color: 0
            });
        }
    });
}


function smart(color, object) {
    if (color == 1) {
        removeSpecial(object);
        $(object).attr({
            class: 'markedRed marked',
            color: color
        });
    } else if (color == 2) {
        removeSpecial(object);
        $(object).attr({
            class: 'markedYellow marked',
            color: color
        });
    } else if (color == 3) {
        clean_sides(object);
        removeSpecial(object);
        $(object).parent().find('.endodontics').each(function () {
            $(this).attr({
                class: 'markedOrange marked',
                color: color
            });
        });
    } else if (color == 4) {
        clean_sides(object);
        removeSpecial(object);
        $(object).parent().find('.absent').each(function () {
            $(this).attr({
                class: 'markedTomate marked',
                color: color
            });
        });

    } else if (color == 5) {
        removeSpecial(object);
        $(object).attr({
            class: 'markedBrown marked',
            color: color
        });

    } else if (color == 6) {
        clean_sides(object);
        removeSpecial(object);
        $(object).parent().find('.implant').each(function () {
            $(this).attr({
                class: 'markedPurple marked',
                color: color
            });
        });
    } else if (color == 7) {
        removeSpecial(object);
        $(object).attr({
            class: 'markedVerde marked',
            color: color
        });
    } else if (color == 8) {
        clean_sides(object);
        removeSpecial(object);
        $(object).parent().find('.crown').each(function () {
            $(this).attr({
                class: 'markedBlue marked',
                color: color
            });
        });
    } else if (color == 9) {
        removeSpecial(object);
        $(object).attr({
            class: 'tooth',
            color: color
        });
    } else if (color == 11) {
        clean_sides(object);
        removeSpecial(object);
        $(object).parent().find('.impaction').each(function () {
            $(this).attr({
                class: 'markedLightGreen marked',
                color: color
            });
        });
    }


    function unmark_tooth() {
        swal({
                title: "Are you sure, You want to unmark this tooth?",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, un-mark tooth!",
                closeOnConfirm: false
            },
            function () {


            });
    }
}


