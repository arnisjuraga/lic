lic
===

Programma paredzēta triviālam, elementāram uzņēmuma licenču un iekārtu reģistram.
Īpaša tā ir ar:
- iespēju piesaisīt katrai iekārtai konkrētu OEM licenci, kura ir tikai un vienīgi uz tās pašas iepirkuma pavadzīmes, ar ko iegādāta arī iekārta (LV likumdošanas prasības)
- piesaistīt konkrētām iekārtām konkrētu pieejamo licenču daudzumu 
- redzēt īsas atskaites par rezultātu.

Programma veidota sen, izmantojot ļooooooti primitīvas programmēšanas zināšanas un ātram rezultātam.
Tomēr savu darbu tā pilda, tāpēc pagaidām netiek atjaunota un uzlabota. 

Priecāšos par atbalstu, ja kāds vēlēsies uzlabot tās funkcionalitāti *un arī kodu*

Instalācija
===
* Izveidojam programmas direktoriju, piemēram, lic-KlientaNOsaukums, un direktorijā izpildām git clone:

git clone https://github.com/arnisjuraga/lic .

* uztaisām jaunu SQL lietotāju un DB "lic_KlientaNosaukums"
* SQL jaunajā DB izpildām failu \_sql_install.txt
* nokopējam failu config.php.example uz config.php 
* saliekam jaunajā config.php failā SQL pieejas
* Atveram aplikāciju pēc adreses, un pievienojam jaunu KLIENTU sadaļā saitē /customer.php, norādot to kā "END USER"

## Aplikācijas aizsardzība

Lai uzreiz uzstādītu WEB lietotāju un paroli (ņemot vērā, ka kods ir vienkārši briesmīgs pagaidām ... šis ir OBLIGĀTS SOLIS)
* pārsaucam .htaccess.txt par .htaccess
* pārsaucam .htpasswd.txt par .htpasswd  VAI izveidojam savu!
 * izveidojam jaunu HTPASSWD lietotāju pēc apache instrukcijas, un saglabājam failā .htpasswd
* uzstādām WEB paroli lietotājam, norādot PAREIZO .htpasswd faila atrašanās vietu failā: .htaccess

```python
AuthUserFile /ceļš-uz-Jūsu-httpasswd-failu/.htpasswd
```
