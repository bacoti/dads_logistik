#!/bin/bash

# Script Deploy Excel Export Feature ke Hostinger
# Usage: ./deploy_excel_feature.sh

echo "=== Deploy Excel Export Feature ke Hostinger ==="

# Konfigurasi server (sesuaikan dengan detail Hostinger Anda)
SERVER_HOST="your-server.hostinger.com"
SERVER_USER="your-username"
SERVER_PATH="/home/your-username/domains/yourdomain.com/public_html"

# Warna untuk output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}1. Membuat backup file yang akan diubah...${NC}"

# Daftar file yang perlu diupload
FILES_TO_UPLOAD=(
    "app/Exports/LossReportsExport.php"
    "app/Exports/MfoRequestsExport.php" 
    "app/Exports/ComprehensiveExport.php"
    "app/Exports/TransactionsExport.php"
    "app/Exports/MonthlyReportsExport.php"
    "app/Http/Controllers/Admin/DashboardController.php"
    "app/Http/Controllers/Admin/TransactionController.php"
    "app/Http/Controllers/Admin/MonthlyReportController.php"
    "app/Http/Controllers/Admin/LossReportController.php"
    "app/Http/Controllers/Admin/MfoRequestController.php"
    "routes/web.php"
    "resources/views/admin/transactions/index.blade.php"
)

echo -e "${YELLOW}2. Upload file ke server...${NC}"

# Upload setiap file
for file in "${FILES_TO_UPLOAD[@]}"; do
    echo -e "${GREEN}Uploading $file...${NC}"
    
    # Pastikan direktori ada di server
    REMOTE_DIR="$SERVER_PATH/$(dirname "$file")"
    ssh $SERVER_USER@$SERVER_HOST "mkdir -p $REMOTE_DIR"
    
    # Upload file
    scp "$file" $SERVER_USER@$SERVER_HOST:$SERVER_PATH/$file
    
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✅ $file uploaded successfully${NC}"
    else
        echo -e "${RED}❌ Failed to upload $file${NC}"
    fi
done

echo -e "${YELLOW}3. Menjalankan perintah di server...${NC}"

# Perintah yang akan dijalankan di server
ssh $SERVER_USER@$SERVER_HOST << 'EOF'
cd /home/your-username/domains/yourdomain.com/public_html

echo "Clearing cache..."
php artisan route:clear
php artisan config:clear  
php artisan cache:clear
php artisan view:clear

echo "Optimizing autoloader..."
composer dump-autoload --optimize

echo "Setting permissions..."
find app/Exports -type f -exec chmod 644 {} \;
find app/Http/Controllers -type f -exec chmod 644 {} \;

echo "Deployment completed!"
EOF

echo -e "${GREEN}=== Deploy selesai! ===${NC}"
echo -e "${YELLOW}Silakan test fitur export di:${NC}"
echo "- https://yourdomain.com/admin/transactions"
echo "- https://yourdomain.com/admin/monthly-reports" 
echo "- https://yourdomain.com/admin/loss-reports"
echo "- https://yourdomain.com/admin/mfo-requests"
