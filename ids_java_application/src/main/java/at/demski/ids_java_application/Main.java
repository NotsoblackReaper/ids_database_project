package at.demski.ids_java_application;

import at.demski.ids_java_application.generate_data.DataGenerator;
import at.demski.ids_java_application.insert_data.SimpleInserts;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.Statement;

public class Main {
    public static void main(String[]args){
        try {
            DataGenerator.generateDocuments("src/main/resources/data/","Documents",100000);
            //DataGenerator.generateEmployees("src/main/resources/data/sampleText/","src/main/resources/data/","Employees",100000);
            String dburl = "jdbc:oracle:thin:@oracle-lab.cs.univie.ac.at:1521:lab";
            Connection con = DriverManager.getConnection(dburl, "a11739260", "dbs21");
            Statement stmt = con.createStatement();

            ResultSet rs = stmt.executeQuery("SELECT COUNT(*) AS total FROM documents");
            while(rs.next()){
                System.out.println("Initially "+rs.getInt("total")+" records");
            }
            stmt.execute("delete from EMPLOYEES");
            SimpleInserts.SimpleInsert2Vals("src/main/resources/data/Documents.csv","documents","a6z","document_url",con);
            System.out.println("Inserted Records");
            rs = stmt.executeQuery("SELECT COUNT(*) AS total FROM documents");
            while(rs.next()){
                System.out.println("Now "+rs.getInt("total")+" records");
            }
            rs.close();

            stmt.close();
            con.close();

            } catch( Exception e ) {System.err.println(e.getMessage());};
    }
}
