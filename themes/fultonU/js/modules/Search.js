import $ from 'jquery';

class Search {
    constructor() {
        this.addSearchHTML();
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
        console.log(e.keyCode)
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
                , 750)
            } else {
                this.resultsDiv.html('')
                this.isSpinnerVisible = false
            }
        }
        this.previousValue = this.searchField.val();
    }

    getResults() {
        $.getJSON(uni_data.root_url + '/wp-json/uni/v1/search?term=' + this.searchField.val(), results => {
            this.resultsDiv.html(`
            <div class="row">
                <div class="one-third">
                    <h2 class="search-overlay__section-title">General Information</h2>
                    ${results.general.length ? '<ul class="link-list min-list">' : '<p>No Results</p>'}
                        ${results.general.map(post => `<li><a href="${post.url}">${post.title}</a> ${post.postType == 'post' ? `by ${post.authorName}` : ''} </li>`).join('')}
                    ${results.general.length ? '</ul>' : ''}
                </div>
                <div class="one-third">
                    <h2 class="search-overlay__section-title">Programs</h2>
                    ${results.programs.length ? '<ul class="link-list min-list">' : `<p>No Programs Match that Search <a href="${uni_data.root_url}/programs">View All Programs</a></p>`}
                        ${results.programs.map(post => `<li><a href="${post.url}">${post.name}</a></li>`).join('')}
                    ${results.programs.length ? '</ul>' : ''}
                    <h2 class="search-overlay__section-title">Instructors</h2>
                    ${results.instructors.length ? '<ul class="professor-cards">' : `<p>No Instructors Match that Search</p>`}
                        ${results.instructors.map(post => `
                        <li class="professor-card__list-item">
                            <a class="professor-card" href="${post.url}">
                                <img class="professor-card__image" src="${post.img}">
                                <span class="professor-card__name">${post.name}</span>
                            </a>
                        </li>
                        `).join('')}
                    ${results.instructors.length ? '</ul>' : ''}
                </div>
                <div class="one-third">
                    <h2 class="search-overlay__section-title">Campuses</h2>
                    ${results.campuses.length ? '<ul class="link-list min-list">' : `<p>No Campuses Match that Search <a href="${uni_data.root_url}/campuses">View All Programs</a></p>`}
                        ${results.campuses.map(post => `<li><a href="${post.url}">${post.name}</a></li>`).join('')}
                    ${results.campuses.length ? '</ul>' : ''}
                    <h2 class="search-overlay__section-title">Events</h2>
                    ${results.events.length ? '' : `<p>No Events Match that Search <a href="${uni_data.root_url}/events">View All Events</a></p>`}
                        ${results.events.map(post => `
                        <div class="event-summary">
                            <a class="event-summary__date t-center" href="${post.url}">
                                <span class="event-summary__month">${post.month}</span>
                                <span class="event-summary__day">${post.day}</span>  
                            </a>
                            <div class="event-summary__content">
                                <h5 class="event-summary__title headline headline--tiny"><a href="${post.url}">${post.name}</a></h5>
                                <p>${post.desc}<a href="${post.url}>class="nu gray">Learn more</a></p>
                            </div>
                        </div>
                        `).join('')}
                </div>
            </div>
            `)
            this.isSpinnerVisible = false
        })

        //Non-custom REST API search
        // $.when(
        //     $.getJSON(uni_data.root_url + '/wp-json/wp/v2/posts?search=' + this.searchField.val()), 
        //     $.getJSON(uni_data.root_url + '/wp-json/wp/v2/pages?search=' + this.searchField.val())
        //     ).then((posts, pages) => {
        //     const combinedResults = posts[0].concat(pages[0])
        //         this.resultsDiv.html(`
        //         <h2>General Information</h2>
        //         ${combinedResults.length ? '<ul class="link-list min-list">' : '<p>No Results</p>'}
        //             ${combinedResults.map(post => `<li><a href="${post.link}">${post.title.rendered}</a> ${post.type == 'post' ? `by ${post.authorName}` : ''} </li>`).join('')}
        //         ${combinedResults.length ? '</ul>' : ''}
        //     `)
        //     this.isSpinnerVisible = false
        // }, () => {
        //     this.resultsDiv.html('<p>Unexpected Error, Please Try Again Later</p>')
        // })
    }

    openOverlay() {
        this.searchOverlay.addClass('search-overlay--active')
        $('body').addClass('body-no-scroll')
        this.searchField.val('')
        setTimeout(() => this.searchField.focus(), 301)
        this.isOverlayOpen = true
    }
    closeOverlay() {
        this.searchOverlay.removeClass('search-overlay--active')
        $('body').removeClass('body-no-scroll')
        this.isOverlayOpen = false
    }
    addSearchHTML() {
        $('body').append(`
        <div class="search-overlay">
        <div class="search-overlay__top">
          <div class="container">
            <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
              <input type="text" class="search-term" placeholder="What are you looking for?" id="search-term">
            <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
            </div>
        </div>
        <div class="container">
          <div id="search-overlay__results"></div>
        </div>
      </div>
        `)
    }
}

export default Search;