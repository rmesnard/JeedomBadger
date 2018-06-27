
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
#include <Wiegand.h>

// Enter a Default MAC address for your controller below.
byte mac[] = { 0x42, 0x41, 0x44, 0x47, 0x45, 0x00 };
// The IP address came from DHCP
//IPAddress ip(192, 168, 0, 177);
IPAddress jeedomip(192, 168, 0, 20);
// API Key
String apiKey="4ScsBmbJYcywtbUbb7ESyK6rhlHCfi61S1H4CAkA52NF4HGv";
byte readerType =  02;
// readerType   00  not define , 01 wiegand rfid , 02 wiegand rfid + keyboard , 03 wiegand keyboard

// Initialize the Ethernet server library
EthernetClient client;
WIEGAND wg;

void setup() {

Serial.begin(9600);
Serial.println("Start");

   wg.begin();

  Serial.println("Start Network");
    
  // start the Ethernet connection and the server:
  if (Ethernet.begin(mac) == 0) {
    Serial.println("Failed to configure Ethernet using DHCP");
    // no point in carrying on, so do nothing forevermore:
    for (;;)
      ;
  }
  
  Serial.print("server is at ");
  Serial.println(Ethernet.localIP());
}


void sendWIECode()
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

      client.print("&model=wiegand1&cmd=tag&value=");      
      client.println(wg.getCode());  
     Serial.println("Wiegand Received :");
     Serial.println(wg.getCode());
    
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

    if(wg.available())
      sendWIECode();
   delay(20);    
}




