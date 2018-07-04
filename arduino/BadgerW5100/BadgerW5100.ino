#include <SPI.h>
#include <Ethernet.h>
#include <Wiegand.h>

// reader and Jeedom settings

// Jeedom server IP
IPAddress server(192,168,0,39);
// Number of reader in networks ( 0 to 255 )
#define READER_NUMBER 2

// End Of Settings

//wiring  green D0 - 2
//        white D1 - 3


static byte mymac[] = { 0x42,0x41,0x44,0x47,0x45,0x00 };


WIEGAND wg;
String Code;
byte CodeLen;
EthernetClient client;


static void sendtoJeedom (char * cmd,char * value) {

  if (client.connect(server, 80)) {
    // Make a HTTP request:

   client.print(F("GET /plugins/badger/core/api/jeebadger.php?name=BADGER"));
    client.print(mymac[5],DEC);
    client.print(F("&ip="));
    client.print(Ethernet.localIP()[0],DEC);
    client.print(".");
    client.print(Ethernet.localIP()[1],DEC);
    client.print(".");
    client.print(Ethernet.localIP()[2],DEC);
    client.print(".");
    client.print(Ethernet.localIP()[3],DEC);
    client.print(F("&cmd="));
    client.print(cmd);     
    client.print(F("&value="));
    client.print(value);     
    client.println(F(" HTTP/1.1"));
    client.print(F("Host: "));
    client.print(server[0]);
    client.print('.');
    client.print(server[1]);
    client.print('.');
    client.print(server[2]);
    client.print('.');
    client.println(server[3]);
    delay(111); 
    client.println(F("Connection: close"));
    client.println(); 
    delay(111); 
    client.stop();  

  } else {
    // if you didn't get a connection to the server:
    Serial.println("connection failed");
  }

 
}

void setup () {

  Serial.begin(9600);

  while (!Serial) {
    ; // wait for serial port to connect. Needed for native USB port only
  }

  // give the ethernet module time to boot up:
  delay(1000);
  Serial.println("Start");
    
  mymac[5] =  READER_NUMBER;

  if (Ethernet.begin(mymac) == 0) {
    Serial.println("Failed to configure Ethernet using DHCP");
  }
  
  // print the Ethernet board/shield's IP address:
  Serial.print("My IP address: ");
  Serial.println(Ethernet.localIP());
  
  wg.begin();
  Code= "";
  CodeLen=0;
}

void loop () {
  
  

if(wg.available())
  {
    
    int type = wg.getWiegandType();

    if ( CodeLen >24 )
    {
          Code = "";
          CodeLen=0;    
    }
  
    if ( type == 8 )
    {
      if ( wg.getCode() == 13 )
      {
        sendtoJeedom("pin",Code.c_str());
        Code = "";
        CodeLen=0;
      }
      else
      {
        if( wg.getCode() == 27 )
        {
           Code = "";
           CodeLen=0;
       }
        else
          Code.concat( String(wg.getCode(),HEX));
      }
    }
    else
    {
      Code = String(wg.getCode(),HEX);
      sendtoJeedom("tag",Code.c_str());
      Code="";
      CodeLen=0;
    } 

  }
  
}
