<?php
final class Repository {
    public function __construct(private PDO $pdo) {}

    public function create(array $data): int {
        $sql = "INSERT INTO submissions (name,email,message,ip,user_agent) VALUES (:name,:email,:message,:ip,:ua)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':message' => $data['message'],
            ':ip' => ip_to_bin($data['ip']),
            ':ua' => mb_substr($data['ua'] ?? '', 0, 255),
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function findPage(int $page=1, int $size=10): array {
        $page = max(1,$page);
        $size = max(1,min(100,$size));
        $offset = ($page-1)*$size;
        $rows = $this->pdo->prepare("SELECT id,name,email,message,created_at FROM submissions ORDER BY created_at DESC LIMIT :lim OFFSET :off");
        $rows->bindValue(':lim', $size, PDO::PARAM_INT);
        $rows->bindValue(':off', $offset, PDO::PARAM_INT);
        $rows->execute();

        $total = (int)$this->pdo->query("SELECT COUNT(*) FROM submissions")->fetchColumn();
        return [
            'data' => $rows->fetchAll(),
            'page' => $page,
            'size' => $size,
            'total' => $total,
            'pages' => (int)ceil($total/$size),
        ];
    }

    public function tooFrequent(string $email, int $seconds=60): bool {
        $stmt = $this->pdo->prepare("SELECT 1 FROM submissions WHERE email=:email AND created_at >= (NOW() - INTERVAL :s SECOND) LIMIT 1");
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':s', $seconds, PDO::PARAM_INT);
        $stmt->execute();
        return (bool)$stmt->fetchColumn();
    }
}
