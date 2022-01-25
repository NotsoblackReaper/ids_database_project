<?php

class ComponentsDatabaseHelper
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
                ComponentsDatabaseHelper::username,
                ComponentsDatabaseHelper::password,
                ComponentsDatabaseHelper::con_string
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

    public function countAllComponents($snr, $pnr,$tid,$kv2, $shipping, $installation)
    {
        $sql = "select count(*) 
            from components left join types on components.type_id=types.guid
            WHERE components.serial_nr LIKE '%{$snr}%'";
        if($pnr!='')
            $sql.="AND components.parent_nr LIKE '%{$pnr}%'";
        if($tid!='')
            $sql.="AND components.type_id LIKE '%{$tid}%'";
        if($kv2!='')
            $sql.="AND upper(types.kv2) LIKE upper('%{$kv2}%')";
        if($shipping!='')
            $sql.="AND components.shipping_date = '{$shipping}'";
        if($installation!='')
            $sql.="AND components.installation_date = '{$installation}'";
        $statement = oci_parse($this->conn, $sql);

        oci_execute($statement);

        $res=oci_fetch_row($statement);

        oci_free_statement($statement);

        return $res;
    }

    public function countChildren($pnr)
    {
        $sql = "select count(*)
            from components left join types on components.type_id=types.guid
            WHERE components.parent_nr = '{$pnr}'";
        if($pnr=='')
            $sql = "select count(*)
            from components left join types on components.type_id=types.guid
            WHERE components.parent_nr is null";

        $statement = oci_parse($this->conn, $sql);

        oci_execute($statement);

        $res=oci_fetch_row($statement);

        oci_free_statement($statement);

        return $res;
    }

    public function selectAllComponents($snr, $pnr,$tid,$kv2, $shipping, $installation,$limit)
    {
        $sql = "select components.serial_nr,components.parent_nr,components.type_id,types.kv2,components.shipping_date,components.installation_date 
            from components left join types on components.type_id=types.guid
            WHERE components.serial_nr LIKE '%{$snr}%'";
        if($pnr!='')
            $sql.="AND components.parent_nr LIKE '%{$pnr}'";
        if($tid!='')
            $sql.="AND components.type_id LIKE '%{$tid}%'";
        if($kv2!='')
               $sql.="AND upper(types.kv2) LIKE upper('%{$kv2}%')";

        if($shipping!='')
              $sql.="AND components.shipping_date = '{$shipping}'";

        if($installation!='')
            $sql.="AND components.installation_date = '{$installation}'";
        if($limit!='')
        $sql.="AND ROWNUM<={$limit}";

        $statement = oci_parse($this->conn, $sql);

        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        oci_free_statement($statement);

        return $res;
    }

    public function selectAllComponentsByParent($pnr)
    {
        if($pnr=='null'){
            $sql = "select components.serial_nr,components.parent_nr,components.type_id,types.kv2,components.shipping_date,components.installation_date 
            from components left join types on components.type_id=types.guid
            WHERE components.parent_nr is null and rownum<=5 order by components.serial_nr asc";
        }
        else{
            $sql = "select components.serial_nr,components.parent_nr,components.type_id,types.kv2,components.shipping_date,components.installation_date 
            from components left join types on components.type_id=types.guid
            WHERE components.parent_nr = {$pnr} and rownum<=5 order by components.serial_nr asc";
        }

        $statement = oci_parse($this->conn, $sql);

        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        oci_free_statement($statement);

        return $res;
    }

    private function getMaxId(){
        $sql = "select * from (select serial_nr from components order by serial_nr desc) where rownum <2";
        $statement = oci_parse($this->conn, $sql);

        oci_execute($statement);

        $res=oci_fetch_row($statement);

        oci_free_statement($statement);

        return $res;
    }

    public function insertIntoComponents($pnr,$tid, $shipping, $installation)
    {
        $id=$this->getMaxId()[0]+1;
        if($shipping!=''&&$shipping!='null')
            $shipping="'".$shipping."'";
        if($installation!=''&&$installation!='null')
            $installation="'".$installation."'";
        $sql = "insert into COMPONENTS (SERIAL_NR, PARENT_NR, TYPE_ID, SHIPPING_DATE, INSTALLATION_DATE)
                values ({$id},{$pnr},{$tid},{$shipping},{$installation})";

        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
        if(!$success)
            var_dump($sql);
        oci_free_statement($statement);
        return $success;
    }

    public function deleteComponent($snr)
    {
        $errorcode = 0;
        $sql = 'BEGIN P_DELETE_COMPONENT(:componentserialnr, :errorcode); END;';
        $statement = oci_parse($this->conn, $sql);

        //  Bind the parameters
        oci_bind_by_name($statement, ':componentserialnr', $snr);
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