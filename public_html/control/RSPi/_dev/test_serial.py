import serial
import sys

port = "/dev/ttyAMA0"
usart = serial.Serial(port, 19200)

usart.flushInput()

print ("serial test: BaudRate = 19200")
 
usart.write("please enter the character:\r")
#thestring = "\x7E\xFF\x03\x00\x01\x00\x02\x0A\x01\xC8\x04\xD0\x01\x02\x80\x00\x00\x00\x00\x8E\xE7\z7E"

while True:
    if( usart.inWaiting()>0 ) :
 
      receive = usart.read(1)
      #sys.stdout.write(receive)
      print receive
 
      usart.write("  send: '")
      usart.write(receive)
      usart.write("'\r")
