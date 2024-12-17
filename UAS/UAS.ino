#include <LiquidCrystal_I2C.h>
#include <Wire.h>
#include <DHT.h>
#include <EEPROM.h>  
#include <ESP8266WiFi.h>  
#include <PubSubClient.h>  
#include <ArduinoJson.h>

#define lampu1 D6   
#define lampu2 D7  
#define lampu3 D8   
#define lampu5 D4  
#define lampu6 D5
#define DHTPIN D3
#define DHTTYPE DHT11    

LiquidCrystal_I2C lcd(0x27, 16, 2);
DHT dht(DHTPIN, DHTTYPE);
WiFiClient espClient;  
PubSubClient client(espClient);  

// Konfigurasi WiFi
const char* wifi_ssid = "s8+";
const char* wifi_password = "12345678";

// Konfigurasi MQTT
const char* mqtt_server = "x2.revolusi-it.com"; 
const char* mqtt_username = "usm";
const char* mqtt_password = "usmjaya001";
const char* client_id = "G231220060_NodeMCU";

// Membuat Variabel
float humidity = 0;
float temperature = 0;
float prevHumidity = 0;
float prevTemperature = 0;
String messages = "";
unsigned long previousMillis = 0;  
const long interval = 5000;

byte Water[] = {
  B00100,
  B01110,
  B01110,
  B11111,
  B11101,
  B11101,
  B11111,
  B01110
};

byte Temperature[] = {
  B00100,
  B01010,
  B01010,
  B01010,
  B10001,
  B10001,
  B11011,
  B01110
};

void callbackMQTT(char* topic, byte* payload, unsigned int length) {  
  char message[length + 1];
  strncpy(message, (char*)payload, length);
  message[length] = '\0';

  Serial.print("Pesan diterima: ");
  Serial.println(message);

  StaticJsonDocument<256> doc;
  DeserializationError error = deserializeJson(doc, message);

  if (error) {
    Serial.println("Gagal mem-parsing JSON");
    return;
  }

 // Static variables to track previous states
  static bool prevStateLampu1 = LOW;
  static bool prevStateLampu2 = LOW;
  static bool prevStateLampu3 = LOW;

  // Lampu D6
  if (doc.containsKey("LED1")) {
    bool currentStateLampu1 = (doc["LED1"] == "on");
    if (currentStateLampu1 != prevStateLampu1) {
      digitalWrite(lampu1, currentStateLampu1 ? HIGH : LOW);
      prevStateLampu1 = currentStateLampu1;
    }
  }

  // Lampu D7
  if (doc.containsKey("LED2")) {
    bool currentStateLampu2 = (doc["LED2"] == "on");
    if (currentStateLampu2 != prevStateLampu2) {
      digitalWrite(lampu2, currentStateLampu2 ? HIGH : LOW);
      prevStateLampu2 = currentStateLampu2;
    }
  }

  // Lampu D8
  if (doc.containsKey("LED3")) {
    bool currentStateLampu3 = (doc["LED3"] == "on");
    if (currentStateLampu3 != prevStateLampu3) {
      digitalWrite(lampu3, currentStateLampu3 ? HIGH : LOW);
      prevStateLampu3 = currentStateLampu3;
    }
  }
}

void reconnect() {  
  while (!client.connected()) {  
    Serial.print("Menghubungkan ke MQTT Server -> ");  
    Serial.println(mqtt_server);  
    if (client.connect(client_id, mqtt_username, mqtt_password)) {  
      Serial.println("Terhubung!");  
      client.subscribe("G231220060/control");
    } else {  
      Serial.print("Gagal, rc=");  
      Serial.println(client.state());
    }  
  }  
}

void connectWifi() {  
  WiFi.begin(wifi_ssid, wifi_password);  
  while (WiFi.status() != WL_CONNECTED) {  
    Serial.print(".");  
  }  
  Serial.println("\nWiFi terhubung"); 
}

void displayTempAndHumid(bool forceUpdate = false) {
  if (forceUpdate || temperature != prevTemperature || humidity != prevHumidity) {
    lcd.setCursor(0,0);
    lcd.write(byte(1));
    lcd.setCursor(1,0);
    lcd.print(" TEMP : " + String(temperature) + " C     ");
    lcd.setCursor(0,1);
    lcd.write(byte(0));
    lcd.setCursor(1,1);
    lcd.print(" HUMD : "+ String(humidity) + " %     ");
    prevTemperature = temperature;
    prevHumidity = humidity;
  }
}

void publishTempAndHumid(){
  client.publish("G231220060/temperature", String(temperature).c_str(), true);
  client.publish("G231220060/humidity", String(humidity).c_str(), true);
}

void ledIndicator() {
  if (temperature > 29 ){
    digitalWrite(lampu5, HIGH);
    digitalWrite(lampu6, LOW);
  } else {
    digitalWrite(lampu5, LOW);
    digitalWrite(lampu6, HIGH);
  }
} 

void setup() {  
  Serial.begin(9600);  
  client.setServer(mqtt_server, 1883);  
  client.setCallback(callbackMQTT);  
  pinMode(lampu1, OUTPUT);  
  pinMode(lampu2, OUTPUT);  
  pinMode(lampu3, OUTPUT);  
  pinMode(lampu5, OUTPUT);  
  pinMode(lampu6, OUTPUT);
  Wire.begin();
  lcd.begin(16,2);
  lcd.backlight();
  dht.begin();
  lcd.createChar(0, Water);
  lcd.createChar(1, Temperature);
}

void loop() { 
  if (WiFi.status() != WL_CONNECTED) connectWifi();
  if (!client.connected()) reconnect();
  client.loop();
  
  unsigned long currentMillis = millis();
  if (currentMillis - previousMillis >= interval) {
    previousMillis = currentMillis;

    humidity = dht.readHumidity();
    temperature = dht.readTemperature();
    ledIndicator();
    publishTempAndHumid();
    displayTempAndHumid();
  }
}

