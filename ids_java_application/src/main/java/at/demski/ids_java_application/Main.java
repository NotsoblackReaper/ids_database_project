package at.demski.ids_java_application;

import at.demski.ids_java_application.generate_data.EmployeeGenerator;
import at.demski.ids_java_application.insert_data.SimpleInserts;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.Statement;

public class Main {
    public static void main(String[]args){
        try {
            String dburl = "jdbc:oracle:thin:@oracle-lab.cs.univie.ac.at:1521:lab";
            Connection con = DriverManager.getConnection(dburl, "a11739260", "dbs21");
            Statement stmt = con.createStatement();
            ResultSet rs = stmt.executeQuery("SELECT COUNT(*) AS total FROM EMPLOYEES");
            while(rs.next()){
                System.out.println("Initially "+rs.getInt("total")+" records");
            }
            stmt.execute("delete from EMPLOYEES");
            SimpleInserts.InsertFromCsv("src/main/resources/data/Employees.csv","Employees",con);
            System.out.println("Inserted Records");
            rs = stmt.executeQuery("SELECT COUNT(*) AS total FROM EMPLOYEES");
            while(rs.next()){
                System.out.println("Now "+rs.getInt("total")+" records");
            }

            rs.close();
            stmt.close();
            con.close();

            //EmployeeGenerator.generateEmployees("src/main/resources/data/sampleText/","src/main/resources/data/","Employees",100000);
        } catch( Exception e ) {System.err.println(e.getMessage());};
    }
}
