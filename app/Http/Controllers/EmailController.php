<?php

namespace App\Http\Controllers;

use mysqli;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function getEmail(Request $request)
    {
        $configs = include('.config.php');
        echo($configs->host);
        $servername = $configs->host;
        $username = $configs->username;
        $password = $configs->password;
        $dbname = $configs->database;
        $result = null;

        $status = $request->input('option');
        $subject = $request->input('subject');
        $content = $request->input('content');

        $details = [

            'title' => $subject,
            'body' => $content

        ];

        $result = $this->queryEmails($servername, $username, $password, $dbname, $status);
        $this->sendEmail($result, $details);

    }
    private function queryEmails($servername, $username, $password, $dbname, $status)
    {
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT email.address FROM proposal
                    JOIN review ON proposal.id = review.proposal_id
                    JOIN collaborator ON proposal.project_id = collaborator.project_id
                    JOIN email ON collaborator.email_id = email.id";
        if ($status == 'Exp') {
            $sql = $sql . " WHERE DATEDIFF(CURDATE(), review.created_at) > proposal.duration * 30 AND review.response = 'accepted';";

        } else {

            $sql = $sql . " WHERE DATEDIFF(CURDATE(), review.created_at) < proposal.duration * 30 AND review.response = 'accepted';";
        }
        $result = $conn->query($sql);
        $conn->close();
        return $result;
    }
    private function sendEmail($result, $details)
    {
        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "email: " . $row["address"] .  "<br>";
                \Mail::to($row['address'])->send(new \App\Mail\MyTestMail($details));
            }
        } else {
            echo "0 results";
        }
    }
}
