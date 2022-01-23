<?php

class TypesDatabaseHelper
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
                TypesDatabaseHelper::username,
                TypesDatabaseHelper::password,
                TypesDatabaseHelper::con_string
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

    public function selectAllTypes($guid, $spec_id, $kv2, $supplier, $type_cost)
    {
        $sql = "SELECT * FROM types
            WHERE guid LIKE '%{$guid}%'
              AND specification_id LIKE '%{$spec_id}%'
              AND upper(kv2) LIKE upper('%{$kv2}%')
              AND upper(supplier) LIKE upper('%{$supplier}%')
              AND type_cost LIKE '%{$type_cost}%'";

        $statement = oci_parse($this->conn, $sql);

        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        oci_free_statement($statement);

        return $res;
    }

    public function insertIntoEmployees($name, $email)
    {
        $sql = "INSERT INTO EMPLOYEES (NAME, EMAIL) VALUES ('{$name}', '{$email}')";

        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
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