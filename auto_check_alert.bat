:loop
 arp -s 192.168.1.254 xx-xx-xx-xx-xx-xx
 ipconfig /flushdns
 timeout /t 60
 goto loop