$(function () {
    $("#radio").buttonset();
    $("#radio_section").buttonset();
    $('#radio').change(function () {
        action = $("input[name='action']:checked").val();
        if (action == 'Delete') {
            $("#radio_section").show('blind', 500);
        } else {
            $("#radio_section").hide();
        }
    });

    //draw the chary

    $.ajax({
        type: 'get',
        url: "/dental-charting/" + $('#global_appointment_id').val(),
        success: function (data) {
            // console.log(data);
            for (var i = 0; i < data.length; i++) {
                if (data[i].treatment == "Fracture" || data[i].treatment == "Restoration") {
                    draw_section(ctx2, data[i].tooth, data[i].section, data[i].color);
                } else {
                    Mark_Extraction(ctx2, data[i].tooth, data[i].color)
                }
            }
        },
        error: function (request, status, error) {
            $('.loading').hide();
        }
    });


});


// Function to draw the lines of each Tooth
function draw_outline(context, start_x, start_y, med, separation_x, separation_y) {
    var ctx = context;
    // Defining drawing points
    cua = med / 4;
    ter = cua * 3;
    /* 1ra zona */
    if (ctx) {
        ctx.strokeStyle = color_line;
        ctx.beginPath();
        ctx.moveTo(start_x, start_y);
        ctx.lineTo(med + start_x, start_y);
        ctx.lineTo(ter + start_x, cua + start_y);
        ctx.lineTo(cua + start_x, cua + start_y);
        ctx.closePath();
        ctx.stroke();
    }
    /* 2da zona */
    if (ctx) {
        ctx.strokeStyle = color_line;
        ctx.beginPath();
        ctx.moveTo(ter + start_x, cua + start_y);
        ctx.lineTo(med + start_x, start_y);
        ctx.lineTo(med + start_x, med + start_y);
        ctx.lineTo(ter + start_x, ter + start_y);
        ctx.closePath();
        ctx.stroke();
    }
    /* 3ra zona */
    if (ctx) {
        ctx.strokeStyle = color_line;
        ctx.beginPath();
        ctx.moveTo(cua + start_x, ter + start_y);
        ctx.lineTo(ter + start_x, ter + start_y);
        ctx.lineTo(med + start_x, med + start_y);
        ctx.lineTo(start_x, med + start_y);
        ctx.closePath();
        ctx.stroke();
    }
    /* 4ta zona */
    if (ctx) {
        ctx.strokeStyle = color_line;
        ctx.beginPath();
        ctx.moveTo(start_x, start_y);
        ctx.lineTo(cua + start_x, cua + start_y);
        ctx.lineTo(cua + start_x, ter + start_y);
        ctx.lineTo(start_x, med + start_y);
        ctx.closePath();
        ctx.stroke();
    }
}

