// Blog Post Management
export class BlogManager {
    constructor() {
        this.posts = [];
        this.currentFilter = 'all';
        this.modal = document.getElementById('post-modal');
        this.init();
    }

    async loadPosts() {
        try {
            const response = await fetch('data/posts.json');
            if (response.ok) {
                this.posts = await response.json();
            }
        } catch (error) {
            console.error('Error loading posts:', error);
            this.posts = this.getFallbackPosts();
        }
        this.distributePosts(this.posts);
    }

    getFallbackPosts() {
        // Your demo posts here
        return [];
    }

    distributePosts(posts) {
        const leftColumn = document.getElementById('column-left');
        const rightColumn = document.getElementById('column-right');
        
        if (!leftColumn || !rightColumn) return;
        
        leftColumn.innerHTML = '';
        rightColumn.innerHTML = '';
        
        if (posts.length === 0) {
            leftColumn.innerHTML = '<div class="empty-state"><p data-translate="noPosts">No posts found with this tag.</p></div>';
            return;
        }
        
        posts.forEach((post, index) => {
            const postElement = this.createPostElement(post);
            if (index % 2 === 0) {
                leftColumn.appendChild(postElement);
            } else {
                rightColumn.appendChild(postElement);
            }
        });
        
        // Update translations
        if (window.languageManager) {
            window.languageManager.updatePageLanguage();
        }
    }

    createPostElement(post) {
        const lang = window.getCurrentLanguage();
        const article = document.createElement('article');
        article.className = 'blog-post';
        article.dataset.tags = post.tags.join(',');
        article.dataset.postId = post.id;
        
        const formattedDate = new Date(post.date).toLocaleDateString(
            lang === 'pl' ? 'pl-PL' : 'en-US', 
            { year: 'numeric', month: 'long', day: 'numeric' }
        );
        
        article.innerHTML = `
            <div class="blog-post-header">
                <span class="blog-post-date">${formattedDate}</span>
            </div>
            <h3>${post.title[lang]}</h3>
            <p class="blog-post-excerpt">${post.excerpt[lang]}</p>
            <div class="blog-post-tags">
                ${post.tags.map(tag => `<span class="post-tag">${tag}</span>`).join('')}
            </div>
            <div class="blog-post-footer">
                <a href="#" class="read-more" data-translate="readMore">
                    Read More <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        `;
        
        article.addEventListener('click', (e) => {
            e.preventDefault();
            this.openPostModal(post);
        });
        
        return article;
    }

    openPostModal(post) {
        const lang = window.getCurrentLanguage();
        const modalTitle = document.getElementById('modal-post-title');
        const modalDate = document.getElementById('modal-post-date');
        const modalTags = document.getElementById('modal-post-tags');
        const modalContent = document.getElementById('modal-post-content');
        
        if (!this.modal || !modalTitle) return;
        
        const formattedDate = new Date(post.date).toLocaleDateString(
            lang === 'pl' ? 'pl-PL' : 'en-US',
            { year: 'numeric', month: 'long', day: 'numeric' }
        );
        
        modalTitle.textContent = post.title[lang];
        modalDate.textContent = formattedDate;
        modalTags.innerHTML = post.tags.map(tag => `<span class="post-tag">${tag}</span>`).join('');
        modalContent.innerHTML = post.content[lang];
        
        this.modal.classList.add('active');
        this.modal.dataset.currentPost = post.id;
        document.body.style.overflow = 'hidden';
    }

    closePostModal() {
        if (!this.modal) return;
        this.modal.classList.remove('active');
        delete this.modal.dataset.currentPost;
        document.body.style.overflow = '';
    }

    filterPosts(tag) {
        this.currentFilter = tag;
        
        document.querySelectorAll('.tag-btn').forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.tag === tag) {
                btn.classList.add('active');
            }
        });
        
        const filteredPosts = tag === 'all' 
            ? this.posts 
            : this.posts.filter(post => post.tags.includes(tag));
        
        this.distributePosts(filteredPosts);
    }

    init() {
        // Load posts
        this.loadPosts();
        
        // Tag filter buttons
        document.querySelectorAll('.tag-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                this.filterPosts(btn.dataset.tag);
            });
        });
        
        // Modal close button
        const closeBtn = document.getElementById('modal-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => this.closePostModal());
        }
        
        // Close on outside click
        if (this.modal) {
            this.modal.addEventListener('click', (e) => {
                if (e.target === this.modal) {
                    this.closePostModal();
                }
            });
        }
        
        // Close on Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closePostModal();
            }
        });
        
        // Listen for language changes
        window.addEventListener('languageChanged', () => {
            this.filterPosts(this.currentFilter);
            
            // Update modal if open
            if (this.modal && this.modal.classList.contains('active')) {
                const currentPostId = this.modal.dataset.currentPost;
                if (currentPostId) {
                    const post = this.posts.find(p => p.id === parseInt(currentPostId));
                    if (post) {
                        this.openPostModal(post);
                    }
                }
            }
        });
    }
}