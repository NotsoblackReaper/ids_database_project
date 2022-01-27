<?php

class EmployeesDatabaseHelper
{
    // Since the connection details are constant, define them as const
    // We can refer to constants like e.g. DatabaseHelper::username
    const username = 'a11739260'; // use a + your matriculation number
    const password = 'dbs21'; // use your oracle db password
    const con_string = '//oracle-lab.cs.univie.ac.at:1521/lab';

    // Since we need only one connection object, it can be stored in a member variable.
    // $conn is set in the constructor.
    protected $conn;

    // Create connection in the constructor
    public function __construct()
    {
        try {
            // Create connection with the command oci_connect(String(username), String(password), String(connection_string))
            $this->conn = oci_connect(
                EmployeesDatabaseHelper::username,
                EmployeesDatabaseHelper::password,
                EmployeesDatabaseHelper::con_string
            );

            //check if the connection object is != null
            if (!$this->conn) {
                // die(String(message)): stop PHP script and output message:
                die("DB error: Connection can't be established!");
            }

        } catch (Exception $e) {
            die("DB error: {$e->getMessage()}");
        }
    }

    public function __destruct()
    {
        // clean up
        oci_close($this->conn);
    }

    public function countAllEmployees($employee_id,  $firstname,$surname, $email)
    {
        $sql = "SELECT count(*) FROM employees
            WHERE employeeid LIKE '%{$employee_id}%'
              AND upper(firstname) LIKE upper('%{$firstname}%')
              AND upper(surname) LIKE upper('%{$surname}%')
              AND upper(email) LIKE upper('%{$email}%')";

        $statement = oci_parse($this->conn, $sql);

        oci_execute($statement);

        $res=oci_fetch_row($statement);

        oci_free_statement($statement);

        return $res;
    }

    public function selectAllEmployees($employee_id,  $firstname,$surname, $email,$limit)
    {
        $sql = "SELECT * FROM employees
            WHERE employeeid LIKE '%{$employee_id}%'
              AND upper(firstname) LIKE upper('%{$firstname}%')
              AND upper(surname) LIKE upper('%{$surname}%')
              AND upper(email) LIKE upper('%{$email}%')";
        if($limit!='')
            $sql.="AND ROWNUM<=50";

        $statement = oci_parse($this->conn, $sql);

        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        oci_free_statement($statement);

        return $res;
    }

    public function selectOneEmploye($employee_id)
    {
        $sql = "SELECT * FROM employees
            WHERE employeeid LIKE '{$employee_id}'";

        $statement = oci_parse($this->conn, $sql);

        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        oci_free_statement($statement);

        return $res;
    }

    public function updateEmployee($empid,$firstname,$surname, $email){
        var_dump($empid);
        var_dump($firstname);
        $sql = "update employees set firstname='{$firstname}',surname='{$surname}',email='{$email}' where employeeid like '{$empid}'";

        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement);
        oci_free_statement($statement);
        return $success;
    }

    public function insertIntoEmployees($firstname,$surname, $email)
    {
        $sql = "INSERT INTO EMPLOYEES (FIRSTNAME,SURNAME, EMAIL) VALUES ('{$firstname}','{$surname}', '{$email}')";

        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement);
        oci_free_statement($statement);
        return $success;
    }

    public function deleteEmployee($employee_id)
    {
        $errorcode = 0;
        $sql = 'BEGIN p_delete_employee(:employeeid, :errorcode); END;';
        $statement = oci_parse($this->conn, $sql);

        //  Bind the parameters
        oci_bind_by_name($statement, ':employeeid', $employee_id);
        oci_bind_by_name($statement, ':errorcode', $errorcode);
        oci_execute($statement);

        //@oci_commit($statement); //not necessary

        //Clean Up
        oci_free_statement($statement);

        //$errorcode == 1 => success
        //$errorcode != 1 => Oracle SQL related errorcode;
        return $errorcode;
    }
}