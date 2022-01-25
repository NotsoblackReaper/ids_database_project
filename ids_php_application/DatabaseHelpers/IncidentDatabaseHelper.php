<?php

class IncidentDatabaseHelper
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
                IncidentDatabaseHelper::username,
                IncidentDatabaseHelper::password,
                IncidentDatabaseHelper::con_string
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

    public function countAllIncidents($guid, $type,$inc_cost)
    {
        $sql = "select count(*) from incidents 
            WHERE incidents.guid LIKE '%{$guid}%'";
        if($type!='')
            $sql.="AND upper(incidents.type) LIKE upper('%{$type}%')";
        if($inc_cost!='')
            $sql.="AND incidents.incident_cost LIKE '%{$inc_cost}%'";

        $statement = oci_parse($this->conn, $sql);

        oci_execute($statement);

        $res=oci_fetch_row($statement);

        oci_free_statement($statement);

        return $res;
    }


    public function countAllAffectedComponents($guid)
    {
        $sql = "select count(*) from affects 
                join components on components.serial_nr = affects.serial_nr
                join types on components.type_id=types.guid
                where affects.guid={$guid}";

        $statement = oci_parse($this->conn, $sql);

        oci_execute($statement);

        $res=oci_fetch_row($statement);

        oci_free_statement($statement);

        return $res;
    }

    public function selectAllAffectedComponents($guid,$limit)
    {
        $sql = "select components.serial_nr,components.parent_nr, types.kv2,components.shipping_date,components.installation_date,types.type_cost from affects 
                join components on components.serial_nr = affects.serial_nr
                join types on components.type_id=types.guid
                where affects.guid={$guid}";
        if($limit!='')
            $sql.="AND ROWNUM<={$limit}";

        $statement = oci_parse($this->conn, $sql);

        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        oci_free_statement($statement);

        return $res;
    }

    public function selectAllIncidents($guid, $type,$inc_cost,$limit)
    {
        $sql = "select * from incidents 
            WHERE incidents.guid LIKE '%{$guid}%'";
        if($type!='')
            $sql.="AND upper(incidents.type) LIKE upper('%{$type}%')";
        if($inc_cost!='')
            $sql.="AND incidents.incident_cost LIKE '%{$inc_cost}%'";
        if($limit!='')
        $sql.="AND ROWNUM<={$limit}";

        $statement = oci_parse($this->conn, $sql);

        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        oci_free_statement($statement);

        return $res;
    }

    public function selectAllNotAffected($guid)
    {
        $sql = "select * from components where serial_nr not in (select serial_nr from affects where guid={$guid})";

        $statement = oci_parse($this->conn, $sql);

        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        oci_free_statement($statement);

        return $res;
    }

    public function getCosts($guid)
    {
        $errorcode = 0;
        $sql = 'BEGIN P_INCIDENT_COSTS(:incident_guid, :result); END;';
        $statement = oci_parse($this->conn, $sql);

        //  Bind the parameters
        oci_bind_by_name($statement, ':incident_guid', $guid);
        oci_bind_by_name($statement, ':result', $res,20,3);
        oci_execute($statement);

        //@oci_commit($statement); //not necessary

        //Clean Up
        oci_free_statement($statement);

        return $res;
    }
}