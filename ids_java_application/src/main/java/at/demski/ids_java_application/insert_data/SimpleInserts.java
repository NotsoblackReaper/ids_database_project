package at.demski.ids_java_application.insert_data;

import java.io.*;
import java.sql.*;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.List;
import java.util.Random;
import java.util.Scanner;

import static java.sql.Statement.EXECUTE_FAILED;

public class SimpleInserts {
    public static int SimpleInsert(String file,String table,int vals,String []vars,int[]var_types, Connection c) throws IOException, SQLException {
        SimpleDateFormat format = new SimpleDateFormat("dd-MMM-yy");
        FileInputStream iStream = null;
        Scanner sc = null;
        int sum=0;
        try {
            iStream = new FileInputStream(file);
            sc = new Scanner(iStream, "UTF-8");

            String statement="insert into "+table+"("+vars[0];
            for(int i=1;i<vals;++i)
                statement+=","+vars[i];

            statement+=") values (?";
            for(int i=1;i<vals;++i)
                statement+=",?";
            statement+=")";


            String[] header=sc.nextLine().split(";");


            PreparedStatement ps=c.prepareStatement(statement);

            while(sc.hasNextLine()){
                ps.clearParameters();
                String[] values=sc.nextLine().split(";");
                for(int i=0;i<vals;++i)
                    if(var_types==null||var_types[i]==0)
                    ps.setString(i+1,values[i]);
                    else if(var_types[i]==1){
                        if(values[i].equals("null"))
                            ps.setNull(i+1, Types.INTEGER);
                        else
                            ps.setLong(i+1,Long.parseLong(values[i]));
                    }else if(var_types[i]==2)
                        ps.setFloat(i+1,Float.parseFloat(values[i]));
                    else if(var_types[i]==3)
                        ps.setDate(i+1, new Date(format.parse(values[i]).getTime()));
                ps.addBatch();
            }
            ps.clearParameters();
            int[]result=ps.executeBatch();

            for(int i:result){
                sum+=i;
                if(i!=1)
                    System.out.println("Insert Error");
            }
        } catch (FileNotFoundException e) {
            e.printStackTrace();
        } finally {
            if (iStream != null)
                iStream.close();
            if (sc != null)
                sc.close();
            return sum;
        }
    }
}
