<?php

class DocumentsDatabaseHelper
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
                DocumentsDatabaseHelper::username,
                DocumentsDatabaseHelper::password,
                DocumentsDatabaseHelper::con_string
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

    public function selectAllDocuments($guid, $az6, $document_url)
    {
        $sql = "SELECT * FROM documents
            WHERE guid LIKE '%{$guid}%'
              AND upper(az6) LIKE upper('%{$az6}%')
              AND upper(document_url) LIKE upper('%{$document_url}%')";

        $statement = oci_parse($this->conn, $sql);

        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        oci_free_statement($statement);

        return $res;
    }

    public function selectUnusedSpec()
    {
        $sql = "select documents.guid,documents.az6 from documents left join types on documents.guid like types.specification_id 
                where types.guid is null and documents.az6 like 'AZ61%'";

        $statement = oci_parse($this->conn, $sql);

        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        oci_free_statement($statement);

        return $res;
    }

    public function insertIntoDocuments($az6, $document_url)
    {
        $sql = "INSERT INTO DOCUMENTS (A6Z, DOCUMENT_URL) VALUES ('{$az6}', '{$document_url}')";

        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);
        return $success;
    }

    public function deleteDocument($guid)
    {
        $errorcode = 0;
        $sql = 'BEGIN P_DELETE_DOCUMENT(:documentguid, :errorcode); END;';
        $statement = oci_parse($this->conn, $sql);

        //  Bind the parameters
        oci_bind_by_name($statement, ':documentguid', $guid);
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