$(function(){
  $('.full_datetime').datetimepicker({
        //language:  'fr',
  format: 'dd-mm-yy hh:ii',
        weekStart: 7,
        todayBtn:  1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                forceParse: 0,
       // showMeridian: 1
    });//end of dateTimePicker
$('.dateBirth').datetimepicker({
  format: 'yyyy-mm-dd hh:ii:ss',
  weekStart: 7,
  todayBtn: 1,
  autoclose: 1,
  todayHighlight: 1,
  startView: 4,
  forceParse: 0
});
});