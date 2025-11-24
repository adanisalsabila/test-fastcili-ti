$(document).ready(function() {
    // Cache menu items excluding those with icons
    const menuItems = [];
    $('.sidebar .nav li a').each(function() {
        const $this = $(this);
        if ($this.find('i').length === 0 && !$this.hasClass('bg-danger')) {
            const text = $this.find('p, .sidebar-normal').text().trim();
            const href = $this.attr('href');
            if (text && href) {
                menuItems.push({
                    text: text,
                    href: href
                });
            }
        }
    });

    // Search functionality for both search forms
    $('.navbar-search-form input').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        const resultsContainer = $(this).closest('.navbar-search-form').find('.search-results');
        
        if (searchTerm.length < 2) {
            resultsContainer.addClass('d-none').empty();
            return;
        }
        
        const filteredItems = menuItems.filter(item => 
            item.text.toLowerCase().includes(searchTerm)
        );
        
        if (filteredItems.length > 0) {
            resultsContainer.removeClass('d-none').empty();
            filteredItems.forEach(item => {
                resultsContainer.append(
                    `<a href="${item.href}">${item.text}</a>`
                );
            });
        } else {
            resultsContainer.removeClass('d-none').html(
                '<div class="p-2 text-muted">Tidak ditemukan</div>'
            );
        }
    });

    // Hide results when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.navbar-search-form').length) {
            $('.search-results').addClass('d-none');
        }
    });

    // Sidebar toggle functionality
    $('.sidebar-toggler, .navbar-brand').on('click', function(e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-mini');
        $(window).trigger('resize');
    });
});