// Function to paint a Tooth region
function draw_section(contexto, num_Tooth, section, color_pas) {
    // console.log(" Tooth:" + num_Tooth + " section:" + section + " color:" + color_pas);
    var ctx = contexto;
    // Definiendo puntos de dibujo
    med = medida;
    cua = med / 4;
    ter = cua * 3;
    num_Tooth = num_Tooth - 1;
    color_line = color_pas;
    if (num_Tooth < 16) {
        start_y = 20;
    } else {
        num_Tooth = num_Tooth - 16;
        start_y = med + 100;
        //if(num_Tooth==1){num_Tooth=0;}
    }
    //alert(num_Tooth);
    start_x = (num_Tooth * med) + (separation_x * num_Tooth) + separation_x;
    /* 1ra zona */
    if (section == 1) {
        if (ctx) {
            ctx.fillStyle = color_line;
            ctx.beginPath();
            ctx.moveTo(start_x, start_y);
            ctx.lineTo(med + start_x, start_y);
            ctx.lineTo(ter + start_x, cua + start_y);
            ctx.lineTo(cua + start_x, cua + start_y);
            ctx.closePath();
            ctx.fill();
            ctx.strokeStyle = 'black';
            ctx.stroke();
        }
    }
    /* 2da zona */
    if (section == 2) {
        if (ctx) {
            ctx.fillStyle = color_line;
            ctx.beginPath();
            ctx.moveTo(ter + start_x, cua + start_y);
            ctx.lineTo(med + start_x, start_y);
            ctx.lineTo(med + start_x, med + start_y);
            ctx.lineTo(ter + start_x, ter + start_y);
            ctx.closePath();
            ctx.fill();
            ctx.strokeStyle = 'black';
            ctx.stroke();
        }
    }
    /* 3ra zona */
    if (section == 3) {
        if (ctx) {
            ctx.fillStyle = color_line;
            ctx.beginPath();
            ctx.moveTo(cua + start_x, ter + start_y);
            ctx.lineTo(ter + start_x, ter + start_y);
            ctx.lineTo(med + start_x, med + start_y);
            ctx.lineTo(start_x, med + start_y);
            ctx.closePath();
            ctx.fill();
            ctx.strokeStyle = 'black';
            ctx.stroke();
        }
    }
    /* 4ta zona */
    if (section == 4) {
        if (ctx) {
            ctx.fillStyle = color_line;
            ctx.beginPath();
            ctx.moveTo(start_x, start_y);
            ctx.lineTo(cua + start_x, cua + start_y);
            ctx.lineTo(cua + start_x, ter + start_y);
            ctx.lineTo(start_x, med + start_y);
            ctx.closePath();
            ctx.fill();
            ctx.strokeStyle = 'black';
            ctx.stroke();
        }
    }
    /* 5ta zona(medio) */
    if (section == 5) {
        if (ctx) {
            ctx.fillStyle = color_line;
            ctx.beginPath();
            ctx.moveTo(cua + start_x, cua + start_y);
            ctx.lineTo(ter + start_x, cua + start_y);
            ctx.lineTo(ter + start_x, ter + start_y);
            ctx.lineTo(cua + start_x, ter + start_y);
            ctx.closePath();
            ctx.fill();
            ctx.strokeStyle = 'black';
            ctx.stroke();
        }
    }
}

//
// Funcion para sombrear
function Mark_section(contexto, num_Tooth, section, color_pas) {
    var ctx = contexto;
    // Definiendo puntos de dibujo
    med = medida;
    cua = med / 4;
    ter = cua * 3;
    num_Tooth = num_Tooth - 1;
    color_line = color_pas;
    if (num_Tooth < 16) {
        start_y = 20;
    } else {
        num_Tooth = num_Tooth - 16;
        start_y = med + 100;
        //if(num_Tooth==1){num_Tooth=0;}
    }
    //alert(num_Tooth);
    start_x = (num_Tooth * med) + (separation_x * num_Tooth) + separation_x;
    /* 1ra zona */
    if (section == 1) {
        if (ctx) {
            ctx.fillStyle = color_line;
            ctx.beginPath();
            ctx.moveTo(start_x, start_y);
            ctx.lineTo(med + start_x, start_y);
            ctx.lineTo(ter + start_x, cua + start_y);
            ctx.lineTo(cua + start_x, cua + start_y);
            ctx.closePath();
            //ctx.fill();
            ctx.strokeStyle = 'yellow';
            ctx.stroke();
        }
    }
    /* 2da zona */
    if (section == 2) {
        if (ctx) {
            ctx.fillStyle = color_line;
            ctx.beginPath();
            ctx.moveTo(ter + start_x, cua + start_y);
            ctx.lineTo(med + start_x, start_y);
            ctx.lineTo(med + start_x, med + start_y);
            ctx.lineTo(ter + start_x, ter + start_y);
            ctx.closePath();
            //ctx.fill();
            ctx.strokeStyle = 'yellow';
            ctx.stroke();
        }
    }
    /* 3ra zona */
    if (section == 3) {
        if (ctx) {
            ctx.fillStyle = color_line;
            ctx.beginPath();
            ctx.moveTo(cua + start_x, ter + start_y);
            ctx.lineTo(ter + start_x, ter + start_y);
            ctx.lineTo(med + start_x, med + start_y);
            ctx.lineTo(start_x, med + start_y);
            ctx.closePath();
            //ctx.fill();
            ctx.strokeStyle = 'yellow';
            ctx.stroke();
        }
    }
    /* 4ta zona */
    if (section == 4) {
        if (ctx) {
            ctx.fillStyle = color_line;
            ctx.beginPath();
            ctx.moveTo(start_x, start_y);
            ctx.lineTo(cua + start_x, cua + start_y);
            ctx.lineTo(cua + start_x, ter + start_y);
            ctx.lineTo(start_x, med + start_y);
            ctx.closePath();
            //ctx.fill();
            ctx.strokeStyle = 'yellow';
            ctx.stroke();
        }
    }
    /* 5ta zona(medio) */
    if (section == 5) {
        if (ctx) {
            ctx.fillStyle = color_line;
            ctx.beginPath();
            ctx.moveTo(cua + start_x, cua + start_y);
            ctx.lineTo(ter + start_x, cua + start_y);
            ctx.lineTo(ter + start_x, ter + start_y);
            ctx.lineTo(cua + start_x, ter + start_y);
            ctx.closePath();
            //ctx.fill();
            ctx.strokeStyle = 'yellow';
            ctx.stroke();
        }
    }
}

