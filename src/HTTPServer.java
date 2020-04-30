import java.io.*;
import java.net.*;
import java.util.*;


public class HTTPServer implements Runnable {


    static final File WEB_ROOT = new File(".");
    static final String DEFAULT_FILE = "index.php";
    static final String FILE_NOT_FOUND = "404.html";
    static final String METHOD_NOT_SUPPORTED = "not_supported.html";

    static final int PORT = 8080;

    static final boolean verbose = true;

    private Socket connect;

    public HTTPServer(Socket c) {
        connect = c;
    }

    public static void main(String[] args) {

        try {
            ServerSocket serverConnect = new ServerSocket(PORT);
            System.out.println("Server started. \nListening for connections on port: " + PORT + "...\n");

            while (true) {
                HTTPServer myServer = new HTTPServer(serverConnect.accept());

                if (verbose) {
                    System.out.println("Connection opened . (" + new Date() + ")");
                }

                Thread thread = new Thread(myServer);
                thread.start();


            }
        } catch (IOException e) {
            System.err.println("Server connection error: " + e.getMessage());

        }

    }

    @Override
    public void run() {

        BufferedReader in = null;
        PrintWriter out = null;
        BufferedOutputStream dataOut = null;
        String fileRequested = null;


        try {
            in = new BufferedReader(new InputStreamReader(connect.getInputStream()));
            out = new PrintWriter(connect.getOutputStream());
            dataOut = new BufferedOutputStream(connect.getOutputStream());

            String input = in.readLine();
            StringTokenizer parse = new StringTokenizer(input);
            String method = parse.nextToken().toUpperCase();
            fileRequested = parse.nextToken().toLowerCase();

            if (!method.equals("GET") && !method.equals("HEAD")) {
                if (verbose) {
                    System.out.println("501 Not Implemented: " + method + " method.");
                }
                File file = new File(WEB_ROOT, METHOD_NOT_SUPPORTED);
                int fileLength = (int) file.length();
                String contentMineType = "text/php";
                byte[] fileData = readFileData(file, fileLength);

                out.println("HTTP/1.1 501 NOT IMPLEMENTED");
                out.println("Server: Java HTTP Server 1.0");
                out.println("Date: " + new Date());
                out.println("Content-type: " + contentMineType);
                out.println("Content-length: " + fileLength);
                out.println();
                out.flush();

                dataOut.write(fileData, 0, fileLength);
                dataOut.flush();

            } else {


                if (fileRequested.endsWith("/")) {
                    fileRequested += DEFAULT_FILE;
                }

                File file = new File(WEB_ROOT, fileRequested);
                int fileLength = (int) file.length();
                String content = getContentType(fileRequested);

                if (method.equals("GET")) {
                    byte[] fileData = readFileData(file, fileLength);

                    out.println("HTTP/1.1 200 OK");
                    out.println("Server: JavaHTTPServer 1.0");
                    out.println("Date: " + new Date());
                    out.println("Content type: " + content);
                    out.println("Content-length: " + fileLength);
                    out.println();
                    out.flush();

                    dataOut.write(fileData, 0, fileLength);
                    dataOut.flush();
                }

                if (verbose) {
                    System.out.println("File " + fileRequested + " of type " + content + " return");
                }
            }

        } catch (FileNotFoundException fnfe) {

            try {
                fileNotFound(out, dataOut, fileRequested);
            } catch (IOException e) {
                System.err.println("Error with file not found exception: " + e.getMessage());
            }

        } catch (IOException e) {
            System.err.println("Server error: " + e);
        } finally {
            try {
                in.close();
                out.close();
                dataOut.close();
                connect.close();
            } catch (Exception e) {
                System.err.println("Error closing stream: " + e.getMessage());
            }


            if (verbose) {
                System.out.println("Connection closed.\n");
            }
        }
    }

    private byte[] readFileData(File file, int fileLength) throws IOException {

        FileInputStream fileIn = null;
        byte[] fileData = new byte[fileLength];

        try {

            fileIn = new FileInputStream(file);
            fileIn.read(fileData);

        } finally {
            if (fileIn != null) {
                fileIn.close();
            }
        }

        return fileData;
    }

    private String getContentType(String fileRequested) {
        if (fileRequested.endsWith(".php") || fileRequested.endsWith(".html")) {
            return "text/php";
        } else {
            return "text/html";
        }
    }

    private void fileNotFound(PrintWriter out, OutputStream dataOut, String fileRequested) throws IOException {
        File file = new File(WEB_ROOT, FILE_NOT_FOUND);
        int fileLength = (int) file.length();
        String content = "text/html";
        byte[] fileData = readFileData(file, fileLength);

        out.println("HTTP/1.1 404 File Not Found");
        out.println("Server: HTTP Server 1.o");
        out.println("Date: " + new Date());
        out.println("Content-type: " + content);
        out.println("Content-length: " + fileLength);
        out.println();
        out.flush();

        dataOut.write(fileData, 0, fileLength);
        dataOut.flush();

        if (verbose) {
            System.out.println("File " + fileRequested + " not found");
        }
    }
}
