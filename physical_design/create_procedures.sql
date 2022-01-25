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

CREATE OR REPLACE PROCEDURE p_delete_component(
   p_component_serial_nr  IN  components.serial_nr%TYPE,
   p_error_code OUT NUMBER
)
AS
  BEGIN
    DELETE  
    FROM components
    WHERE p_component_serial_nr = components.serial_nr;

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
  END p_delete_component;
/ 

CREATE OR REPLACE PROCEDURE p_incident_costs(
   P_incident_guid  IN  incidents.guid%TYPE,
   p_result OUT DECIMAL
)
AS
v_tmp DECIMAL;
BEGIN
  
select sum(types.type_cost) into p_result from incidents 
join affects on incidents.guid = affects.guid 
join components on components.serial_nr = affects.serial_nr 
join types on components.type_id=types.guid where incidents.guid=p_incident_guid;

select incident_cost into v_tmp from incidents where guid=p_incident_guid;

p_result:=p_result+v_tmp;

  END p_incident_costs;
/ 
commit;