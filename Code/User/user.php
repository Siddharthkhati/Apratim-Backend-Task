<?php

class User
{

    private $conn;
    private $conn_2;
    private $table_name = "users";
    private $table_name_2 = "sessiontoken";
    public $id;
    public $username;
    public $college;
    public $email;
    public $password;
    public $created;
    public $token;
    public $user;
    public function __construct($db)
    {
        $this->conn = $db;
        $this->conn_2 = $db;
    }

    function signup()
    {
        if ($this->isAlreadyExist()) {
            return false;
        }

        $query = "INSERT INTO 
                    " . $this->table_name . "
                SET
                    username=:username,
                    college=:college,
                    email=:email,
                    password=:password,
                    created=:created";

        $stmt = $this->conn->prepare($query);

        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->college = htmlspecialchars(strip_tags($this->college));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->created = htmlspecialchars(strip_tags($this->created));

        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":college", $this->college);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":created", $this->created);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function login()
    {
        $query = "SELECT
                    `id`, `username`, `college`, `email`, `password`, `created`
                FROM
                    " . $this->table_name . "
                WHERE 
                    username='" . $this->username . "'
                        AND password='" . $this->password . "'";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $n = 15;
        function getName($n)
        {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $randomString = '';

            for ($i = 0; $i < $n; $i++) {
                $index = rand(0, strlen($characters) - 1);
                $randomString .= $characters[$index];
            }

            return $randomString;
        }

        $this->token = getName($n);
        return $stmt;
    }

    function session_token()
    {
        $query = "INSERT INTO 
                    " . $this->table_name_2 . "
                SET
                    username=:username,
                    token=:token,
                    password=:password";

        $stmt = $this->conn->prepare($query);

        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->token = htmlspecialchars(strip_tags($this->token));
        $this->password = htmlspecialchars(strip_tags($this->password));

        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":token", $this->token);
        $stmt->bindParam(":password", $this->password);

        $stmt->execute();
    }

    function status_active()
    {
        $sql = "UPDATE sessiontoken SET status='active' WHERE username='" . $this->username . "'";
        $sqmt = $this->conn_2->prepare($sql);
        $sqmt->execute();
    }

    function status_active_1()
    {
        $sql = "UPDATE sessiontoken SET status='active' WHERE token='" . $this->token . "'";
        $sqmt = $this->conn_2->prepare($sql);
        $sqmt->execute();
    }


    function isAlreadyExist()
    {
        $query = "SELECT *
            FROM
                " . $this->table_name . "
            WHERE 
                username='" . $this->username . "'";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function session_logout()
    {
        $query = "SELECT
                    `username`, `token`, `password`, `status`
                FROM
                    " . $this->table_name_2 . "
                WHERE 
                     token='" . $this->token . "'";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    function status_inactive()
    {
        $sql = "UPDATE sessiontoken SET status='inactive' WHERE token='" . $this->token . "'";
        $sqmt = $this->conn_2->prepare($sql);
        $sqmt->execute();
    }

    
}

?>