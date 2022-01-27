package at.demski.ids_java_application.generate_data;

import com.sun.source.tree.StatementTree;

import javax.swing.plaf.nimbus.State;
import java.io.*;
import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.text.SimpleDateFormat;
import java.util.*;

public class DataGenerator {
    public static void generateEmployees(String names_dir, String out_dir, String out_name, long amount) throws IOException {
        String firstNames = names_dir + "names.csv";
        String surnames = names_dir + "surnames.csv";
        String out_file = out_dir + out_name + ".csv";

        File output = new File(out_file);
        if (output.exists()) output.delete();
        output.createNewFile();
        StringBuilder sb = new StringBuilder();
        sb.append("firstname;surname;email").append(System.lineSeparator());

        FileInputStream iStreamFNames = null;
        FileInputStream iStreamSNames = null;
        Scanner scFNames = null;
        Scanner scSNames = null;
        try {
            iStreamFNames = new FileInputStream(firstNames);
            iStreamSNames = new FileInputStream(surnames);
            scFNames = new Scanner(iStreamFNames, "UTF-8");
            scSNames = new Scanner(iStreamSNames, "UTF-8");

            long i = 0;
            List<String> fName_list = new ArrayList<>();
            List<String> sName_list = new ArrayList<>();
            Random rand = new Random();
            while (i < amount) {

                for (int j = 0; j < 500; ++j) {
                    if (scFNames.hasNextLine())
                        fName_list.add(scFNames.nextLine());
                    if (scSNames.hasNextLine())
                        sName_list.add(scSNames.nextLine());
                }

                for (int j = 0; j < 500 && i < amount; ++j) {
                    int fNameIndex = rand.nextInt(fName_list.size());
                    int sNameIndex = rand.nextInt(sName_list.size());
                    String fname = fName_list.get(fNameIndex);
                    String sname = sName_list.get(sNameIndex);
                    sname = sname.substring(0, 1).toUpperCase() + sname.substring(1).toLowerCase();
                    sb.append(fname).append(";").append(sname).append(';').append(fname).append('.').append(sname).append("@company.at").append(System.lineSeparator());
                    ++i;
                    if (rand.nextFloat() < 0.8f)
                        fName_list.remove(fNameIndex);
                    if (rand.nextFloat() < 0.6f)
                        sName_list.remove(sNameIndex);
                }
            }
            FileWriter fw = new FileWriter(out_file);
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

    public static void generateDocuments(String out_dir, String out_name, long amount) throws IOException {
        String out_file = out_dir + out_name + ".csv";

        File output = new File(out_file);
        if (output.exists()) output.delete();
        output.createNewFile();
        StringBuilder sb = new StringBuilder();
        sb.append("az6;document_url").append(System.lineSeparator());

        for (int cat = 0; cat < 3; cat++) {
            for (int i = 0; i < amount; i++) {
                String az6 = "AZ6" + cat;
                String number = String.valueOf(i);
                String numberstring = "00000000";
                az6 += numberstring.substring(0, numberstring.length() - number.length()) + number;
                sb.append(az6).append(";SAP/documents/").append(az6).append(System.lineSeparator());
            }
        }

        FileWriter fw = new FileWriter(out_file);
        fw.write(sb.toString());
        fw.flush();
        fw.close();
    }

    public static void generateTypes(String out_dir, String out_name, long amount, Connection con) throws IOException {
        String out_file = out_dir + out_name + ".csv";
        String[] supppliers = {"GXO Logistics", "DHL Supply Chain", "Americold", "Ryder Supply Chain Solutions", "EODIS North America",
                "FedEx Supply Chain", "Lineage Logistics", "Kenco Logistic Services LLC"};
        File output = new File(out_file);
        if (output.exists()) output.delete();
        output.createNewFile();

        Random rnd = new Random();
        StringBuilder sb = new StringBuilder();
        try {
            Statement stmt = con.createStatement();
            ResultSet rs = stmt.executeQuery("select * from documents  where az6 like 'AZ61%'");

            sb.append("specification_id;kv2;supplier;type_cost").append(System.lineSeparator());
            for (int i = 0; i < amount; i++) {
                if (!rs.next())
                    break;
                long spec_id = rs.getLong("guid");
                sb.append(spec_id).append(';');
                String kv2 = "KV2000000000";
                String number = String.valueOf(i + 1);
                kv2 = kv2.substring(0, kv2.length() - number.length()) + number;
                sb.append(kv2).append(';');
                String supplier = supppliers[rnd.nextInt(supppliers.length)];
                sb.append(supplier).append(';');
                float cost = 0;
                cost += 100 * rnd.nextInt(5);
                cost += 50 * rnd.nextInt(2);
                cost += 25 * rnd.nextInt(2);
                cost += 0.99f * rnd.nextInt(2);
                if (cost == 0) {
                    cost = 100;
                    cost += 100 * rnd.nextInt(5);
                }
                sb.append(cost).append(System.lineSeparator());
            }

            rs.close();
            stmt.close();
        } catch (SQLException e) {
            e.printStackTrace();
        }

        FileWriter fw = new FileWriter(out_file);
        fw.write(sb.toString());
        fw.flush();
        fw.close();
    }

    private static long component_id;

    public static StringBuilder generateComponentLayer(int layers, int layer, StringBuilder sb, Random rnd, List<List<Long>> types, long[] layer_amount, String parent_id) {
        SimpleDateFormat format = new SimpleDateFormat("dd-MMM-yy");
        String today = format.format(Calendar.getInstance().getTime());
        for (long i = 0; i < layer_amount[layer]; ++i) {
            String serial_nr = String.valueOf(component_id++);
            String type_id = String.valueOf(types.get(layer).get(rnd.nextInt(types.get(layer).size())));
            String shipping_date = today;
            String installation_date = today;
            sb.append(serial_nr).append(';').append(parent_id).append(';').append(type_id).append(';')
                    .append(shipping_date).append(';').append(installation_date).append(System.lineSeparator());
            if (layer < layers - 1)
                sb = generateComponentLayer(layers, layer + 1, sb, rnd, types, layer_amount, serial_nr);
        }
        return sb;
    }

    public static void generateComponents(String out_dir, String out_name, long amount, Connection con) throws IOException {
        String out_file = out_dir + out_name + ".csv";

        File output = new File(out_file);
        if (output.exists()) output.delete();
        output.createNewFile();

        int layers = 4;
        float[] layer_vals = {0.001f, 0.005f, 0.05f, 0.95f};
        long[] layer_amount = new long[layers];
        layer_amount[0] = (long) (amount * layer_vals[0]);
        for (int i = 1; i < layers; ++i) {
            long div = 1;
            for (int j = i - 1; j >= 0; --j)
                div *= layer_amount[j];
            layer_amount[i] = (long) (amount * (layer_vals[i] / div));
        }

        Random rnd = new Random();
        StringBuilder sb = new StringBuilder();
        try {
            Statement stmt = con.createStatement();
            ResultSet rs = stmt.executeQuery("select guid from types");

            List<List<Long>> types = new ArrayList<>();
            for (int i = 0; i < layers; ++i)
                types.add(new ArrayList<>());
            while (rs.next()) {
                long type_id = rs.getLong("guid");
                types.get(rnd.nextInt(layers)).add(type_id);
            }

            rs.close();
            stmt.close();

            sb.append("serial_nr;parent_nr;type_id;shipping_date;installation_date").append(System.lineSeparator());
            component_id = 1;
            sb = generateComponentLayer(layers, 0, sb, rnd, types, layer_amount, "null");

        } catch (SQLException e) {
            e.printStackTrace();
        }

        FileWriter fw = new FileWriter(out_file);
        fw.write(sb.toString());
        fw.flush();
        fw.close();
    }

    public static void generateIncidents(String out_dir, String out_name, long amount) throws IOException {
        String out_file = out_dir + out_name + ".csv";
        String[] types = {"Installation Error", "Shipping Damage","Vandalism","Production Error"};

        File output = new File(out_file);
        if (output.exists()) output.delete();
        output.createNewFile();
        Random rnd=new Random();
        StringBuilder sb = new StringBuilder();
        sb.append("type;cost").append(System.lineSeparator());

        for (int i = 0; i < amount/1000; i++) {
           String type=types[rnd.nextInt(types.length)];
            float cost = 0;
            cost += 100 * rnd.nextInt(5);
            cost += 50 * rnd.nextInt(2);
            cost += 25 * rnd.nextInt(2);
            cost += 0.99f * rnd.nextInt(2);
            sb.append(type).append(';').append(cost).append(System.lineSeparator());
        }

        FileWriter fw = new FileWriter(out_file);
        fw.write(sb.toString());
        fw.flush();
        fw.close();
    }

    public static void generateAffectedComponents(String out_dir, String out_name, long amount, Connection con) throws IOException {
        String out_file = out_dir + out_name + ".csv";

        File output = new File(out_file);
        if (output.exists()) output.delete();
        output.createNewFile();

        Random rnd=new Random();
        StringBuilder sb = new StringBuilder();
        sb.append("serial_nr;guid").append(System.lineSeparator());

        try {
            Statement stmt = con.createStatement();
            ResultSet rs = stmt.executeQuery("select serial_nr from (SELECT serial_nr FROM components ORDER BY dbms_random.value )");

            long remaining=amount;
            for (int i = 0; i < amount/1000; i++) {
                long affected=rnd.nextLong(remaining);
                affected=affected>1000?1000:affected;
                remaining-=affected;
                for(int j=0;j<affected;++j){
                    if(rs.next())
                    sb.append(rs.getLong("SERIAL_NR")).append(';').append(i+1).append(System.lineSeparator());
                }
            }

            rs.close();
            stmt.close();
        } catch (SQLException e) {
            e.printStackTrace();
        }

        FileWriter fw = new FileWriter(out_file);
        fw.write(sb.toString());
        fw.flush();
        fw.close();
    }
}
