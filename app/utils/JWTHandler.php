<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

class JWTHandler {
    private $secretKey;
    private $algorithm = 'HS256';
    private $issuer = "webbanhang";
    private $audience = "webbanhang_user";
    private $expireAfter = 3600; // 1 hour

    public function __construct() {
        $this->secretKey = 'your-secret-key-at-least-32-chars'; // Thay bằng key mạnh
    }

    public function encode(array $data): string {
        $issuedAt = time();
        $expire = $issuedAt + $this->expireAfter;
        
        $payload = [
            'iss' => $this->issuer,
            'aud' => $this->audience,
            'iat' => $issuedAt,
            'exp' => $expire,
            'data' => $data
        ];

        return JWT::encode($payload, $this->secretKey, $this->algorithm);
    }

    public function decode(string $jwt): ?array {
        try {
            $decoded = JWT::decode($jwt, new Key($this->secretKey, $this->algorithm));
            return (array) $decoded;
        } catch (ExpiredException $e) {
            throw new Exception('Token đã hết hạn');
        } catch (SignatureInvalidException $e) {
            throw new Exception('Chữ ký token không hợp lệ');
        } catch (Exception $e) {
            throw new Exception('Token không hợp lệ: ' . $e->getMessage());
        }
    }

    public function validateToken(string $token): bool {
        try {
            $decoded = $this->decode($token);
            return isset($decoded['data']);
        } catch (Exception $e) {
            return false;
        }
    }
}