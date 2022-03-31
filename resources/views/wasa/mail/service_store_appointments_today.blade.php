{{ __("wasateam::messages.本日收到Ｎ筆新預約，請前往後台查看",[
'count'=>count($formated_appointments),
],$lang) }}
<a
  target="_blank"
  href="{{$link}}"
>{{$link}}</a>


<table>
  <tr>
    <th>日期</th>
    <th>起始時間</th>
    <th>結束時間</th>
    <th>會員</th>
    <th>據點</th>
  </tr>
  @foreach ($formated_appointments as $formated_appointment)
  <tr>
    <td>{{$formated_appointment['date']}}</td>
    <td>{{$formated_appointment['start_time']}}</td>
    <td>{{$formated_appointment['end_time']}}</td>
    <td>{{$formated_appointment['user_name']}}</td>
    <td>{{$formated_appointment['service_store_name']}}</td>
  </tr>
  @endforeach
</table>