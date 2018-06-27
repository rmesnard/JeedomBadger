
/*
  Badger
 
 Arduino sketch to connect RFID/NFC readers to Jeedom

 Hardware Supported :
 - Wiegand 26 Compatible RFID reader
 - NFC Arduino Reader.

 Not yet supported :
 - Wiegand 26 Compatible Keyboard
 
 Circuit:
 * Ethernet shield attached to pins 4, 10, 11, 12, 13
 * Wiegand connected to 2,3  ( D0 / D1 ) 
 * NFC Reader conneced to 0,1 ( TX / RX )
 
 */

#include <SPI.h>
#include <Ethernet.h>
#include <EEPROM.h>
//#include <Wiegand.h>

// Enter a Default MAC address for your controller below.
byte mac[] = { 0x42, 0x41, 0x44, 0x47, 0x45, 0x00 };
// The IP address came from DHCP
//IPAddress ip(192, 168, 1, 177);
IPAddress jeedomip(192, 168, 0, 100);
// API Key
String apiKey;

byte readerType = 0;
// Initialize the Ethernet server library
EthernetServer server(80);
String HTTP_req; 
//WIEGAND wg;

void setup() {

Serial.begin(9600);
Serial.println("Start");
  // Read EEPROM Config

  bool configeeprom = true;
  
  // Check if EEprom config exist
  for ( int offset = 0; offset < 5 ; offset ++ ) 
  {
    if ( EEPROM.read(offset) != mac[offset] )
      configeeprom = false;
  }

  if ( configeeprom )
  {
    Serial.println("Load EEProm Config");
    // last byte of Mac are badger number  
    mac[5] = EEPROM.read(5);
    // get jeedom Ip adress
    jeedomip[0] = EEPROM.read(6);
    jeedomip[1] = EEPROM.read(7);
    jeedomip[2] = EEPROM.read(8);
    jeedomip[3] = EEPROM.read(9);
    readerType =  EEPROM.read(10);
    // readerType   00  not define , 01 wiegand rfid , 02 wiegand rfid + keyboard , 03 wiegand keyboard, 04 NFC reader
    
    for ( int offset = 0; offset < 48 ; offset ++ ) 
      apiKey[offset] = EEPROM.read(offset + 10);
    
  }

//  if (( readerType && 0x01 ) | ( readerType && 0x02 ))
//    wg.begin();

  Serial.println("Start Network");
    
  // start the Ethernet connection and the server:
  if (Ethernet.begin(mac) == 0) {
    Serial.println("Failed to configure Ethernet using DHCP");
    // no point in carrying on, so do nothing forevermore:
    for (;;)
      ;
  }
  server.begin();

  Serial.print("server is at ");
  Serial.println(Ethernet.localIP());
}

void saveEEprom ()
{
  for ( int offset = 0; offset < 6 ; offset ++ ) 
    EEPROM.write(offset,mac[offset]);

    EEPROM.write(6,jeedomip[0]);
    EEPROM.write(7,jeedomip[1]);
    EEPROM.write(8,jeedomip[2]);
    EEPROM.write(9,jeedomip[3]);

    EEPROM.write(10,readerType);

  for ( int offset = 0; offset < 48 ; offset ++ ) 
    EEPROM.write(offset + 10,apiKey[offset]);

}

