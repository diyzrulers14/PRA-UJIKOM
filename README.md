## 🚀 Cara Setup Project di WSL / Linux

### Prasyarat
- PHP & Composer
- Git

### Langkah Installation

1. **Update & Install PHP + Dependencies:**
   ```bash
   sudo apt update && sudo apt install -y php-cli php-mbstring php-xml php-curl php-zip unzip git

   Install Composer di WSL: curl -sS [https://getcomposer.org/installer](https://getcomposer.org/installer) | php sudo mv composer.phar /usr/local/bin/composer
   Clone Repository GitHub: cd ~
     git clone [https://github.com/diyzrulers14/PRA-UJIKOM.git](https://github.com/diyzrulers14/PRA-UJIKOM.git)
     cd PRA-UJIKOM/Backend
   Install Library Laravel (Composer): composer install
   Setup File Environment & Key: cp .env.example .env
     php artisan key:generate
   Jalankan Server Laravel: php artisan serve
