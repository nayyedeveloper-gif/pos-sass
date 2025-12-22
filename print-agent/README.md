# Local Print Agent for Browser POS

Browser-based POS application မှ localhost:1818/print endpoint သို့ fetch request ပို့၍ လက်ရှိ network ပေါ်က receipt printer များကို print လုပ်ရန် local print agent service ဖြစ်ပါသည်။

## 🎯 Architecture

```
┌─────────────────┐         ┌──────────────┐         ┌─────────────┐
│  Browser POS    │  fetch  │ Print Agent  │  ESC/POS│   Printer   │
│  (Web App)      ├────────►│ (Local PC)   ├────────►│ 192.168.x.x │
│                 │         │ Port: 1818   │         │             │
└─────────────────┘         └──────────────┘         └─────────────┘
```

## 📋 Requirements

- Node.js 16+
- Local network ထဲမှာ printer များနှင့် ချိတ်ဆက်ထားသော PC/Server တစ်ခု
- Port 1818 ကို firewall မှ ခွင့်ပြုထားရမည်

## 🚀 Installation

### Windows:
```cmd
install.bat
```

### Linux/Mac:
```bash
chmod +x install.sh
./install.sh
```

### Manual:
```bash
npm install
npm start
```

## ⚙️ Configuration

### 1. Local IP Address ရယူရန်

**Windows:**
```cmd
ipconfig
```

**Linux/Mac:**
```bash
ifconfig
# or
ip addr show
```

Example: `192.168.1.100`

### 2. Firewall Configuration

**Windows:**
- Windows Defender Firewall → Inbound Rules
- New Rule → Port → TCP → 1818
- Allow the connection

**Linux (Ubuntu):**
```bash
sudo ufw allow 1818/tcp
```

**Mac:**
System Preferences → Security & Privacy → Firewall → Firewall Options → Allow port 1818

### 3. Test Connection

```bash
# From local machine
curl http://localhost:1818/health
```

Response:
```json
{
  "status": "ok",
  "message": "Print Agent is running",
  "timestamp": "2025-11-13T10:30:00.000Z"
}
```

## 📡 API Endpoints

### Health Check
```
GET /health
```

### Unified Print Endpoint
```
POST /print
Content-Type: application/json

{
  "printType": "receipt|kitchen|bar",
  "printerIp": "192.168.1.50",
  "printerPort": 9100,
  "printerType": "network",
  "orderData": {
    "businessName": "သာချို ကဖေး",
    "businessAddress": "Yangon, Myanmar", 
    "businessPhone": "09123456789",
    "orderNumber": "ORD-2025-001",
    "date": "13/11/2025 10:30 AM",
    "table": "Table 5",
    "items": [
      {
        "name": "ကော်ဖီ",
        "nameEn": "Coffee",
        "quantity": 2,
        "amount": 4000,
        "isFoc": false,
        "notes": ""
      }
    ],
    "subtotal": 4000,
    "tax": 200,
    "discount": 0,
    "total": 4200
  }
}
```

## 🔧 Laravel Integration

VPS ပေါ်က Laravel application မှ print agent ကို ချိတ်ဆက်ရန်:

```php
// In .env file
PRINT_AGENT_URL=http://192.168.1.100:3001
```

## 🐛 Troubleshooting

### Connection Refused
- Print agent running ရှိမရှိ စစ်ပါ: `npm start`
- Firewall configuration စစ်ပါ
- Local IP မှန်ကန်မှု စစ်ပါ

### Printer Not Found
- Printer IP နှင့် Port မှန်ကန်မှု စစ်ပါ
- Printer ပွင့်နေမှု စစ်ပါ (`ping 192.168.1.50`)
- Same network ပေါ်တွင် ရှိမရှိ စစ်ပါ

### Slow Printing
- Network connection စစ်ပါ
- Printer timeout setting ကို တိုးပါ

## 🔄 Auto-Start on Boot

### Windows (Task Scheduler):
1. Create batch file `start-print-agent.bat`:
```bat
cd C:\path\to\print-agent
npm start
```
2. Task Scheduler → Create Basic Task
3. Run at startup
4. Action: Start a program → `start-print-agent.bat`

### Linux (systemd):
```bash
sudo nano /etc/systemd/system/print-agent.service
```

```ini
[Unit]
Description=Teahouse Print Agent
After=network.target

[Service]
Type=simple
User=youruser
WorkingDirectory=/path/to/print-agent
ExecStart=/usr/bin/node server.js
Restart=always

[Install]
WantedBy=multi-user.target
```

```bash
sudo systemctl enable print-agent
sudo systemctl start print-agent
```

## 📝 Notes

- Print agent သည် 24/7 running ဖြစ်သင့်သည်
- VPS နှင့် Print Agent ကြား VPN သုံးလျှင် ပို၍ secure ဖြစ်သည်
- Multiple printers များကို support လုပ်နိုင်သည်
- Load balancing အတွက် multiple print agents များ run နိုင်သည်
