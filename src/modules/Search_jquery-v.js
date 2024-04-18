// JS to control behaviour of live search results using search icon on site

// Import jquery to use in class module - ensures it gets added with import into index.js
import $ from "jquery";

class Search {
  // 1. describe and create/initiate our object
  constructor() {
    this.addSearchHTML();
    // Selecting element using jquery - same as document.querySelector
    this.openButton = $(".js-search-trigger");
    this.closeButton = $(".search-overlay__close");
    this.searchOverlay = $(".search-overlay");
    this.searchField = $("#search-term");
    this.resultsDiv = $("#search-overlay__results");
    this.isOverlayOpen = false; // Used to monitor state of overlay if it's open or not - quicker to check this than checking the DOM to see if an element has a class attached to it.
    this.isSpinnerVisible = false;
    this.typingTimer;
    this.previousValue;

    // Calls events method on page load to set up event handlers
    this.events();
  }

  // 2. events - setting up event listeners to fire callback functions
  events() {
    // adding event in jquery - same as element.addEventListener('click', functionName);
    this.openButton.on("click", this.openOverlay.bind(this));
    this.closeButton.on("click", this.closeOverlay.bind(this));
    $(document).on("keydown", this.keyPressDispatcher.bind(this));
    this.searchField.on("keyup", this.typingLogic.bind(this)); // Use keyup event for search field so that it can store value entered in input field to variable in time (will fire after key button has been lifted)
  }

  // 3. methods
  openOverlay() {
    this.searchOverlay.addClass("search-overlay--active");
    $("body").addClass("body-no-scroll");
    this.isOverlayOpen = true;
    this.searchField.val("");
    setTimeout(() => this.searchField.trigger("focus"), 301);
  }

  closeOverlay() {
    this.searchOverlay.removeClass("search-overlay--active");
    $("body").removeClass("body-no-scroll");
    this.isOverlayOpen = false;
  }

  keyPressDispatcher(e) {
    if (
      e.keyCode === 83 &&
      !this.isOverlayOpen &&
      !$("input, textarea").is(":focus")
    )
      this.openOverlay();
    if (e.keyCode === 27 && this.isOverlayOpen) this.closeOverlay();
  }

