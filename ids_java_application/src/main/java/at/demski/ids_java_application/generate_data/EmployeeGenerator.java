package at.demski.ids_java_application.generate_data;

import java.io.*;
import java.util.*;

public class EmployeeGenerator {
    public static void generateEmployees(String names_dir,String out_dir,String out_name,long amount) throws IOException {
        String firstNames=names_dir+"names.csv";
        String surnames=names_dir+"surnames.csv";
        String out_file=out_dir+out_name+".csv";

        File output=new File(out_file);
        if(output.exists())output.delete();
        output.createNewFile();
        StringBuilder sb=new StringBuilder();
        sb.append("guid;name;email").append(System.lineSeparator());

        FileInputStream iStreamFNames = null;
        FileInputStream iStreamSNames = null;
        Scanner scFNames = null;
        Scanner scSNames = null;
        try {
            iStreamFNames = new FileInputStream(firstNames);
            iStreamSNames = new FileInputStream(surnames);
            scFNames = new Scanner(iStreamFNames, "UTF-8");
            scSNames = new Scanner(iStreamSNames, "UTF-8");

            long i=0;
            List<String>fName_list=new ArrayList<>();
            List<String>sName_list=new ArrayList<>();
            Random rand=new Random();
            while(i<amount){

                for(int j=0;j<500;++j){
                    if(scFNames.hasNextLine())
                        fName_list.add(scFNames.nextLine());
                    if(scSNames.hasNextLine())
                        sName_list.add(scSNames.nextLine());
                }

                for(int j=0;j<500&&i<amount;++j){
                    int fNameIndex= rand.nextInt(fName_list.size());
                    int sNameIndex= rand.nextInt(sName_list.size());
                    String fname=fName_list.get(fNameIndex);
                    String sname=sName_list.get(sNameIndex);
                    sname=sname.substring(0, 1).toUpperCase() + sname.substring(1).toLowerCase();
                    sb.append(i+1).append(';').append(fname).append(" ").append(sname).append(';').append(fname).append('.').append(sname).append("@company.at").append(System.lineSeparator());
                    ++i;
                    if(rand.nextFloat()<0.8f)
                    fName_list.remove(fNameIndex);
                    if(rand.nextFloat()<0.6f)
                    sName_list.remove(sNameIndex);
                }
            }
            FileWriter fw=new FileWriter(out_file);
            fw.write(sb.toString());
            fw.flush();
            fw.close();
        } catch (FileNotFoundException e) {
            e.printStackTrace();
        } finally {
            if (iStreamFNames != null)
                iStreamFNames.close();
            if (iStreamSNames != null)
                iStreamSNames.close();
            if (scFNames != null)
                scFNames.close();
            if (scSNames != null)
                scSNames.close();
        }
    }
}
