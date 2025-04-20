<?php
require_once 'db.php';

// Remove all authentication-related checks and session usage

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create_post'])) {
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        
        if (!empty($title) && !empty($content)) {
            $stmt = $pdo->prepare("INSERT INTO blog_posts (title, content) VALUES (?, ?)");
$stmt->execute([$title, $content]);
            $success = "Post created successfully!";
        }
    }
    elseif (isset($_POST['add_comment'])) {
        $post_id = (int)$_POST['post_id'];
        $content = trim($_POST['comment_content']);
        
        if (!empty($content)) {
            $stmt = $pdo->prepare("INSERT INTO post_comments (post_id, user_id, content) VALUES (?, 1, ?)");
            $stmt->execute([$post_id, $content]);
        }
    }
    elseif (isset($_POST['like_post'])) {
        $post_id = (int)$_POST['post_id'];
        
        // No user tracking for likes
        $stmt = $pdo->prepare("UPDATE blog_posts SET likes = likes + 1 WHERE id = ?");
        $stmt->execute([$post_id]);
    }
}

// Modified SQL query without user associations
$stmt = $pdo->query("
    SELECT 
        bp.*,
        COUNT(pc.id) as comment_count,
        bp.likes
    FROM blog_posts bp
    LEFT JOIN post_comments pc ON bp.id = pc.post_id
    GROUP BY bp.id
    ORDER BY bp.created_at DESC
");
$posts = $stmt->fetchAll();

// Get comments
foreach ($posts as &$post) {
    $stmt = $pdo->prepare("
        SELECT pc.* 
        FROM post_comments pc
        WHERE post_id = ?
        ORDER BY pc.created_at ASC
    ");
    $stmt->execute([$post['id']]);
    $post['comments'] = $stmt->fetchAll();
}
unset($post);
?>

<?php include 'header.php'; ?>

<div class="container mt-4">
    <!-- Always show post creation form -->
    <div class="card mb-4">
        <div class="card-body">
            <h4 class="card-title"> New Post</h4>
            <form method="POST">
                <div class="mb-3">
                    <input type="text" name="title" class="form-control" placeholder="Post Title" required>
                </div>
                <div class="mb-3">
                    <textarea name="content" class="form-control" rows="3" 
                              placeholder="Write your post..." required></textarea>
                </div>
                <button type="submit" name="create_post" class="btn btn-primary">Post</button>
            </form>
        </div>
    </div>

    <?php foreach ($posts as $post): ?>
        <div class="card mb-4">
            <div class="card-body">
                <h3 class="card-title"><?= htmlspecialchars($post['title']) ?></h3>
                <p class="text-muted small">
                    Posted on <?= date('M j, Y \a\t g:i a', strtotime($post['created_at'])) ?>
                </p>
                <p class="card-text"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                
                <div class="d-flex align-items-center gap-3 mb-3">
                    <form method="POST">
                        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                        <button type="submit" name="like_post" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-heart"></i> <?= $post['likes'] ?>
                        </button>
                    </form>
                    <span class="text-muted">
                        <i class="bi bi-chat"></i> <?= $post['comment_count'] ?> comments
                    </span>
                </div>

                <!-- Always show comment form -->
                <form method="POST" class="mb-3">
                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                    <div class="input-group">
                        <input type="text" name="comment_content" class="form-control" 
                               placeholder="Write a comment..." required>
                        <button type="submit" name="add_comment" class="btn btn-primary">
                            Comment
                        </button>
                    </div>
                </form>

                <div class="comments-section">
                    <?php foreach ($post['comments'] as $comment): ?>
                        <div class="card mb-2">
                            <div class="card-body py-2">
                                <p class="mb-0"><?= htmlspecialchars($comment['content']) ?></p>
                                <small class="text-muted">
                                    <?= date('M j, g:i a', strtotime($comment['created_at'])) ?>
                                </small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include 'footer.php'; ?>