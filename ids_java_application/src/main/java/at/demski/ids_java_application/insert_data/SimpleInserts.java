package at.demski.ids_java_application.insert_data;

import java.io.*;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;
import java.util.Random;
import java.util.Scanner;

public class SimpleInserts {
    public static void InsertFromCsv(String file, String table, Connection c) throws IOException, SQLException {
        FileInputStream iStream = null;
        Scanner sc = null;
        try {
            iStream = new FileInputStream(file);
            sc = new Scanner(iStream, "UTF-8");

            String statement="insert into "+table+" values (?";

            String[] header=sc.nextLine().split(";");

            for (int i=1;i<header.length;++i)
                statement+=", ?";
            statement+=")";

            PreparedStatement ps=c.prepareStatement(statement);

            while(sc.hasNextLine()){
                ps.clearParameters();
                String[] values=sc.nextLine().split(";");
                ps.setInt(1,Integer.parseInt(values[0]));
                ps.setString(2,values[1]);
                ps.setString(3,values[2]);
                ps.addBatch();
            }
            ps.clearParameters();
            int[]result=ps.executeBatch();

        } catch (FileNotFoundException e) {
            e.printStackTrace();
        } finally {
            if (iStream != null)
                iStream.close();
            if (sc != null)
                sc.close();
        }
    }
}