// Full Tooth shading function
function Mark_Tooth(contexto, num_Tooth, color_pas) {
    var ctx = contexto;
    // Defining drawing points
    med = medida;
    num_Tooth = num_Tooth - 1;
    color_line = color_pas;
    if (num_Tooth < 16) {
        start_y = 20;
    } else {
        num_Tooth = num_Tooth - 16;
        start_y = med + 100;
    }
    //alert(num_Tooth);
    start_x = (num_Tooth * med) + (separation_x * num_Tooth) + separation_x;

    ctx.fillStyle = color_line;
    ctx.beginPath();
    ctx.moveTo(start_x, start_y);
    ctx.lineTo(start_x + 40, start_y);
    ctx.lineTo(start_x + 40, start_y + 40);
    ctx.lineTo(start_x, start_y + 40);
    ctx.closePath();
    ctx.strokeStyle = color_line;
    ctx.stroke();
}

// Full Tooth shading function
function Mark_Extraction(contexto, num_Tooth, color_pas) {
    console.log(" Tooth: " + num_Tooth + " color: " + color_pas)
    var ctx = contexto;
    // Defining drawing points
    med = medida;
    num_Tooth = num_Tooth - 1;
    color_line = color_pas;
    if (num_Tooth < 16) {
        start_y = 20;
    } else {
        num_Tooth = num_Tooth - 16;
        start_y = med + 100;
    }
    //alert(num_Tooth);
    start_x = (num_Tooth * med) + (separation_x * num_Tooth) + separation_x;

    ctx.fillStyle = color_line;
    ctx.beginPath();
    ctx.lineWidth = 3;
    ctx.moveTo(start_x, start_y);
    ctx.lineTo(start_x + 40, start_y + 40);
    ctx.moveTo(start_x + 40, start_y);
    ctx.lineTo(start_x, start_y + 40);
    ctx.stroke();
    ctx.lineWidth = 1;
}

// Function to mark bridge
function Delete_Tooth(contexto, num_Tooth) {
    ctx = contexto;
    // Definiendo puntos de dibujo
    med = medida;
    num_Tooth = num_Tooth - 1;
    if (num_Tooth < 16) {
        start_y = 20;
    } else {
        num_Tooth = num_Tooth - 16;
        start_y = med + 100;
    }
    //alert(num_Tooth);
    start_x = (num_Tooth * med) + (separation_x * num_Tooth) + separation_x;
    ctx.clearRect(start_x, start_y, med, med);
}

// Starting initials
var canvas = document.getElementById('myCanvas');
var context = canvas.getContext('2d');
//
var layer2 = document.getElementById("myCanvas2");
var ctx2 = layer2.getContext("2d");
//
var layer3 = document.getElementById("myCanvas3");
var ctx3 = layer3.getContext("2d");
//
var layer4 = document.getElementById("myCanvas4");
var ctx4 = layer4.getContext("2d");
//
var color_line = 'black';
var medida = 40;
var separation_x = 10;
var separation_y = 10;
var start_x = 0;
var start_y = 20;
// Tooths for the bridge
var Tooth1 = 0;
var Tooth2 = 0;
// 1 - 16 Tooths
for (x = 0; x < 16; x++) {
    start_x = (x * medida) + (separation_x * x) + separation_x;
    draw_outline(context, start_x, start_y, medida, separation_x, 10);
    /* Numero de Tooth */
    context.font = '10pt Calibri';
    context.textAlign = 'center';
    context.fillStyle = 'blue';
    context.fillText(x + 1, start_x + (medida / 2), (start_y / 2) + 5);
}
// 17 - 32 Tooths
start_x = 0;
start_y = medida + 100;
for (x = 0; x < 16; x++) {
    start_x = (x * medida) + (separation_x * x) + separation_x;
    draw_outline(context, start_x, start_y, medida, separation_x, 10);
    /* Numero de Tooth */
    context.font = '10pt Calibri';
    context.textAlign = 'center';
    context.fillStyle = 'blue';
    context.fillText(x + 17, start_x + (medida / 2), (start_y - 10) + 5);
}

