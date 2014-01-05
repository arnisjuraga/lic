1. Pārkopejam failus no lic-TEMPLATE uz jaunu direktoriju lic-KlientaNosaukums
2. uztaisām jaunu SQL lietotāju un DB "lic_KlientaNosaukums"
3. SQL jaunajā DB izpildām failu _sql_install.txt
4. saliekam SQL jaunas pieejas config.php failā
5. Pievienojam KLIENTU sadaļā saitē /customer.php, norādot tos kā "END USER"
6. uzstādām WEB paroli lietotājam, norādot PAREIZO .htpasswd faila atrašanās vietu failā:
/.htaccess

Piemēram:
AuthUserFile /home/www/indeed.pro/www/public_html/lic/lic-gpb/.htpasswd

7. VĒL KAUT KAS?