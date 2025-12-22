/**
 * Cloud Sync Service
 * Handles automatic synchronization between local and cloud databases
 */
class CloudSyncService {
    constructor() {
        this.cloudUrl = null;
        this.apiToken = null;
        this.syncInterval = null;
        this.isOnline = navigator.onLine;
        this.lastSync = localStorage.getItem('last_cloud_sync') || null;
        this.syncInProgress = false;
        this.pendingChanges = JSON.parse(localStorage.getItem('pending_sync_changes') || '[]');
        
        this.init();
    }

    init() {
        // Listen for online/offline events
        window.addEventListener('online', () => {
            this.isOnline = true;
            this.showNotification('Connected to internet', 'success');
            this.syncNow();
        });

        window.addEventListener('offline', () => {
            this.isOnline = false;
            this.showNotification('Working offline - changes will sync when connected', 'warning');
        });

        // Load settings from meta tags or localStorage
        this.loadSettings();
    }

    loadSettings() {
        const cloudUrlMeta = document.querySelector('meta[name="cloud-sync-url"]');
        const apiTokenMeta = document.querySelector('meta[name="cloud-sync-token"]');
        
        this.cloudUrl = cloudUrlMeta?.content || localStorage.getItem('cloud_sync_url');
        this.apiToken = apiTokenMeta?.content || localStorage.getItem('cloud_sync_token');
    }

    configure(cloudUrl, apiToken, autoSyncMinutes = 5) {
        this.cloudUrl = cloudUrl;
        this.apiToken = apiToken;
        
        localStorage.setItem('cloud_sync_url', cloudUrl);
        localStorage.setItem('cloud_sync_token', apiToken);
        
        // Start auto sync
        this.startAutoSync(autoSyncMinutes);
    }

    startAutoSync(minutes = 5) {
        if (this.syncInterval) {
            clearInterval(this.syncInterval);
        }
        
        this.syncInterval = setInterval(() => {
            if (this.isOnline && this.cloudUrl) {
                this.syncNow();
            }
        }, minutes * 60 * 1000);
        
        console.log(`[CloudSync] Auto sync started - every ${minutes} minutes`);
    }

    stopAutoSync() {
        if (this.syncInterval) {
            clearInterval(this.syncInterval);
            this.syncInterval = null;
        }
    }

    async syncNow() {
        if (!this.cloudUrl || !this.apiToken) {
            console.warn('[CloudSync] Not configured - skipping sync');
            return { success: false, message: 'Cloud sync not configured' };
        }

        if (!this.isOnline) {
            console.warn('[CloudSync] Offline - skipping sync');
            return { success: false, message: 'Device is offline' };
        }

        if (this.syncInProgress) {
            console.warn('[CloudSync] Sync already in progress');
            return { success: false, message: 'Sync already in progress' };
        }

        this.syncInProgress = true;
        this.dispatchEvent('sync-started');

        try {
            // 1. Push pending local changes to cloud
            if (this.pendingChanges.length > 0) {
                await this.pushToCloud();
            }

            // 2. Pull changes from cloud
            await this.pullFromCloud();

            this.lastSync = new Date().toISOString();
            localStorage.setItem('last_cloud_sync', this.lastSync);
            
            this.dispatchEvent('sync-completed', { lastSync: this.lastSync });
            this.showNotification('Sync completed successfully', 'success');
            
            return { success: true, lastSync: this.lastSync };
        } catch (error) {
            console.error('[CloudSync] Sync failed:', error);
            this.dispatchEvent('sync-failed', { error: error.message });
            this.showNotification('Sync failed: ' + error.message, 'error');
            
            return { success: false, message: error.message };
        } finally {
            this.syncInProgress = false;
        }
    }

    async pushToCloud() {
        const response = await fetch(`${this.cloudUrl}/api/sync/push`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${this.apiToken}`,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                changes: this.pendingChanges,
                device_id: this.getDeviceId(),
                last_sync: this.lastSync
            })
        });

        if (!response.ok) {
            throw new Error(`Push failed: ${response.status}`);
        }

        const result = await response.json();
        
        if (result.success) {
            // Clear pending changes
            this.pendingChanges = [];
            localStorage.setItem('pending_sync_changes', '[]');
        }

        return result;
    }

    async pullFromCloud() {
        const response = await fetch(`${this.cloudUrl}/api/sync/pull?last_sync=${this.lastSync || ''}`, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${this.apiToken}`,
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`Pull failed: ${response.status}`);
        }

        const result = await response.json();
        
        if (result.success && result.data) {
            // Apply changes locally via Livewire or direct API
            this.applyChanges(result.data);
        }

        return result;
    }

    applyChanges(data) {
        // Dispatch event for Livewire components to handle
        this.dispatchEvent('changes-received', { data });
        
        // Reload page if significant changes
        if (data.reload_required) {
            window.location.reload();
        }
    }

    queueChange(type, model, data) {
        this.pendingChanges.push({
            type,
            model,
            data,
            timestamp: new Date().toISOString(),
            device_id: this.getDeviceId()
        });
        
        localStorage.setItem('pending_sync_changes', JSON.stringify(this.pendingChanges));
        
        // Try to sync immediately if online
        if (this.isOnline && this.cloudUrl) {
            this.syncNow();
        }
    }

    getDeviceId() {
        let deviceId = localStorage.getItem('device_id');
        if (!deviceId) {
            deviceId = 'device_' + Math.random().toString(36).substr(2, 9);
            localStorage.setItem('device_id', deviceId);
        }
        return deviceId;
    }

    dispatchEvent(name, detail = {}) {
        window.dispatchEvent(new CustomEvent(`cloudsync:${name}`, { detail }));
    }

    showNotification(message, type = 'info') {
        // Dispatch for UI to handle
        this.dispatchEvent('notification', { message, type });
        
        // Also log to console
        console.log(`[CloudSync] ${type.toUpperCase()}: ${message}`);
    }

    getStatus() {
        return {
            configured: !!(this.cloudUrl && this.apiToken),
            online: this.isOnline,
            lastSync: this.lastSync,
            pendingChanges: this.pendingChanges.length,
            syncInProgress: this.syncInProgress
        };
    }
}

// Initialize global instance
window.CloudSync = new CloudSyncService();

// Export for module usage
export default CloudSyncService;