window.onload = function () {
    localStorage.clear();
    click();
}

function click() {
    // Add an addEventListener to the canvas to recognize the click
    layer4.addEventListener("click",
        // Once clicked, the following function will be activated
        getPosition
        , false);
    layer4.addEventListener("mousemove", Mark, false);
}

//canvas.addEventListener("mousedown", getPosition, false);

function getPosition(event) {
    var x = event.x;
    var y = event.y;
    //alert(y);
    //alert(x);
    var canvas = document.getElementById("myCanvas");
    var div_can = document.getElementById("canvasesdiv");
    x -= div_can.offsetLeft;
    y -= div_can.offsetTop;
    //alert(div_can.offsetTop);
    var div = 0;
    var color = '';
    var action = '';
    selection = $("input[name='action']:checked").val();
    if (selection == 'Fracture') {
        color = 'red';
        action = 'section';
    } else if (selection == 'Restoration') {
        color = 'blue';
        action = 'section';
    } else if (selection == 'Extraction') {
        color = 'black';
        action = 'Mark';
    } else if (selection == 'Delete') {
        action = 'Delete';
    }
    //alert(y);
    Tooth = 0;
    section = 0;
    if (y >= 20 && y <= 60) {
        //alert(x);
        if (x >= 10 && x <= 50) {
            Tooth = 1;
        } else if (x >= 60 && x <= 800) {
            div = parseInt(x / 50, 10);
            ini = (div * 40) + (10 * div) + 10;
            fin = ini + 40;
            if (x >= ini && x <= fin) {
                Tooth = div + 1;
            }
        }
    } else if (y >= 140 && y <= 180) {
        if (x >= 10 && x <= 50) {
            Tooth = 17;
        } else if (x >= 60 && x <= 800) {
            div = parseInt(x / 50, 10);
            ini = (div * 40) + (10 * div) + 10;
            fin = ini + 40;
            if (x >= ini && x <= fin) {
                Tooth = div + 17;
            }
        }
    }
    if (Tooth) {
        //alert(Tooth);
        if (action == 'section') {
            x = x - ((div * 40) + (10 * div) + 10);
            y = y - 20;
            if (Tooth > 16) {
                y = y - 120;
            }
            //alert(y);
            if (y > 0 && y < 10 && x > y && y < 40 - x) {
                section = 1;
            } else if (x > 30 && x < 40 && y < x && 40 - x < y) {
                section = 2;
            } else if (y > 30 && y < 40 && x < y && x > 40 - y) {
                section = 3;
            } else if (x > 0 && x < 10 && y > x && x < 40 - y) {
                section = 4;
            } else if (x > 10 && x < 30 && y > 10 && y < 30) {
                section = 5;
            }
        } else if (action == 'Mark') {
            cod = Tooth + '-0-' + '3';
            if (cod && !localStorage.getItem(cod)) {
                new_array = [Tooth, 0, 3, Date.now(), 0];
                save = new_array.toLocaleString();
                localStorage.setItem(cod, save);
                // Mark_Extraction(ctx2, Tooth, 'black')
                // //not extraction has not section jst the whole tooth so value is zero
                // insert_selection("Extraction", Tooth, 0, 'black');
            } else {
                // alert("it was already marked");
            }
        } else if (action == 'Delete') {
            Delete_Tooth(ctx2, Tooth);
            //cargar el last pintado
            section_chk = $("input[name='section']:checked").val();
            if (section_chk == 'section') {
                x = x - ((div * 40) + (10 * div) + 10);
                y = y - 20;
                if (Tooth > 16) {
                    y = y - 120;
                }
                section_b = locate_section(x, y);
                if (section_b) {
                    last = '';
                    key_cod = '';
                    for (var i = 0; i < localStorage.length; i++) {
                        var key_name = localStorage.key(i);
                        item = localStorage.getItem(key_name);
                        item = item.split(',');
                        Tooth_comp = parseInt(item[0], 10);
                        section_comp = parseInt(item[1], 10);
                        action_comp = parseInt(item[2], 10);
                        if (Tooth_comp == Tooth && section_b == section_comp && (action_comp == 1 || action_comp == 2)) {
                            if (last == '') {
                                last = item;
                                key_cod = key_name;
                            } else {
                                date_ult = parseInt(item[3], 10);
                                if (last[3] < date_ult) {
                                    last = item;
                                    key_cod = key_name;
                                }
                            }
                        }
                    }
                    if (key_cod != '') {
                        //console.log(key_cod);
                        localStorage.removeItem(key_cod);
                    }
                }

            }

            //un mark the already selected section
            pint_data();
        }
    }
    // alert(Tooth);
    if (section && color != '') {
        //alert(color);

        // alert(section);
        //[numero_Tooth, section, action, fecha, Tooth2]
        if (color == 'red') {
            cod = Tooth + '-' + section + '-' + '1';
            action_g = 1;
        } else if (color == 'blue') {
            cod = Tooth + '-' + section + '-' + '2';
            action_g = 2;
        }
        ;
        if (cod && !localStorage.getItem(cod)) {
            new_array = [Tooth, section, action_g, Date.now(), 0];
            save = new_array.toLocaleString();
            localStorage.setItem(cod, save);
            // console.log(" Tooth:"+Tooth +" section:"+section+" color:"+ color);
            // draw_section(ctx2, Tooth, section, color);
            // //insert selection
            // if (color == "red") {
            //     insert_selection("Fracture", Tooth, section, color);
            // } else {
            //     insert_selection("Restoration", Tooth, section, color);
            // }

        } else {
            // alert("it was already marked");
        }

    }
    if ('Delete' == $("input[name='action']:checked").val()) {
        //alert("x-> "+x+" y-> "+y);
        //ctx4.clearRect(0, 0, 810, 300);

        if (x >= 30 && x <= 780 && ((y > 78 && y < 82) || (y > 198 && y < 202))) {
            //alert(x);
            div = parseInt(x / 50, 10);
            //alert(div);
            last = '';
            key_cod = '';
            for (var i = 0; i < localStorage.length; i++) {
                var key_name = localStorage.key(i);
                item = localStorage.getItem(key_name);
                item = item.split(',');
                Tooth1_comp = parseInt(item[0], 10);
                Tooth2_comp = parseInt(item[4], 10);
                action_comp = parseInt(item[2], 10);
                if (action_comp == 4) {
                    if (Tooth1_comp > 16) {
                        Tooth1_comp = Tooth1_comp - 17;
                        Tooth2_comp = Tooth2_comp - 17;
                    } else {
                        Tooth1_comp = Tooth1_comp - 1;
                        Tooth2_comp = Tooth2_comp - 1;
                    }
                    start_x = (Tooth1_comp * 40) + (10 * Tooth1_comp) + 10 + 20;
                    fin_X = (Tooth2_comp * 40) + (10 * Tooth2_comp) + 10 + 20;
                    if (x >= start_x && x <= fin_x) {
                        if (last == '') {
                            last = item;
                            key_cod = key_name;
                        } else {
                            date_ult = parseInt(item[3], 10);
                            if (last[3] < date_ult) {
                                last = item;
                                key_cod = key_name;
                            }
                        }
                    }

                }
            }
            if (key_cod != '') {
                console.log(key_cod);
                if (parseInt(last[0], 10) < 16) {
                    section_p = 1;
                    ctx4.clearRect(0, 0, 810, 130);
                } else {
                    ctx4.clearRect(0, 130, 810, 150);
                    section_p = 2;
                }
                localStorage.removeItem(key_cod);
                bridges_paint(section_p);
            }
        }

    }

}


