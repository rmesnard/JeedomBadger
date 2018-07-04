

#include <EtherCard.h>
#include <Wiegand.h>

// reader and Jeedom settings

// Jeedom server IP
#define JEEDOM_IP "192.168.0.39"
// Number of reader in networks ( 0 to 255 )
#define READER_NUMBER 2

// End Of Settings

//wiring  green D0 - 3
//        white D1 - 4


byte Ethernet::buffer[600];
static uint32_t timer;
static byte mymac[] = { 0x42,0x41,0x44,0x47,0x45,0x00 };


WIEGAND wg;
String Code;
byte CodeLen;


static void my_callback (byte status, word off, word len) {
  
  Ethernet::buffer[off+300] = 0;
  

}

static void sendtoJeedom (char * cmd,char * value) {
  char url[100]; 
 
  sprintf(url,"name=BADGER%d&ip=%d.%d.%d.%d&cmd=%s&value=%s",mymac[5],ether.myip[0],ether.myip[1],ether.myip[2],ether.myip[3],cmd,value);   // prepare the GET, all variables in one string
 
  ether.browseUrl(PSTR("/plugins/badger/core/api/jeebadger.php?"), url, JEEDOM_IP, my_callback); // send it to the server
}

void setup () {
  mymac[5] =  READER_NUMBER;

  ether.begin(sizeof Ethernet::buffer, mymac,8);
  ether.dhcpSetup();

  ether.parseIp(ether.hisip, JEEDOM_IP);

  wg.begin();
  Code= "";
  CodeLen=0;
}

void loop () {
  
  ether.packetLoop(ether.packetReceive());

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
