<?php
if (!function_exists('isPrime')) {
    function isPrime($number)
    {
        if ($number <= 1)
            return false;
        $i = $number - 1;
        while ($i > 1) {
            if ($number % $i == 0)
                return false;
            $i--;
        }
        return true;
    }
}

if (!function_exists('calculateGPA')) {
    function calculateGPA($grades)
    {
        $totalPoints = 0;
        $totalCreditHours = 0;
        foreach ($grades as $grade) {
            $points = match ($grade->grade) {
                'A' => 4,
                'B' => 3,
                'C' => 2,
                'D' => 1,
                'F' => 0,
                default => 0
            };
            $totalPoints += $points * $grade->credit_hours;
            $totalCreditHours += $grade->credit_hours;
        }
        return $totalCreditHours ? round($totalPoints / $totalCreditHours, 2) : 0;
    }
}

if (!function_exists('transcript')) {
    function transcript()
    {
        $transcript = [
            ['course' => 'Web Security', 'grade' => 'A'],
            ['course' => 'Network Fundamentals', 'grade' => 'B+'],
            ['course' => 'Cyber Defense', 'grade' => 'A-'],
            ['course' => 'Database Systems', 'grade' => 'B'],
        ];

        return view('students.transcript', compact('transcript'));
    }
}

if (!function_exists('emailFromLoginCertificate')) {
    function emailFromLoginCertificate()
    {
        if (!isset($_SERVER['SSL_CLIENT_CERT']))
            return null;

        $clientCertPEM = $_SERVER['SSL_CLIENT_CERT'];
        $certResource = openssl_x509_read($clientCertPEM);
        if (!$certResource)
            return null;
        $subject = openssl_x509_parse($certResource, false);
        if (!isset($subject['subject']['emailAddress']))
            return null;
        return $subject['subject']['emailAddress'];
    }
}