<!DOCTYPE html>
<html>
<head>
<style>
#customers {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #04AA6D;
  color: white;
}
</style>
</head>
<body>

<center>
<h1>Ambber Salma College</h1>
</center>
<div style="width: 45%; padding-bottom: 2%;">
  <table id="customers">
    <tr>
      <th>Student Name</th>
      <td>{{ $user->first_name . ' ' . $user->last_name }}</td>
    </tr>
    <tr>
    <th>Student Email</th>
    <td>{{ $user->email }}</td>
  </tr>
  <tr>
    <th>Student Contact</th>
    <td>{{ $user->phone_number }}</td>
  </tr>
  </table>
</div>
<div style="padding-bottom: 2%;">
  <table id="customers" style="padding-bottom: 4%;">
    <tr>
      <th>Months</th>
        @isset($monthlywisehoures)
            @foreach ($monthlywisehoures as $month=>$days)
            <td>{{ $month }}</td>
            @endforeach
        @endisset
    </tr>
    <tr>
      <th>Days</th>
      @isset($monthlywisehoures)
            @foreach ($monthlywisehoures as $month=>$days)
            <td>{{ $days }}</td>
            @endforeach
        @endisset

    </tr>
  </table>
</div>
<table id="customers">
  <tr>
    <th>Check In</th>
    <th>Check Out</th>
    <th>Total</th>
  </tr>
 @isset($attendance)
    @foreach ($attendance as $row)
    <tr>
        <td>{{(!empty($row->sign_in))?date('D d M, Y h:i A ',strtotime($row->sign_in)) : ""}}</td>
        <td>{{(!empty($row->sign_out))?date('D d M, Y h:i A ',strtotime($row->sign_out)) : ""}}</td>
        <td>
            @php
            $total = "0";
                if (!empty($row->sign_out) && !empty($row->sign_out)){
                     $totalHours = calculateHoursBetweenDates($row->sign_in,$row->sign_out);
                    if ($totalHours < 1){
                        $totalMinutes  = calculateMinutesBetweenDates($row->sign_in,$row->sign_out);
                        $total = ($totalMinutes == 1) ? $totalMinutes." Minute":$totalMinutes." Minutes";
                    } else {
                        $total = ($totalHours == 1) ? $totalHours." Hour":$totalHours." Hours";
                    }
                }
        @endphp
        {{$total}}
        </td>
      </tr>
    @endforeach
 @endisset

</table>

</body>
</html>


