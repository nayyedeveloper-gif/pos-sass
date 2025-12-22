<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 myanmar-text">QR Menu</h2>
            <p class="mt-1 text-sm text-gray-600 myanmar-text">ဧည့်သည်များအတွက် QR Code Menu</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- QR Code Display -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4 myanmar-text">QR Code</h3>
            
            <div class="flex justify-center p-8 bg-gray-50 rounded-lg">
                {!! QrCode::size(300)->generate($menuUrl) !!}
            </div>
            
            <div class="mt-6 space-y-3">
                <button onclick="downloadQR()" class="w-full btn btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    <span class="myanmar-text">QR Code ဒေါင်းလုဒ်လုပ်မည်</span>
                </button>
                
                <button onclick="printQR()" class="w-full btn btn-outline">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    <span class="myanmar-text">ပရင့်ထုတ်မည်</span>
                </button>
            </div>
        </div>

        <!-- Menu Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4 myanmar-text">Menu အချက်အလက်</h3>
            
            <div class="space-y-4">
                <!-- Public URL -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 myanmar-text">Public Menu Link</label>
                    <div class="flex">
                        <input type="text" value="{{ $menuUrl }}" readonly class="input flex-1 bg-gray-50">
                        <button onclick="copyToClipboard('{{ $menuUrl }}')" class="ml-2 btn btn-outline">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </button>
                    </div>
                    <p class="mt-1 text-xs text-gray-500 myanmar-text">ဤ link ကို မျှဝေနိုင်ပါသည်</p>
                </div>

                <!-- Features -->
                <div class="bg-blue-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-blue-900 mb-2 myanmar-text">လုပ်ဆောင်ချက်များ:</h4>
                    <ul class="space-y-2 text-sm text-blue-800 myanmar-text">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 flex-shrink-0 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Menu items နှင့် စျေးနှုန်းများ ကြည့်ရှုနိုင်သည်</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 flex-shrink-0 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Category အလိုက် စစ်ထုတ်နိုင်သည်</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 flex-shrink-0 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>ရှာဖွေနိုင်သည်</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 flex-shrink-0 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>မည်သည့် device မှ ဖွင့်ကြည့်နိုင်သည်</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 flex-shrink-0 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Order လုပ်၍ မရပါ (ကြည့်ရှုရန်သာ)</span>
                        </li>
                    </ul>
                </div>

                <!-- Usage Instructions -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-2 myanmar-text">အသုံးပြုပုံ:</h4>
                    <ol class="space-y-2 text-sm text-gray-700 myanmar-text list-decimal list-inside">
                        <li>QR Code ကို ဒေါင်းလုဒ် သို့မဟုတ် ပရင့်ထုတ်ပါ</li>
                        <li>စားပွဲများပေါ်တွင် ထားပါ</li>
                        <li>လိပ်စာကဒ်များတွင် ထည့်ပါ</li>
                        <li>Social Media တွင် မျှဝေပါ</li>
                    </ol>
                </div>

                <!-- Preview Button -->
                <a href="{{ $menuUrl }}" target="_blank" class="w-full btn btn-success">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <span class="myanmar-text">Menu ကြည့်ရှုမည်</span>
                </a>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Link ကို ကူးယူပြီးပါပြီ!');
            });
        }

        function downloadQR() {
            const svg = document.querySelector('svg');
            const svgData = new XMLSerializer().serializeToString(svg);
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            const img = new Image();
            
            img.onload = function() {
                canvas.width = img.width;
                canvas.height = img.height;
                ctx.drawImage(img, 0, 0);
                const pngFile = canvas.toDataURL('image/png');
                const downloadLink = document.createElement('a');
                downloadLink.download = 'menu-qr-code.png';
                downloadLink.href = pngFile;
                downloadLink.click();
            };
            
            img.src = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(svgData)));
        }

        function printQR() {
            const printWindow = window.open('', '_blank');
            const svg = document.querySelector('svg').outerHTML;
            printWindow.document.write(`
                <html>
                    <head>
                        <title>QR Code - Menu</title>
                        <style>
                            body {
                                display: flex;
                                flex-direction: column;
                                align-items: center;
                                justify-content: center;
                                min-height: 100vh;
                                margin: 0;
                                font-family: Arial, sans-serif;
                            }
                            .qr-container {
                                text-align: center;
                                padding: 40px;
                            }
                            h1 { margin-bottom: 20px; }
                            p { margin-top: 20px; font-size: 18px; }
                            @media print {
                                body { margin: 0; }
                            }
                        </style>
                    </head>
                    <body>
                        <div class="qr-container">
                            <h1>Scan for Menu</h1>
                            ${svg}
                            <p>{{ $menuUrl }}</p>
                        </div>
                    </body>
                </html>
            `);
            printWindow.document.close();
            setTimeout(() => {
                printWindow.print();
            }, 250);
        }
    </script>
</div>
