#!/bin/bash
# Setup direktori storage untuk Hostinger

echo "=== SETUP STORAGE DIRECTORY ==="

# Path hosting Anda
STORAGE_PATH="/home/u203849739/domains/ptdads.co.id/public_html/logistik/storage"

echo "Creating directory: $STORAGE_PATH"

# Buat direktori
mkdir -p "$STORAGE_PATH/transaction-proofs"
mkdir -p "$STORAGE_PATH/documents"

# Set permission
chmod -R 755 "$STORAGE_PATH"

# Buat .htaccess untuk keamanan
cat > "$STORAGE_PATH/.htaccess" << 'EOF'
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} -f
    RewriteRule ^(.*)$ $1 [L]
</IfModule>

<FilesMatch "\.(jpg|jpeg|png|gif|pdf)$">
    Header set X-Content-Type-Options nosniff
</FilesMatch>

<FilesMatch "\.php$">
    Order Deny,Allow
    Deny from all
</FilesMatch>
EOF

echo "✓ Setup completed!"
echo "✓ Directory: $STORAGE_PATH"
echo "✓ Permissions: 755"
echo "✓ .htaccess: Created"

ls -la "$STORAGE_PATH"
