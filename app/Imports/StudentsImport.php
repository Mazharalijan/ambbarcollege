<?php

namespace App\Imports;

use App\Models\Book;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToCollection, WithHeadingRow
{
    public $rows;

    public function __construct()
    {
        $this->rows = new Collection();
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $this->rows->push($row);
        }
    }

    public function getRows()
    {

        foreach ($this->rows as $row) {

            $existStudents = User::where('role_id', 3)->where('email', $row['email'])->first();
            if (! $existStudents) {
                $nameParts = explode(' ', $row['name'], 2);

                $firstName = $nameParts[0];
                $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

                $row['from'] = Carbon::createFromFormat('M. d, Y', $row['from']);

                $specificDate = new DateTime($row['from']);
                $timeToAdd = '09:10:00';

                [$hours, $minutes, $seconds] = explode(':', $timeToAdd);

                $specificDate->setTime($hours, $minutes, $seconds);

                $row['from'] = $specificDate->format('Y-m-d h:m:s');
                try {
                    $user = new User();

                    $user->username = $row['username'];
                    $user->first_name = $firstName;
                    $user->last_name = $lastName;
                    $user->phone_number = $row['phone'];
                    $user->email = $row['email'];
                    $user->password = strtolower($firstName).'1234*';
                    $user->status = 'active';
                    $user->role_id = 3;
                    $user->created_at = $row['from'];

                    $user->save();

                    $book = Book::first();
                    $user->books()->sync([$book->id], false);
                } catch (\Exception $error) {
                    return response('Some data error', 500);
                }

            }

        }
    }
}