function Mark(event) {
    var x = event.x;
    var y = event.y;
    var canvas2 = document.getElementById("myCanvas2");
    var div_can = document.getElementById("canvasesdiv");
    x -= div_can.offsetLeft;
    y -= div_can.offsetTop;
    //alert(x);
    Tooth = 0;
    section = 0;
    var div = 0;

    if (y >= 20 && y <= 60) {
        //alert(x);
        if (x >= 10 && x <= 50) {
            Tooth = 1;
            //alert("1");
        } else if (x >= 60 && x <= 800) {
            div = parseInt(x / 50, 10);
            ini = (div * 40) + (10 * div) + 10;
            fin = ini + 40;
            if (x >= ini && x <= fin) {
                Tooth = div + 1;
            }
        }
    } else if (y >= 140 && y <= 180) {
        if (x >= 10 && x <= 50) {
            Tooth = 17;
        } else if (x >= 60 && x <= 800) {
            div = parseInt(x / 50, 10);
            ini = (div * 40) + (10 * div) + 10;
            fin = ini + 40;
            if (x >= ini && x <= fin) {
                Tooth = div + 17;
            }
        }
    }
    //alert(Tooth);
    if (Tooth) {
        action = $("input[name='action']:checked").val();
        var selection = '';
        if (action == 'Fracture') {
            selection = 'section';
        } else if (action == 'Restoration') {
            selection = 'section';
        } else if (action == 'Extraction') {
            selection = 'Tooth';
        } else if (action == 'Delete') {
            section_chk = $("input[name='section']:checked").val();
            if (section_chk == 'Tooth') {
                selection = 'Tooth';
            } else {
                selection = 'section';
            }
        }
        if (selection == 'section') {
            x = x - ((div * 40) + (10 * div) + 10);
            y = y - 20;
            if (Tooth > 16) {
                y = y - 120;
            }
            //alert(y);
            /*if (y>=x && y<=39){}*/
            // locater la section clickeada
            if (y > 0 && y < 10 && x > y && y < 40 - x) {
                section = 1;
            } else if (x > 30 && x < 40 && y < x && 40 - x < y) {
                section = 2;
            } else if (y > 30 && y < 40 && x < y && x > 40 - y) {
                section = 3;
            } else if (x > 0 && x < 10 && y > x && x < 40 - y) {
                section = 4;
            } else if (x > 10 && x < 30 && y > 10 && y < 30) {
                section = 5;
            }
            //Comprobacion de si eta en una section
            if (section) {
                //alert(section);
                color = 'yellow';
                ctx3.clearRect(0, 0, 810, 300);
                Mark_section(ctx3, Tooth, section, color);
                //alert(section);
            } else {
                //ctx2.fillStyle = "white";
                //ctx2.fillRect(0, 0, 810, 300);
                ctx3.clearRect(0, 0, 810, 300);
            }
        } else if (selection == 'Tooth') {
            ctx3.clearRect(0, 0, 810, 300);
            Mark_Tooth(ctx3, Tooth, 'yellow');
        }
    } else {
        ctx3.clearRect(0, 0, 810, 300);
    }

    //draw_outline(canvas, start_x, start_y, med, separation_x, separation_y)
}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


