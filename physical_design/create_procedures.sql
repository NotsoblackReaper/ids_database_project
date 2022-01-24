CREATE OR REPLACE PROCEDURE p_delete_document(
   p_document_guid  IN  documents.guid%TYPE,
   p_error_code OUT NUMBER
)
AS
  BEGIN
    DELETE  
    FROM documents
    WHERE p_document_guid = documents.guid;

    p_error_code := SQL%ROWCOUNT;
    IF (p_error_code = 1)
    THEN
      COMMIT;
    ELSE
      ROLLBACK;
    END IF;
    EXCEPTION
    WHEN OTHERS
    THEN
      p_error_code := SQLCODE;
  END p_delete_document;
/ 

CREATE OR REPLACE PROCEDURE p_delete_employee(
   p_employee_id  IN  employees.employeeid%TYPE,
   p_error_code OUT NUMBER
)
AS
  BEGIN
    DELETE  
    FROM employees
    WHERE p_employee_id = employees.employeeid;

    p_error_code := SQL%ROWCOUNT;
    IF (p_error_code = 1)
    THEN
      COMMIT;
    ELSE
      ROLLBACK;
    END IF;
    EXCEPTION
    WHEN OTHERS
    THEN
      p_error_code := SQLCODE;
  END p_delete_employee;
/ 

CREATE OR REPLACE PROCEDURE p_delete_type(
   p_type_id  IN  employees.employeeid%TYPE,
   p_error_code OUT NUMBER
)
AS
  BEGIN
    DELETE  
    FROM types
    WHERE p_type_id = types.guid;

    p_error_code := SQL%ROWCOUNT;
    IF (p_error_code = 1)
    THEN
      COMMIT;
    ELSE
      ROLLBACK;
    END IF;
    EXCEPTION
    WHEN OTHERS
    THEN
      p_error_code := SQLCODE;
  END p_delete_type;
/ 

commit;