function runClock () {

  var today = new Date();
  var h = today.getHours();
  var m = today.getMinutes();
  var s = today.getSeconds();
  m = checkTime(m);
  s = checkTime(s);
//dzienTygodnia, dzien miesiaca rok

  var months = ["Stycznia", "Lutego", "Marca", "Kwietnia", "Maja", "Czerwca", "Lipca", "Sierpnia", "Września", "Października", "Listopada", "Grudnia"];
  var weekDays = ["Poniedziałek", "Wtorek", "Środa", "Czwartek", "Piątek", "Sobota", "Niedziela"];

  var currentMonth = months[today.getMonth()];
  var currentMontDay = today.getDate();
  var currentWeekDay = weekDays[today.getDay()];
  var currentYear = today.getFullYear();

  document.getElementById('clock').innerHTML =
  '<div id "dayInfo">' + currentWeekDay + ', ' +  currentMontDay + ' ' + currentMonth + ' ' + currentYear + '<br/></div>' +
  '<div id = "clockHours">' + h + '</div><div id = "clockSeparator">:</div><div id = "clockMinutes">' + m + '</div><div id = "clockSeconds">' + s + '</div>';

  var t = setTimeout(runClock, 500);
}

function checkTime(i) {
  // add zero in front of numbers < 10
  if (i < 10) {
    i = "0" + i
  }
  return i;
}
