#!/bin/bash
# ====================================================
# Production Deployment Script - SanghaCollegeOngtue
# ====================================================
# ວິທີໃຊ້:
#   1. ຄັ້ງທໍາອິດ: ຕັ້ງຄ່າ .env ໃນ server ກ່ອນ (cp .env.example .env ແລ້ວແກ້ຄ່າ)
#   2. ຮັນ: bash deploy.sh
#
# ໝາຍເຫດ: Vite assets (public/build) ຖືກ build ຢູ່ local ແລ້ວ commit ເຂົ້າ git —
# server ນີ້ບໍ່ຕ້ອງການ Node/npm. ຖ້າມີການແກ້ resources/js ຫຼື resources/css,
# ຕ້ອງ `npm run build` ຢູ່ local ແລ້ວ commit public/build ກ່ອນ deploy.
# ====================================================

set -e

BOLD='\033[1m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

log()  { echo -e "${GREEN}[+]${NC} $1"; }
warn() { echo -e "${YELLOW}[!]${NC} $1"; }
fail() { echo -e "${RED}[x]${NC} $1"; exit 1; }

log "${BOLD}=== SanghaCollegeOngtue Deployment ===${NC}"

# -------- ດຶງ code ຈາກ git --------
log "Pulling latest code from origin/main..."
git fetch --all
git reset --hard origin/main

# -------- ກວດ .env --------
if [ ! -f ".env" ]; then
    warn ".env not found. Copying from .env.example..."
    cp .env.example .env
    fail "Please fill in .env values then re-run deploy.sh"
fi

# -------- Composer --------
log "Installing PHP dependencies (no-dev)..."
composer install --no-dev --optimize-autoloader --no-interaction

# -------- Discover packages (ຕ້ອງມາກ່ອນ cache) --------
log "Discovering packages..."
php artisan package:discover --ansi
php artisan filament:upgrade

# -------- Key (ສ້າງຖ້າຍັງບໍ່ມີ) --------
if grep -q "^APP_KEY=$" .env; then
    log "Generating application key..."
    php artisan key:generate --force
fi

# -------- Storage directories --------
log "Ensuring storage directories exist..."
mkdir -p storage/framework/views
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/logs
mkdir -p storage/app/public
mkdir -p bootstrap/cache

# -------- Permissions (ຕ້ອງ fix ກ່ອນ artisan commands) --------
log "Setting permissions on storage and bootstrap/cache..."
chmod -R 777 storage bootstrap/cache

# -------- Storage link --------
# symlink() ອາດຖືກປິດຢູ່ host ນີ້ — ຖ້າແມ່ນແນວນັ້ນ ໃຫ້ຕັ້ງ PUBLIC_STORAGE_URL
# ໃນ .env ໃຫ້ໃຊ້ route /media ແທນ (ເບິ່ງ config/filesystems.php)
log "Attempting storage symlink (may be unsupported on this host)..."
php artisan storage:link 2>/dev/null || warn "storage:link failed/unsupported — set PUBLIC_STORAGE_URL in .env to use the /media route instead"

# -------- Clear stale cache ກ່ອນ rebuild --------
log "Clearing stale cache..."
php artisan optimize:clear

# -------- Migrate --------
log "Running database migrations..."
php artisan migrate --force

# -------- Build cache ໃໝ່ --------
log "Caching config and routes..."
php artisan config:cache
php artisan route:cache
# view:cache ບໍ່ລັນ — ຖ້າລັນໃນນາມ root/nongphou ຈະສ້າງໄຟລ໌ owner ບໍ່ຖືກຕ້ອງ
# ໃຫ້ web server compile views ເອງໃນ request ທຳອິດ

# -------- Fix permissions ອີກຄັ້ງ ຫຼັງ artisan --------
chmod -R 777 storage bootstrap/cache

log ""
log "${BOLD}Deployment complete! ✓${NC}"
