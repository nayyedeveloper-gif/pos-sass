# Print Agent Deployment Guide

## 🚀 Quick Start

1. **Install dependencies:**
   ```bash
   npm install
   ```

2. **Start the server:**
   ```bash
   npm start
   ```

3. **Test health endpoint:**
   ```bash
   curl http://localhost:3001/health
   ```

## 🌐 Network Configuration

### Local Network Setup
- Your current IP: `192.168.100.53`
- Port: `3001`
- VPS Configuration:
  ```
  PRINTING_MODE=cloud
  USE_PRINT_AGENT=true
  PRINT_AGENT_URL=http://192.168.100.53:3001
  ```

### Tailscale VPN Setup (Recommended)
1. Install Tailscale on this machine:
   ```bash
   brew install tailscale
   sudo tailscale up
   ```

2. Note your Tailscale IP:
   ```bash
   tailscale ip
   ```

3. VPS Configuration:
   ```
   PRINTING_MODE=cloud
   USE_PRINT_AGENT=true
   PRINT_AGENT_URL=http://100.X.X.X:3001  # Your Tailscale IP
   ```

## 🔌 Printer Configuration

Make sure your printers are configured in the VPS database:
- Kitchen Printer: 192.168.0.77:9100
- Bar Printer: 192.168.0.88:9100
- Receipt Printer: 192.168.0.66:9100

## 🧪 Testing

### Test Kitchen Print
```bash
curl -X POST http://localhost:3001/print/kitchen \
  -H "Content-Type: application/json" \
  -d '{
    "printerType": "network",
    "printerIp": "192.168.0.77",
    "printerPort": 9100,
    "orderData": {
      "orderNumber": "202511130001",
      "table": "Table 5",
      "time": "2:30 PM",
      "items": [
        {
          "name": "မုန့်ပေါင်ထုပ်",
          "nameEn": "Mote Pauk Thote",
          "quantity": 2,
          "notes": "အပူပိုပါအောင်",
          "isFoc": false
        }
      ]
    }
  }'
```

## 🛠️ Troubleshooting

### Common Issues:
1. **Port already in use:**
   ```bash
   lsof -i :3001 | grep LISTEN | awk '{print $2}' | xargs kill -9
   ```

2. **Printer connection failed:**
   - Check printer IP and port
   - Ensure printer is powered on
   - Verify network connectivity

3. **Myanmar text not rendering properly:**
   - Printer must support Myanmar Unicode
   - For perfect rendering, install canvas dependencies:
     ```bash
     brew install pkg-config cairo pango libpng jpeg giflib librsvg pixman
     npm install canvas
     ```

## 📦 Production Deployment

### Run as Background Service
```bash
# Install PM2 process manager
npm install -g pm2

# Start print agent
pm2 start server.js --name "print-agent"

# Save configuration
pm2 save

# Set to start on boot
pm2 startup
```

### Firewall Configuration
Make sure port 3001 is accessible:
```bash
# macOS
sudo pfctl -f /etc/pf.conf

# Ubuntu/Debian
sudo ufw allow 3001
```