  getResults() {
    // Running an HTTP request using jquery's built in method
    $.getJSON(
      `${
        // Grab variable created in functions.php file will add the site domain URL to page so below endpoint will work in both local hosted sites and live sites
        universityData.root_url
      }/wp-json/university/v1/search?term=${this.searchField.val()}`,
      (results) => {
        this.resultsDiv.html(`
        <div class="row">
          <div class="one-third">
            <h2 class="search-overlay__section-title">General Information</h2>
            ${
              results.generalInfo.length
                ? '<ul class="link-list min-list">'
                : "<p>No general matches that search</p>"
            }
            ${results.generalInfo
              .map(
                (item) => `
              <li>
                <a href="${item.url}">${item.title}</a> ${
                  item.postType === "post" ? `by ${item.authorName}` : ""
                }
              </li>`
              )
              .join("")}
            ${results.generalInfo.length ? "</ul>" : ""}
          </div>
          <div class="one-third">
            <h2 class="search-overlay__section-title">Programs</h2>
            ${
              results.programs.length
                ? '<ul class="link-list min-list">'
                : `<p>No programs match that search. <a href="${universityData.root_url}/programs">View all programs</a></p>`
            }
            ${results.programs
              .map(
                (item) => `
              <li>
                <a href="${item.url}">${item.title}</a>
              </li>`
              )
              .join("")}
            ${results.programs.length ? "</ul>" : ""}

            <h2 class="search-overlay__section-title">Professors</h2>
            ${
              results.professors.length
                ? '<ul class="professor-cards">'
                : `<p>No professors match that search.</p>`
            }
            ${results.professors
              .map(
                (item) => `
                <li class="professor-card__list-item">
                  <a class="professor-card" href="${item.url}">
                    <img class="professor-card__image" src="${item.image}" alt="">
                    <span class="professor-card__name">${item.title}</span>
                  </a>
                </li>
              `
              )
              .join("")}
            ${results.professors.length ? "</ul>" : ""}
          </div>
          <div class="one-third">
            <h2 class="search-overlay__section-title">Campuses</h2>
            ${
              results.campuses.length
                ? '<ul class="link-list min-list">'
                : `<p>No campuses match that search. <a href="${universityData.root_url}/campuses">View all campuses</a></p>`
            }
            ${results.campuses
              .map(
                (item) => `
              <li>
                <a href="${item.url}">${item.title}</a>
              </li>`
              )
              .join("")}
            ${results.campuses.length ? "</ul>" : ""}

            <h2 class="search-overlay__section-title">Events</h2>
            ${
              results.events.length
                ? ""
                : `<p>No events match that search. <a href="${universityData.root_url}/events">View all events</a></p>`
            }
            ${results.events
              .map(
                (item) => `
                <div class="event-summary">
                  <a class="event-summary__date t-center" href="${item.url}">
                    <span class="event-summary__month">
                      ${item.month}
                    </span>
                    <span class="event-summary__day">
                    ${item.day}
                    </span>
                  </a>
                  <div class="event-summary__content">
                    <h5 class="event-summary__title headline headline--tiny">
                      <a href="${item.url}">${item.title}</a>
                    </h5>
                    <p>
                      ${item.description}
                      <a href="${item.url}" class="nu gray">Learn more</a>
                    </p>
                  </div>
                </div>
              `
              )
              .join("")}
          </div>
        </div>
      `);
        this.isSpinnerVisible = false;
      }
    );

    // Code no longer needed - using custom endpoint from search-route.php file which pulls all post types in one
    /*
    $.when(
      $.getJSON(
        `${
          // Grab variable created in functions.php file will add the site domain URL to page so below endpoint will work in both local hosted sites and live sites
          universityData.root_url
        }/wp-json/wp/v2/posts?search=${this.searchField.val()}`
      ),
      $.getJSON(
        `${
          universityData.root_url
        }/wp-json/wp/v2/pages?search=${this.searchField.val()}`
      )
    ).then(
      (posts, pages) => {
        // use arrow function to allow this keyword to target class in callback function
        const combineResults = posts[0].concat(pages[0]);
        this.resultsDiv.html(`
        <h2 class="search-overlay__section-title">General Information</h2>
        ${
          combineResults.length
            ? '<ul class="link-list min-list">'
            : "<p>No general matches that search</p>"
        }
        ${combineResults
          .map(
            (item) => `
          <li>
            <a href="${item.link}">${item.title.rendered}</a> ${
              item.type === "post" ? `by ${item.authorName}` : ""
            }
          </li>`
          )
          .join("")}
        ${combineResults.length ? "</ul>" : ""}
        `);
        this.isSpinnerVisible = false;
      },
      () => {
        // Function which fires should there be any issues with above promises fulfilling
        this.resultsDiv.html("<p>Unexpected error, please try again.</p>");
      }
    );
    */
  }

  typingLogic() {
    if (this.searchField.val() === this.previousValue) return; // checks if value in input field at time of execution is the same as previous value searched - will exit out of method and not do anything.
    // Debouncing on input field won't fire on every key press straight away - will wait 2 seconds between key presses before firing
    clearTimeout(this.typingTimer);
    if (this.searchField.val()) {
      if (!this.isSpinnerVisible) {
        // only adds a loading spinner whilst search is running will reset after geResults method is finally fired after timeout
        this.resultsDiv.html('<div class="spinner-loader"></div>');
        this.isSpinnerVisible = true;
      }
      this.typingTimer = setTimeout(this.getResults.bind(this), 750);
    } else {
      this.resultsDiv.html("");
      this.isSpinnerVisible = false;
    }

    this.previousValue = this.searchField.val();
  }

  // method to add HTML for search field on site
  addSearchHTML() {
    // Overlay element for when user opens search on site
    $("body").append(`
      <div class="search-overlay">
        <div class="search-overlay__top">
          <div class="container">
            <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
            <input type="text" class="search-term" placeholder="What are you looking for?" id="search-term" autocomplete="off" />
            <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
          </div>
        </div>

        <div class="container">
          <div id="search-overlay__results"></div>
        </div>
      </div>
    `);
  }
}

export default Search;
