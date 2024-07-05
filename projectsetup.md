# Proje Kurulumu

Bu döküman, projenizi indirip çalıştırmak isteyen kişilere adım adım rehberlik edecektir.

## Gereksinimler

- [Docker](https://www.docker.com/get-started) (Kurulu olmalı)
- [Docker Compose](https://docs.docker.com/compose/install/) (Kurulu olmalı)
- [Composer](https://getcomposer.org/) (Kurulu olmalı)
- PHP 7.4 veya daha yeni bir sürüm (Kurulu olmalı)
- Node.js ve npm (Opsiyonel)
- Sanctum kurulu olmalı 

## Kurulum Adımları

### 1. Projeyi Klonlayın
 Terminali açın ve terminale bu komutları girin.

git clone https://github.com/selinhurel/kle-api.git
cd kle-api

### 2. Composer Bağımlılıklarını Kurun

composer install

### 3. Projeyi VS Code üzerinden açın 

### 4. Bir .env dosyası oluşturun ve .env.example dosyasındaki ayarlamaları oraya kopyalayın.


### 5. Sanctumu Kurun 

composer require laravel/sanctum

### 6.Sanctum yapılandırması için aşağıdaki komutları çalıştırın: 

php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate


### 7. Node.js Bağımlılıklarını Kurun 

npm install

### 8. Laravel UI ile kullanılacak ön yüz paketi (Opsiyonel)

composer require laravel/ui
php artisan ui bootstrap

### 9. Laravel Projesini Kurun ve Laravel Migrationları yükleyin 

php artisan migrate --seed #(Eger işe yaramazsa Docker'ı başlattıktan sonraki adımı uygulayın)
php artisan key:generate

### 10. Laravel servisini başlatmak için:


php artisan serve

### 11. Docker ve DockerCompose kurulumunu yaptıktan sonra docker-compose.yml ve Dockerfile ayarlamalarını yapın.

### 12. Docker Container'larını Başlatın (Aynı zamanda frontendin de)

docker-compose up -d


### 13. Migration yüklemek için bir diğer seçenek:

docker compose exec api bash

php artisan migrate



### 14. Projeyi Çalıştırın
http://localhost:(frontend'de env dosyanızda yazan portunuz) adresine gidin.

### api dosyasıyla ilgili kontrolleri postman üzerinden gerçekleştirebilirsiniz

