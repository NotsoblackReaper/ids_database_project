package at.demski.ids_java_application;

import at.demski.ids_java_application.generate_data.DataGenerator;
import at.demski.ids_java_application.insert_data.SimpleInserts;

import java.io.IOException;
import java.sql.*;

public class Main {

    public static void clearData(Connection con,boolean[]skips) throws SQLException {
        System.out.println("Clearing Data");
        Statement stmt=con.createStatement();
        if(!skips[0]){
            stmt.execute("delete * from documents");
             System.out.println("\tCleared documents");
        }else
            System.out.println("\tSkipped documents");
        if(!skips[1]){
            stmt.execute("delete * from employees");
            System.out.println("\tCleared employees");
        }else
            System.out.println("\tSkipped employees");
        if(!skips[2]){
            stmt.execute("delete * from types");
            System.out.println("\tCleared types");
        }else
            System.out.println("\tSkipped types");
        con.commit();
    }

    public static void generateData(Connection con,boolean[]skips, int n) throws IOException {
        System.out.println("Generating Data");
        if(!skips[0]){
            DataGenerator.generateDocuments("src/main/resources/data/","Documents",n);
            System.out.println("\tGenerated documents");
        }else
            System.out.println("\tSkipped documents");
        if(!skips[1]){
            DataGenerator.generateEmployees("src/main/resources/data/sampleText/","src/main/resources/data/","Employees",n);
            System.out.println("\tGenerated employees");
        }else
            System.out.println("\tSkipped employees");
        if(!skips[2]){
            DataGenerator.generateTypes("src/main/resources/data/","Types",n,con);
            System.out.println("\tGenerated types");
        }else
            System.out.println("\tSkipped types");
    }

    public static void insertData(Connection con,boolean[]skips) throws SQLException, IOException {
        System.out.println("Inserting data:");
        int res=0;
        if(!skips[0]){
            res=SimpleInserts.SimpleInsert("src/main/resources/data/Documents.csv","documents",2, new String[]{"az6", "document_url"},null,con);
            System.out.println("\tInserted "+res+" rows into documents");
        }else
            System.out.println("\tSkipped documents");
        if(!skips[1]){
            res=SimpleInserts.SimpleInsert("src/main/resources/data/Employees.csv","employees",2, new String[]{"name", "email"},null,con);
            System.out.println("\tInserted "+res+" rows into employees");
        }else
            System.out.println("\tSkipped employees");
        if(!skips[2]){
            res=SimpleInserts.SimpleInsert("src/main/resources/data/Types.csv","types",4, new String[]{"specification_id", "kv2","supplier","type_cost"},
                    new int[]{1,0,0,2},con);
            System.out.println("\tInserted "+res+" rows into types");
        }else
            System.out.println("\tSkipped types");
        con.commit();
    }

    public static void main(String[]args){
        try {

             String dburl = "jdbc:oracle:thin:@oracle-lab.cs.univie.ac.at:1521:lab";
            Connection con = DriverManager.getConnection(dburl, "a11739260", "dbs21");
            con.setAutoCommit(false);
            con.commit();
            clearData(con,new boolean[]{false,false,false});
            //Generate and insert Documents and Employees
            generateData(con, new boolean[]{false, false, true},5000);
            insertData(con,new boolean[]{false, false,true});

            //Generate and insert Types, dependant on documents
            generateData(con, new boolean[]{true, true, false},5000);
            insertData(con,new boolean[]{true, true,false});
            /*
            Statement stmt = con.createStatement();
            ResultSet rs = stmt.executeQuery("SELECT COUNT(*) AS total FROM documents");
            while(rs.next()){
                System.out.println("Initially "+rs.getInt("total")+" records");
            }
            System.out.println("Inserted Records");
            rs = stmt.executeQuery("SELECT COUNT(*) AS total FROM documents");
            while(rs.next()){
                System.out.println("Now "+rs.getInt("total")+" records");
            }
            rs.close();
            stmt.close();
            */
            con.close();

            } catch( Exception e ) {System.err.println(e.getMessage());};
    }
}
