import $ from 'jquery';

class Search {
    constructor() {
        this.resultsDiv = $('#search-overlay__results')
        this.openButton = $('.js-search-trigger')
        this.closeButton = $('.search-overlay__close')
        this.searchOverlay = $('.search-overlay')
        this.searchField = $('#search-term')
        this.events();
        this.isOverlayOpen = false;
        this.isSpinnerVisible = false;
        this.previousValue;
        this.typingTimer;
    }
    events() {
        this.openButton.on('click', this.openOverlay.bind(this))
        this.closeButton.on('click', this.closeOverlay.bind(this))
        $(document).on('keydown', this.keyPress.bind(this))
        this.searchField.on('keyup', this.typeSearch.bind(this))
    }
    keyPress(e){
    
        if (e.keyCode == 83 && !this.isOverlayOpen && !$("input, textarea").is(':focus')) {
            this.openOverlay()
        }
        if (e.keyCode == 27 && this.isOverlayOpen) {
            this.closeOverlay()
        } 
    }

    typeSearch() {
        if(this.searchField.val() != this.previousValue) {
            clearTimeout(this.typingTimer);
            if(this.searchField.val()){
                if (!this.isSpinnerVisible){
                    this.resultsDiv.html('<div class="spinner-loader"></div>')
                    this.isSpinnerVisible = true
                }
                this.typingTimer = setTimeout(this.getResults.bind(this)
                , 1000)
            } else {
                this.resultsDiv.html('')
                this.isSpinnerVisible = false
            }
        }
        this.previousValue = this.searchField.val();
    }

    getResults() {
        $.getJSON(uni_data.root_url + '/wp-json/wp/v2/posts?search=' + this.searchField.val(), posts => {
            this.resultsDiv.html(`
                <h2>General Information</h2>
                ${posts.length ? '<ul class="link-list min-list">' : '<p>No Results</p>'}
                    ${posts.map(post => `<li><a href="${post.link}">${post.title.rendered}</a></li>`).join('')}
                ${posts.length ? '</ul>' : ''}
            `)
            this.isSpinnerVisible = false
        })
    }

    openOverlay() {
        this.searchOverlay.addClass('search-overlay--active')
        $('body').addClass('body-no-scroll')
        this.isOverlayOpen = false
    }
    closeOverlay() {
        this.searchOverlay.removeClass('search-overlay--active')
        $('body').removeClass('body-no-scroll')
        this.isOverlayOpen = false
    }
}

export default Search;