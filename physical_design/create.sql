create table documents(
    guid number(10,0) not null,
    a6z char(12) null,
    document_url varchar(45) null,
    primary key(guid)
);

create table types(
    guid number(10,0) not null,
    specification_id number(10,0) not null,
    kv2 char(12) null,
    supplier varchar(45) null,
    type_cost number(20,2) null,
    primary key(guid),
    CONSTRAINT type_document_fk FOREIGN KEY (specification_id) REFERENCES documents (guid)
);

create table components(
    serial_nr number(10,0) not null,
    parent_nr number(10,0) not null,
    type_id number(10,0) not null,
    primary key(serial_nr),
    CONSTRAINT component_parent_fk FOREIGN KEY (parent_nr) REFERENCES components (serial_nr),
    CONSTRAINT component_type_fk FOREIGN KEY (type_id) REFERENCES types (guid)
);

create table incidentReports(
    guid number(10,0) not null,
    k3 char(12) null,
    date_filed date null,
    severity number (1,0) null,
    primary key(guid),
    CONSTRAINT report_document_fk FOREIGN KEY (guid) REFERENCES documents (guid)
);

create table employees(
    employeeId number(10,0) not null,
    name varchar(45) null,
    email varchar(45) null,
    primary key(employeeId)
);

 create table incidents(
    guid number(10,0) not null,
    type varchar(45) null,
    incident_cost number(20,2) null,
    primary key(guid)
);

create table isFiled(
    reportGuid number(10,0) not null,
    employeeId number(10,0) not null,
    incidentGuid number(10,0) not null,
    primary key(reportGuid,employeeId,incidentGuid),
    CONSTRAINT filed_report_fk FOREIGN KEY (reportGuid) REFERENCES incidentReports (guid),
    CONSTRAINT filed_employee_fk FOREIGN KEY (employeeId) REFERENCES employees (employeeId),
    CONSTRAINT filed_incident_fk FOREIGN KEY (incidentGuid) REFERENCES incidents (guid)
);

create table affects(
    serial_nr number(10,0) not null,
    incidentGuid number(10,0) not null,
    primary key(serial_nr,incidentGuid),
    CONSTRAINT affects_component_fk FOREIGN KEY (serial_nr) REFERENCES components (serial_nr),
    CONSTRAINT affects_incident_fk FOREIGN KEY (incidentGuid) REFERENCES incidents (guid)
);