void sendHome(EthernetClient cl)
{
        cl.println("HTTP/1.1 200 OK");
        cl.println("Content-Type: text/html");
        cl.println("Connection: close");
        cl.println();
        // send web page
        cl.println("<!DOCTYPE html>");
        cl.println("<html>");
        cl.println("<head>");
        cl.println("<title>BADGER Settings</title>");
        cl.println("</head>");
        cl.println("<body>"); 
        cl.println("<form method=\"get\"><div>");

        cl.print("<div>Bardger Nb : <input type=\"text\" name=\"BDGNUM\" value=\"");
        cl.print(mac[5],DEC);
        cl.println("\" ></div>");

        cl.print("<div>Jeedom IP : <input type=\"text\" name=\"JEEDOMIP\" value=\"");
          cl.print(jeedomip[0],DEC);
          cl.print(".");
          cl.print(jeedomip[1],DEC);
          cl.print(".");
          cl.print(jeedomip[2],DEC);
          cl.print(".");
          cl.print(jeedomip[3],DEC);
        cl.println("\" ></div>");

        cl.print("<div>API Key : <input type=\"text\" name=\"APIKEY\" value=\"");
        cl.print(apiKey);
        cl.println("\" ></div>");
/*
        if (readerType && 0x01 )
          cl.println("<div><input type=\"checkbox\" name=\"WIERFID\" value=\"1\" Checked> Wiegand RFID </div>");
        else
          cl.println("<div><input type=\"checkbox\" name=\"WIERFID\" value=\"0\" > Wiegand RFID </div>");
          
        if (readerType && 0x02 )
          cl.println("<div><input type=\"checkbox\" name=\"WIEKBD\" value=\"1\" Checked> Wiegand Keyboard </div>");
        else
          cl.println("<div><input type=\"checkbox\" name=\"WIEKBD\" value=\"0\" > Wiegand Keyboard </div>");

        cl.println("<input type=\"hidden\" name=\"STOP\" value=\"1\" >");
           
        cl.println("<div><input type=\"button\" value=\"Save\" onclick=\"submit();\" ></div>");
*/
        cl.println("</div></form>");
        cl.println("</body>");
        cl.println("</html>");                    
}


void sendWIECode(EthernetClient client)
{
  if (client.connect(jeedomip, 80)) {
    // send the HTTP PUT request:
    client.print("GET /plugins/badger/core/api/jeebadger.php?apikey=");
    client.print(apiKey);
    client.print("&name=BADGER");  
    client.print(mac[5],DEC);
    client.print("&ip=");  
    client.print(Ethernet.localIP()[0], DEC);
    client.print(".");
    client.print(Ethernet.localIP()[1], DEC);
    client.print(".");
    client.print(Ethernet.localIP()[2], DEC);
    client.print(".");
    client.print(Ethernet.localIP()[3], DEC);
    client.print("&id=");      
    client.print(mac[0],HEX); 
    client.print(mac[1],HEX); 
    client.print(mac[2],HEX); 
    client.print(mac[3],HEX); 
    client.print(mac[4],HEX); 
    client.print(mac[5],HEX); 
    if ( readerType == 0x01 )
    {
      client.print("&model=wiegand1&cmd=tag&value=");      
   //   client.println(wg.getCode());  
    }
    
    client.println(" HTTP/1.1");
    client.println("Host: server1");
    client.println("Connection: close");    
    client.println();
  }
  delay(20);  
  client.stop();
  
}

void loop() 
{
 EthernetClient client = server.available();  // try to get client

    if (client) {  // got client?
        boolean currentLineIsBlank = true;
        while (client.connected()) {
            if (client.available()) {   // client data available to read
                char c = client.read(); // read 1 byte (character) from client
                HTTP_req += c;  // save the HTTP request 1 char at a time
                // last line of client request is blank and ends with \n
                // respond to client only after last line received
                if (c == '\n' && currentLineIsBlank) {
                    // send a standard http response header
                    ProcessRequest();
                    sendHome(client);
                    HTTP_req = "";    // finished with request, empty string
                    break;
                }
                // every line of text received from the client ends with \r\n
                if (c == '\n') {
                    // last character on line of received text
                    // starting new line with next character read
                    currentLineIsBlank = true;
                } 
                else if (c != '\r') {
                    // a text character was received from client
                    currentLineIsBlank = false;
                }
            } // end if (client.available())
        } // end while (client.connected())
        delay(1);      // give the web browser time to receive the data
        client.stop(); // close the connection
    } // end if (client)

 //   if(wg.available())
 //     sendWIECode(client);
}

void ProcessRequest()
{

   int endfound = -1;
   Serial.println(HTTP_req);
    
    int startfound = HTTP_req.indexOf("BDGNUM=");
    if ( startfound > -1) 
    {
      Serial.print("BDGNUM=");
      endfound= HTTP_req.indexOf("&",startfound);
      mac[5]=  HTTP_req.substring(startfound + 7 ,endfound).toInt();
      Serial.println(HTTP_req.substring(startfound + 7 ,endfound));
    }

}



