# LAN Network Setup Guide for Waiter Tablets

သင်၏ ဆိုင်ကွန်ယက် (LAN/WiFi) အတွင်းရှိ Tablet များနှင့် မိုဘိုင်းဖုန်းများမှ POS စနစ်ကို အသုံးပြုနိုင်ရန် အောက်ပါအတိုင်း ဆောင်ရွက်ပါ။

## ၁. ကြိုတင်ပြင်ဆင်ခြင်း (Server Computer)
Host ကွန်ပျူတာ (POS Server) တွင် အောက်ပါတို့ကို စစ်ဆေးပါ။
1.  ကွန်ပျူတာနှင့် Tablet များသည် **WiFi သို့မဟုတ် Network တစ်ခုတည်း** တွင် ရှိနေရမည်။
2.  **Firewall** တွင် Port `8000` (သို့မဟုတ် Laragon သုံးလျှင် `80`) ကို ခွင့်ပြုထားရမည်။

## ၂. စနစ်စတင်ခြင်း

### နည်းလမ်း (က) - Built-in Script အသုံးပြုခြင်း (အလွယ်ဆုံး)
1.  `start_pos_lan.bat` ဖိုင်ကို Double Click နှိပ်ပါ။
2.  Black Screen ပေါ်လာပြီး "Server IP Address" ကို ပြသပေးပါလိမ့်မည်။ (ဥပမာ - `192.168.0.103`)
3.  Tablet များမှ ထိုလိပ်စာကို ရိုက်ထည့်၍ ဝင်ရောက်ပါ။
    *   URL: `http://192.168.0.103:8000` (သင့်စက်၏ IP အမှန်ကို Script က ပြပေးပါမည်)

### နည်းလမ်း (ခ) - Laragon အသုံးပြုခြင်း
1.  Laragon ကို ဖွင့်ပါ။ `Start All` ကို နှိပ်ပါ။
2.  `Menu` > `Apache` > `httpd.conf` တွင် အောက်ပါလိုင်းကို ရှာပြီး `#` ကို ဖြုတ်ပါ (ရှိလျှင်)။
    `Listen 0.0.0.0:80`
3.  သင်၏ ကွန်ပျူတာ IP ကို ရှာပါ။ (Command Prompt တွင် `ipconfig` ရိုက်ပါ)
4.  Tablet ဘရောက်ဆာတွင် `http://<Your-IP>/teahouse-pos/public` ဟု ရိုက်ထည့်ပါ။
    *   ဥပမာ: `http://192.168.0.103/teahouse-pos/public`

## ၃. Tablet မှ ချိတ်ဆက်ခြင်း
1.  Waiter Tablet ၏ Browser (Chrome/Safari) ကို ဖွင့်ပါ။
2.  Address Bar တွင် Server IP ကို ရိုက်ထည့်ပါ။
    *   `http://192.168.0.103:8000` (Script သုံးလျှင်)
    *   `http://192.168.0.103/teahouse-pos/public` (Laragon သုံးလျှင်)
3.  Login ဝင်ရောက်ပြီး "Waiter" အကောင့်ဖြင့် စတင်အသုံးပြုနိုင်ပါပြီ။

## ၄. ပြဿနာဖြေရှင်းခြင်း (Troubleshooting)
*   **ချိတ်ဆက်မရလျှင် (Site can't be reached):**
    *   Firewall ပိတ်ကြည့်ပါ။ (Control Panel > Windows Defender Firewall > Turn Windows Defender Firewall on or off)
    *   Network Profile ကို "Private" ပြောင်းပါ။
*   **ဒီဇိုင်းပုံစံ ပျက်နေလျှင်:**
    *   Server တွင် `npm run build` ကို run ထားကြောင်း သေချာပါစေ။
*   **ပရင်တာ မထွက်လျှင်:**
    *   Admin Dashboard > Printers တွင် IP Address များ မှန်ကန်ကြောင်း စစ်ဆေးပါ။
    *   Printer နှင့် Server ကွန်ပျူတာ Network မိမမိ စစ်ဆေးပါ (`ping 192.168.0.xx`)။
