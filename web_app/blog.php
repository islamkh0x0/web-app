<?php
require_once 'config.php';

// Get the latest 10 posts
$query = "SELECT p.content, p.created_at, u.username 
        FROM posts p 
        JOIN users u ON p.user_id = u.id 
        ORDER BY p.created_at DESC 
        LIMIT 10";
$posts_result = mysqli_query($conn, $query);
$posts = [];
while ($post = mysqli_fetch_assoc($posts_result)) {
    $posts[] = $post;
}
?>
<?php include 'header.php'; ?>
<div class="container">
    <h2>Blog - Latest Posts</h2>
    
    <!-- Search Form -->
    <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Search posts..." onkeyup="searchPosts()">
        <button onclick="searchPosts()">Search</button>
    </div>

    <!-- Posts Display -->
    <div id="postsContainer">
        <?php
        if (!empty($posts)) {
            foreach ($posts as $post) {
                echo "<div class='post'>";
                echo "<p><strong>" . $post['username'] . "</strong>: " . $post['content'] . "</p>";
                echo "<small>Posted on: " . $post['created_at'] . "</small>";
                echo "</div><hr>";
            }
        } else {
            echo "<p>No posts available.</p>";
        }
        ?>
    </div>
</div>

<script>
    //searching posts
    function searchPosts() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const postsContainer = document.getElementById('postsContainer');
        const posts = <?php echo json_encode($posts); ?>;

        // Clear current posts
        postsContainer.innerHTML = '';

        // Filter posts
        const filteredPosts = posts.filter(post => 
            post.content.toLowerCase().includes(searchTerm) || 
            post.username.toLowerCase().includes(searchTerm)
        );

        // Display filtered posts
        if (filteredPosts.length > 0) {
            filteredPosts.forEach(post => {
                postsContainer.innerHTML += 
                    `<div class='post'>
                        <p><strong>${post.username}</strong>: ${post.content}</p>
                        <small>Posted on: ${post.created_at}</small>
                    </div><hr>`;
            });
        } else {
            postsContainer.innerHTML = '<p>No matching posts found.</p>';
        }
    }
</script>
</body>
</html>