function pint_data() {
    array_local = [];
    for (var i = 0; i < localStorage.length; i++) {
        var key_name = localStorage.key(i);
        array_local[i] = localStorage.getItem(key_name).split(',');
    }
    //console.log(array_local);
    array_local.sort(function (a, b) {
        return a[3] > b[3]; // orden ascendente por las fechas;
    });
    // console.log(array_local);
    for (var i = 0; i < array_local.length; i++) {
        item = array_local[i];
        if (parseInt(item[0], 10) == Tooth) {
            acc = parseInt(item[2], 10);
            // console.log(acc);
            if (acc == 1) {
                color = 'red';
                draw_section(ctx2, item[0], item[1], color);
            } else if (acc == 2) {
                color = 'blue';
                draw_section(ctx2, item[0], item[1], color);
            } else if (acc == 3) {
                Mark_Extraction(ctx2, item[0], 'black');
            }
        }
    }
}

function locate_section(X, Y) {
    y = Y;
    x = X;
    restore_section = 0;
    if (y > 0 && y < 10 && x > y && y < 40 - x) {
        restore_section = 1;
    } else if (x > 30 && x < 40 && y < x && 40 - x < y) {
        restore_section = 2;
    } else if (y > 30 && y < 40 && x < y && x > 40 - y) {
        restore_section = 3;
    } else if (x > 0 && x < 10 && y > x && x < 40 - y) {
        restore_section = 4;
    } else if (x > 10 && x < 30 && y > 10 && y < 30) {
        restore_section = 5;
    }
    return restore_section;
}
