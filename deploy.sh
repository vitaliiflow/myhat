#!/bin/sh

# Theme folder name
THEME_NAME="theme-name"

# FTP/SFTP credentials and server details
FTP_SERVER="host"
FTP_USER="use"
FTP_PASS="pass"

# Remote theme directory on the server
REMOTE_THEME_DIR="/path/to/the/theme/folder/theme-name"

# Set USE_SFTP to true to use SFTP, false to use FTP
USE_SFTP=false

npm run zip

cd ..

rm -rf deploy-theme/

mkdir deploy-theme

unzip "${THEME_NAME}.zip" -d deploy-theme/

if [ "$USE_SFTP" = true ]; then
    # Use sftp for upload
    sftp -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null -o IdentityFile=/path/to/private/key ${FTP_USER}@${FTP_SERVER}:${REMOTE_THEME_DIR} <<EOF
    put -r deploy-theme/*
    bye
EOF
else
    # Use lftp for FTP upload
    lftp -c "open -u ${FTP_USER},${FTP_PASS} ${FTP_SERVER}; set ssl:verify-certificate no; mirror -R deploy-theme/ ${REMOTE_THEME_DIR}"
fi

echo "WordPress theme deployed successfully."
