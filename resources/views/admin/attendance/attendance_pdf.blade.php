<!DOCTYPE html>
<html>
<head>
    <title>Student Attendance</title>
    <style>
        /* Add some styles for the PDF */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid black;
        }
    </style>
</head>
<body>
    <center>
        <h4>
            <u>
                Student Attendance
            </u>

        </h4>
        <div style="border: 1px solid black; width: 45%; padding-left:20px; text-align:left;">
            <p><strong style="padding-right: 10px;">Student Name:</strong> {{ $studentInfo['student_name'] }}</p>
            <p><strong style="padding-right: 10px;">Student Email:</strong> {{ $studentInfo['student_email'] }}</p>
            <p><strong style="padding-right: 10px;">Student Phone:</strong> {{ $studentInfo['student_phone'] }}</p>
        </div>

    </center>
    <h4>Attendance Records</h4>
    <table>
        <thead>
            <tr>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Hours</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendanceRecords as $record)
                <tr>
                    <td>{{(!empty($record->sign_in))?date('D d M, Y h:i A ',strtotime($record->sign_in)) : ""}}</td>
                    <td>{{(!empty($record->sign_out))?date('D d M, Y h:i A ',strtotime($record->sign_out)) : ""}}</td>
                    <td>
                        @php
                            $total = "0";
                                if (!empty($record->sign_out) && !empty($record->sign_out)){
                                     $totalHours = calculateHoursBetweenDates($record->sign_in,$record->sign_out);
                                    if ($totalHours < 1){
                                        $totalMinutes  = calculateMinutesBetweenDates($record->sign_in,$record->sign_out);
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
        </tbody>
    </table>




</body>
</html>
