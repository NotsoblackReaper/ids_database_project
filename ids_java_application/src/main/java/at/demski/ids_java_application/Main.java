package at.demski.ids_java_application;

import at.demski.ids_java_application.generate_data.DataGenerator;
import at.demski.ids_java_application.insert_data.SimpleInserts;

import java.io.IOException;
import java.sql.*;

public class Main {

    public static void clearData(Connection con,boolean[]skips) throws SQLException {
        System.out.println("Clearing Data");
        Statement stmt=con.createStatement();
        if(!skips[5]){
            stmt.executeUpdate("delete from AFFECTS");
            System.out.println("\tCleared affects");
        }
        if(!skips[3]){
            stmt.executeUpdate("delete from COMPONENTS");
            System.out.println("\tCleared components");
        }
        if(!skips[2]){
            stmt.executeUpdate("delete from TYPES");
            System.out.println("\tCleared types");
        }
        if(!skips[0]){
            stmt.executeUpdate("delete from DOCUMENTS");
             System.out.println("\tCleared documents");
        }
        if(!skips[1]){
            stmt.executeUpdate("delete from EMPLOYEES");
            System.out.println("\tCleared employees");
        }
        if(!skips[4]){
            stmt.executeUpdate("delete from INCIDENTS");
            System.out.println("\tCleared incidents");
        }
        con.commit();
    }

    public static void generateData(Connection con,boolean[]skips, long n) throws IOException {
        System.out.println("Generating Data");
        if(!skips[0]){
            DataGenerator.generateDocuments("src/main/resources/data/","Documents", (long) (n*0.33f));
            System.out.println("\tGenerated documents");
        }
        if(!skips[1]){
            DataGenerator.generateEmployees("src/main/resources/data/sampleText/","src/main/resources/data/","Employees",n);
            System.out.println("\tGenerated employees");
        }
        if(!skips[2]){
            DataGenerator.generateTypes("src/main/resources/data/","Types", (long) (n*0.33),con);
            System.out.println("\tGenerated types");
        }
        if(!skips[3]){
            DataGenerator.generateComponents("src/main/resources/data/","Components", n,con);
            System.out.println("\tGenerated components");
        }
        if(!skips[4]){
            DataGenerator.generateIncidents("src/main/resources/data/","Incidents", n);
            System.out.println("\tGenerated incidents");
        }
        if(!skips[5]){
            DataGenerator.generateAffectedComponents("src/main/resources/data/","Affects", n,con);
            System.out.println("\tGenerated affects");
        }
    }

    public static void insertData(Connection con,boolean[]skips) throws SQLException, IOException {
        System.out.println("Inserting data:");
        int res=0;
        if(!skips[0]){
            res=SimpleInserts.SimpleInsert("src/main/resources/data/Documents.csv","documents",2,
                    new String[]{"az6", "document_url"},null,con);
            System.out.println("\tInserted "+res+" rows into documents");
        }
        if(!skips[1]){
            res=SimpleInserts.SimpleInsert("src/main/resources/data/Employees.csv","employees",3,
                    new String[]{"firstname","surname", "email"},null,con);
            System.out.println("\tInserted "+res+" rows into employees");
        }
        if(!skips[2]){
            res=SimpleInserts.SimpleInsert("src/main/resources/data/Types.csv","types",4,
                    new String[]{"specification_id", "kv2","supplier","type_cost"},
                    new int[]{1,0,0,2},con);
            System.out.println("\tInserted "+res+" rows into types");
        }
        if(!skips[3]){
            res=SimpleInserts.SimpleInsert("src/main/resources/data/Components.csv","components",5,
                    new String[]{"serial_nr", "parent_nr","type_id","shipping_date","installation_date"},
                    new int[]{1,1,1,3,3},con);
            System.out.println("\tInserted "+res+" rows into components");
        }
        if(!skips[4]){
            res=SimpleInserts.SimpleInsert("src/main/resources/data/Incidents.csv","incidents",2,
                    new String[]{"type","incident_cost"},
                    new int[]{0,2},con);
            System.out.println("\tInserted "+res+" rows into incidents");
        }
        if(!skips[5]){
            res=SimpleInserts.SimpleInsert("src/main/resources/data/Affects.csv","affects",2,
                    new String[]{"serial_nr","guid"},
                    new int[]{1,1},con);
            System.out.println("\tInserted "+res+" rows into affects");
        }
        con.commit();
    }

    public static void main(String[]args){
        try {

             String dburl = "jdbc:oracle:thin:@oracle-lab.cs.univie.ac.at:1521:lab";
            Connection con = DriverManager.getConnection(dburl, "a11739260", "dbs21");
            con.setAutoCommit(false);
            con.commit();

            long amount=50000;
/*
            clearData(con,new boolean[]{false,false,false,false,false,false});
            //Generate and insert Documents and Employees
            generateData(con, new boolean[]{false, false, true,true,false,true},amount);
            insertData(con,new boolean[]{false, false,true,true,false,true});

            //Generate and insert Types, dependant on documents
            generateData(con, new boolean[]{true, true, false,true,true,true},amount);
            insertData(con,new boolean[]{true, true,false,true,true,true});

            //Generate components, dependant on types
            generateData(con, new boolean[]{true, true, true,false,true,true},amount);
            insertData(con,new boolean[]{true, true,true,false,true,true});
*/
            //generate affects, dependant on components
            generateData(con, new boolean[]{true, true, true,true,true,false},amount);
            insertData(con,new boolean[]{true, true,true,true,true,false});

            con.close();

            } catch( Exception e ) {System.err.println(e.getMessage());};
    }
}
