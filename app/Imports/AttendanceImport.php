<?php

namespace App\Imports;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateTime;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AttendanceImport implements ToModel, WithHeadingRow
{
    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Fetch the student by email
        $student = User::where('email', $row['email'])->first();

        if ($student) {
            // Create a DateTime object from the 'from' date string in the row
            $fromDate = new DateTime($row['from']);

            // Clone the fromDate and add 3 months to create the toDate
            $toDate = (clone $fromDate)->modify('+3 months');

            // Create a CarbonPeriod from fromDate to toDate
            $period = CarbonPeriod::create($fromDate, $toDate);

            // Define the weekdays on which attendance should be recorded
            $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'];

            // Initialize an array to hold the attendance data
            $attendanceData = [];

            // Iterate through each date in the period
            foreach ($period as $date) {
                // Format the date to get the day abbreviation (e.g., 'Mon')
                $dayOfWeek = $date->format('D');

                // Check if the day is one of the specified weekdays
                if (in_array($dayOfWeek, $days)) {
                    // Generate random sign-in and sign-out times
                    $randomHour = mt_rand(10, 14);
                    $randomMinute = mt_rand(0, 59);

                    // Create the sign-in datetime
                    $signInDate = Carbon::instance($date)->hour($randomHour)->minute($randomMinute);

                    // Create the sign-out datetime by adding a random number of hours and subtracting a random number of minutes
                    $signOutDate = (clone $signInDate)->addHours(mt_rand(3, 4))->subMinutes(mt_rand(5, 10));

                    // Add the attendance record to the array
                    $attendanceData[] = [
                        'sign_in' => $signInDate->format('Y-m-d H:i:s'),
                        'sign_out' => $signOutDate->format('Y-m-d H:i:s'),
                        'user_id' => $student->id,
                        'created_at' => Carbon::now(),
                    ];
                }
            }

            // Insert the attendance records into the database
            Attendance::insert($attendanceData);
        }
    }
}
