// Force reload the page to ensure script functions work properly
window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        // Page is being loaded from cache
        window.location.reload();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    fetch('user_status.json?t=' + new Date().getTime())
        .then(response => response.json())
        .then(data => {
            const profileLink = document.getElementById('profile-link');
            if (data.logged_in) {
                profileLink.href = "profile.html";
                document.getElementById('user-greeting1').innerHTML = `<p>Hello, ${data.user.username}!</p>`;
            } else {
                profileLink.href = "login.html";
                document.getElementById('user-greeting1').innerHTML = `<p>Welcome, Guest!</p>`;
            }
        })
        .catch(error => console.error('Error fetching user status:', error));
});

document.addEventListener('DOMContentLoaded', function() {
    fetch('user_status.json?t=' + new Date().getTime())
        .then(response => response.json())
        .then(data => {
            const exploreLink = document.getElementById('explore-link');
            if (data.logged_in) {
                exploreLink.href = "explore.html";
            } else {
                exploreLink.href = "login.html";
            }
        })
        .catch(error => console.error('Error fetching user status:', error));
});


document.addEventListener('DOMContentLoaded', function() {
    if (document.body.contains(document.getElementById('user-greeting2'))) {
        fetch('user_status.json?t=' + new Date().getTime())
            .then(response => response.json())
            .then(data => {
                if (data.logged_in) {
                    document.getElementById('user-greeting2').innerHTML = `<p>Hello, ${data.user.username}!</p>`;
                    fetchBlogs(data.user.id);
                } else {
                    window.location.href = 'login.html';
                }
            });
    }
});

function fetchBlogs(userId) {
    fetch(`profile.php?user_id=${userId}`)
        .then(response => response.json())
        .then(data => {
            const blogContainer = document.getElementById('blog-container');
            if (data.error) {
                blogContainer.innerHTML = `<p>${data.error}</p>`;
            } else if (data.blogs.length === 0) {
                blogContainer.innerHTML = '<p>No blogs found.</p>';
            } else {
                data.blogs.forEach(blog => {
                    const blogBox = document.createElement('div');
                    blogBox.classList.add('blog-box');

                    const blogImage = document.createElement('img');
                    blogImage.src = blog.images[0]; // First image of the blog
                    blogBox.appendChild(blogImage);

                    const blogDate = document.createElement('p');
                    blogDate.textContent = `Date: ${blog.date}`;
                    blogBox.appendChild(blogDate);

                    const deleteButton = document.createElement('button');
                    deleteButton.textContent = 'Delete';
                    deleteButton.classList.add('delete-button');
                    deleteButton.onclick = () => deleteBlog(blog.blog_id);
                    blogBox.appendChild(deleteButton);

                    blogBox.addEventListener('click', () => {
                        window.location.href = `view_blog.html?blog_id=${blog.blog_id}`;
                    });

                    blogContainer.appendChild(blogBox);
                });
            }
        })
        .catch(error => {
            const blogContainer = document.getElementById('blog-container');
            blogContainer.innerHTML = `<p>Error loading blogs: ${error.message}</p>`;
        });
}

function deleteBlog(blogId) {
    if (confirm('Are you sure you want to delete this blog?')) {
        fetch(`delete_blog.php?blog_id=${blogId}`, { method: 'DELETE' })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Blog deleted successfully.');
                    window.location.reload();
                } else {
                    alert('Error deleting blog: ' + data.error);
                }
            })
            .catch(error => {
                alert('Error deleting blog: ' + error.message);
            });
    }
}


function logout() {
    fetch('logout.php')
        .then(response => response.json())
        .then(data => {
            if (data.logged_out) {
                window.location.href = 'index.html';
            } else {
                alert('Logout failed');
            }
        });
}

document.getElementById('signup-form').addEventListener('submit', function(event) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    if (password !== confirmPassword) {
        alert('Passwords do not match.');
        event.preventDefault();
    }
});

// Password confirmation check, SIGNUP
//document.querySelector('form').addEventListener('submit', function(event) {
//    const password = document.getElementById('password').value;
//    const confirmPassword = document.getElementById('confirm_password').value;
//    if (password !== confirmPassword) {
//        alert('Passwords do not match.');
//       event.preventDefault();
//    }
//});

// view blog
