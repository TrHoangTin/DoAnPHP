<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

class JWTHandler {
    private $secret_key;
    private $algorithm = 'HS256';
    private $issuer = "webbanhang";
    private $audience = "webbanhang_user";
    private $expire_after = 3600; // 1 hour

    public function __construct() {
        $this->secretKey = 'your-secret-key-at-least-32-chars'; // Thay bằng key mạnh
        $this->algorithm = 'HS256';
        $this->tokenExpireTime = 3600; // 1 giờ
    }
    public function encode(array $data): string {
        $issued_at = time();
        $expire = $issued_at + $this->expire_after;
        
        $payload = [
            'iss' => $this->issuer,
            'aud' => $this->audience,
            'iat' => $issued_at,
            'exp' => $expire,
            'data' => $data
        ];

        return JWT::encode($payload, $this->secret_key, $this->algorithm);
    }

    public function decode(string $jwt): ?array {
        try {
            $decoded = JWT::decode($token, $this->secretKey, [$this->algorithm]);
            return (array) $decoded;
        } catch (Firebase\JWT\ExpiredException $e) {
            // Token hết hạn
            throw new Exception('Token đã hết hạn');
        } catch (Exception $e) {
            // Các lỗi khác
            throw new Exception('Token không hợp lệ');
        }
    }

    public function validateToken(string $token): bool {
        $decoded = $this->decode($token);
        if (!$decoded) return false;
        
        // Additional validation checks
        $now = time();
        if ($decoded['exp'] < $now) return false;
        
        return true;
    }
}