#!/bin/bash

# Print Agent Installation Script for Linux/Mac

echo "🖨️  Installing Print Agent for Teahouse POS..."
echo ""

# Check if Node.js is installed
if ! command -v node &> /dev/null; then
    echo "❌ Node.js is not installed!"
    echo "📥 Please install Node.js from: https://nodejs.org/"
    echo ""
    echo "For Ubuntu/Debian:"
    echo "  curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -"
    echo "  sudo apt-get install -y nodejs"
    echo ""
    exit 1
fi

echo "✅ Node.js version: $(node -v)"
echo "✅ NPM version: $(npm -v)"
echo ""

# Install dependencies
echo "📦 Installing dependencies..."
npm install

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ Print Agent installed successfully!"
    echo ""
    echo "🚀 To start the print agent:"
    echo "   npm start"
    echo ""
    echo "📝 Configuration:"
    echo "   1. Get your local IP: ifconfig (Mac/Linux) or ipconfig (Windows)"
    echo "   2. Your current IP is likely: 192.168.100.53"
    echo "   3. Make sure firewall allows port 3001"
    echo "   4. Configure VPS to send print requests to http://YOUR_LOCAL_IP:3001"
    echo ""
    echo "📋 For Tailscale VPN setup:"
    echo "   1. Install Tailscale on this machine"
    echo "   2. Join the same tailnet as your VPS"
    echo "   3. Use Tailscale IP in VPS config: http://100.x.x.x:3001"
    echo ""
    echo "🧪 Test the agent:"
    echo "   curl http://localhost:3001/health"
    echo ""
    echo "📖 Read the README.md for more details"
else
    echo "❌ Installation failed!"
    exit 1
fi
