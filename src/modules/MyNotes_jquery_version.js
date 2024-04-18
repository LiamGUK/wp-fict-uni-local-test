import $ from "jquery";

class MyNotes {
  constructor() {
    this.events();
  }

  events() {
    // Target ul element and add class as 2nd argument in on method for event bubbling (target elements that won't exist until future event)
    $("#my-notes").on("click", ".delete-note", this.deleteNote);
    $("#my-notes").on("click", ".edit-note", this.editNote.bind(this));
    $("#my-notes").on("click", ".update-note", this.updateNote.bind(this));
    $(".submit-note").on("click", this.createNote.bind(this));
  }

  // Custom methods here...
  editNote(e) {
    // use event object to determine which button was clicked and to look at nearest li parent (will hold the id value set via page-my-notes.php)
    const thisNote = $(e.target).parents("li");

    if (thisNote.data("state") === "editable") {
      // make read only
      this.makeNoteReadOnly(thisNote);
    } else {
      // make editable
      this.makeNoteEditable(thisNote);
    }
  }

  makeNoteEditable(thisNote) {
    thisNote
      .find(".note-title-field, .note-body-field")
      .removeAttr("readonly")
      .addClass("note-active-field");

    thisNote.find(".update-note").addClass("update-note--visible");

    thisNote
      .find(".edit-note")
      .html('<i class="fa fa-times" aria-hidden="true"></i> Cancel');

    thisNote.data("state", "editable");
  }

  makeNoteReadOnly(thisNote) {
    thisNote
      .find(".note-title-field, .note-body-field")
      .attr("readonly", "readonly") // attr method takes 2 arguments - 1) name of attribute 2) value of attribute
      .removeClass("note-active-field");

    thisNote.find(".update-note").removeClass("update-note--visible");

    thisNote
      .find(".edit-note")
      .html('<i class="fa fa-pencil" aria-hidden="true"></i> Edit');

    thisNote.data("state", "cancel");
  }

  deleteNote(e) {
    // use event object to determine which button was clicked and to look at nearest li parent (will hold the id value set via page-my-notes.php)
    const thisNote = $(e.target).parents("li");

    // Use jquery method ajax to determine the type of HTTP request you want to make
    $.ajax({
      // Add beforeSend key to let request know you want to add a requestHeader to the request
      beforeSend: (xhr) => {
        // use setRequestHeader to create new header for request and add in X-WP-NONCE with value of nonce value set up in functions.php under wp_localize_script hook. Will allow permission
        xhr.setRequestHeader("X-WP-NONCE", universityData.nonce);
      },
      url:
        universityData.root_url + "/wp-json/wp/v2/note/" + thisNote.data("id"), // root_url set in functions.php file via wp_localize_script hook - stores the site domain to page in script tags under variable name universityData
      type: "DELETE",
      success: (response) => {
        // if request is successful will call function assigned to success key
        thisNote.slideUp(); // slideUp jquery hides element with slide up animation

        if (response.userNoteCount < 5) {
          $(".note-limit-message").removeClass("active");
        }
        console.log("Congrats");
        console.log(response);
      },
      error: (error) => {
        // if request fails will call function assigned to error key
        console.log("ERROR");
        console.log(error);
      },
    });
  }

  updateNote(e) {
    // use event object to determine which button was clicked and to look at nearest li parent (will hold the id value set via page-my-notes.php)
    const thisNote = $(e.target).parents("li");

    const ourUpdatedPost = {
      // When updating data on WP REST API it looks for specific keys in object
      title: thisNote.find(".note-title-field").val(), // title key will update title value for post
      content: thisNote.find(".note-body-field").val(), // content key will be for updating content value for post
    };

    // Use jquery method ajax to determine the type of HTTP request you want to make
    $.ajax({
      // Add beforeSend key to let request know you want to add a requestHeader to the request
      beforeSend: (xhr) => {
        // use setRequestHeader to create new header for request and add in X-WP-NONCE with value of nonce value set up in functions.php under wp_localize_script hook. Will allow permission
        xhr.setRequestHeader("X-WP-NONCE", universityData.nonce);
      },
      url:
        universityData.root_url + "/wp-json/wp/v2/note/" + thisNote.data("id"), // root_url set in functions.php file via wp_localize_script hook - stores the site domain to page in script tags under variable name universityData
      type: "POST",
      data: ourUpdatedPost,
      success: (response) => {
        // if request is successful will call function assigned to success key
        this.makeNoteReadOnly(thisNote);
        console.log(response);
      },
      error: (error) => {
        // if request fails will call function assigned to error key
        console.log("ERROR");
        console.log(error);
      },
    });
  }

  createNote() {
    const ourNewPost = {
      // When updating data on WP REST API it looks for specific keys in object
      title: $(".new-note-title").val(), // title key will update title value for post
      content: $(".new-note-body").val(), // content key will be for updating content value for post
      status: "publish", // Add status key to set post to be published in POST request
    };

    // Use jquery method ajax to determine the type of HTTP request you want to make
    $.ajax({
      // Add beforeSend key to let request know you want to add a requestHeader to the request
      beforeSend: (xhr) => {
        // use setRequestHeader to create new header for request and add in X-WP-NONCE with value of nonce value set up in functions.php under wp_localize_script hook. Will allow permission
        xhr.setRequestHeader("X-WP-NONCE", universityData.nonce);
      },
      url: universityData.root_url + "/wp-json/wp/v2/note/", // root_url set in functions.php file via wp_localize_script hook - stores the site domain to page in script tags under variable name universityData
      type: "POST",
      data: ourNewPost,
      success: (response) => {
        // if request is successful will call function assigned to success key
        $(".new-note-title, .new-note-body").val("");
        $(`
          <li data-id="${response.id}">
            <input readonly class="note-title-field" value="${response.title.raw}">
            <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
            <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
             <textarea readonly class="note-body-field">
               ${response.content.raw}
            </textarea>
            <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</span>
          </li>
        `)
          .prependTo("#my-notes")
          .hide()
          .slideDown();
        console.log(response);
      },
      error: (error) => {
        // if request fails will call function assigned to error key
        if (error.responseText === "\n\nYou have reached your note limit.") {
          $(".note-limit-message").addClass("active");
        }
        console.log("ERROR");
        console.log(error);
      },
    });
  }
}

export default MyNotes;
