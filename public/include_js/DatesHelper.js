function todaysDate() {
    let day = new Date();
    return formatDate(new Date(day.setDate(day.getDate())));
}


function YesterdaysDate() {
    let day = new Date();
    return formatDate(new Date(day.setDate(day.getDate() - 1)));
}

function thisWeek() {
    let dt = new Date(); // current date of week
    let currentWeekDay = dt.getDay();
    let lessDays = currentWeekDay == 0 ? 7 : currentWeekDay - 1;
    let wkStart = new Date(new Date(dt).setDate(dt.getDate() - lessDays));
    return formatDate(wkStart); //this weeks start date
    // var wkEnd = new Date(new Date(wkStart).setDate(wkStart.getDate() + 6)); // this weeks end date
    // console.log(currentWeekDay);
}

function lastWeek() {
    let d = new Date();
    let lastWeekEndDay = new Date(new Date(d).setTime(d.getTime() - (d.getDay() ? d.getDay() : 7) * 24 * 60 * 60 * 1000));
    let lastWeekStartDay = new Date(new Date(d).setTime(d.getTime() - 7 * 24 * 60 * 60 * 1000));
    //set time inputs
    $('.start_date').val(formatDate(lastWeekStartDay));
    $('.end_date').val(formatDate(lastWeekEndDay));
}

function thisMonth() {
    let date = new Date();
    let startDate = new Date(date.getFullYear(), date.getMonth(), 1);// start date of this month
    return startDate;
    // var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
}


function lastMonth() {
    let date = new Date();
    let firstDay = new Date(date.getFullYear(), date.getMonth() - 1, 1);
    let lastDay = new Date(date.getFullYear(), date.getMonth(), 0);
    //set input values
    $('.start_date').val(formatDate(firstDay));
    $('.end_date').val(formatDate(lastDay));
}


function formatDate(date) { // format the date (yyyy-mm-dd)
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2)
        month = '0' + month;
    if (day.length < 2)
        day = '0' + day;

    return [year, month, day].join('-');
}

function currentTimeSelect() {
    let date = new Date();
    let hours = date.getHours();
    // let hours = (date.getHours() > 12) ? date.getHours() : date.getHours() - 12;
    let minutes = date.getMinutes();
    // let seconds = date.getSeconds();
    let ampm = hours >= 12 ? "pm" : "am";
    return hours + ":" + minutes + " " + ampm;
}

